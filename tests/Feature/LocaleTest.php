<?php

use App\Domains\Account\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    // Clear cache/settings
    cache()->forget('system_settings');

    // Define a test route protected by the web middleware which contains our preferred language middleware
    Route::middleware('web')->get('/test-locale', function () {
        return response()->json(['locale' => app()->getLocale()]);
    });
});

test('middleware uses default system setting language when no session or user setting exists', function () {
    SystemSettings::updateOrCreate(
        ['key' => SystemSettingKey::DEFAULT_LANGUAGE->value],
        ['value' => 'id']
    );

    $response = $this->get('/test-locale');
    expect($response->json('locale'))->toBe('id');
});

test('middleware respects session locale over system default settings', function () {
    SystemSettings::updateOrCreate(
        ['key' => SystemSettingKey::DEFAULT_LANGUAGE->value],
        ['value' => 'en']
    );

    // Request with session locale set to 'id'
    $response = $this->withSession(['locale' => 'id'])->get('/test-locale');
    expect($response->json('locale'))->toBe('id');
});

test('middleware respects session locale over authenticated user settings', function () {
    $user = User::factory()->create([
        'settings' => [
            UserSettingKey::LANGUAGE->value => 'en',
            UserSettingKey::TIMEZONE->value => 'UTC',
        ],
    ]);

    // Request with session locale set to 'id', authenticated as user who has 'en'
    $response = $this->actingAs($user)
        ->withSession(['locale' => 'id'])
        ->get('/test-locale');

    expect($response->json('locale'))->toBe('id');
});
