<?php

namespace App\Policies;

use App\Enum\RoleType;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function update(User $user, Role $model): bool
    {
        return $user->hasPermissionTo('role index') && ($model->name != RoleType::ADMINISTRATOR->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function delete(User $user, Role $model): bool
    {
        return $user->hasPermissionTo('role delete') && ($model->name != RoleType::ADMINISTRATOR->value);
    }
}
