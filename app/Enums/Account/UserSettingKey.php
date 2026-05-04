<?php

namespace App\Enums\Account;

enum UserSettingKey: string
{
    case NOTIFICATION = 'notification';
    case LANGUAGE = 'language';
    case TIMEZONE = 'timezone';

    public function label(): string
    {
        return __("domains/account.enum.user_settings.{$this->value}");
    }

    public static function effect(string $settingKey, string|int $value): void
    {
        match ($settingKey) {
            self::LANGUAGE->value => app()->setLocale($value),
            self::TIMEZONE->value => date_default_timezone_set($value),
            default => null,
        };
    }

    public function default(): mixed
    {
        return match ($this) {
            self::NOTIFICATION => 0,
            self::LANGUAGE => 'en',
            self::TIMEZONE => 'UTC',
        };
    }

    public function type(): string
    {
        return match ($this) {
            self::NOTIFICATION => 'option',
            self::LANGUAGE => 'option',
            self::TIMEZONE => 'option',
        };
    }

    public function options(): array
    {
        return match ($this) {
            self::LANGUAGE => [
                ['key' => 'en', 'label' => __('domains/account.enum.user_settings.options.language.en')],
                ['key' => 'id', 'label' => __('domains/account.enum.user_settings.options.language.id')],
            ],
            self::TIMEZONE => [
                ['key' => 'UTC', 'label' => 'UTC'],
                ['key' => 'Asia/Jakarta', 'label' => 'Asia/Jakarta'],
            ],
            self::NOTIFICATION => [
                ['key' => 1, 'label' => __('domains/account.enum.user_settings.options.notification.on')],
                ['key' => 0, 'label' => __('domains/account.enum.user_settings.options.notification.off')],
            ],
            default => [],
        };
    }

    public function validation(): string
    {
        return match ($this) {
            self::LANGUAGE => 'required|in:en,id',
            self::TIMEZONE => 'required|in:UTC,Asia/Jakarta',
            self::NOTIFICATION => 'required|in:0,1',
            default => '',
        };
    }
}
