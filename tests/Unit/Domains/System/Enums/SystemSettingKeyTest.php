<?php

use App\Domains\System\Enums\SystemSettingKey;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct input types', function () {
    expect(SystemSettingKey::WEB_LOGO->inputType()->value)->toBe('file')
        ->and(SystemSettingKey::WEB_DESCRIPTION->inputType()->value)->toBe('textarea')
        ->and(SystemSettingKey::WEB_NAME->inputType()->value)->toBe('input');
});

test('it has correct defaults', function () {
    expect(SystemSettingKey::DEFAULT_LANGUAGE->default())->toBe('en')
        ->and(SystemSettingKey::TIMEZONE->default())->toBe('UTC')
        ->and(SystemSettingKey::WEB_NAME->default())->toBe('Acme Inc');
});

test('it has correct options for select fields', function () {
    expect(SystemSettingKey::DEFAULT_LANGUAGE->options())->toHaveKeys(['en', 'id'])
        ->and(SystemSettingKey::TIMEZONE->options())->toHaveKey('UTC');
});

test('it identifies images correctly', function () {
    expect(SystemSettingKey::WEB_LOGO->isImage())->toBeTrue()
        ->and(SystemSettingKey::WEB_FAVICON->isImage())->toBeTrue()
        ->and(SystemSettingKey::WEB_NAME->isImage())->toBeFalse();
});

test('it has correct validation for images', function () {
    expect(SystemSettingKey::WEB_LOGO->validation())->toContain('file')
        ->toContain('required');
});
