<?php

use App\Domains\System\Actions\Settings\ResolveIpTimezone;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

test('it returns system timezone for localhost', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::TIMEZONE->value,
        'value' => 'Asia/Jakarta',
    ]);
    GetSystemSettings::flushMemory();

    $action = new ResolveIpTimezone();

    expect($action->execute('127.0.0.1'))->toBe('Asia/Jakarta');
});

test('it fetches timezone from API and caches it', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::TIMEZONE->value,
        'value' => 'UTC',
    ]);
    GetSystemSettings::flushMemory();

    Http::fake([
        'ip-api.com/*' => Http::response(['timezone' => 'Asia/Makassar'], 200),
    ]);

    $action = new ResolveIpTimezone();

    expect($action->execute('1.1.1.1'))->toBe('Asia/Makassar');

    // Verify cache is set
    expect(Cache::get('timezone_ip_1.1.1.1'))->toBe('Asia/Makassar');
});

test('it falls back to system timezone if API fails', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::TIMEZONE->value,
        'value' => 'UTC',
    ]);
    GetSystemSettings::flushMemory();

    Http::fake([
        'ip-api.com/*' => Http::response(null, 500),
    ]);

    $action = new ResolveIpTimezone();

    expect($action->execute('1.1.1.1'))->toBe('UTC');
});
