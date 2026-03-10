<?php

namespace Database\Seeders;

use App\Enum\GenderType;
use App\Enum\RoleType;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@web.io',
            'gender' => GenderType::MALE,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'status' => true,
        ]);
        $user->syncRoles(RoleType::ADMINISTRATOR);

        $users = \App\Models\User::factory()
            ->count(100)
            ->create();
        foreach ($users as $user) {
            $user->syncRoles(RoleType::GUEST);
        }
    }
}
