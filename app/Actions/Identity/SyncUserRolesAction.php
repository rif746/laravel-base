<?php

namespace App\Actions\Identity;

use App\Models\Identity\User;

class SyncUserRolesAction
{
    public function execute(User $user, array|string $roles): void
    {
        $user->syncRoles($roles);
    }
}
