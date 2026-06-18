<?php

namespace App\Domains\Identity\Actions\AccessControl;

use App\Domains\Identity\DTOs\AccessControl\UpdateRoleDTO;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Events\AuditCustom;
use Spatie\Permission\Contracts\Role;
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

        if ($newPermissions !== $oldPermissions) {
            /** @var Auditable $role */

            // Tell Owen-It exactly what to log
            $role->auditEvent = 'permissions_synced';
            $role->isCustomEvent = true;
            $role->auditCustomOld = ['permissions' => implode(', ', $oldPermissions)];
            $role->auditCustomNew = ['permissions' => implode(', ', $newPermissions)];

            event(new AuditCustom($role));
        }

        return true;
    }
}
