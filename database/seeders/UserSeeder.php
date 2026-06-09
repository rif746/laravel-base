<?php

namespace Database\Seeders;

use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\User;
use Database\Factories\Account\ProfileFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = new ProfileFactory();
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@web.io',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $admin->profile()->create($profile->definition());
        $admin->assignRole(RoleType::ADMIN);

        foreach (range(1, 10) as $i) {
            User::factory()
                ->count(100)
                ->create()
                ->each(function ($user) use ($profile) {
                    $user->profile()->create($profile->definition());
                    $user->assignRole(RoleType::USER);
                });
        }
    }
}
