<?php

namespace Database\Seeders;

use App\Enums\Identity\RoleType;
use App\Models\Identity\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@web.io',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $admin->assignRole(RoleType::ADMIN);

        User::factory()->count(1000)->create()->each(function ($user) {
            $user->assignRole(RoleType::USER);
        });
    }
}
