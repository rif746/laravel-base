<?php

namespace App\Domains\System\Enums;

use App\Domains\System\Traits\Enum\HasPredicateMethod;
use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;
use App\UI\Enums\Contracts\HasSchema;
use App\UI\Enums\FileType;
use App\UI\Enums\InputType;
use App\UI\Support\Schemas\InputSchema;
use App\UI\Support\Schemas\InputSchemaField;

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
    public function schema(): InputSchema
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
            self::WEB_NAME => InputSchema::make()
                ->type(InputType::TEXTLINE)
                ->label($this->label())
                ->default('Acme Inc'),

            self::WEB_DESCRIPTION => InputSchema::make()
                ->label($this->label())
                ->type(InputType::TEXTAREA),

            self::WEB_ADDRESS => InputSchema::make()
                ->type(InputType::TEXTLINE)
                ->label($this->label())
                ->default('123 Main St, Anytown, USA'),

            self::WEB_PHONE => InputSchema::make()
                ->type(InputType::TEXTLINE)
                ->label($this->label())
                ->default('+1234567890'),

            self::WEB_EMAIL => InputSchema::make()
                ->type(InputType::TEXTLINE)
                ->label($this->label())
                ->default('acme@web.io'),

            self::WEB_LOGO,
            self::WEB_FAVICON => InputSchema::make()
                ->type(InputType::FILE)
                ->label($this->label())
                ->rules($imageRules)
                ->attributes($imageAttrs),

            self::DEFAULT_LANGUAGE => InputSchema::make()
                ->type(InputType::SELECT)
                ->default('en')
                ->label($this->label())
                ->options([
                    'en' => __('domains/system/enum.system_setting_key_options.default_language.en'),
                    'id' => __('domains/system/enum.system_setting_key_options.default_language.id'),
                ]),

            self::TIMEZONE => InputSchema::make()
                ->type(InputType::SELECT)
                ->default('UTC')
                ->label($this->label())
                ->options([
                    'UTC' => __('domains/system/enum.system_setting_key_options.timezone.UTC'),
                    'Asia/Jakarta' => __('domains/system/enum.system_setting_key_options.timezone.Asia/Jakarta'),
                    'Asia/Makassar' => __('domains/system/enum.system_setting_key_options.timezone.Asia/Makassar'),
                    'Asia/Jayapura' => __('domains/system/enum.system_setting_key_options.timezone.Asia/Jayapura'),
                ]),

            default => InputSchema::make()
                ->label($this->label())
                ->type(InputType::TEXTLINE)
                ->rules(['nullable', 'string']),
        };
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

    public function getSchema(): InputSchemaField
    {
        return $this->schema()->getSchema();
    }

    public function getValidation(): array
    {
        return $this->schema()->getSchema()->rules;
    }
}
