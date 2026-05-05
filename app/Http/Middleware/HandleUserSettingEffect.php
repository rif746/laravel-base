<?php

namespace App\Http\Middleware;

use App\Enums\Account\UserSettingKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUserSettingEffect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->settings) {
            $settings = $request->user()->settings;
            foreach ($settings as $key => $value) {
                UserSettingKey::effect($key, $value);
            }
        } else {
            $settings = UserSettingKey::cases();
            foreach ($settings as $case) {
                UserSettingKey::effect($case->value, $case->default());
            }
        }

        return $next($request);
    }
}
