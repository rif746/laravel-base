<?php

use App\Domains\Identity\Actions\Registration\RegisterUser;
use App\Domains\Identity\Actions\Registration\ResendVerificationEmail;
use App\Domains\Identity\Actions\Registration\VerifyUserEmail;
use App\Domains\Identity\DTOs\Registration\RegisterUserDTO;
use App\Domains\Identity\Events\Registration\UserEmailVerified;
use App\Domains\Identity\Events\Registration\UserRegistered;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('RegisterUser action registers a user, fires events, and stores in database', function () {
    Event::fake();

    $dto = new RegisterUserDTO(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'securepassword123'
    );

    $action = app(RegisterUser::class);
    $user = $action->execute($dto);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('john@example.com');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Event::assertDispatched(Registered::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    Event::assertDispatched(UserRegistered::class, function ($event) use ($user, $dto) {
        return $event->user->id === $user->id && $event->dto === $dto;
    });
});

test('VerifyUserEmail action marks email as verified and dispatches events', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    expect($user->hasVerifiedEmail())->toBeFalse();

    $action = app(VerifyUserEmail::class);
    $result = $action->execute($user);

    expect($result)->toBeTrue();
    expect($user->refresh()->hasVerifiedEmail())->toBeTrue();

    Event::assertDispatched(Verified::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    Event::assertDispatched(UserEmailVerified::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});

test('VerifyUserEmail action returns false if email is already verified', function () {
    Event::fake();

    $user = User::factory()->create(); // already verified by default in factory

    expect($user->hasVerifiedEmail())->toBeTrue();

    $action = app(VerifyUserEmail::class);
    $result = $action->execute($user);

    expect($result)->toBeFalse();

    Event::assertNotDispatched(Verified::class);
    Event::assertNotDispatched(UserEmailVerified::class);
});

test('ResendVerificationEmail action triggers VerifyEmailNotification', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $action = app(ResendVerificationEmail::class);
    $action->execute($user);

    Notification::assertSentTo(
        $user,
        VerifyEmailNotification::class
    );
});
