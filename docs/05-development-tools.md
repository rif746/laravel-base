# Development Tools

This document covers the custom code generators (`domain:make` and related commands), stub customization, and Pest PHP testing strategies.

---

## 1. The `domain:make` Command

To maintain the strict folder structure of the Antigravity architecture, **do not use standard `make:` commands (like `make:model`) for Domain files.** Use the custom `domain:make` command to generate classes in the correct `app/Domains/` namespaces.

### Signature

```bash
php artisan domain:make {type} {domain} {name} [options]
```

### Arguments

* `type`: The file type to generate. See the table below for all supported types.
* `domain`: The target Domain folder (e.g., `Identity`, `Account`, `System`).
* `name`: The class name. Supports sub-directory grouping (e.g., `Management/ProvisionNewUser`).

### Options

* `--factory`: Generates an associated database factory (Models only).
* `--migration`: Generates a database migration file (Models only).
* `--policy`: Generates an associated policy (Models only).
* `--all`: Generates a factory, migration, and policy together (Models only).
* `--model=`: Associates the export with an Eloquent model (Exports), or sets the `@implements` hint for the value-object type (Casts).

### Supported Types

| Type | Output Directory | Auto-suffix | Notes |
|---|---|---|---|
| `model` | `Models/` | — | Supports `--factory`, `--migration`, `--policy`, `--all` |
| `action` | `Actions/` | — | |
| `dto` | `DTOs/` | — | Generates a `readonly` class |
| `enum` | `Enums/` | — | |
| `event` | `Events/` | — | |
| `listener` | `Listeners/` | — | |
| `notification` | `Notifications/` | — | |
| `policy` | `Policies/` | — | |
| `scope` | `Scopes/` | — | Implements `Illuminate\Database\Eloquent\Scope` |
| `trait` | `Traits/` | — | |
| `query` | `Queries/` | — | For complex, read-only data retrieval |
| `provider` | `Providers/` | — | |
| `relationship-provider` | `Providers/` | — | Pre-wired for `resolveRelationUsing()` cross-domain bindings |
| `view-provider` | `Providers/` | — | Pre-wired with a View Composer example |
| `export` | `Exports/` | — | Supports `--model=` |
| `mapper` | `Integration/Mappers/` | `DataMapper` | Implements `DataPayloadMapper`; lives in fixed sub-directory |
| `mailable` | `Mail/` | — | |
| `cast` | `Casts/` | — | Implements `CastsAttributes`; use `--model=ValueObjectClass` for typed hint |
| `value-object` | `Support/ValueObjects/` | — | Implements `Stringable`; lives in fixed sub-directory |
| `registry` | `Support/Registry/` | `Registry` | Static registry with `register()`, `all()`, `flush()`; fixed sub-directory |
| `observer` | `Observers/` | `Observer` | Eloquent observer with `created`, `updated`, `deleted`, `restored` hooks |
| `integration-interface` | `Support/Integration/` | — | PHP `interface` scaffold; lives in fixed sub-directory |

---

## 2. The `domain:datatable` Command

Generates a Yajra DataTable service class inside `app/Http/DataTables/{Domain}/` and a corresponding index Blade view inside `resources/views/pages/{domainLower}/{capability}/`.

### Signature

```bash
php artisan domain:datatable {name} {domain} [--model=]
```

---

## 3. The `domain:make-page` Command

Generates a Blade view or a unified Livewire modal component inside `resources/views/pages/{domain}/{capability}/`.

### Signature

```bash
php artisan domain:make-page {domain} {capability} {name} [--modal]
```

* Use `--modal` to generate a "Lightning Component" (⚡) which includes both a PHP class and a Blade view in the same directory.

---

## 4. The `domain:new` Command

This scaffolds a brand-new domain structure by establishing its main `ServiceProvider` (pre-wired with the `RegistersDomainEvents` trait) and its companion `RelationshipServiceProvider` (intended solely for `Model::resolveRelationUsing()` mapping), registering the main provider within `bootstrap/providers.php` automatically.

### Signature

```bash
php artisan domain:new {domain}
```

---

## 5. Purpose & Usage of Domain Providers

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

---

## 6. Customizing Generators (Stubs)

All `domain:make` templates are stored as `.stub` files in `app/Console/stubs/domain-make/`. You can freely edit these stubs to customize the default boilerplate for your project's specific needs (e.g., changing the default methods in a Repository or adjusting the strict typing in a DTO).

---

## 7. Pest PHP Testing Strategies

We use [Pest PHP](https://pestphp.com) for our test suite. See also [TESTING.md](../TESTING.md) for quick command reference.

### Running Tests

| Command | Description |
|---|---|
| `composer test` | Run all tests. |
| `php artisan test --testsuite=Feature` | Run Feature tests only. |
| `php artisan test --testsuite=Unit` | Run Unit tests only. |
| `php artisan test --testsuite=Architecture` | Run Architecture integrity tests. |
| `php artisan test tests/Feature/Identity/LoginTest.php` | Run a specific test file. |
| `php artisan test --filter=test_user_can_login` | Run a specific test by name. |

### Test Directory Structure

Tests follow a strict directory structure that mirrors the application's domain-based architecture:

- `tests/Feature/`: High-level application features. These tests interact with the application like a user (HTTP requests, Livewire components, form submissions).
- `tests/Unit/`: Low-level logic, helpers, and domain-specific logic. These tests should be fast and have zero external dependencies.
- `tests/Architecture/`: Ensures the integrity of our Modular Monolith (e.g., verifying that the Domain layer does not depend on the HTTP layer).

### Writing Domain Tests

When testing a new Domain feature, create a corresponding test file in the `tests/Feature/` or `tests/Unit/` directory.

**Example:** If you create `app/Domains/Identity/Actions/CreateUser.php`, create a test at `tests/Unit/Domains/Identity/Actions/CreateUserTest.php`.

### Testing Events

```php
use App\Domains\Identity\Events\UserLoggedIn;
use Illuminate\Support\Facades\Event;

it('dispatches the user logged in event', function () {
    Event::fake();

    // Perform action
    $this->post('/login', [...]);

    Event::assertDispatched(UserLoggedIn::class);
});
```

### Testing Jobs

```php
use App\Domains\System\Jobs\NotifyExportReady;
use Illuminate\Support\Facades\Queue;

it('queues the export notification job', function () {
    Queue::fake();

    // Perform action that triggers the job
    // ...

    Queue::assertPushed(NotifyExportReady::class);
});
```

### Testing Notifications

```php
use App\Domains\Identity\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

it('sends a welcome notification', function () {
    Notification::fake();

    // Perform action
    // ...

    Notification::assertSentTo($user, WelcomeNotification::class);
});
```

### Mocking Injected Services

For services or classes that are dependency-injected, use `mock()` or `spy()`:

```php
use App\Domains\System\Integration\ExternalApiService;

it('uses the external api service', function () {
    $this->mock(ExternalApiService::class, function ($mock) {
        $mock->shouldReceive('call')
            ->once()
            ->andReturn(['status' => 'success']);
    });

    // Run code that uses ExternalApiService
});
```

### Architectural Rules

Architecture tests prevent "spaghetti code." Key rules enforced in `tests/Architecture/`:

- **Layer Isolation:** The `Domains/` directory must not import anything from `Http/` or `Livewire/`.
- **Debugging:** Ensure `dd()` or `dump()` calls are not committed to the repository.

### Best Practices

- **Test Behavior, Not Implementation:** Focus on what the code does, not how it does it.
- **Keep Tests Fast:** Unit tests should run in milliseconds. If a test is slow, it might be a candidate for a unit test rather than a feature test.
- **Use Factories:** Utilize Laravel model factories to generate test data instead of manually creating records.
- **Mock External Services:** Use dependency injection and interfaces to mock external API calls or email services.
- **Use `Event::fake()`, `Queue::fake()`, `Notification::fake()`** to isolate side effects in all Feature tests.
