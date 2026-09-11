# Architecture & Philosophy

This document describes the core architectural philosophy, directory structure, and naming conventions that govern the **Antigravity** Modular Monolith.

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
├── Attributes/               <-- PHP 8 Attributes (e.g., #[Seo], #[LayoutData])
│   ├── Form/
│   ├── Model/
│   └── Ui/
├── Console/
│   ├── Commands/             <-- Custom Artisan commands (DomainMakeCommand, CleanOrphanedFiles)
│   └── stubs/                <-- Custom code generation stubs
├── Domains/                  <-- The Vault (Business Logic)
│   ├── Account/              <-- Business Concept: Profiles & Billing
│   │   ├── Actions/
│   │   ├── DTOs/
│   │   ├── Enums/
│   │   ├── Listeners/
│   │   ├── Models/
│   │   └── Providers/
│   ├── Identity/             <-- Business Concept: Authentication & Users
│   │   ├── Actions/          <-- Capability-grouped mutations
│   │   ├── DTOs/             <-- Capability-grouped Data Transfer Objects
│   │   ├── Enums/
│   │   ├── Events/           <-- Past-tense truths
│   │   ├── Exports/
│   │   ├── Integration/      <-- External system mappers
│   │   ├── Listeners/        <-- Active-verb handlers
│   │   ├── Mail/
│   │   ├── Models/           <-- User, Role, Permission
│   │   ├── Notifications/
│   │   ├── Policies/
│   │   ├── Providers/
│   │   └── Queries/          <-- Complex Reads
│   └── System/               <-- Business Concept: Cross-cutting Infrastructure
│       ├── Actions/
│       ├── Casts/            <-- Custom Eloquent casts (CastsAttributes)
│       ├── Concerns/         <-- Domain-specific traits (HasFile)
│       ├── Console/
│       ├── DTOs/
│       ├── Enums/
│       ├── Events/
│       ├── Helpers/          <-- Domain-specific helpers (asset.php)
│       ├── Jobs/             <-- Domain-specific background jobs
│       ├── Listeners/
│       ├── Mail/             <-- Domain-specific mailables
│       ├── Models/           <-- File, SystemSettings, Backup
│       ├── Observers/        <-- Eloquent model observers
│       ├── Policies/
│       ├── Providers/        <-- SystemServiceProvider
│       ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
│       └── Support/          <-- Integration contracts, Registry, ValueObjects
├── Http/                     <-- The Gateway (HTTP Layer)
│   ├── Controllers/
│   │   ├── Api/              <-- Versioned API controllers
│   │   └── Web/              <-- Web controllers (Auth, Identity, Account)
│   ├── DataTables/           <-- Livewire DataTable configurations
│   ├── Ingestion/            <-- Excel Import/Ingestion classes
│   ├── Middleware/           <-- HandlePreferredLanguage, HandleSeoSetting, etc.
│   ├── Requests/             <-- API and Web form requests
│   └── Resources/            <-- API resources (LookupResource, SuccessResource, etc.)
├── Livewire/                 <-- Livewire Components & Forms
│   ├── Concerns/             <-- Shared Livewire traits (WithModal, WithToast)
│   └── Forms/                <-- Livewire Form Objects
├── Providers/                <-- AppServiceProvider, UiServiceProvider
└── UI/                       <-- UI-specific logic
    ├── Actions/              <-- UI-layer actions (SetSeoMetadata, ApplyLayoutMetadata)
    ├── Enums/                <-- UI-specific enums (FileType, InputType)
    └── Support/              <-- UI helper classes (LayoutState, StyledExport)
```

---

## 3. Naming Conventions

This architecture uses a strict, intentional naming language. Every name must communicate **Business Intent**, not database operations.

### 3.1 Domain Folders (`app/Domains/{Name}/`)

Domain names are **Business Concepts**, not technical layers. They must be a singular noun that describes a bounded context.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Identity` | `Users` | Identity covers auth, roles, and user lifecycle — not just a table |
| `Account` | `Profile` | Account owns the full user account surface, not one model |
| `System` | `Utils` / `Helpers` | System is a real bounded context for cross-cutting infrastructure |

### 3.2 Capability Folders (Action / DTO / Event / Listener subdirectories)

Subdirectories inside `Actions/`, `DTOs/`, `Events/`, and `Listeners/` must be named after **Business Capabilities**, not database nouns.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `Onboarding/` | `Users/` | Describes the lifecycle stage, not the DB table |
| `AccessControl/` | `Roles/` | Describes the capability, not the resource |
| `Governance/` | `Admin/` | Describes the compliance intent |
| `Passwords/` | `Auth/` | Narrow, precise scope |
| `Registration/` | `Signup/` | Uses the system's formal language |

**Rule:** If a folder name is also a valid Eloquent Model name, it is wrong.

### 3.3 Action Class Names

Actions must be named after the **specific Business Intent** they fulfill. Use an active verb + business noun pattern.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Describes *who* triggers it and *why* |
| `SuspendUser` | `DeleteUser` | Reveals the business consequence (soft revoke, not destroy) |
| `UpdateUserRole` | `SaveRole` | Explicit about the subject and property being changed |
| `RegisterUser` | `StoreUser` | Domain language, not HTTP verb language |
| `SendPasswordResetLink` | `ResetPassword` | Reflects the actual side effect triggered |

CRUD names (`CreateCategory`, `UpdateSetting`) are only acceptable for trivial lookup tables with **no side effects**.

### 3.4 DTO Class Names

DTOs are named after the Action they serve, with a `DTO` suffix.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 3.5 Event Class Names

Events are **past-tense facts** about something that already happened in the domain. The class name must be grammatically a completed truth.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Explicit past-tense removes ambiguity |
| `UserWasSuspended` | `UserSuspended` | Reads as a state, not a completed fact |
| `UserLoggedIn` | `LoginEvent` | Noun + verb pattern; avoids the `Event` suffix |
| `UserEmailVerified` | `EmailVerification` | Describes the completed action |

**Rule:** Never suffix Events with `Event` (e.g., `UserRegisteredEvent` is wrong). The namespace `Events\` already communicates the type.

### 3.6 Listener Class Names

Listeners describe the **active reaction** to an event using an imperative verb phrase.

| ✅ Correct | ❌ Wrong | Why |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Describes what the listener *does*, not what it reacts to |
| `DispatchWelcomeNotification` | `WelcomeListener` | Imperative verb makes the intent crystal clear |

**Rule:** Never suffix Listeners with `Listener` in the class name. The namespace `Listeners\` already communicates the type.

---

## 4. Mutation Verbs Matrix

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
