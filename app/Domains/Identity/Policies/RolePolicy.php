<?php

namespace App\Domains\Identity\Policies;

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('role.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $model): bool
    {
        return $user->can('role.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('role.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $model): bool
    {
        return $user->can('role.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $model): bool
    {
        return $user->can('role.delete');
    }
}
