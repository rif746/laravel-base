<?php

namespace App\Domains\System\Actions\Settings;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ResolveIpTimezone
{
    public function __construct(protected GetSystemSettings $getSystemSettings) {}

    public function execute(string $ip): string
    {
        $systemTZ = $this->getSystemSettings->get(SystemSettingKey::TIMEZONE);

        // Localhost fallback
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return $systemTZ;
        }

        // Cache the result for 24 hours (86,400 seconds)
        return Cache::remember("timezone_ip_{$ip}", 86400, function () use ($ip, $systemTZ) {
            try {
                // Quick 2-second timeout so the app doesn't hang if the API is down
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=timezone");

                return $response->json('timezone') ?? $systemTZ;
            } catch (Exception $e) {
                // If API fails, safely fallback to UTC
                return $systemTZ;
            }
        });
    }
}
