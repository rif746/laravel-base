<?php

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;

test('it can lookup roles via api', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.v1.lookups.roles', ['search' => 'admin']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', 'admin')
        ->assertJsonPath('data.0.text', 'admin');
});

test('it can fetch all roles when no search is provided', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.v1.lookups.roles'));

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});
