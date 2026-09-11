# Domain Rules

This document defines the core rules of engagement for writing business logic inside the `app/Domains/` directory.

---

## 1. DTOs (Data Transfer Objects)

All untrusted data from the Gateway must be packed into a strictly typed, readonly DTO before entering the Domain.

* DTOs only contain data meant to change state.
* Do not put Eloquent models inside DTOs. Pass the Model as a separate parameter to the Action.
* DTOs are named after the Action they serve, with a `DTO` suffix (e.g., `ProvisionUserDTO` for `ProvisionNewUser`).

---

## 2. Actions (The Executors)

Actions are the **only** place database mutations (`create`, `update`, `delete`, `syncRoles`) are allowed.

* Actions must have a single responsibility.
* Use `DB::transaction()` inside Actions when multiple database writes (e.g., creating a user and assigning a Spatie role) must succeed or fail together.
* Use **Action Composition** (injecting one Action into another via the constructor) to reuse logic without duplicating code.

**Example of Action Composition:**

```php
// Injecting AccessControl\UpdateUserRole into Onboarding\ProvisionNewUser
class ProvisionNewUser
{
    public function __construct(
        private readonly UpdateUserRole $updateUserRole,
    ) {}

    public function execute(ProvisionUserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $user = User::create([...]);
            $this->updateUserRole->execute($user, $dto->roleId);
            UserWasProvisioned::dispatch($user);
            return $user;
        });
    }
}
```

---

## 3. Events & Listeners

Use Event-Driven Architecture for all side effects (emails, logging, background processing).

* The Gateway dispatches Events for non-mutating session facts (e.g., `UserLoggedIn`).
* Actions dispatch Events immediately after a successful state mutation (e.g., `UserWasProvisioned`).
* Listeners handle the reaction outside the main HTTP lifecycle and are the **only** place `Notification::send()`, `Mail::send()`, or logging calls are made in response to a Domain Event.
* Events and Listeners must be **grouped under the same capability folder** as the Action that dispatches them (e.g., `Events/Onboarding/`, `Listeners/Onboarding/`).

---

## 4. Audit Logging & Tracking

All critical database mutations are tracked to maintain a compliant historical ledger.

* **Model Auditing:** We utilize Eloquent events to automatically log row changes.
* **Complex Relation Auditing:** Tracking many-to-many relationship changes (like Spatie `syncPermissions`) bypasses standard Eloquent events. Therefore, we explicitly dispatch Custom Audit Events directly inside the relevant Domain Action (e.g., `UpdateRolePermissions`). This guarantees the "Before" and "After" state is captured cleanly in a single transactional row.

---

## 5. Global Settings & Application State

Settings that dictate the runtime state of the application (Timezones, Localization, SEO tags) are managed by the `System` domain to ensure high performance and context awareness.

* **Memoization & Singletons:** The `GetSystemSettings` query is registered as a Singleton in the `SystemServiceProvider`. It fetches data from the database/cache *once* (using the cache key defined in `SystemSettings::$cacheName`) and stores it in local PHP memory for the duration of the request.
* **Contextual Middlewares:** We use dedicated middlewares (`HandlePreferredLanguage`, `HandlePreferredTimezone`) to dynamically check the authenticated user's preferences, falling back to the global settings if no preference exists.
* **View Composers:** Global layout variables (like Logos and Web Names) are injected globally via View Composers in the `ViewServiceProvider` (registered by the `SystemServiceProvider`), preventing repetitive `@inject` directives.

---

## 6. Multi-Language Models (The HasTranslation Trait)

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
use App\Domains\System\Concerns\Model\HasTranslation;

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
