<?php

namespace App\Domains\Account\Enums;

enum GenderOption: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function label(): string
    {
        return __("domains/account/enum.gender.{$this->value}");
    }

    public static function fromLabel($value): self
    {
        return match ($value) {
            self::MALE->label() => self::MALE,
            self::FEMALE->label() => self::FEMALE,
        };
    }
}
