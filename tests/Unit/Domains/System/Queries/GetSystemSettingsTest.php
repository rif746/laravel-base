<?php

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    GetSystemSettings::flushMemory();
});

test('it returns default value when setting does not exist in database', function () {
    $value = GetSystemSettings::get(SystemSettingKey::WEB_NAME);

    expect($value)->toBe('Acme Inc');
});

test('it returns value from database when it exists', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Custom Web Name',
    ]);
    GetSystemSettings::flushMemory();
    Cache::flush();

    $value = GetSystemSettings::get(SystemSettingKey::WEB_NAME);

    expect($value)->toBe('Custom Web Name');
});

test('it caches settings', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Cached Name',
    ]);
    GetSystemSettings::flushMemory();
    Cache::flush();

    // First call, should fetch from DB
    expect(GetSystemSettings::get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Update DB directly
    SystemSettings::where('key', SystemSettingKey::WEB_NAME->value)->update(['value' => 'Updated Name']);

    // Call again, should still return cached value (in memory)
    expect(GetSystemSettings::get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Flush memory, should still be cached in Laravel Cache
    GetSystemSettings::flushMemory();
    expect(GetSystemSettings::get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Clear Cache
    Cache::flush();
    GetSystemSettings::flushMemory();

    // Should now fetch updated value
    expect(GetSystemSettings::get(SystemSettingKey::WEB_NAME))->toBe('Updated Name');
});

test('it flushes memory correctly', function () {
    // Fill memory
    GetSystemSettings::fetch();

    $reflection = new ReflectionClass(GetSystemSettings::class);
    $property = $reflection->getProperty('settings');
    $property->setAccessible(true);

    expect($property->getValue())->not->toBeNull();

    GetSystemSettings::flushMemory();

    expect($property->getValue())->toBeNull();
});
