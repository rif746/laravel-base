<?php

namespace App\Domains\Identity\Actions\Roles;

use App\Domains\Identity\DTOs\Roles\UpdateRoleDTO;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role as RoleContract;

class UpdateSystemRole
{
    /**
     * @throws \Throwable
     */
    public function execute(RoleContract $role, UpdateRoleDTO $dto): bool
    {
        return DB::transaction(function () use ($role, $dto) {
            $role->syncPermissions($dto->permissions);
            return true;
        });
    }
}
