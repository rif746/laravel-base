<?php

use App\Domains\Identity\Actions\Onboarding\VerifyUserEmail;
use App\Domains\Identity\Events\Onboarding\UserEmailWasVerified;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can verify user email', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $action = new VerifyUserEmail;
    $result = $action->execute($user);

    expect($result)->toBeTrue()
        ->and($user->fresh()->hasVerifiedEmail())->toBeTrue();

    Event::assertDispatched(Verified::class);
    Event::assertDispatched(UserEmailWasVerified::class);
});

test('it returns false if email is already verified', function () {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $action = new VerifyUserEmail;
    $result = $action->execute($user);

    expect($result)->toBeFalse();

    Event::assertNotDispatched(Verified::class);
    Event::assertNotDispatched(UserEmailWasVerified::class);
});
