<?php

namespace Database\Seeders;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\User;
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

        User::factory()->count(100)->create()->each(function ($user) {
            $user->assignRole(RoleType::USER);
        });
    }
}
