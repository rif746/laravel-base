<?php

namespace App\Enums\Identity;

enum RoleType: string
{
    case ADMIN = 'Administrator';
    case USER = 'User';

    public static function permissions(): array
    {
        return [
            // Dashboard
            [
                'name' => 'dashboard index',
                'description' => 'permissions.dashboard.index',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::ADMIN, self::USER],
            ],
            [
                'name' => 'dashboard admin',
                'description' => 'permissions.dashboard.admin',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'dashboard user',
                'description' => 'permissions.dashboard.user',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::USER],
            ],

            // User
            [
                'name' => 'user index',
                'description' => 'permissions.user.index',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'user create',
                'description' => 'permissions.user.create',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'user edit',
                'description' => 'permissions.user.edit',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'user delete',
                'description' => 'permissions.user.delete',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],

            // Role
            [
                'name' => 'role index',
                'description' => 'permissions.role.index',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'role create',
                'description' => 'permissions.role.create',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'role edit',
                'description' => 'permissions.role.edit',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
            [
                'name' => 'role delete',
                'description' => 'permissions.role.delete',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::ADMIN],
            ],
        ];
    }
}
