<?php

namespace App\Domains\System\Enums;

use Artesaos\SEOTools\Facades\SEOMeta;

enum SystemSettingKey: string
{
    case WEB_NAME = 'web-name';
    case WEB_LOGO = 'web-logo';
    case WEB_FAVICON = 'web-favicon';
    case WEB_PHONE = 'web-phone';
    case WEB_EMAIL = 'web-email';
    case WEB_ADDRESS = 'web-address';

    case DEFAULT_LANGUAGE = 'default_language';
    case TIMEZONE = 'timezone';

    case GOOGLE_TAG_MANAGER_ID = 'google-tag_manager_id';
    case GOOGLE_WEBMASTER_ID = 'google-webmaster_id';

    public function label(): string
    {
        return __('domains/system.settings.'.str_replace('-', '.', $this->value));
    }

    public static function section(): array
    {
        return [
            __('domains/system.settings.sections.web') => [
                self::WEB_NAME,
                self::WEB_ADDRESS,
                self::WEB_PHONE,
                self::WEB_EMAIL,
                self::WEB_LOGO,
                self::WEB_FAVICON,
            ],
            __('domains/system.settings.sections.general') => [
                self::DEFAULT_LANGUAGE,
                self::TIMEZONE,
            ],
            __('domains/system.settings.sections.webmaster') => [
                self::GOOGLE_TAG_MANAGER_ID,
                self::GOOGLE_WEBMASTER_ID,
            ],
        ];
    }

    public function inputType(): string
    {
        return match ($this) {
            self::WEB_LOGO, self::WEB_FAVICON => 'file',
            self::DEFAULT_LANGUAGE, self::TIMEZONE => 'options',
            default => 'text',
        };
    }

    public function validation(): array
    {
        return match ($this) {
            self::WEB_LOGO, self::WEB_FAVICON => ['required', 'file', 'image'],
            self::DEFAULT_LANGUAGE, self::TIMEZONE => ['required', 'string'],
            default => ['required', 'string'],
        };
    }

    public function default(): ?string
    {
        return match ($this) {
            self::DEFAULT_LANGUAGE => 'en',
            self::TIMEZONE => 'UTC',
            self::WEB_NAME => 'Acme Inc',
            self::WEB_ADDRESS => '123 Main St, Anytown, USA',
            self::WEB_PHONE => '+1234567890',
            self::WEB_EMAIL => 'acme@web.io',
            default => null
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
                'UTC' => 'UTC',
                'Asia/Jakarta' => 'Asia/Jakarta',
                'Asia/Makassar' => 'Asia/Makassar',
                'Asia/Jayapura' => 'Asia/Jayapura',
            ],
            default => [],
        };
    }

    public function isImage(): bool
    {
        return in_array($this, [
            self::WEB_LOGO,
            self::WEB_FAVICON,
        ]);
    }

    public static function effect(string $key, mixed $value): void
    {
        $enumKey = self::tryFrom($key);

        if ($enumKey === self::WEB_NAME) {
            config(['seotools.meta.defaults.title' => $value]);
            config(['seotools.opengraph.defaults.title' => $value]);
            config(['seotools.json-ld.defaults.title' => $value]);

            return;
        }

        match ($enumKey) {
            self::DEFAULT_LANGUAGE => app()->setLocale($value),
            self::TIMEZONE => config(['app.timezone' => $value]),
            self::GOOGLE_WEBMASTER_ID => SEOMeta::addMeta('google-site-verification', $value),
            default => null,
        };
    }
}
