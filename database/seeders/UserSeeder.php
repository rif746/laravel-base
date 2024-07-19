<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'email' => 'admin@web.io',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $user->syncRoles(\App\Models\Role::ADMIN);
        $user->profile()->create([
            'full_name' => 'Administrator'
        ]);

        $users = \App\Models\User::factory()
            ->has(Profile::factory(1))
            ->count(100)
            ->create();
        foreach ($users as $user) {
            $user->syncRoles(\App\Models\Role::GUEST);
        }
    }
}
