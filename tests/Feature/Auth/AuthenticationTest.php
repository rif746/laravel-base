<?php

use App\Domains\Identity\Models\User;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Livewire::test('pages::auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login');

    $component->assertHasNoErrors();
    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Livewire::test('pages::auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password')
        ->call('login');

    $component->assertHasErrors(['form.email']);
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('layouts::profile-dropdown')
        ->call('logout');

    $component->assertRedirect(route('login'));
    $this->assertGuest();
});
