<?php

namespace App\Domains\Identity\Actions\AccessControl;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use Exception;
use function in_array;

class RemoveSystemRole
{
    /**
     * @throws Exception
     */
    public function execute(Role $role): void
    {
        if (in_array($role->name, [RoleType::ADMIN->value, RoleType::SYSTEM_ADMIN->value])) {
            throw new Exception('Can\'t remove system role.');
        }

        if ($role->users()->exists()) {
            throw new Exception('This role has a users attached to it.');
        }
    }
}
