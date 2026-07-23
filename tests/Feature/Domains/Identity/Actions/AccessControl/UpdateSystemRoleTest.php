<?php

use App\Domains\Identity\Actions\AccessControl\UpdateSystemRole;
use App\Domains\Identity\DTOs\AccessControl\UpdateRoleDTO;
use App\Domains\Identity\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('it can update a system role permissions', function () {
    // Setup
    $role = Role::create(['name' => 'Existing Role', 'guard_name' => 'web']);
    $permission1 = Permission::create(['name' => 'test.permission.1', 'guard_name' => 'web', 'description' => 'test', 'group' => 'test']);
    $permission2 = Permission::create(['name' => 'test.permission.2', 'guard_name' => 'web', 'description' => 'test', 'group' => 'test']);

    $role->givePermissionTo('test.permission.1');

    $dto = new UpdateRoleDTO(
        permissions: ['test.permission.2']
    );

    $action = new UpdateSystemRole;

    // Execute
    $result = $action->execute($role, $dto);

    // Assert
    expect($result)->toBeTrue();
    expect($role->hasPermissionTo('test.permission.1'))->toBeFalse()
        ->and($role->hasPermissionTo('test.permission.2'))->toBeTrue();
});
