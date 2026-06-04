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
├── Http/                     <-- The Gateway (Controllers, Middleware)
└── Domains/
    ├── Identity/             <-- Business Concept: Authentication & Users
    │   ├── Actions/          <-- Actions (Mutations)
    │   │   ├── Management/   <-- Grouped by specific workflows
    │   │   ├── Registration/
    │   │   └── Roles/
    │   ├── DTOs/             <-- Data Transfer Objects
    │   ├── DataTables/
    │   ├── Enums/
    │   ├── Events/
    │   ├── Listeners/
    │   ├── Models/
    │   ├── Notifications/
    │   ├── Policies/
    │   └── Queries/          <-- Complex Reads (Queries)
    └── Account/              <-- Business Concept: Profiles & Billing

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

* The Gateway dispatches Events for non-mutating actions (like `UserLoggedIn`).
* Actions dispatch Events immediately after mutating state (like `UserProvisioned`).
* Listeners handle the reaction outside the main HTTP lifecycle.

---

## 4. Ubiquitous Language (Naming Conventions)

We do not name files after database operations. We name them after the **Business Intent**.

| CRUD Term (Avoid) | Business Intent (Use These) | Scenario |
| --- | --- | --- |
| `CreateUser` | `RegisterUser` | A user signs up via the frontend. |
| `CreateUser` | `ProvisionNewUser` | An admin creates an account from a dashboard. |
| `UpdateUser` | `UpdateUserProfile` | Updating a name, bio, or email. |
| `UpdateUser` | `UpdateUserRoles` | Changing Spatie permissions. |
| `DeleteUser` | `SuspendUserAccount` | Revoking access without destroying data. |

*Note: Standard CRUD naming (e.g., `CreateCategory`) is permissible only for trivial lookup tables that trigger no side effects.*

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
- Action Composition: Inject Actions into other Actions via the constructor to reuse logic (e.g., injecting `UpdateUserRoles` into `UpdateUserProfile`).
- Events: Use Events to decouple side effects (Notifications, Activity Logs).

### 4. File Generation Rules**
* NEVER use standard Laravel generators (e.g., `php artisan make:model`) for Domain classes.
* ALWAYS use the custom `domain:make` command to create Domain files.
* Example: `php artisan domain:make action Identity Passwords/UpdateUserPassword`
* Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

Write modern PHP 8.2+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.

```
## 6. Development Tools & Generators

To maintain the strict folder structure of the Antigravity architecture, **do not use standard `make:` commands (like `make:model`) for Domain files.** Use the custom `domain:make` command to generate classes in the correct `app/Domains/` namespaces.

### The `domain:make` Command

**Signature:**

```bash
php artisan domain:make {type} {domain} {name} [options]

```

**Arguments:**

* `type`: The file type to generate (`model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `datatable`, `query`).
* `domain`: The target Domain folder (e.g., `Identity`, `Account`, `System`).
* `name`: The class name. Supports sub-directory grouping (e.g., `Management/ProvisionNewUser`).

**Options (Models Only):**

* `--factory`: Generates an associated database factory.
* `--migration`: Generates a database migration file.

### Usage Examples

**1. Generate a Model with a Factory and Migration:**

```bash
php artisan domain:make model Identity User --factory --migration

```

**2. Generate a Grouped Action:**

```bash
php artisan domain:make action Identity Management/ProvisionNewUser

```

**3. Generate a DTO:**

```bash
php artisan domain:make dto Identity Management/ProvisionUserDTO

```

---
