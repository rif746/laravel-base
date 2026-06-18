<?php

use App\Domains\Identity\Models\Permission;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Policies\RolePolicy;
use App\Domains\Identity\Policies\UserPolicy;
use App\Domains\System\Policies\SystemSettingPolicy;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('UserPolicy checks correct permissions', function () {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    $policy = new UserPolicy;

    // 1. viewAny / view
    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $targetUser))->toBeFalse();
    $user->givePermissionTo('user index');
    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->view($user, $targetUser))->toBeTrue();

    // 2. create
    $user->revokePermissionTo('user index');
    expect($policy->create($user))->toBeFalse();
    $user->givePermissionTo('user create');
    expect($policy->create($user))->toBeTrue();

    // 3. update
    expect($policy->update($user, $targetUser))->toBeFalse();
    $user->givePermissionTo('user edit');
    expect($policy->update($user, $targetUser))->toBeTrue();

    // 4. delete
    expect($policy->delete($user, $targetUser))->toBeFalse();
    $user->givePermissionTo('user delete');
    expect($policy->delete($user, $targetUser))->toBeTrue();
});

test('RolePolicy checks correct permissions', function () {
    $user = User::factory()->create();
    $targetRole = Role::create(['name' => 'CustomRole', 'guard_name' => 'web']);

    $policy = new RolePolicy;

    // 1. viewAny / view
    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $targetRole))->toBeFalse();
    $user->givePermissionTo('role index');
    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->view($user, $targetRole))->toBeTrue();

    // 2. create
    $user->revokePermissionTo('role index');
    expect($policy->create($user))->toBeFalse();
    $user->givePermissionTo('role create');
    expect($policy->create($user))->toBeTrue();

    // 3. update
    expect($policy->update($user, $targetRole))->toBeFalse();
    $user->givePermissionTo('role edit');
    expect($policy->update($user, $targetRole))->toBeTrue();

    // 4. delete
    expect($policy->delete($user, $targetRole))->toBeFalse();
    $user->givePermissionTo('role delete');
    expect($policy->delete($user, $targetRole))->toBeTrue();
});

test('SystemSettingPolicy checks correct permissions', function () {
    $user = User::factory()->create();
    $policy = new SystemSettingPolicy;

    expect($policy->viewAny($user))->toBeFalse();

    Permission::create([
        'name' => 'system-setting index',
        'guard_name' => 'web',
        'description' => 'System settings index',
        'group' => 'system-setting',
    ]);

    $user->givePermissionTo('system-setting index');
    expect($policy->viewAny($user))->toBeTrue();
});
