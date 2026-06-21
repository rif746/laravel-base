<?php

namespace App\Http\Middleware;

use App\Domains\Account\Enums\UserSettingKey;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePreferredTimezone
{
    public function __construct(protected GetSystemSettings $getSystemSettings) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = $this->getSystemSettings->get(SystemSettingKey::TIMEZONE);
        $userSetting = $request->user()?->settings;

        if ($userSetting !== null) {
            $timezone = $userSetting[UserSettingKey::TIMEZONE->value];
        }

        config(['app.display_timezone' => $timezone]);

        return $next($request);
    }
}
