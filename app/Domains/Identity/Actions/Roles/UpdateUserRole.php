<?php

namespace App\Domains\Identity\Actions\Roles;

use App\Domains\Identity\Models\User;

class UpdateUserRole
{
    public function execute(User $user, array $roles): void
    {
        $user->syncRoles($roles);
    }
}
