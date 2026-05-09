<?php

namespace Database\Seeders;

use App\Enums\System\SystemSettingKey;
use App\Models\System\SystemSettings;
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
