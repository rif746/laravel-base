<?php

namespace Database\Seeders;

use App\Enums\Identity\RoleType;
use App\Models\Identity\Permission;
use App\Models\Identity\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RoleType::cases();
        $roleCollection = [];
        foreach ($roles as $role) {
            $roleCollection[] = Role::create([
                'name' => $role->value,
                'guard_name' => 'web',
            ]);
        }

        $roleCollection = collect($roleCollection);

        $permissions = RoleType::permissions();

        foreach ($permissions as $permission) {
            $roles = $roleCollection->filter(fn ($value) => in_array($value->name, array_column($permission['roles'], 'value')));
            Permission::create([
                'name' => $permission['name'],
                'description' => $permission['description'],
                'group' => $permission['group'],
                'guard_name' => $permission['guard_name'],
            ])->roles()->sync($roles);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
