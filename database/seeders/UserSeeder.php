<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@web.io',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ])->syncRoles(\App\Models\Role::ADMIN);

        $users = \App\Models\User::factory(100)->create();
        foreach ($users as $user) {
            $user->syncRoles(\App\Models\Role::GUEST);
        }
    }
}
