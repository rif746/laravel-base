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
            throw new Exception(__('domains/identity/messages.exceptions.cannot_remove_system_role'));
        }

        if ($role->users()->exists()) {
            throw new Exception(__('domains/identity/messages.exceptions.role_has_users'));
        }
    }
}
