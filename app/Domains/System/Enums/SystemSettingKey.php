<?php

namespace App\Domains\System\Enums;

use Artesaos\SEOTools\Facades\SEOMeta;

enum SystemSettingKey: string
{
    case WEB_NAME = 'web-name';
    case WEB_DESCRIPTION = 'web-description';
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
        return __('domains/system.fields.settings.'.str_replace('-', '.', $this->value));
    }

    public static function section(): array
    {
        return [
            [
                __('domains/system.pages.settings.sections.web') => [
                    self::WEB_NAME,
                    self::WEB_DESCRIPTION,
                    self::WEB_ADDRESS,
                    self::WEB_PHONE,
                    self::WEB_EMAIL,
                    self::WEB_LOGO,
                    self::WEB_FAVICON,
                ],
            ],
            [
                __('domains/system.pages.settings.sections.general') => [
                    self::DEFAULT_LANGUAGE,
                    self::TIMEZONE,
                ],
                __('domains/system.pages.settings.sections.webmaster') => [
                    self::GOOGLE_TAG_MANAGER_ID,
                    self::GOOGLE_WEBMASTER_ID,
                ],
            ]
        ];
    }

    public function inputType(): InputType
    {
        return match ($this) {
            self::WEB_LOGO, self::WEB_FAVICON => InputType::FILE,
            self::DEFAULT_LANGUAGE, self::TIMEZONE => InputType::SELECT,
            self::WEB_DESCRIPTION => InputType::TEXTAREA,
            default => InputType::TEXTLINE
        };
    }

    public function inputAttributes(): array
    {
        $options = match ($this) {
            self::WEB_LOGO, self::WEB_FAVICON => [
                'allow-image-crop' => true,
                'allow-image-resize' => true,
                'allow-image-transform' => true,
                'image-crop-aspect-ratio' => "1:1",
                'image-resize-target-width' => "500",
                'image-resize-target-height'=> "500"
            ],
            self::TIMEZONE, self::DEFAULT_LANGUAGE => ['options' => $this->options()],
            default => [],
        };
        $options['label'] = $this->label();
        return $options;
    }

    public function validation(): array
    {
        return match ($this) {
            self::WEB_LOGO, self::WEB_FAVICON => ['required', 'file', 'mimetypes:'.implode(',', FileType::IMAGE->mimeType()), 'max:1024'],
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
}
