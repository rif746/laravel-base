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
                'name' => 'dashboard.index',
                'description' => 'permissions.dashboard.index',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN, self::USER],
            ],
            [
                'name' => 'dashboard.admin',
                'description' => 'permissions.dashboard.admin',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
            [
                'name' => 'dashboard.user',
                'description' => 'permissions.dashboard.user',
                'group' => 'dashboard',
                'guard_name' => 'web',
                'roles' => [self::USER],
            ],

            ...self::generatePolicy('role', [self::SYSTEM_ADMIN, self::ADMIN]),
            ...self::generatePolicy('user', [self::SYSTEM_ADMIN, self::ADMIN]),

            // System Setting
            [
                'name' => 'system-setting.manage',
                'description' => 'permissions.system-setting.manage',
                'group' => 'system-setting',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],

            // System Backup
            [
                'name' => 'system-backup.manage',
                'description' => 'permissions.system-backup.manage',
                'group' => 'system-backup',
                'guard_name' => 'web',
                'roles' => [self::SYSTEM_ADMIN, self::ADMIN],
            ],
        ];
    }

    private static function generatePolicy(string $modelName, array $roles = []): array
    {
        $policy = ['viewAny', 'view', 'create', 'update', 'delete'];
        $permissions = [];
        foreach ($policy as $policyName) {
            $permissions[] = [
                'name' => "{$modelName}.{$policyName}",
                'description' => "permissions.{$modelName}.{$policyName}",
                'group' => $modelName,
                'guard_name' => 'web',
                'roles' => $roles,
            ];
        }
        return $permissions;
    }
}
