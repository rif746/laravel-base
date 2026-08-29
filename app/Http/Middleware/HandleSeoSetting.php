<?php

namespace App\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $title = GetSystemSettings::get(SystemSettingKey::WEB_NAME);
        $description = GetSystemSettings::get(SystemSettingKey::WEB_DESCRIPTION);

        config(['seotools.meta.defaults.title' => $title]);
        config(['seotools.opengraph.defaults.title' => $title]);
        config(['seotools.json-ld.defaults.title' => $title]);

        config(['seotools.meta.defaults.description' => $description]);
        config(['seotools.opengraph.defaults.description' => $description]);
        config(['seotools.json-ld.defaults.description' => $description]);

        return $next($request);
    }
}
