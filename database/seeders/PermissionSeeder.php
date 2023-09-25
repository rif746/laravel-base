<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::getRoles();
        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = Permission::getDefaultPermissions();
        foreach ($permissions as $key => $value) {
            foreach ($value as $k => $v) {
                $perm = $key . ' ' . $k;
                Permission::create([
                    'name' => $perm
                ])->assignRole(...$v);
            }
        };
    }
}
