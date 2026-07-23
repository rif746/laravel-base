<?php

namespace Tests\Unit\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Http\Middleware\HandleSeoSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it sets seo settings in config', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::WEB_NAME)->andReturn('My App');
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::WEB_DESCRIPTION)->andReturn('My App Description');

    $middleware = new HandleSeoSetting($getSystemSettings);
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('seotools.meta.defaults.title'))->toBe('My App')
        ->and(config('seotools.meta.defaults.description'))->toBe('My App Description');
});
