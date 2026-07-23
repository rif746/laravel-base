<?php

namespace App\Domains\Identity\Enums;

use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;
use App\UI\Enums\Contracts\HasSchema;
use App\UI\Enums\InputType;
use App\UI\Support\Settings\SettingSchema;
use Illuminate\Validation\Rule;

enum UserSettingKey: string implements HasLabel, HasSchema
{
    use InteractsWithLabels;

    case NOTIFICATION = 'notification';
    case LANGUAGE = 'language';
    case TIMEZONE = 'timezone';

    public function schema(): SettingSchema
    {
        $options = match ($this) {
            self::LANGUAGE => [
                'en' => __('domains/identity/enum.user_setting_key.options.language.en'),
                'id' => __('domains/identity/enum.user_setting_key.options.language.id'),
            ],
            self::TIMEZONE => [
                'UTC' => __('domains/identity/enum.user_setting_key.options.timezone.UTC'),
                'Asia/Jakarta' => __('domains/identity/enum.user_setting_key.options.timezone.Asia/Jakarta'),
                'Asia/Makassar' => __('domains/identity/enum.user_setting_key.options.timezone.Asia/Makassar'),
                'Asia/Jayapura' => __('domains/identity/enum.user_setting_key.options.timezone.Asia/Jayapura'),
            ],
            self::NOTIFICATION => [
                1 => __('domains/identity/enum.user_setting_key.options.notification.on'),
                0 => __('domains/identity/enum.user_setting_key.options.notification.off'),
            ],
        };

        $default = match ($this) {
            self::NOTIFICATION => 0,
            self::LANGUAGE => 'en',
            self::TIMEZONE => 'UTC',
        };

        return SettingSchema::make(InputType::SELECT, ['required', Rule::in(array_keys($options))])
            ->default($default)
            ->options($options);
    }
}
