<?php

namespace Tests\Unit\Http\Middleware;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Http\Middleware\HandleSeoSetting;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it sets seo settings in config', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'My App',
    ]);
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_DESCRIPTION->value,
        'value' => 'My App Description',
    ]);
    GetSystemSettings::flushMemory();

    $middleware = new HandleSeoSetting();
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('seotools.meta.defaults.title'))->toBe('My App')
        ->and(config('seotools.meta.defaults.description'))->toBe('My App Description');
});
