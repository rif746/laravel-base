<?php

use App\Domains\System\Actions\Settings\ResolveIpTimezone;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Cache::flush();
});

test('it returns system timezone for localhost', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::TIMEZONE)->andReturn('Asia/Jakarta');

    $action = new ResolveIpTimezone($getSystemSettings);

    expect($action->execute('127.0.0.1'))->toBe('Asia/Jakarta');
});

test('it fetches timezone from API and caches it', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::TIMEZONE)->andReturn('UTC');

    Http::fake([
        'ip-api.com/*' => Http::response(['timezone' => 'Asia/Makassar'], 200),
    ]);

    $action = new ResolveIpTimezone($getSystemSettings);

    expect($action->execute('1.1.1.1'))->toBe('Asia/Makassar');

    // Verify cache is set
    expect(Cache::get('timezone_ip_1.1.1.1'))->toBe('Asia/Makassar');
});

test('it falls back to system timezone if API fails', function () {
    $getSystemSettings = Mockery::mock(GetSystemSettings::class);
    $getSystemSettings->shouldReceive('get')->with(SystemSettingKey::TIMEZONE)->andReturn('UTC');

    Http::fake([
        'ip-api.com/*' => Http::response(null, 500),
    ]);

    $action = new ResolveIpTimezone($getSystemSettings);

    expect($action->execute('1.1.1.1'))->toBe('UTC');
});
