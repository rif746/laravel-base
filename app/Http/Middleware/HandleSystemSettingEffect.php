<?php

namespace App\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleSystemSettingEffect
{
    public function __construct(public GetSystemSettings $getSystemSettings) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $systemSettings = $this->getSystemSettings->fetch();

        foreach ($systemSettings as $key => $value) {
            if ($value !== null) {
                SystemSettingKey::effect($key, $value);
            }
        }

        View::share('settings', $systemSettings);

        return $next($request);
    }
}
