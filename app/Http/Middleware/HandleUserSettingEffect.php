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
        $settings = $request->user()->settings;
        foreach ($settings as $key => $value) {
            UserSettingKey::effect($key, $value);
        }

        return $next($request);
    }
}
