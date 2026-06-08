<?php

namespace App\Domains\Identity\Actions\Roles;

use App\Domains\Identity\DTOs\Roles\UpdateRoleDTO;
use App\Domains\Identity\Models\Role;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Events\AuditCustom;
use Throwable;

class UpdateSystemRole
{
    /**
     * @throws Throwable
     */
    public function execute(Role $role, UpdateRoleDTO $dto): bool
    {
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        DB::transaction(function () use ($role, $dto) {
            $role->syncPermissions($dto->permissions);
        });

        $role->load('permissions');
        $newPermissions = $role->permissions->pluck('name')->toArray();

        if($newPermissions !== $oldPermissions) {
            // Tell Owen-It exactly what to log
            $role->auditEvent = 'permissions_synced';
            $role->isCustomEvent = true;
            $role->auditCustomOld = ['permissions' => implode(', ', $oldPermissions)];
            $role->auditCustomNew = ['permissions' => implode(', ', $newPermissions)];

            // Dispatch the event to generate the row in the `audits` table
            event(new AuditCustom($role));
        }

        return true;
    }
}
