<?php

use App\Domains\Identity\Actions\Onboarding\ResendVerificationEmail;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can resend verification email', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $action = new ResendVerificationEmail;
    $action->execute($user);

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});
