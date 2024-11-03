<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user index') && ($user->name != $model->name);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user delete') && ($user->name != $model->name);
    }
}
