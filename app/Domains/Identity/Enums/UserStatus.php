<?php

namespace App\Domains\Identity\Enums;

use App\Domains\System\Traits\Enum\HasPredicateMethod;
use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;
use App\UI\Enums\Contracts\HasUiBadge;

/**
 * @method bool isActive()
 * @method bool isInactive()
 */
enum UserStatus: string implements HasLabel, HasUiBadge
{
    use HasPredicateMethod;
    use InteractsWithLabels;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function variant(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }
}
