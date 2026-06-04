<?php

use App\Http\Middleware\HandleSeoAttribute;
use App\Http\Middleware\HandleSystemSettingEffect;
use App\Http\Middleware\HandleUserSettingEffect;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withEvents(discover: [
        __DIR__.'/../app/Domains/*/Listeners',
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
            HandleSystemSettingEffect::class,
            HandleUserSettingEffect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
