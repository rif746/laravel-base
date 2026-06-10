<?php

use App\Http\Middleware\HandlePreferredLanguage;
use App\Http\Middleware\HandlePreferredTimezone;
use App\Http\Middleware\HandleSeoAttribute;
use App\Http\Middleware\HandleSeoSetting;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seo' => HandleSeoAttribute::class,
        ]);
        $middleware->web(append: [
            HandleSeoSetting::class,
            HandlePreferredTimezone::class,
            HandlePreferredLanguage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
