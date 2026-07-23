<?php

use App\Domains\Identity\Actions\Passwords\SendPasswordResetLink;
use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can send password reset link', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $dto = new ForgotPasswordDTO(
        email: 'test@example.com',
    );

    $action = new SendPasswordResetLink;
    $status = $action->execute($dto);

    expect($status)->toBe(Password::RESET_LINK_SENT);
});

test('it returns user not found if email does not exist', function () {
    $dto = new ForgotPasswordDTO(
        email: 'nonexistent@example.com',
    );

    $action = new SendPasswordResetLink;
    $status = $action->execute($dto);

    expect($status)->toBe(Password::INVALID_USER);
});
