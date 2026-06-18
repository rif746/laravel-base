<?php

namespace App\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoSetting
{
    public function __construct(protected GetSystemSettings $getSystemSettings) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $title = $this->getSystemSettings->get(SystemSettingKey::WEB_NAME);
        $description = $this->getSystemSettings->get(SystemSettingKey::WEB_DESCRIPTION);

        config(['seotools.meta.defaults.title' => $title]);
        config(['seotools.opengraph.defaults.title' => $title]);
        config(['seotools.json-ld.defaults.title' => $title]);

        config(['seotools.meta.defaults.description' => $description]);
        config(['seotools.opengraph.defaults.description' => $description]);
        config(['seotools.json-ld.defaults.description' => $description]);

        return $next($request);
    }
}
