# Antigravity Architecture: Laravel Modular Monolith

Welcome to the repository. This application is built using a strict **Pragmatic Domain-Driven Design (DDD)** architecture. We refer to this as the "Antigravity" architecture because it prevents the codebase from collapsing under its own weight as it scales.

This document serves as the ultimate source of truth for human developers and AI coding agents working on this codebase.

---

## 1. The Core Philosophy

This architecture enforces a hard, physical boundary between **Delivery** (how the user interacts with the app) and **Business Logic** (what the app actually does).

* **The Gateway (HTTP Layer):** Controllers, Livewire, and Volt components are purely traffic cops. They handle web sessions, cookies, redirects, and form validation.
* **The Domain (The Vault):** Actions, DTOs, and Models inside `app/Domains/` handle the actual business rules, database mutations, and external API calls.

> **The Golden Rule:** The Domain must remain completely ignorant of the web. You must never use `request()`, `session()`, or throw HTTP exceptions inside the `app/Domains/` directory.

---

## 2. Directory Structure

The application is divided by **Business Concepts**, not technical features.

```text
app/
├── Attributes/               <-- PHP 8 Attributes (e.g., #[Seo])
├── Console/
│   └── Commands/             <-- Custom Artisan commands (DomainMakeCommand, CleanOrphanedFiles)
├── Http/                     <-- The Gateway (HTTP Layer)
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/           <-- Versioned API controllers
│   │   └── Web/
│   │       ├── Auth/         <-- Authentication controllers
│   │       ├── Identity/     <-- User & role management controllers
│   │       └── System/       <-- System settings controllers
│   ├── Middleware/           <-- HandlePreferredLanguage, HandlePreferredTimezone, HandleSeoSetting, etc.
│   ├── Requests/
│   │   ├── Api/              <-- API form requests
│   │   └── Web/              <-- Web form requests
│   └── Resources/            <-- API resources (LookupResource, SuccessResource, etc.)
├── Livewire/
│   └── Concerns/             <-- Shared Livewire traits (WithModal, WithToast, HasSeoAttributes)
├── Providers/                <-- AppServiceProvider
├── UI/
│   ├── Actions/              <-- UI-layer actions (non-domain mutations)
│   └── Enums/
└── Domains/
    ├── Identity/             <-- Business Concept: Authentication & Users
    │   ├── Actions/          <-- Capability-grouped mutations
    │   │   ├── Onboarding/   <-- RegisterSelfServiceUser, ProvisionNewUser,
    │   │   │               <-- UpdateUser, VerifyUserEmail, ResendVerificationEmail
    │   │   ├── AccessControl/<-- CreateSystemRole, UpdateSystemRole, UpdateUserRole, RemoveSystemRole
    │   │   ├── Governance/   <-- SuspendUser, UpdateUserStatus
    │   │   └── Passwords/    <-- ResetUserPassword, UpdatePassword, SendPasswordResetLink
    │   ├── DTOs/             <-- Capability-grouped Data Transfer Objects
    │   │   ├── Onboarding/   <-- RegisterSelfServiceUserDTO, ProvisionUserDTO, UpdateUserDTO
    │   │   ├── AccessControl/<-- CreateRoleDTO, UpdateRoleDTO
    │   │   └── Passwords/    <-- ForgotPasswordDTO, ResetPasswordDTO, UpdatePasswordDTO
    │   ├── DataTables/
    │   ├── Enums/
    │   ├── Events/           <-- Past-tense truths, grouped by capability
    │   │   ├── Authentication/<-- UserLoggedIn
    │   │   ├── Onboarding/   <-- UserWasRegistered, UserWasProvisioned, UserEmailWasVerified
    │   │   └── Governance/   <-- UserWasSuspended
    │   ├── Exports/
    │   ├── Integration/
    │   │   └── Mappers/      <-- DataPayloadMapper implementations
    │   ├── Listeners/        <-- Active-verb handlers, grouped by capability
    │   │   └── Authentication/<-- SendSignInActivityNotification
    │   ├── Models/           <-- User, Role, Permission
    │   ├── Notifications/
    │   ├── Policies/
    │   └── Queries/          <-- Complex Reads (reserved for future use)
    ├── Account/              <-- Business Concept: Profiles & Billing
    │   ├── Actions/
    │   │   └── Profile/
    │   ├── DTOs/
    │   ├── Enums/
    │   └── Models/
    └── System/               <-- Business Concept: Cross-cutting Infrastructure
        ├── Actions/
        │   ├── Backup/
        │   ├── Files/        <-- UploadAndAttachFile, ReplaceSingleFile
        │   └── Settings/
        ├── Casts/            <-- Custom Eloquent casts (e.g., ByteHumanReadable)
        ├── DTOs/
        ├── Enums/
        ├── Helpers/          <-- assets.php (system_asset() helper, autoloaded via composer.json)
        ├── Models/           <-- File, SystemSettings, Backup
        ├── Policies/
        ├── Providers/        <-- SystemServiceProvider (Singleton registration, View Composers)
        ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
        ├── Support/
        │   └── ValueObjects/
        └── Traits/
            └── Model/        <-- HasFile trait

```

---

## 3. The Rules of Engagement

### DTOs (Data Transfer Objects)

All untrusted data from the Gateway must be packed into a strictly typed, readonly DTO before entering the Domain.

* DTOs only contain data meant to change state.
* Do not put Eloquent models inside DTOs. Pass the Model as a separate parameter to the Action.

### Actions (The Executors)

Actions are the only place database mutations (`create`, `update`, `delete`, `syncRoles`) are allowed.

* Actions must have a single responsibility.
* Use `DB::transaction()` inside Actions when multiple database writes (e.g., creating a user and assigning a Spatie role) must succeed or fail together.
* Use **Action Composition** (injecting one Action into another via the constructor) to reuse logic without duplicating code.

### Events & Listeners

Use Event-Driven Architecture for all side effects (emails, logging, background processing).

* The Gateway dispatches Events for non-mutating session facts (e.g., `UserLoggedIn`).
* Actions dispatch Events immediately after a successful state mutation (e.g., `UserWasProvisioned`).
* Listeners handle the reaction outside the main HTTP lifecycle and are the **only** place `Notification::send()`, `Mail::send()`, or logging calls are made in response to a Domain Event.
* Events and Listeners must be **grouped under the same capability folder** as the Action that dispatches them (e.g., `Events/Onboarding/`, `Listeners/Onboarding/`).

---

## 4. Naming Conventions

This architecture uses a strict, intentional naming language. Every name must communicate **Business Intent**, not database operations.

### 4.1 Domain Folders (`app/Domains/{Name}/`)

Domain names are **Business Concepts**, not technical layers. They must be a singular noun that describes a bounded context.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Identity` | `Users` | Identity covers auth, roles, and user lifecycle — not just a table |
| `Account` | `Profile` | Account owns the full user account surface, not one model |
| `System` | `Utils` / `Helpers` | System is a real bounded context for cross-cutting infrastructure |

### 4.2 Capability Folders (Action / DTO / Event / Listener subdirectories)

Subdirectories inside `Actions/`, `DTOs/`, `Events/`, and `Listeners/` must be named after **Business Capabilities**, not database nouns.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Onboarding/` | `Users/` | Describes the lifecycle stage, not the DB table |
| `AccessControl/` | `Roles/` | Describes the capability, not the resource |
| `Governance/` | `Admin/` | Describes the compliance intent |
| `Passwords/` | `Auth/` | Narrow, precise scope |
| `Registration/` | `Signup/` | Uses the system's formal language |

**Rule:** If a folder name is also a valid Eloquent Model name, it is wrong.

### 4.3 Action Class Names

Actions must be named after the **specific Business Intent** they fulfill. Use an active verb + business noun pattern.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Describes *who* triggers it and *why* |
| `SuspendUser` | `DeleteUser` | Reveals the business consequence (soft revoke, not destroy) |
| `UpdateUserRole` | `SaveRole` | Explicit about the subject and property being changed |
| `RegisterUser` | `StoreUser` | Domain language, not HTTP verb language |
| `SendPasswordResetLink` | `ResetPassword` | Reflects the actual side effect triggered |

CRUD names (`CreateCategory`, `UpdateSetting`) are only acceptable for trivial lookup tables with **no side effects**.

### 4.4 DTO Class Names

DTOs are named after the Action they serve, with a `DTO` suffix.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 4.5 Event Class Names

Events are **past-tense facts** about something that already happened in the domain. The class name must be grammatically a completed truth.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Explicit past-tense removes ambiguity |
| `UserWasSuspended` | `UserSuspended` | Reads as a state, not a completed fact |
| `UserLoggedIn` | `LoginEvent` | Noun + verb pattern; avoids the `Event` suffix |
| `UserEmailVerified` | `EmailVerification` | Describes the completed action |

**Rule:** Never suffix Events with `Event` (e.g., `UserRegisteredEvent` is wrong). The namespace `Events\` already communicates the type.

### 4.6 Listener Class Names

Listeners describe the **active reaction** to an event using an imperative verb phrase.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Describes what the listener *does*, not what it reacts to |
| `DispatchWelcomeNotification` | `WelcomeListener` | Imperative verb makes the intent crystal clear |

**Rule:** Never suffix Listeners with `Listener` in the class name. The namespace `Listeners\` already communicates the type.

---

## 5. AI Agent Prompt (System Instructions)

**For Developers:** Copy and paste the block below into your AI agent's chat or system instructions before asking it to write or refactor code in this repository.

```text
You are an autonomous Senior Laravel Architect specializing in Pragmatic Domain-Driven Design (DDD) and Event-Driven Architecture. You must strictly obey the "Antigravity" rules of this repository.

### 1. The HTTP Gateway (Delivery Layer)
- Lives in `app/Http/Controllers/` or `resources/views/` (Volt/Livewire).
- Responsibilities: HTTP validation, rate limiting, session management (`Auth::login`, `session()->regenerate()`), and redirects.
- HARD RESTRICTION: The Gateway MUST NEVER call `Model::create()`, `Model::update()`, or `Hash::make()`. It must map validated data into a DTO and pass it to a Domain Action.

### 2. The Domain (Business Logic)
- Lives inside `app/Domains/{Concept}/`.
- Responsibilities: DTO definitions, Actions (database writes), Models, and Events.
- HARD RESTRICTION: The Domain MUST NEVER read from the `request()` helper, manipulate cookies/sessions, or throw HTTP-specific exceptions (like `ValidationException`).

### 3. Execution & Workflow
- DTOs: Must be strictly typed readonly classes.
- Actions: Must represent a specific Business Intent (e.g., `ProvisionNewUser` not `SaveUser`). Actions that perform multiple database writes must wrap them in a `DB::transaction()`.
- Action Composition: Inject Actions into other Actions via the constructor to reuse logic (e.g., injecting `AccessControl\UpdateUserRole` into `Onboarding\ProvisionNewUser`).
- Events: Use Events to decouple side effects (Notifications, Activity Logs). Events must be **past-tense** (e.g., `UserWasProvisioned` not `UserProvisioned`).

### 4. Naming Rules
- **Domains**: Singular Business Concepts, never database nouns (✅ `Identity` ❌ `Users`).
- **Capability Folders**: Name subdirectories after business capabilities, never after models (✅ `Onboarding/`, `AccessControl/`, `Governance/` ❌ `Users/`, `Roles/`).
- **Actions**: Active verb + business noun (✅ `ProvisionNewUser` ❌ `CreateUser`).
- **Events**: Past-tense completed facts, no `Event` suffix (✅ `UserWasProvisioned` ❌ `UserProvisionedEvent`).
- **Listeners**: Imperative active-verb phrases, no `Listener` suffix (✅ `SendSignInActivityNotification` ❌ `UserLoggedInListener`).

### 5. File Generation Rules
* NEVER use standard Laravel generators (e.g., `php artisan make:model`) for Domain classes.
* ALWAYS use the custom `domain:make` command to create Domain files.
* Example: `php artisan domain:make action Identity Onboarding/ProvisionNewUser`
* Examples for the Integration layer:
  * `php artisan domain:make export Identity UserExport --model=User`
  * `php artisan domain:make mapper Identity User` → generates `Integration/Mappers/UserDataMapper.php`
* Excel ingestion (Import) classes live in the **Gateway layer** at `app/Http/Ingestion/` — do NOT generate them with `domain:make`.
* Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

Write modern PHP 8.2+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.

```

---

## 6. Development Tools & Generators

To maintain the strict folder structure of the Antigravity architecture, **do not use standard `make:` commands (like `make:model`) for Domain files.** Use the custom `domain:make` command to generate classes in the correct `app/Domains/` namespaces.

### The `domain:make` Command

**Signature:**

```bash
php artisan domain:make {type} {domain} {name} [options]

```

**Arguments:**

* `type`: The file type to generate (`model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `trait`, `query`, `provider`, `export`, `mapper`).
* `domain`: The target Domain folder (e.g., `Identity`, `Account`, `System`).
* `name`: The class name. Supports sub-directory grouping (e.g., `Management/ProvisionNewUser`).

**Options:**

* `--factory`: Generates an associated database factory (Models only).
* `--migration`: Generates a database migration file (Models only).
* `--model=`: Associates the export class with an Eloquent model (Exports only).

---

## 7. Universal File Management (The System Domain)

File handling (uploads, attachments, image cropping, and deletions) is a universally shared capability. To prevent every domain from writing its own file storage logic, all physical files are managed by a centralized engine within the **`System`** domain.

### The Polymorphic Engine

We do not add file paths directly to business tables (e.g., no `avatar_path` column on the `users` table). Instead, we use a central `files` table and the `System\Models\File` model.

* Files are attached **polymorphically** to any entity in the application.
* The `files` table includes a strictly typed `relation_name` string column (e.g., `'avatar'`) to prevent multiple file types attached to the same model from colliding.

### The Consumer Trait

When a Domain Model (like `User` or `Invoice`) needs to accept a file attachment, it pulls in the `HasFile` trait. This trait provides isolated relationship builders.

```php
namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Traits\HasFile;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model
{
    use HasFile; 

    // The string 'avatar' perfectly isolates this file in the database
    public function avatar(): MorphOne
    {
        return $this->singleFile('avatar'); 
    }
}

```

### File Actions (No DTOs Required)

Because Laravel's `Illuminate\Http\UploadedFile` is already a strictly-typed object, we **do not** wrap files in DTOs. The Gateway passes the raw file and the target `relation_name` directly into the central System Actions.

* **`UploadAndAttachFile`**: The base action. It stores the physical file to the disk and creates the polymorphic database record. It also handles server-side image compression (via Intervention Image) based on mime-types.
* **`ReplaceSingleFile`**: Used for 1-to-1 replacements (like changing an avatar). It safely deletes the old file before delegating the new upload back to the base action.

**Gateway Example:**

```php
$action->execute(
    targetModel: auth()->user()->profile,
    relationName: 'avatar', // Matches the relation method name exactly
    uploadedFile: $request->file('photo'),
    disks: 'local',
    directory: 'avatars'
);

```

### System Asset Helper

To avoid polluting Laravel's global namespace with a junk `app/helpers.php` file, we maintain a strictly domain-bound helper file at `app/Domains/System/Helpers/assets.php`. It is autoloaded via `composer.json` and provides the `system_asset()` function to elegantly resolve public and private files in our Blade views.

---

## 8. Global Settings & Application State

Settings that dictate the runtime state of the application (Timezones, Localization, SEO tags) are managed by the `System` domain to ensure high performance and context awareness.

* **Memoization & Singletons:** The `GetSystemSettings` query is registered as a Singleton in the `SystemServiceProvider`. It fetches data from the database/cache *once* and stores it in local PHP memory for the duration of the request.
* **Contextual Middlewares:** We use dedicated middlewares (`HandlePreferredLanguage`, `HandlePreferredTimezone`) to dynamically check the authenticated user's preferences, falling back to the global settings if no preference exists.
* **View Composers:** Global layout variables (like Logos and Web Names) are injected globally via View Composers in the Service Provider, preventing repetitive `@inject` directives.

---

## 9. Audit Logging & Tracking

All critical database mutations are tracked to maintain a compliant historical ledger.

* **Model Auditing:** We utilize Eloquent events to automatically log row changes.
* **Complex Relation Auditing:** Tracking many-to-many relationship changes (like Spatie `syncPermissions`) bypasses standard Eloquent events. Therefore, we explicitly dispatch Custom Audit Events directly inside the relevant Domain Action (e.g., `UpdateRolePermissions`). This guarantees the "Before" and "After" state is captured cleanly in a single transactional row.

---

## 10. Dynamic UIs & Livewire Interoperability

When building data-driven interfaces (like dynamic settings forms), we utilize the **Renderable Enum** pattern combined with Laravel's native dynamic components.

* Enums act as **Metadata Providers** (returning the string name of the target Blade component).
* We use `<x-dynamic-component>` in the Blade file to swap UI elements.
* **CRITICAL:** Enums must never return raw HTML strings compiled via the `Blade` facade. Doing so breaks Livewire's DOM-diffing engine and severs `wire:model` bindings.

---

## 11. Excel Import & Export

The Laravel component `resources/views/components/datatables/⚡excel-manager.blade.php` (registered as `<livewire:datatables.excel-manager>`) provides a reusable, queue-backed mechanism for importing and exporting Excel files in any DataTable page. It is a **unified single-file Laravel component** — PHP class logic and Blade template co-exist in the same file, following the `⚡` naming convention used across all Laravel components in this project.

### Architecture Overview

The component relies on three collaborating layers:

1. **The Laravel Component** (`⚡excel-manager.blade.php`) — Handles UI state, file uploads via `WithFilePond`, validation, and Livewire event listeners. All props are secured with `#[Locked]` to prevent client-side tampering.
2. **The `StyledExport` Decorator** (`App\UI\Support\Excel\StyledExport`) — A UI-layer wrapper that enriches any domain Export with standardized visual styling (frozen header row, landscape orientation, thin borders, centered alignment, and auto-sized columns) without polluting domain Export classes with presentation logic.
3. **The Event-Driven Notification Pipeline** — After the export file is written to disk, a queued `NotifyExportReady` job dispatches the `ExportCompleted` event, which is handled by the `SendExportReportEmail` listener to deliver the file via email.

### How It Works

* **Import:** The user uploads an `.xlsx` file via the FilePond modal. The file is stored to `local/excel/import/{resourceName}` and a new import instance is constructed with a UUID (`$importId`) and the authenticated user's ID (`$initiatorId`) before being dispatched to the queue via `Excel::queueImport()`. The Ingestion class lives in `app/Http/Ingestion/` (Gateway layer) and implements `WithChunkReading` (chunk size: 200 rows) to stay within shared-hosting memory limits. A success toast (`ui.excel.import.success`) is shown immediately upon queuing.

* **Export:** A Livewire browser event (`export-excel`) — dispatched by the DataTable's Export button — triggers the `export()` method via `#[On('export-excel')]`. The domain Export is wrapped in `StyledExport` and queued via `Excel::queue()`. The job chain appends `NotifyExportReady`, which dispatches `ExportCompleted`, which is handled by `SendExportReportEmail` to send the file as an email attachment to the authenticated user. A success toast (`ui.excel.export.success`) is shown immediately upon queuing.

### Component Props

| Prop | Type | Description |
| --- | --- | --- |
| `importClass` | `string` | Fully-qualified class name of the Gateway Ingestion class (e.g., `App\Http\Ingestion\Identity\UserImport`). |
| `exportClass` | `string` | Fully-qualified class name of the domain Export (e.g., `App\Domains\Identity\Exports\UserExport`). |
| `resourceName` | `string` | A slug used to name the stored import file and the timestamped export file (e.g., `user`). |

### Usage

Embed the component in any DataTable page view. All props must be provided as fully-qualified PHP class name strings:

```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    :import-class="\App\Http\Ingestion\Identity\UserImport::class"
    resource-name="user"
/>
```

The DataTable's Export button should dispatch the `export-excel` Livewire event, and the Import button should open the `#excel-import-modal` Bootstrap modal:

```php
// In your DataTable html() builder:
Button::make('excel')
    ->action("Livewire.dispatch('export-excel')"),

Button::make('excel')
    ->action("$('#excel-import-modal').modal('show')"),
```

### Generating Export & Mapper Classes

Use the `domain:make` command to create Export and Integration layer classes:

```bash
# Generate a domain Export class
php artisan domain:make export Identity UserExport --model=User

# Generate an Integration Mapper (auto-appends DataMapper suffix)
php artisan domain:make mapper Identity User
# → app/Domains/Identity/Integration/Mappers/UserDataMapper.php
```

Domain Export classes must implement `FromQuery & WithHeadings & WithMapping & WithColumnFormatting`. The `StyledExport` decorator will apply all visual styling automatically at queue time — do **not** implement `WithStyles` directly on domain Exports.

> **Gateway Layer:** Excel Ingestion (Import) classes live in `app/Http/Ingestion/` and are **not** generated by `domain:make`. Create them manually or with `make:class` as standard PHP classes implementing `ToCollection`, `WithHeadingRow`, and `WithChunkReading`.

### The Notification Pipeline

The export notification flow follows a strict, fully-queued event-driven chain:

```
Excel::queue(StyledExport, $path)
  └─> NotifyExportReady (Job)        [app/Domains/System/Jobs/]
        └─> ExportCompleted::dispatch (Event)  [app/Domains/System/Events/]
              └─> SendExportReportEmail (Listener)  [app/Domains/System/Listeners/]
                    └─> ExcelExportEmail (Mailable)  [app/Domains/System/Mail/]
```

The import notification is sent by the domain Import class itself upon completion, using `ExcelImportEmail` from the same `App\Domains\System\Mail\` namespace.

### Mailables

Both Mailable classes live in the **System domain**, not the root `App\Mail\` namespace:

* **`App\Domains\System\Mail\ExcelImportEmail`** — Sent when a queued import finishes. Uses `domains/system.notifications.excel.import_email.*` translations.
* **`App\Domains\System\Mail\ExcelExportEmail`** — Sent when a queued export is ready, with the file attached from the `local` disk. Uses `domains/system.notifications.excel.export_email.*` translations.

### Translation Keys

| File | Key | Purpose |
| --- | --- | --- |
| `lang/{locale}/ui.php` | `ui.excel.import.file_label` | FilePond upload label inside the import modal. |
| `lang/{locale}/ui.php` | `ui.excel.import.success` | Toast shown after import is queued. |
| `lang/{locale}/ui.php` | `ui.excel.export.success` | Toast shown after export is queued. |
| `lang/{locale}/domains/system.php` | `notifications.excel.import_email.*` | Email body for the import completion notification. |
| `lang/{locale}/domains/system.php` | `notifications.excel.export_email.*` | Email body for the export ready notification. |


