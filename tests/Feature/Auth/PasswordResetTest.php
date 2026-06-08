<?php

use App\Domains\Identity\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $component = Livewire::test('pages::auth.forgot-password')
        ->set('email', $user->email)
        ->call('forgotPassword');

    $component->assertHasNoErrors();
    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->get('/reset-password/dummy-token?email='.urlencode($user->email));

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Trigger sending token
    Livewire::test('pages::auth.forgot-password')
        ->set('email', $user->email)
        ->call('forgotPassword');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $component = Livewire::test('pages::auth.reset-password')
            ->set('token', $notification->token)
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword');

        $component->assertHasNoErrors();
        $component->assertRedirect(route('login'));

        return true;
    });
});
