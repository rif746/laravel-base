<?php

namespace Tests\Unit\Http\Middleware;

use App\Domains\Identity\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Http\Middleware\HandlePreferredLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it sets language from system settings', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::DEFAULT_LANGUAGE)->andReturn('en');

    $middleware = new HandlePreferredLanguage($getSystemSettings);
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(app()->getLocale())->toBe('en');
});

test('it uses session locale if set', function () {
    session()->put('locale', 'id');
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    // Should not call getSystemSettings because session has locale

    $middleware = new HandlePreferredLanguage($getSystemSettings);
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(app()->getLocale())->toBe('id');
});

test('it uses user preference if set', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::DEFAULT_LANGUAGE)->andReturn('en');

    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('settings')->andReturn(collect([
        UserSettingKey::LANGUAGE->value => 'id',
    ]));

    $middleware = new HandlePreferredLanguage($getSystemSettings);
    $request = Request::create('/', 'GET');
    $request->setUserResolver(fn () => $user);
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(app()->getLocale())->toBe('id');
});
