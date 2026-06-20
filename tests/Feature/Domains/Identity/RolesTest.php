<?php

use App\Domains\Identity\Actions\AccessControl\CreateSystemRole;
use App\Domains\Identity\Actions\AccessControl\RemoveSystemRole;
use App\Domains\Identity\Actions\AccessControl\UpdateSystemRole;
use App\Domains\Identity\DTOs\AccessControl\CreateRoleDTO;
use App\Domains\Identity\DTOs\AccessControl\UpdateRoleDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('CreateSystemRole action creates role and syncs permissions', function () {
    $permissions = ['user.viewAny', 'user.create'];

    $dto = new CreateRoleDTO(
        name: 'Manager',
        guard_name: 'web',
        permissions: $permissions
    );

    $action = app(CreateSystemRole::class);
    $result = $action->execute($dto);

    expect($result)->toBeTrue();

    $role = Role::findByName('Manager', 'web');
    expect($role)->not->toBeNull();
    expect($role->hasPermissionTo('user.viewAny'))->toBeTrue();
    expect($role->hasPermissionTo('user.create'))->toBeTrue();
    expect($role->hasPermissionTo('user.update'))->toBeFalse();
});

test('UpdateSystemRole action syncs new permissions on role', function () {
    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
    $role->syncPermissions(['user.viewAny']);

    $dto = new UpdateRoleDTO(
        permissions: ['user.create', 'user.update']
    );

    $action = app(UpdateSystemRole::class);
    $result = $action->execute($role, $dto);

    expect($result)->toBeTrue();
    expect($role->hasPermissionTo('user.create'))->toBeTrue();
    expect($role->hasPermissionTo('user.update'))->toBeTrue();
    expect($role->hasPermissionTo('user.viewAny'))->toBeFalse();
});

test('RemoveSystemRole action throws exception when deleting Admin role', function () {
    $role = Role::findByName(RoleType::ADMIN->value, 'web');

    $action = app(RemoveSystemRole::class);

    expect(fn () => $action->execute($role))
        ->toThrow(Exception::class, "Can't remove system role.");
});

test('RemoveSystemRole action throws exception when role has users attached', function () {
    $role = Role::findByName(RoleType::USER->value, 'web');

    $user = User::factory()->create();
    $user->assignRole($role);

    $action = app(RemoveSystemRole::class);

    expect(fn () => $action->execute($role))
        ->toThrow(Exception::class, 'This role has a users attached to it.');
});

test('RemoveSystemRole action executes successfully for a clean custom role', function () {
    $role = Role::create(['name' => 'GuestRole', 'guard_name' => 'web']);

    $action = app(RemoveSystemRole::class);

    // Should not throw any exception
    $action->execute($role);
    expect(true)->toBeTrue();
});
