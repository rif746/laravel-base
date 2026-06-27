<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\Role;

class GetRoleDistributions
{
    public function fetch(): array
    {
        $roles = Role::select('name')
            ->withCount('users')
            ->get();

        $series = $roles->map(fn ($role) => $role->users_count);
        $categories = $roles->map(fn ($role) => $role->name);

        return [
            'series' => $series,
            'categories' => $categories,
        ];
    }
}
