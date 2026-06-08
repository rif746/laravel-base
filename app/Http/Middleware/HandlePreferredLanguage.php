<?php

namespace App\Http\Middleware;

use App\Domains\Account\Enums\UserSettingKey;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePreferredLanguage
{
    public function __construct(protected GetSystemSettings $getSystemSettings)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $this->getSystemSettings->get(SystemSettingKey::DEFAULT_LANGUAGE);
        $userSetting = $request->user()?->settings;

        if($userSetting !== null) {
            $lang = $userSetting[UserSettingKey::LANGUAGE->value];
        }

        app()->setLocale($lang);

        return $next($request);
    }
}
