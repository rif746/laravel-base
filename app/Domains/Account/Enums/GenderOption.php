<?php

namespace App\Domains\Account\Enums;

use App\Domains\System\Traits\Enum\HasPredicateMethod;
use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;

/*
 * @method bool isMale()
 * @method bool isFemale()
 */
enum GenderOption: string implements HasLabel
{
    use HasPredicateMethod;
    use InteractsWithLabels;

    case MALE = 'male';
    case FEMALE = 'female';

    public static function fromLabel($value): self
    {
        return match ($value) {
            self::MALE->label() => self::MALE,
            self::FEMALE->label() => self::FEMALE,
        };
    }
}
