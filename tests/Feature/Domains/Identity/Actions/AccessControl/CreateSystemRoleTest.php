<?php

use App\Domains\Identity\Actions\AccessControl\CreateSystemRole;
use App\Domains\Identity\DTOs\AccessControl\CreateRoleDTO;
use App\Domains\Identity\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('it can create a system role with permissions', function () {
    // Setup
    $permission1 = Permission::create(['name' => 'test.permission.1', 'guard_name' => 'web', 'description' => 'test', 'group' => 'test']);
    $permission2 = Permission::create(['name' => 'test.permission.2', 'guard_name' => 'web', 'description' => 'test', 'group' => 'test']);

    $dto = new CreateRoleDTO(
        name: 'New Role',
        guard_name: 'web',
        permissions: ['test.permission.1', 'test.permission.2']
    );

    $action = new CreateSystemRole();

    // Execute
    $result = $action->execute($dto);

    // Assert
    expect($result)->toBeTrue();
    $this->assertDatabaseHas('roles', [
        'name' => 'New Role',
        'guard_name' => 'web'
    ]);

    $role = Role::where('name', 'New Role')->first();
    expect($role->hasPermissionTo('test.permission.1'))->toBeTrue()
        ->and($role->hasPermissionTo('test.permission.2'))->toBeTrue();
});
