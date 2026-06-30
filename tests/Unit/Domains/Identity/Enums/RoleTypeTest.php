<?php

use App\Domains\Identity\Enums\RoleType;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct cases', function () {
    expect(RoleType::SYSTEM_ADMIN->value)->toBe('System Administrator')
        ->and(RoleType::ADMIN->value)->toBe('Administrator')
        ->and(RoleType::USER->value)->toBe('User');
});

test('it returns permissions with correct structure', function () {
    $permissions = RoleType::permissions();

    expect($permissions)->toBeArray()->not->toBeEmpty();

    foreach ($permissions as $permission) {
        expect($permission)->toHaveKeys(['name', 'description', 'group', 'guard_name', 'roles'])
            ->and($permission['roles'])->toBeArray();
    }
});

test('it includes generated policies', function () {
    $permissions = RoleType::permissions();
    $names = array_column($permissions, 'name');

    expect($names)->toContain('role.viewAny')
        ->toContain('role.create')
        ->toContain('user.viewAny')
        ->toContain('user.delete');
});
