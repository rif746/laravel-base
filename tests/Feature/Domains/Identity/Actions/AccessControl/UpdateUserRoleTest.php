<?php

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can sync user roles', function () {
    // Setup
    $user = User::factory()->create();
    $role1 = Role::create(['name' => 'Role 1', 'guard_name' => 'web']);
    $role2 = Role::create(['name' => 'Role 2', 'guard_name' => 'web']);

    $user->assignRole($role1);

    $action = new UpdateUserRole;

    // Execute
    $action->execute($user, ['Role 2']);

    // Assert
    expect($user->hasRole('Role 1'))->toBeFalse()
        ->and($user->hasRole('Role 2'))->toBeTrue();
});
