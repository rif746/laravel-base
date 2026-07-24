# Antigravity Architecture: Laravel Modular Monolith

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-4.x-FF6B6B?logo=pest&logoColor=white)](https://pestphp.com)

Welcome to the repository. This application is built using a strict **Pragmatic Domain-Driven Design (DDD)** architecture. We refer to this as the "Antigravity" architecture because it prevents the codebase from collapsing under its own weight as it scales.

---

## 1. Technical Stack

- **Backend:** PHP 8.4+ & Laravel 13.0
- **Frontend:** Vite, AlpineJS, Livewire, Tailwind CSS
- **Database:** SQLite (default), MySQL, or PostgreSQL
- **Testing:** Pest PHP
- **Package Managers:** Composer (PHP), NPM (JS)

---

## 2. Requirements

- **PHP:** ^8.4
- **Node.js:** Latest LTS recommended
- **Composer:** ^2.0
- **Extensions:** `ext-zip`, `ext-pdo_sqlite` (if using SQLite)

---

## 3. Setup & Installation

The project includes a unified setup script in `composer.json`.

```bash
# 1. Clone the repository
git clone <repo-url>
cd laravel-base

# 2. Run the automated setup
# This installs PHP/JS deps, creates .env, generates key, and runs migrations
composer setup
```

---

## 4. Running the Application

Use the pre-configured development command which runs the server, queue listener, logs, and Vite concurrently:

```bash
composer dev
```

The application will be available at `http://localhost:8000`.

---

## 5. Scripts & Commands

Available via Composer:
- `composer setup`: Initial project bootstrap.
- `composer dev`: Start development environment (Server + Queue + Logs + Vite).
- `composer test`: Run the test suite.
- `php artisan domain:make`: Custom generator for the DDD architecture (see Section 12).
- `php artisan domain:new`: Scaffold a new domain (see Section 12).
- `php artisan domain:datatable`: Generate a domain-bound DataTable and its view (see Section 12).
- `php artisan domain:make-page`: Generate a Blade view or Livewire modal in a domain (see Section 12).
- `php artisan system:prune-files`: Clean up orphaned database records and stranded disk files (see Section 13).

Available via NPM:
- `npm run dev`: Start Vite dev server.
- `npm run build`: Build assets for production.

---

## 6. Project Structure

```text
.
├── app/
│   ├── Attributes/       <-- PHP 8 Attributes (SEO, Layout)
│   ├── Domains/          <-- Core Business Logic (The Vault)
│   ├── Http/             <-- Web/API Gateway (Controllers, Requests, DataTables)
│   ├── Livewire/         <-- Livewire Components & Forms
│   ├── Providers/        <-- Service Providers
│   └── UI/               <-- UI-specific logic (Actions, Enums)
├── bootstrap/            <-- App bootstrap logic
├── config/               <-- Laravel configuration files
├── database/             <-- Migrations, factories, and seeders
├── public/               <-- Web server entry point (index.php) and static assets
├── resources/
│   ├── lang/             <-- Localization files
│   ├── views/            <-- Blade templates & Livewire components
│   └── css/js/           <-- Frontend source assets
├── routes/               <-- Web, API, and Console routes
├── tests/                <-- Pest test suite
├── storage/              <-- Logs, file uploads, and cache
└── vite.config.js        <-- Vite configuration
```

---

## 7. The Core Philosophy

This architecture enforces a hard, physical boundary between **Delivery** (how the user interacts with the app) and **Business Logic** (what the app actually does).

* **The Gateway (HTTP Layer):** Controllers, Livewire, and Volt components are purely traffic cops. They handle web sessions, cookies, redirects, and form validation.
* **The Domain (The Vault):** Actions, DTOs, and Models inside `app/Domains/` handle the actual business rules, database mutations, and external API calls.

> **The Golden Rule:** The Domain must remain completely ignorant of the web. You must never use `request()`, `session()`, or throw HTTP exceptions inside the `app/Domains/` directory.

---

## 8. Directory Structure

The application is divided by **Business Concepts**, not technical features.

```text
app/
├── Attributes/               <-- PHP 8 Attributes (e.g., #[Seo], #[LayoutData])
├── Console/
│   ├── Commands/             <-- Custom Artisan commands (DomainMakeCommand, CleanOrphanedFiles)
│   └── stubs/                <-- Custom code generation stubs
├── Http/                     <-- The Gateway (HTTP Layer)
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/           <-- Versioned API controllers
│   │   └── Web/
│   │       ├── Auth/         <-- Authentication controllers
│   │       ├── Identity/     <-- User & role management controllers
│   │       └── Account/      <-- Profile management controllers
│   ├── DataTables/           <-- Livewire DataTable configurations
│   ├── Ingestion/            <-- Excel Import/Ingestion classes
│   ├── Middleware/           <-- HandlePreferredLanguage, HandleSeoSetting, etc.
│   ├── Requests/
│   │   ├── Api/              <-- API form requests
│   │   └── Web/              <-- Web form requests
│   └── Resources/            <-- API resources (LookupResource, SuccessResource, etc.)
├── Livewire/
│   ├── Concerns/             <-- Shared Livewire traits (WithModal, WithToast)
│   └── Forms/                <-- Livewire Form Objects
├── Providers/                <-- AppServiceProvider, UiServiceProvider
├── UI/
│   ├── Actions/              <-- UI-layer actions (SetSeoMetadata, ApplyLayoutMetadata)
│   ├── Enums/                <-- UI-specific enums (FileType, InputType)
│   └── Support/              <-- UI helper classes (LayoutState, StyledExport)
└── Domains/
    ├── Identity/             <-- Business Concept: Authentication & Users
    │   ├── Actions/          <-- Capability-grouped mutations
    │   ├── DTOs/             <-- Capability-grouped Data Transfer Objects
    │   ├── Enums/
    │   ├── Events/           <-- Past-tense truths
    │   ├── Exports/
    │   ├── Integration/      <-- External system mappers
    │   ├── Listeners/        <-- Active-verb handlers
    │   ├── Models/           <-- User, Role, Permission
    │   ├── Notifications/
    │   ├── Policies/
    │   ├── Providers/
    │   ├── Queries/          <-- Complex Reads
    │   └── Scopes/           <-- Eloquent Global Scopes
    ├── Account/              <-- Business Concept: Profiles & Billing
    │   ├── Actions/
    │   ├── DTOs/
    │   ├── Enums/
    │   ├── Listeners/
    │   ├── Models/
    │   └── Providers/
    └── System/               <-- Business Concept: Cross-cutting Infrastructure
        ├── Actions/
        ├── Casts/            <-- Custom Eloquent casts
        ├── DTOs/
        ├── Enums/
        ├── Events/
        ├── Helpers/          <-- Domain-specific helpers (asset.php)
        ├── Jobs/             <-- Domain-specific background jobs
        ├── Listeners/
        ├── Mail/             <-- Domain-specific mailables
        ├── Models/           <-- File, SystemSettings, Backup
        ├── Policies/
        ├── Providers/        <-- SystemServiceProvider
        ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
        ├── Support/
        ├── Traits/           <-- Domain-specific traits (HasFile)
        └── ...

```

---

## 9. The Rules of Engagement

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

## 10. Naming Conventions

This architecture uses a strict, intentional naming language. Every name must communicate **Business Intent**, not database operations.

### 10.1 Domain Folders (`app/Domains/{Name}/`)

Domain names are **Business Concepts**, not technical layers. They must be a singular noun that describes a bounded context.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Identity` | `Users` | Identity covers auth, roles, and user lifecycle — not just a table |
| `Account` | `Profile` | Account owns the full user account surface, not one model |
| `System` | `Utils` / `Helpers` | System is a real bounded context for cross-cutting infrastructure |

### 10.2 Capability Folders (Action / DTO / Event / Listener subdirectories)

Subdirectories inside `Actions/`, `DTOs/`, `Events/`, and `Listeners/` must be named after **Business Capabilities**, not database nouns.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Onboarding/` | `Users/` | Describes the lifecycle stage, not the DB table |
| `AccessControl/` | `Roles/` | Describes the capability, not the resource |
| `Governance/` | `Admin/` | Describes the compliance intent |
| `Passwords/` | `Auth/` | Narrow, precise scope |
| `Registration/` | `Signup/` | Uses the system's formal language |

**Rule:** If a folder name is also a valid Eloquent Model name, it is wrong.

### 10.3 Action Class Names

Actions must be named after the **specific Business Intent** they fulfill. Use an active verb + business noun pattern.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Describes *who* triggers it and *why* |
| `SuspendUser` | `DeleteUser` | Reveals the business consequence (soft revoke, not destroy) |
| `UpdateUserRole` | `SaveRole` | Explicit about the subject and property being changed |
| `RegisterUser` | `StoreUser` | Domain language, not HTTP verb language |
| `SendPasswordResetLink` | `ResetPassword` | Reflects the actual side effect triggered |

CRUD names (`CreateCategory`, `UpdateSetting`) are only acceptable for trivial lookup tables with **no side effects**.

### 10.4 DTO Class Names

DTOs are named after the Action they serve, with a `DTO` suffix.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 10.5 Event Class Names

Events are **past-tense facts** about something that already happened in the domain. The class name must be grammatically a completed truth.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Explicit past-tense removes ambiguity |
| `UserWasSuspended` | `UserSuspended` | Reads as a state, not a completed fact |
| `UserLoggedIn` | `LoginEvent` | Noun + verb pattern; avoids the `Event` suffix |
| `UserEmailVerified` | `EmailVerification` | Describes the completed action |

**Rule:** Never suffix Events with `Event` (e.g., `UserRegisteredEvent` is wrong). The namespace `Events\` already communicates the type.

### 10.6 Listener Class Names

Listeners describe the **active reaction** to an event using an imperative verb phrase.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Describes what the listener *does*, not what it reacts to |
| `DispatchWelcomeNotification` | `WelcomeListener` | Imperative verb makes the intent crystal clear |

**Rule:** Never suffix Listeners with `Listener` in the class name. The namespace `Listeners\` already communicates the type.

### 10.7 Action Verb & Intent Guide

To maintain consistency in naming Actions, use the following standard verb prefixes to communicate the specific lifecycle and impact of the operation.

| Verb Prefix | Intent Scope | Real-World Context Example |
| --- | --- | --- |
| **`Initialize`** / **`Register`** | Declares the setup of an operational business track or engine. | `RegisterSelfServiceUser`, `InitializeSystemCluster` |
| **`Draft`** | Spawns a new entity row but locks it out of live visibility as a pending draft. | `DraftSystemNotification`, `DraftIdentityAccessPolicy` |
| **`Publish`** / **`Activate`** | Handles state transition to shift an existing record to a live production state. | `ActivateUserStatus`, `PublishAnnouncement` |
| **`Define`** | Configures static lookups or structural reference dictionary elements. | `CreateSystemRole`, `DefineIdentityPermission` |
| **`Adjust`** / **`Modify`** | Performs precise partial modifications or data tuning on active records. | `UpdateUserSettings`, `AdjustBackupFrequency` |
| **`Replace`** / **`Overwrite`** | Performs a full destructive replacement of an entry's total data layout. | `ReplaceSingleFile`, `OverwriteDomainSetting` |
| **`Synchronize`** | Forces full state matching with an authoritative external system registry. | `SyncBackupCatalog`, `SynchronizeIdentityData` |
| **`Suspend`** / **`Pause`** | Halts access temporarily while leaving underlying structures fully intact. | `SuspendUser`, `PauseSystemJob` |
| **`Archive`** | Executes soft-deletion, shifting records permanently to history ledgers. | `ArchiveAuditLog`, `ArchiveProcessedImport` |

---

**For Developers:** Copy and paste the block below into your AI agent's chat or system instructions before asking it to write or refactor code in this repository.

```text
You are an autonomous Senior Laravel Architect specializing in Pragmatic Domain-Driven Design (DDD) and Event-Driven Architecture. You must strictly obey the "Antigravity" rules of this repository.

### 1. The HTTP Gateway (Delivery Layer)
- Lives in `app/Http/Controllers/`, `app/Http/DataTables/`, or `app/Livewire/`.
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
- NEVER use standard Laravel generators (e.g., `php artisan make:model`) for Domain classes.
- ALWAYS use the custom `domain:make` command to create Domain files.
- Example: `php artisan domain:make action Identity Onboarding/ProvisionNewUser`
- Supported types: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `query`, `provider`, `export`, `mapper`, `scope`, `trait`, `mailable`.
- Examples for the Integration layer:
  - `php artisan domain:make export Identity UserExport --model=User`
  - `php artisan domain:make mapper Identity User` → generates `Integration/Mappers/UserDataMapper.php`
- Excel ingestion (Import) classes live in the **Gateway layer** at `app/Http/Ingestion/Excel/` — do NOT generate them with `domain:make`.
- Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

### 6. Testing Strategy
- Always write tests using Pest PHP.
- When generating new features (Actions/DTOs/Models), create corresponding tests in `tests/Feature/` or `tests/Unit/`.
- Ensure 100% adherence to Architectural tests (check `tests/Architecture/` for existing rules).
- When writing tests, use `Event::fake()`, `Queue::fake()`, `Notification::fake()` to isolate side effects.
- For dependency-injected services, use `$this->mock()` to define expected behaviors.

Write modern PHP 8.4+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.

```

---

## 12. Development Tools & Generators

To maintain the strict folder structure of the Antigravity architecture, **do not use standard `make:` commands (like `make:model`) for Domain files.** Use the custom `domain:make` command to generate classes in the correct `app/Domains/` namespaces.

### The `domain:make` Command

**Signature:**

```bash
php artisan domain:make {type} {domain} {name} [options]
```

**Arguments:**

* `type`: The file type to generate. Supported: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `scope`, `trait`, `query`, `provider`, `export`, `mapper`, `mailable`.
* `domain`: The target Domain folder (e.g., `Identity`, `Account`, `System`).
* `name`: The class name. Supports sub-directory grouping (e.g., `Management/ProvisionNewUser`).

**Options:**

* `--factory`: Generates an associated database factory (Models only).
* `--migration`: Generates a database migration file (Models only).
* `--model=`: Associates the export class with an Eloquent model (Exports only).

### The `domain:datatable` Command

Generates a Yajra DataTable service class inside `app/Http/DataTables/{Domain}/` and a corresponding index Blade view inside `resources/views/pages/{domainLower}/{capability}/`.

**Signature:**

```bash
php artisan domain:datatable {name} {domain} [--model=]
```

### The `domain:make-page` Command

Generates a Blade view or a unified Livewire modal component inside `resources/views/pages/{domain}/{capability}/`.

**Signature:**

```bash
php artisan domain:make-page {domain} {capability} {name} [--modal]
```

* Use `--modal` to generate a "Lightning Component" (⚡) which includes both a PHP class and a Blade view in the same directory.

### The `domain:new` Command

This scaffolds a brand-new domain structure by establishing its main `ServiceProvider` (pre-wired with the `RegistersDomainEvents` trait) and its companion `RelationshipServiceProvider` (intended solely for `Model::resolveRelationUsing()` mapping), registering the main provider within `bootstrap/providers.php` automatically.

**Signature:**

```bash
php artisan domain:new {domain}
```

### Purpose & Usage of Domain Providers

To keep business logic decoupled from presentation layer gluing and cross-domain relational imports, domain modules utilize a tiered Service Provider hierarchy:

1. **`{Domain}ServiceProvider`**: The entry point for the domain. It uses the `RegistersDomainEvents` trait to scan its local `$listen` array and wire up domain events. It also acts as the bootstrapper that registers the internal providers below (like `RelationshipServiceProvider` and `ViewServiceProvider`).
2. **`RelationshipServiceProvider`**: Dedicated exclusively to cross-domain relationships using Laravel's `Model::resolveRelationUsing()`. For example, binding a polymorphic relation between models of different domains. Since it is loaded automatically by the root provider, it avoids compile-time dependencies between domains.
3. **`ViewServiceProvider`**: Used to map UI components to data (View Composers) without polluting the root domain logic. It is registered by default in the domain's main ServiceProvider. Create it via `domain:make` if it doesn't exist:
   ```bash
   php artisan domain:make view-provider {domain} ViewServiceProvider
   ```
   Inside its `boot()` method, map your custom view composers:
   ```php
   View::composer('components.layouts.sidebar', function ($view) {
       $view->with('navigationItems', [ ... ]);
   });
   ```

### Customizing Generators (Stubs)

All `domain:make` templates are stored as `.stub` files in `app/Console/stubs/domain-make/`. You can freely edit these stubs to customize the default boilerplate for your project's specific needs (e.g., changing the default methods in a Repository or adjusting the strict typing in a DTO).

---

## 13. Universal File Management (The System Domain)

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

### File Actions (Metadata via DTO)

Because Laravel's `Illuminate\Http\UploadedFile` is a complex object, we pass it alongside a strictly-typed `FileDTO` that contains the metadata (target model, disk, and relation name). This ensures the Gateway remains clean while the Domain receives all necessary context.

* **`UploadAndAttachFile`**: The base action. It stores the physical file to the disk and creates the polymorphic database record.
* **`ReplaceSingleFile`**: Used for 1-to-1 replacements (like changing an avatar). It safely deletes the old file before delegating the new upload back to the base action.

**Gateway Example:**

```php
$action->execute(
    newFile: $request->file('photo'),
    dto: new FileDTO(
        modelType: $user->getMorphClass(),
        modelId: $user->id,
        relationName: 'avatar',
        disk: 'local',
        directory: 'avatars',
        options: [],
        uploaderId: auth()->id(),
    )
);

```

### System Asset Helper

To avoid polluting Laravel's global namespace with a junk `app/helpers.php` file, we maintain a strictly domain-bound helper file at `app/Domains/System/Helpers/asset.php`. It is autoloaded via `composer.json` and provides the `asset_static()` function to elegantly resolve public and private files in our Blade views.

### File Reconciliation (Pruning)

Over time, the database might contain records for files that no longer exist on disk, or the disk might contain files that are no longer referenced in the database. Use the prune command to reconcile these:

```bash
php artisan system:prune-files --disk=public --directory=uploads
```

---

## 14. Multi-Language Models (The HasTranslation Trait)

For models that require content in multiple languages (e.g., Categories or Products), we use the `HasTranslation` trait. This keeps the main table clean and follows a normalized schema for localizable content.

### The Translation Schema

Translations are stored in a dedicated table named `{singular_table}_translations` (e.g., `category_translations`). This table must contain:
* `locale`: The language code (e.g., `en`, `id`).
* `{singular_table}_id`: The foreign key to the parent model.
* The translatable columns (e.g., `name`, `description`).

### Implementation

1. Use the `HasTranslation` trait in your model.
2. Define the `$translatable` array containing the names of the fields.

```php
namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Traits\Model\HasTranslation;

class Category extends Model
{
    use HasTranslation;

    protected array $translatable = ['name', 'description'];
}
```

### Usage

The trait automatically intercepts getters and setters to handle the current application locale.

```php
$category = Category::first();

// Returns the name for the current App::getLocale()
echo $category->name; 

// Sets the name for the current locale
$category->name = 'Electronic';

// Filling multiple languages at once
$category->fill([
    'en' => ['name' => 'Electronic'],
    'id' => ['name' => 'Elektronik'],
]);

$category->save(); // Automatically saves to category_translations
```

---

## 15. Global Settings & Application State

Settings that dictate the runtime state of the application (Timezones, Localization, SEO tags) are managed by the `System` domain to ensure high performance and context awareness.

* **Memoization & Singletons:** The `GetSystemSettings` query is registered as a Singleton in the `SystemServiceProvider`. It fetches data from the database/cache *once* (using the cache key defined in `SystemSettings::$cacheName`) and stores it in local PHP memory for the duration of the request.
* **Contextual Middlewares:** We use dedicated middlewares (`HandlePreferredLanguage`, `HandlePreferredTimezone`) to dynamically check the authenticated user's preferences, falling back to the global settings if no preference exists.
* **View Composers:** Global layout variables (like Logos and Web Names) are injected globally via View Composers in the `ViewServiceProvider` (registered by the `SystemServiceProvider`), preventing repetitive `@inject` directives.

---

## 16. Audit Logging & Tracking

All critical database mutations are tracked to maintain a compliant historical ledger.

* **Model Auditing:** We utilize Eloquent events to automatically log row changes.
* **Complex Relation Auditing:** Tracking many-to-many relationship changes (like Spatie `syncPermissions`) bypasses standard Eloquent events. Therefore, we explicitly dispatch Custom Audit Events directly inside the relevant Domain Action (e.g., `UpdateRolePermissions`). This guarantees the "Before" and "After" state is captured cleanly in a single transactional row.

---

## 17. Dynamic UIs & Livewire Interoperability

When building data-driven interfaces (like dynamic settings forms), we utilize the **Renderable Enum** pattern combined with Laravel's native dynamic components.

* Enums act as **Metadata Providers** (returning the string name of the target Blade component).
* We use `<x-dynamic-component>` in the Blade file to swap UI elements.
* **CRITICAL:** Enums must never return raw HTML strings compiled via the `Blade` facade. Doing so breaks Livewire's DOM-diffing engine and severs `wire:model` bindings.

---

## 18. Excel Import & Export

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
| `importClass` | `string` | Fully-qualified class name of the Gateway Ingestion class (e.g., `App\Http\Ingestion\Excel\Identity\UserImport`). |
| `exportClass` | `string` | Fully-qualified class name of the domain Export (e.g., `App\Domains\Identity\Exports\UserExport`). |
| `resourceName` | `string` | A slug used to name the stored import file and the timestamped export file (e.g., `user`). |

### Usage

Embed the component in any DataTable page view. All props must be provided as fully-qualified PHP class name strings:

```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
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

> **Gateway Layer:** Excel Ingestion (Import) classes live in `app/Http/Ingestion/Excel/` and are **not** generated by `domain:make`. Create them manually or with `make:class` as standard PHP classes implementing `ToCollection`, `WithHeadingRow`, and `WithChunkReading`.

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
* **`App\Domains\Identity\Mail\Registration\WelcomeEmail`** — Example of a domain-specific mailable.
* **`App\Domains\System\Mail\ExcelExportEmail`** — Sent when a queued export is ready, with the file attached from the `local` disk. Uses `domains/system.notifications.excel.export_email.*` translations.

### Translation Keys

| File | Key | Purpose |
| --- | --- | --- |
| `lang/{locale}/ui.php` | `ui.excel.import.file_label` | FilePond upload label inside the import modal. |
| `lang/{locale}/ui.php` | `ui.excel.import.success` | Toast shown after import is queued. |
| `lang/{locale}/ui.php` | `ui.excel.export.success` | Toast shown after export is queued. |
| `lang/{locale}/domains/system.php` | `notifications.excel.import_email.*` | Email body for the import completion notification. |
| `lang/{locale}/domains/system.php` | `notifications.excel.export_email.*` | Email body for the export ready notification. |

---

## 19. Testing

We use [Pest PHP](https://pestphp.com) for our test suite. Please refer to [TESTING.md](TESTING.md) for detailed instructions on running, structuring, and writing tests.

---

## 20. Environment Variables

Key variables used in `.env`:

- `APP_NAME`: Name of the application.
- `APP_ENV`: Application environment (`local`, `production`, etc.).
- `APP_KEY`: Application encryption key.
- `DB_CONNECTION`: Database driver (`sqlite`, `mysql`, `pgsql`).
- `QUEUE_CONNECTION`: Queue driver (default: `database`).
- `MAIL_MAILER`: Mail driver (default: `log`).

See `.env.example` for the full list of available options.

---

## 21. License

This project is licensed under the **MIT License**.


