<?php

namespace App\Domains\Account\Enums;

enum GenderOption: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function label(): string
    {
        return __("domains/identity.enum.gender.{$this->value}");
    }
}
