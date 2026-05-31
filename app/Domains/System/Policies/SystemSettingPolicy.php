<?php

namespace App\Domains\System\Policies;

use App\Domains\Identity\Models\User;

class SystemSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('system-setting index');
    }
}
