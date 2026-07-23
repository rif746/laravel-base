<?php

namespace App\Domains\System\Enums;

use App\Domains\System\Traits\Enum\HasPredicateMethod;
use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;
use App\UI\Enums\Contracts\HasSchema;
use App\UI\Enums\FileType;
use App\UI\Enums\InputType;
use App\UI\Support\Settings\SettingSchema;

enum SystemSettingKey: string implements HasLabel, HasSchema
{
    use HasPredicateMethod;
    use InteractsWithLabels;

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

    /**
     * Centralized Schema Definitions
     */
    public function schema(): SettingSchema
    {
        $imageRules = ['required', 'file', 'mimetypes:'.implode(',', FileType::IMAGE->mimeType()), 'max:1024'];
        $imageAttrs = [
            'allow-image-crop' => true,
            'allow-image-resize' => true,
            'allow-image-transform' => true,
            'image-crop-aspect-ratio' => '1:1',
            'image-resize-target-width' => '500',
            'image-resize-target-height' => '500',
        ];

        return match ($this) {
            self::WEB_NAME => SettingSchema::make(InputType::TEXTLINE)->default('Acme Inc'),
            self::WEB_DESCRIPTION => SettingSchema::make(InputType::TEXTAREA),
            self::WEB_ADDRESS => SettingSchema::make(InputType::TEXTLINE)->default('123 Main St, Anytown, USA'),
            self::WEB_PHONE => SettingSchema::make(InputType::TEXTLINE)->default('+1234567890'),
            self::WEB_EMAIL => SettingSchema::make(InputType::TEXTLINE)->default('acme@web.io'),

            self::WEB_LOGO,
            self::WEB_FAVICON => SettingSchema::make(InputType::FILE, $imageRules)->attributes($imageAttrs),

            self::DEFAULT_LANGUAGE => SettingSchema::make(InputType::SELECT)
                ->default('en')
                ->options([
                    'en' => __('domains/system/enum.system_setting_key.options.default_language.en'),
                    'id' => __('domains/system/enum.system_setting_key.options.default_language.id'),
                ]),

            self::TIMEZONE => SettingSchema::make(InputType::SELECT)
                ->default('UTC')
                ->options([
                    'UTC' => __('domains/system/enum.system_setting_key.options.timezone.UTC'),
                    'Asia/Jakarta' => __('domains/system/enum.system_setting_key.options.timezone.Asia/Jakarta'),
                    'Asia/Makassar' => __('domains/system/enum.system_setting_key.options.timezone.Asia/Makassar'),
                    'Asia/Jayapura' => __('domains/system/enum.system_setting_key.options.timezone.Asia/Jayapura'),
                ]),

            default => SettingSchema::make(InputType::TEXTLINE)->rules(['nullable', 'string']),
        };
    }

    public function default(): mixed
    {
        return $this->schema()->default;
    }

    public function inputAttributes(): array
    {
        return array_merge($this->schema()->attributes, [
            'label' => $this->label(),
            'options' => $this->schema()->type->isSelect() ? $this->schema()->options : ''
        ]);
    }

    public static function section(): array
    {
        return [
            [
                __('domains/system/pages.settings.sections.web') => [
                    self::WEB_NAME, self::WEB_DESCRIPTION, self::WEB_ADDRESS,
                    self::WEB_PHONE, self::WEB_EMAIL, self::WEB_LOGO, self::WEB_FAVICON,
                ],
            ],
            [
                __('domains/system/pages.settings.sections.general') => [self::DEFAULT_LANGUAGE, self::TIMEZONE],
                __('domains/system/pages.settings.sections.webmaster') => [self::GOOGLE_TAG_MANAGER_ID, self::GOOGLE_WEBMASTER_ID],
            ],
        ];
    }
}
