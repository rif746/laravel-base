<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\DTOs\RoleDTO;
use App\Domains\Identity\Models\Role;
use Illuminate\Database\Eloquent\Model;

class SaveRole
{
    public function execute(RoleDTO $dto): Role|Model
    {
        $role = Role::updateOrCreate(
            ['id' => $dto->id],
            [
                'name'       => $dto->name,
                'guard_name' => $dto->guard_name,
            ]
        );

        $role->syncPermissions($dto->selected_permissions);

        return $role;
    }
}
