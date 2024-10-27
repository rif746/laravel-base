<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check()) {
            $prefs = auth()->user()->settings;
            app()->setLocale($prefs['lang'] ?? 'id');
            view()->share('lang', $prefs['lang'] ?? 'id');
            view()->share('theme', $prefs['theme'] ?? 'light');
        }
        return $next($request);
    }
}
