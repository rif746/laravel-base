# System Services

This document covers the shared, cross-cutting services provided by the `System` domain: Universal File Management, Dynamic UIs with Renderable Enums, and the Excel Import & Export Engine.

---

## 1. Universal File Management (The `HasFile` Trait)

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
use App\Domains\System\Concerns\HasFile;
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

To avoid polluting Laravel's global namespace with a junk `app/helpers.php` file, we maintain a strictly domain-bound helper file at `app/Domains/System/Helpers/asset.php`. It is autoloaded via `composer.json` and provides the `asset_static()` function to elegantly resolve public and private files in Blade views.

### File Reconciliation (Pruning)

Over time, the database might contain records for files that no longer exist on disk, or the disk might contain files that are no longer referenced in the database. Use the prune command to reconcile these:

```bash
php artisan system:prune-files --disk=public --directory=uploads
```

---

## 2. Dynamic UIs & Renderable Enums

When building data-driven interfaces (like dynamic settings forms), we utilize the **Renderable Enum** pattern combined with Laravel's native dynamic components.

* Enums act as **Metadata Providers** (returning the string name of the target Blade component).
* We use `<x-dynamic-component>` in the Blade file to swap UI elements.
* **CRITICAL:** Enums must never return raw HTML strings compiled via the `Blade` facade. Doing so breaks Livewire's DOM-diffing engine and severs `wire:model` bindings.

---

## 3. Excel Import & Export Engine

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

### Generating Export, Mapper & Other Domain Classes

Use the `domain:make` command to create Export, Integration, and infrastructure classes:

```bash
# Generate a domain Export class
php artisan domain:make export Identity UserExport --model=User

# Generate an Integration Mapper (auto-appends DataMapper suffix)
php artisan domain:make mapper Identity User
# → app/Domains/Identity/Integration/Mappers/UserDataMapper.php

# Generate a custom Eloquent Cast (with typed @implements hint)
php artisan domain:make cast System AsMoneyAmount --model=Money
# → app/Domains/System/Casts/AsMoneyAmount.php

# Generate a Value Object (Stringable)
php artisan domain:make value-object System Money
# → app/Domains/System/Support/ValueObjects/Money.php

# Generate a static Domain Registry (auto-appends Registry suffix)
php artisan domain:make registry System Feature
# → app/Domains/System/Support/Registry/FeatureRegistry.php

# Generate an Eloquent Observer (auto-appends Observer suffix)
php artisan domain:make observer System Backup
# → app/Domains/System/Observers/BackupObserver.php

# Generate an Integration Interface
php artisan domain:make integration-interface System ExternalPaymentGateway
# → app/Domains/System/Support/Integration/ExternalPaymentGateway.php
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
