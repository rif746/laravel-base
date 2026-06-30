<?php

use App\Domains\Identity\Actions\AccessControl\RemoveSystemRole;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it throws exception when removing a protected system role', function () {
    $role = Role::create(['name' => RoleType::SYSTEM_ADMIN->value, 'guard_name' => 'web']);
    $action = new RemoveSystemRole();

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Can\'t remove system role.');

    $action->execute($role);
});

test('it throws exception when role has attached users', function () {
    $role = Role::create(['name' => 'Custom Role', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role->name);

    $action = new RemoveSystemRole();

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('This role has a users attached to it.');

    $action->execute($role);
});

test('it does not throw exception for removable role', function () {
    $role = Role::create(['name' => 'Custom Role', 'guard_name' => 'web']);
    $action = new RemoveSystemRole();

    $action->execute($role);

    expect(true)->toBeTrue(); // If no exception, it passes
});
