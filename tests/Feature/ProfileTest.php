<?php

use App\Domains\Identity\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated via livewire component', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::account.profile.update-profile-modal')
        ->call('show', $user->id)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.gender', 'male')
        ->set('form.date_of_birth', '1995-01-01')
        ->set('form.phone_number', '081234567890')
        ->call('save');

    $component->assertHasNoErrors();

    $user->refresh();
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
});

test('password can be updated', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::account.profile.update-password-modal')
        ->set('form.current_password', 'password')
        ->set('form.new_password', 'new-password')
        ->set('form.new_password_confirmation', 'new-password')
        ->call('save');

    $component->assertHasNoErrors();

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::account.profile.update-password-modal')
        ->set('form.current_password', 'wrong-password')
        ->set('form.new_password', 'new-password')
        ->set('form.new_password_confirmation', 'new-password')
        ->call('save');

    $component->assertHasErrors(['form.current_password']);
});
