<?php

use App\Domains\Identity\Enums\UserSettingKey;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct labels', function () {
    expect(UserSettingKey::NOTIFICATION->label())->toBe(__('domains/account/enum.user_settings.notification'))
        ->and(UserSettingKey::LANGUAGE->label())->toBe(__('domains/account/enum.user_settings.language'))
        ->and(UserSettingKey::TIMEZONE->label())->toBe(__('domains/account/enum.user_settings.timezone'));
});

test('it has correct defaults', function () {
    expect(UserSettingKey::NOTIFICATION->default())->toBe(0)
        ->and(UserSettingKey::LANGUAGE->default())->toBe('en')
        ->and(UserSettingKey::TIMEZONE->default())->toBe('UTC');
});

test('it has correct types', function () {
    expect(UserSettingKey::NOTIFICATION->type())->toBe('option')
        ->and(UserSettingKey::LANGUAGE->type())->toBe('option')
        ->and(UserSettingKey::TIMEZONE->type())->toBe('option');
});

test('it has correct options', function () {
    expect(UserSettingKey::LANGUAGE->options())->toHaveKeys(['en', 'id'])
        ->and(UserSettingKey::TIMEZONE->options())->toHaveKey('UTC')
        ->and(UserSettingKey::NOTIFICATION->options())->toHaveKeys([1, 0]);
});

test('it returns correct validation rules', function () {
    expect(UserSettingKey::LANGUAGE->validation())->toContain('required');
});

test('it can apply effects', function () {
    // Test Language effect
    UserSettingKey::effect('language', 'id');
    expect(app()->getLocale())->toBe('id');

    // Test Timezone effect
    UserSettingKey::effect('timezone', 'Asia/Jakarta');
    expect(date_default_timezone_get())->toBe('Asia/Jakarta');

    // Reset
    UserSettingKey::effect('language', 'en');
    UserSettingKey::effect('timezone', 'UTC');
});
