<?php

namespace App\Domains\System\Queries;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Support\Facades\Cache;

class GetSystemSettings
{
    private ?array $settings = null;

    public function get(SystemSettingKey $setting): ?string
    {
        return $this->fetch()[$setting->value] ?? $setting->default();
    }

    public function fetch(): array
    {
        if ($this->settings !== null) {
            return $this->settings;
        }

        $this->settings = Cache::rememberForever(SystemSettings::$cacheName, function () {
            $settings = SystemSettings::pluck('value', 'key')->toArray();
            $finalSettings = [];
            foreach (SystemSettingKey::cases() as $key) {
                $finalSettings[$key->value] = $settings[$key->value] ?? $key->default();
            }

            return $finalSettings;
        });

        return $this->settings;
    }

    /**
     * Clears the local memory. Crucial for long-running processes like Laravel Octane.
     */
    public function flushMemory(): void
    {
        $this->settings = null;
    }
}
