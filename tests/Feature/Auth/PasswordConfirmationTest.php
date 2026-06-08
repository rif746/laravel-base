<?php

use App\Domains\Identity\Models\User;
use Livewire\Livewire;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::auth.confirm-password')
        ->set('password', 'password')
        ->call('confirmPassword');

    $component->assertHasNoErrors();
    expect(session()->has('auth.password_confirmed_at'))->toBeTrue();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::auth.confirm-password')
        ->set('password', 'wrong-password')
        ->call('confirmPassword');

    $component->assertHasErrors(['password']);
});
