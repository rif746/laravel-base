<?php

namespace App\Domains\Identity\Actions\AccessControl;

use App\Domains\Identity\DTOs\AccessControl\CreateRoleDTO;
use App\Domains\Identity\Models\Role;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateSystemRole
{
    /**
     * @throws Throwable
     */
    public function execute(CreateRoleDTO $dto): bool
    {
        return DB::transaction(function () use ($dto) {
            $role = Role::create([
                'name' => $dto->name,
            ]);
            $role->syncPermissions($dto->permissions);

            return true;
        });
    }
}
