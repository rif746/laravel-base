<?php

namespace App\Domains\Identity\Actions\Roles;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use Exception;

class RemoveSystemRole
{
    /**
     * @throws Exception
     */
    public function execute(Role $role): void
    {
        if ($role->name === RoleType::ADMIN->value) {
            throw new Exception('Can\'t remove system role.');
        }

        if ($role->users()->exists()) {
            throw new Exception('This role has a users attached to it.');
        }
    }
}
