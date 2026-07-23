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
});

test('it returns default value when setting does not exist in database', function () {
    $query = new GetSystemSettings;
    $value = $query->get(SystemSettingKey::DEFAULT_LANGUAGE);

    expect($value)->toBe(SystemSettingKey::DEFAULT_LANGUAGE->default());
});

test('it returns value from database when it exists', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Custom Web Name',
    ]);

    $query = new GetSystemSettings;
    $value = $query->get(SystemSettingKey::WEB_NAME);

    expect($value)->toBe('Custom Web Name');
});

test('it caches settings', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Cached Name',
    ]);

    $query = new GetSystemSettings;

    // First call, should fetch from DB
    expect($query->get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Update DB directly
    SystemSettings::where('key', SystemSettingKey::WEB_NAME->value)->update(['value' => 'Updated Name']);

    // Call again, should still return cached value
    expect($query->get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Flush memory, should still be cached in Laravel Cache
    $query->flushMemory();
    expect($query->get(SystemSettingKey::WEB_NAME))->toBe('Cached Name');

    // Clear Cache
    Cache::flush();
    $query->flushMemory();

    // Should now fetch updated value
    expect($query->get(SystemSettingKey::WEB_NAME))->toBe('Updated Name');
});

test('it flushes memory correctly', function () {
    $query = new GetSystemSettings;

    // Fill memory
    $query->fetch();

    $reflection = new ReflectionClass($query);
    $property = $reflection->getProperty('settings');
    $property->setAccessible(true);

    expect($property->getValue($query))->not->toBeNull();

    $query->flushMemory();

    expect($property->getValue($query))->toBeNull();
});
