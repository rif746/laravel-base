<?php

namespace App\Domains\Identity\Enums;

enum RoleType: string
{
    case SYSTEM_ADMIN = 'System Administrator';
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
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN, self::USER],
            ],
            [
                'name' => 'dashboard admin',
                'description' => 'permissions.dashboard.admin',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
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
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'user create',
                'description' => 'permissions.user.create',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'user edit',
                'description' => 'permissions.user.edit',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'user delete',
                'description' => 'permissions.user.delete',
                'group' => 'user',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],

            // Role
            [
                'name' => 'role index',
                'description' => 'permissions.role.index',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'role create',
                'description' => 'permissions.role.create',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'role edit',
                'description' => 'permissions.role.edit',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'role delete',
                'description' => 'permissions.role.delete',
                'group' => 'role',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],

            // System Setting
            [
                'name' => 'system-setting manage',
                'description' => 'permissions.system-setting.manage',
                'group' => 'system-setting',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],

            // System Backup
            [
                'name' => 'system-backup manage',
                'description' => 'permissions.system-backup.manage',
                'group' => 'system-backup',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
        ];
    }
}
