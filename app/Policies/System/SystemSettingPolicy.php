<?php

namespace App\Policies\System;

use App\Models\Identity\User;

class SystemSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('system-setting index');
    }
}
