<?php

namespace Database\Seeders;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = SystemSettingKey::cases();
        foreach ($settings as $setting) {
            SystemSettings::create([
                'key' => $setting->value,
                'value' => $setting->default(),
            ]);
        }
    }
}
