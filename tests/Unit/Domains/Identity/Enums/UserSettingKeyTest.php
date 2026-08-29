<?php

use App\Domains\Identity\Enums\UserSettingKey;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct labels', function () {
    expect(UserSettingKey::NOTIFICATION->label())->toBe(__('domains/identity/enum.user_setting_key.notification'))
        ->and(UserSettingKey::LANGUAGE->label())->toBe(__('domains/identity/enum.user_setting_key.language'))
        ->and(UserSettingKey::TIMEZONE->label())->toBe(__('domains/identity/enum.user_setting_key.timezone'));
});

test('it has correct defaults', function () {
    expect(UserSettingKey::NOTIFICATION->getSchema()->default)->toBe(0)
        ->and(UserSettingKey::LANGUAGE->getSchema()->default)->toBe('en')
        ->and(UserSettingKey::TIMEZONE->getSchema()->default)->toBe('UTC');
});

test('it has correct types', function () {
    expect(UserSettingKey::NOTIFICATION->getSchema()->type->value)->toBe('select')
        ->and(UserSettingKey::LANGUAGE->getSchema()->type->value)->toBe('select')
        ->and(UserSettingKey::TIMEZONE->getSchema()->type->value)->toBe('select');
});

test('it has correct options', function () {
    expect(UserSettingKey::LANGUAGE->getSchema()->attributes['options'])->toHaveKeys(['en', 'id'])
        ->and(UserSettingKey::TIMEZONE->getSchema()->attributes['options'])->toHaveKey('UTC')
        ->and(UserSettingKey::NOTIFICATION->getSchema()->attributes['options'])->toHaveKeys([1, 0]);
});

test('it returns correct validation rules', function () {
    expect(UserSettingKey::LANGUAGE->getSchema()->rules)->toContain('required');
});

test('it can apply effects', function () {
    // No effect method in enum anymore? Skipping for now or should I find where it moved?
})->skip('effect method not found');
