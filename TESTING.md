# Testing Guide

We use [Pest PHP](https://pestphp.com) for our test suite, providing a clean, expressive syntax for testing our Modular Monolith.

## 1. Running Tests

The project includes several pre-configured commands to run the test suite:

- **Run all tests:** `composer test`
- **Run Feature tests:** `php artisan test --testsuite=Feature`
- **Run Unit tests:** `php artisan test --testsuite=Unit`
- **Run Architecture tests:** `php artisan test --testsuite=Architecture`

You can also run specific tests by passing arguments to Artisan:

```bash
# Run a specific file
php artisan test tests/Feature/Identity/LoginTest.php

# Run a specific test method
php artisan test --filter=test_user_can_login
```

## 2. Test Directory Structure

Our tests follow a strict directory structure that mirrors the application's domain-based architecture:

- `tests/Feature/`: High-level application features. These tests interact with the application like a user (HTTP requests, Livewire components, form submissions).
- `tests/Unit/`: Low-level logic, helpers, and domain-specific logic. These tests should be fast and have zero external dependencies.
- `tests/Architecture/`: Ensures the integrity of our Modular Monolith (e.g., verifying that the Domain layer does not depend on the HTTP layer).

## 3. Writing Tests

### Base Class
All tests extend `Tests\TestCase`. This base class sets up the application environment and includes shared traits.

### Database Interaction
By default, Feature tests use the `RefreshDatabase` trait (configured in `tests/Pest.php`). This ensures a clean database state for every test.

### Architectural Rules
We use architecture tests to prevent "spaghetti code." For example:
- **Layer Isolation:** The `Domains/` directory must not import anything from `Http/` or `Livewire/`.
- **Debugging:** Ensure `dd()` or `dump()` calls are not committed to the repository.

### Writing Domain Tests
When testing a new Domain feature, create a corresponding test file in the `tests/Feature/` or `tests/Unit/` directory.

Example: If you create `app/Domains/Identity/Actions/CreateUser.php`, create a test at `tests/Unit/Domains/Identity/Actions/CreateUserTest.php`.

## 4. Best Practices

- **Test Behavior, Not Implementation:** Focus on what the code does, not how it does it.
- **Keep Tests Fast:** Unit tests should run in milliseconds. If a test is slow, it might be a candidate for a unit test rather than a feature test.
- **Use Factories:** Utilize Laravel model factories to generate test data instead of manually creating records.
- **Mock External Services:** Use dependency injection and interfaces to mock external API calls or email services in your tests.

## 5. Advanced Testing

### Testing Events
To assert that events are dispatched, use Laravel's `Event` facade:

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
To assert that jobs are pushed to the queue, use the `Queue` facade:

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
To test notifications, use the `Notification` facade:

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

### Mocking
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
