<?php

namespace Database\Seeders;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Scopes\HideSystemAdminRole;
use Database\Factories\Account\ProfileFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = new ProfileFactory;
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'system@web.io',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $systemRoles = Role::withoutGlobalScopes([HideSystemAdminRole::class])
            ->where('name', RoleType::SYSTEM_ADMIN)
            ->first();

        $admin->profile()->create($profile->definition());
        $admin->assignRole($systemRoles);

        foreach (range(1, 1) as $i) {
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
