<?php

namespace App\Actions\Identity;

use App\Models\Identity\Role;

class SyncRolePermissionsAction
{
    public function execute(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }
}
