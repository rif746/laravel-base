<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        foreach ($role->getDefaultRoles() as $key => $value) {
            $role->create(['name' => $value, 'guard_name' => 'web']);
        }

        $permission = new Permission();
        foreach ($permission->getDefaultPermissions() as $key => $value) {
            foreach ($value as $k => $v) {
                $perm = $key . ' ' . $k;
                $permission->create([
                    'name' => $perm,
                    'description' => $v['description']
                ])->assignRole(...$v['role']);
            }
        };
    }
}
