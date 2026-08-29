<?php

namespace App\Domains\System\Queries;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Support\Facades\Cache;

class GetSystemSettings
{
    private static ?array $settings = null;

    public static function get(SystemSettingKey $setting): ?string
    {
        return self::fetch()[$setting->value] ?? $setting->getSchema()->default;
    }

    public static function fetch(): array
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        self::$settings = Cache::rememberForever(SystemSettings::$cacheName, function () {
            $settings = SystemSettings::pluck('value', 'key')->toArray();
            $finalSettings = [];
            foreach (SystemSettingKey::cases() as $key) {
                $finalSettings[$key->value] = $settings[$key->value] ?? $key->getSchema()->default;
            }

            return $finalSettings;
        });

        return self::$settings;
    }

    /**
     * Clears the local memory. Crucial for long-running processes like Laravel Octane.
     */
    public static function flushMemory(): void
    {
        self::$settings = null;
    }
}
