<?php

use App\Domains\Identity\Actions\Passwords\ResetUserPassword;
use App\Domains\Identity\DTOs\Passwords\ResetPasswordDTO;
use App\Domains\Identity\Events\Passwords\UserPasswordReset;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can reset user password', function () {
    Event::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'old_password',
    ]);

    $token = Password::createToken($user);

    $dto = new ResetPasswordDTO(
        token: $token,
        email: 'test@example.com',
        password: 'new_password',
        password_confirmation: 'new_password',
    );

    $action = new ResetUserPassword;
    $status = $action->execute($dto);

    expect($status)->toBe(Password::PASSWORD_RESET)
        ->and(Hash::check('new_password', $user->fresh()->password))->toBeTrue();

    Event::assertDispatched(PasswordReset::class);
    Event::assertDispatched(UserPasswordReset::class);
});

test('it fails to reset password with invalid token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'old_password',
    ]);

    $dto = new ResetPasswordDTO(
        token: 'invalid-token',
        email: 'test@example.com',
        password: 'new_password',
        password_confirmation: 'new_password',
    );

    $action = new ResetUserPassword;
    $status = $action->execute($dto);

    expect($status)->toBe(Password::INVALID_TOKEN)
        ->and(Hash::check('old_password', $user->fresh()->password))->toBeTrue();
});
