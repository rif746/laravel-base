<?php

namespace App\Domains\System\Queries;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Support\Facades\Cache;

class GetSystemSettings
{
    public function fetch(): array
    {
        return Cache::rememberForever('system_settings', function () {
            $settings = SystemSettings::pluck('value', 'key')->toArray();
            $finalSettings = [];
            foreach (SystemSettingKey::cases() as $key) {
                $finalSettings[$key->value] = $settings[$key->value] ?? $key->default();
            }

            return $finalSettings;
        });
    }

    public function get(SystemSettingKey $setting): string
    {
        return $this->fetch()[$setting->value] ?? $setting->default();
    }
}
