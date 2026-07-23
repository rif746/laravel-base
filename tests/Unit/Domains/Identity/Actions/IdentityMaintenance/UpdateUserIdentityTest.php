<?php

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserIdentity;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can update user identity without changing email', function () {
    Notification::fake();

    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'test@example.com',
        'email_verified_at' => now(),
    ]);

    $mockUpdateUserRole = $this->mock(UpdateUserRole::class);

    $dto = new UpdateUserIdentityDTO(
        name: 'New Name',
        email: 'test@example.com',
    );

    $action = new UpdateUserIdentity($mockUpdateUserRole);
    $action->execute($user, $dto);

    expect($user->fresh()->name)->toBe('New Name')
        ->and($user->fresh()->email)->toBe('test@example.com')
        ->and($user->fresh()->email_verified_at)->not->toBeNull();

    Notification::assertNothingSent();
});

test('it resets verification and sends notification when email changes', function () {
    Notification::fake();

    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'email_verified_at' => now(),
    ]);

    $mockUpdateUserRole = $this->mock(UpdateUserRole::class);

    $dto = new UpdateUserIdentityDTO(
        name: 'New Name',
        email: 'new@example.com',
    );

    $action = new UpdateUserIdentity($mockUpdateUserRole);
    $action->execute($user, $dto);

    $updatedUser = $user->fresh();
    expect($updatedUser->name)->toBe('New Name')
        ->and($updatedUser->email)->toBe('new@example.com')
        ->and($updatedUser->email_verified_at)->toBeNull();

    Notification::assertSentTo($updatedUser, VerifyEmailNotification::class);
});
