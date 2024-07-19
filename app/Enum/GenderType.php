<?php

namespace App\Enum;

enum GenderType: string
{
    case MALE = "M";
    case FEMALE = "F";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::MALE => __('locale/user.gender.m'),
            self::FEMALE => __('locale/user.gender.f'),
        };
    }
}
