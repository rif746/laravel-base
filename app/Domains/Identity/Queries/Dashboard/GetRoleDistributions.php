<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetRoleDistributions
{
    public function fetch(): array
    {
        $modelRolesTable = config('permission.table_names.model_has_roles');
        $roles = Role::select('name')
            ->get()
            ->mapWithKeys(fn($role) => [
                str($role->name)->snake()->toString() => $role->name
            ]);

        $stats = User::query()
            ->leftJoin($modelRolesTable, fn($join) => $join
                ->on('users.id', '=', "{$modelRolesTable}.model_id")
                ->where('model_type', User::class))
            ->leftJoin('roles', $modelRolesTable.'.role_id', '=', "roles.id");

        foreach ($roles as $key => $role) {
            $stats->selectRaw("COUNT(DISTINCT CASE WHEN roles.name = '{$role}' THEN users.id END) as {$key}");
        }

        $stats = $stats->first();

        return [
            'series' => array_values($stats->toArray()),
            'categories' => $roles->values(),
        ];
    }
}
