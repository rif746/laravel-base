<?php

namespace Tests\Unit\Http\Middleware;

use App\Domains\Identity\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Http\Middleware\HandlePreferredTimezone;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it sets default timezone from system settings', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::TIMEZONE->value,
        'value' => 'UTC',
    ]);
    GetSystemSettings::flushMemory();

    $middleware = new HandlePreferredTimezone();
    $request = Request::create('/', 'GET');
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('app.display_timezone'))->toBe('UTC');
});

test('it uses user preference if set', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::TIMEZONE->value,
        'value' => 'UTC',
    ]);
    GetSystemSettings::flushMemory();

    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('settings')->andReturn(collect([
        UserSettingKey::TIMEZONE->value => 'Asia/Jakarta',
    ]));

    $middleware = new HandlePreferredTimezone();
    $request = Request::create('/', 'GET');
    $request->setUserResolver(fn () => $user);
    $next = fn ($req) => new Response;

    $middleware->handle($request, $next);

    expect(config('app.display_timezone'))->toBe('Asia/Jakarta');
});
