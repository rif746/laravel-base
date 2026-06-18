<?php

use App\Domains\Identity\Actions\Onboarding\RegisterSelfServiceUser;
use App\Domains\Identity\Actions\Onboarding\ResendVerificationEmail;
use App\Domains\Identity\Actions\Onboarding\VerifyUserEmail;
use App\Domains\Identity\DTOs\Onboarding\RegisterSelfServiceUserDTO;
use App\Domains\Identity\Events\Onboarding\UserEmailWasVerified;
use App\Domains\Identity\Events\Onboarding\UserWasRegistered;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('RegisterUser action registers a user, fires events, and stores in database', function () {
    Event::fake();

    $dto = new RegisterSelfServiceUserDTO(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'securepassword123'
    );

    $action = app(RegisterSelfServiceUser::class);
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

    Event::assertDispatched(UserWasRegistered::class, function ($event) use ($user, $dto) {
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

    Event::assertDispatched(UserEmailWasVerified::class, function ($event) use ($user) {
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
    Event::assertNotDispatched(UserEmailWasVerified::class);
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
