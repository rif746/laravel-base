<?php

use App\Domains\Identity\Models\User;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use App\Domains\System\Queries\GetModelAuditLog;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::forget('system_settings');
});

test('GetSystemSettings query fetches settings with defaults and returns specific keys', function () {
    // 1. Fetch from empty database (should return defaults)
    $query = app(GetSystemSettings::class);
    $settings = $query->fetch();

    expect($settings[SystemSettingKey::DEFAULT_LANGUAGE->value])->toBe('en');
    expect($settings[SystemSettingKey::WEB_NAME->value])->toBe('Acme Inc');

    // 2. Fetch with DB values
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'My Test App',
    ]);
    
    // Clear cache to force DB refresh
    Cache::forget('system_settings');

    $refreshedSettings = $query->fetch();
    expect($refreshedSettings[SystemSettingKey::WEB_NAME->value])->toBe('My Test App');
    expect($query->get(SystemSettingKey::WEB_NAME))->toBe('My Test App');
    expect($query->get(SystemSettingKey::DEFAULT_LANGUAGE))->toBe('en');
});

test('GetModelAuditLog query retrieves audit records for a model', function () {
    config(['audit.console' => true]);

    $user = User::factory()->create([
        'name' => 'John Auditor',
        'email' => 'auditor@example.com',
    ]);

    $user->update(['name' => 'Updated Auditor']);

    $query = app(GetModelAuditLog::class);
    $logs = $query->get($user);

    expect($logs)->not->toBeEmpty();
    $events = $logs->pluck('event')->toArray();
    expect($events)->toContain('created');
});
