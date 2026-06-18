<?php

use App\Domains\Identity\Actions\Governance\SuspendUser;
use App\Domains\Identity\Actions\Governance\UpdateUserStatus;
use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserIdentity;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Events\Onboarding\UserWasProvisioned;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('ProvisionNewUser action creates user, assigns role, and dispatches event', function () {
    Event::fake();

    $dto = new ProvisionUserDTO(
        name: 'Jane Smith',
        email: 'jane@example.com',
        password: 'adminpassword123',
        role: RoleType::USER->value
    );

    $action = app(ProvisionNewUser::class);
    $user = $action->execute($dto);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('Jane Smith');
    expect($user->hasRole(RoleType::USER->value))->toBeTrue();

    Event::assertDispatched(UserWasProvisioned::class);
});

test('SuspendUser action throws an exception for admin user', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleType::ADMIN->value);

    $action = app(SuspendUser::class);

    expect(fn () => $action->execute($admin))
        ->toThrow(Exception::class, "You can't suspend an admin user.");
});

test('SuspendUser action sets active user to inactive and dispatches event', function () {
    Event::fake();

    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);
    $user->assignRole(RoleType::USER->value);

    $action = app(SuspendUser::class);
    $action->execute($user);

    expect($user->refresh()->status)->toBe(UserStatus::INACTIVE);

    Event::assertDispatched(UserWasSuspended::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});

test('SuspendUser action deletes inactive user from database', function () {
    $user = User::factory()->create(['status' => UserStatus::INACTIVE]);
    $user->assignRole(RoleType::USER->value);

    $action = app(SuspendUser::class);
    $action->execute($user);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

test('UpdateUser action updates user details and role without dirty email', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $user->assignRole(RoleType::USER->value);

    $action = app(UpdateUserIdentity::class);
    $result = $action->execute($user, new UpdateUserIdentityDTO(
        name: 'Updated Name',
        email: 'original@example.com',
    ));

    expect($result)->toBeInstanceOf(User::class);
    $user->refresh();

    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('original@example.com');
    expect($user->email_verified_at)->not->toBeNull();
});

test('UpdateUser action marks email unverified and resends verification if email is changed', function () {
    Notification::fake();

    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $user->assignRole(RoleType::USER->value);

    $action = app(UpdateUserIdentity::class);
    $result = $action->execute($user, new UpdateUserIdentityDTO(
        name: 'Updated Name',
        email: 'newemail@example.com',
    ));

    expect($result)->toBeInstanceOf(User::class);
    $user->refresh();

    expect($user->email)->toBe('newemail@example.com');
    expect($user->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('UpdateUserStatus action updates user status', function () {
    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);

    $action = app(UpdateUserStatus::class);
    $action->execute($user, UserStatus::INACTIVE);

    expect($user->refresh()->status)->toBe(UserStatus::INACTIVE);
});

test('UpdateUserStatus action throws exception when setting current status', function () {
    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);

    $action = app(UpdateUserStatus::class);

    $thrown = false;
    try {
        $action->execute($user, UserStatus::ACTIVE);
    } catch (Throwable $e) {
        $thrown = true;
    }

    expect($thrown)->toBeTrue();
});
