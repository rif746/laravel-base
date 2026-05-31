<?php

namespace App\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleSystemSettingEffect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $systemSettings = Cache::rememberForever('system-settings', function () {
            return SystemSettings::all()->pluck('value', 'key')->toArray();
        });

        foreach ($systemSettings as $key => $value) {
            SystemSettingKey::effect($key, $value);
        }

        View::share('settings', $systemSettings);

        return $next($request);
    }
}
