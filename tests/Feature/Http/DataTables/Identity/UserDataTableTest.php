<?php

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Models\User;
use Database\Seeders\RoleSeeder;

test('user index page can be rendered', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole(RoleType::SYSTEM_ADMIN->value);
    // Assuming the user needs permission to view users, but for now just acting as a user
    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(200);
    $response->assertSee('user-table');
});

test('user datatable returns data in json format', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create(['name' => 'John Doe']);
    $user->assignRole(RoleType::SYSTEM_ADMIN->value);

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('users.index', ['draw' => 1]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ])
        ->assertJsonPath('recordsTotal', 1)
        ->assertJsonPath('data.0.name', 'John Doe');
});

test('user datatable can be filtered by role', function () {
    $this->seed(RoleSeeder::class);

    $admin = User::factory()->create(['name' => 'Admin User']);
    $admin->assignRole(RoleType::ADMIN->value);

    $regularUser = User::factory()->create(['name' => 'Regular User']);
    $regularUser->assignRole(RoleType::USER->value);

    $response = $this->actingAs($admin)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('users.index', [
            'draw' => 1,
            'role' => RoleType::ADMIN->value,
        ]));

    $response->assertStatus(200)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Admin User');
});

test('user datatable can be filtered by status', function () {
    $this->seed(RoleSeeder::class);
    $activeUser = User::factory()->create([
        'name' => 'Active User',
        'status' => UserStatus::ACTIVE,
    ]);
    $activeUser->assignRole(RoleType::SYSTEM_ADMIN->value);
    $inactiveUser = User::factory()->create([
        'name' => 'Inactive User',
        'status' => UserStatus::INACTIVE,
    ]);

    $response = $this->actingAs($activeUser)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('users.index', [
            'draw' => 1,
            'status' => UserStatus::ACTIVE->value,
        ]));

    $response->assertStatus(200)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Active User');
});
