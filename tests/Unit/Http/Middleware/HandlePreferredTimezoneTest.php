<?php

namespace Tests\Unit\Http\Middleware;

use App\Domains\Identity\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Http\Middleware\HandlePreferredTimezone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it sets default timezone from system settings', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::TIMEZONE)->andReturn('UTC');

    $middleware = new HandlePreferredTimezone($getSystemSettings);
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('app.display_timezone'))->toBe('UTC');
});

test('it uses user preference if set', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::TIMEZONE)->andReturn('UTC');

    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('settings')->andReturn(collect([
        UserSettingKey::TIMEZONE->value => 'Asia/Jakarta',
    ]));

    $middleware = new HandlePreferredTimezone($getSystemSettings);
    $request = Request::create('/', 'GET');
    $request->setUserResolver(fn () => $user);
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('app.display_timezone'))->toBe('Asia/Jakarta');
});
