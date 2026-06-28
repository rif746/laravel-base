<?php

use Database\Seeders\RoleSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new users can register', function () {
    $component = Livewire::test('pages::auth.register')
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->set('form.password_confirmation', 'password')
        ->call('register');

    $component->assertHasNoErrors();
    $this->assertAuthenticated();
    $component->assertRedirect(route('dashboard', absolute: false));
});
