<?php

namespace App\Enums\System;

enum SystemSettingKey: string
{
    case COMPANY_NAME = 'company.name';
    case COMPANY_ADDRESS = 'company.address';
    case COMPANY_PHONE = 'company.phone';
    case COMPANY_EMAIL = 'company.email';
    case COMPANY_LOGO = 'company.logo';
    case COMPANY_FAVICON = 'company.favicon';

    case DEFAULT_LANGUAGE = 'default_language';
    case TIMEZONE = 'timezone';

    public function label(): string
    {
        return __('domains/system.settings.'.$this->value);
    }

    public static function section(): array
    {
        return [
            __('domains/system.settings.sections.company') => [
                self::COMPANY_NAME,
                self::COMPANY_ADDRESS,
                self::COMPANY_PHONE,
                self::COMPANY_EMAIL,
                self::COMPANY_LOGO,
                self::COMPANY_FAVICON,
            ],
            __('domains/system.settings.sections.general') => [
                self::DEFAULT_LANGUAGE,
                self::TIMEZONE,
            ],
        ];
    }

    public function inputType(): string
    {
        return match ($this) {
            self::COMPANY_LOGO, self::COMPANY_FAVICON => 'file',
            self::DEFAULT_LANGUAGE, self::TIMEZONE => 'options',
            default => 'text',
        };
    }

    public function default(): string
    {
        return match ($this) {
            default => '-'
        };
    }

    public function options(): array
    {
        return match ($this) {
            self::DEFAULT_LANGUAGE => [
                'en' => 'English',
                'id' => 'Indonesian',
            ],
            self::TIMEZONE => [
                'Asia/Jakarta' => 'Indonesia (Western Time)',
                'Asia/Makassar' => 'Indonesia (Central Time)',
                'Asia/Jayapura' => 'Indonesia (Eastern Time)',
            ],
            default => [],
        };
    }

    public function isImage(): bool
    {
        return match ($this) {
            self::COMPANY_LOGO, self::COMPANY_FAVICON => true,
            default => false,
        };
    }
}
