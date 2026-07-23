<?php

use App\Domains\System\Enums\SystemSettingKey;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct input types', function () {
    expect(SystemSettingKey::WEB_LOGO->schema()->type->value)->toBe('file')
        ->and(SystemSettingKey::WEB_DESCRIPTION->schema()->type->value)->toBe('text_area')
        ->and(SystemSettingKey::WEB_NAME->schema()->type->value)->toBe('text_line');
});

test('it has correct defaults', function () {
    expect(SystemSettingKey::DEFAULT_LANGUAGE->schema()->default)->toBe('en')
        ->and(SystemSettingKey::TIMEZONE->schema()->default)->toBe('UTC')
        ->and(SystemSettingKey::WEB_NAME->schema()->default)->toBe('Acme Inc');
});

test('it has correct options for select fields', function () {
    expect(SystemSettingKey::DEFAULT_LANGUAGE->schema()->options)->toHaveKeys(['en', 'id'])
        ->and(SystemSettingKey::TIMEZONE->schema()->options)->toHaveKey('UTC');
});

test('it identifies images correctly', function () {
    expect(SystemSettingKey::WEB_LOGO->value === 'web-logo')->toBeTrue()
        ->and(SystemSettingKey::WEB_FAVICON->value === 'web-favicon')->toBeTrue()
        ->and(SystemSettingKey::WEB_NAME->value === 'web-name')->toBeTrue();
});

test('it has correct validation for images', function () {
    expect(SystemSettingKey::WEB_LOGO->schema()->rules)->toContain('file')
        ->toContain('required');
});
