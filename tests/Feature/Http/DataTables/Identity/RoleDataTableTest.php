<?php

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\User;
use Database\Seeders\RoleSeeder;

test('role index page can be rendered', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole(RoleType::SYSTEM_ADMIN->value);
    $response = $this->actingAs($user)->get(route('roles.index'));

    $response->assertStatus(200);
    $response->assertSee('role-table');
});

test('role datatable returns data in json format', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole(RoleType::SYSTEM_ADMIN->value);

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('roles.index', ['draw' => 1]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ])
        ->assertJsonPath('data.0.name', RoleType::SYSTEM_ADMIN->value);
});
