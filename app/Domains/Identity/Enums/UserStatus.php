<?php

namespace App\Domains\Identity\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return __('domains/identity.enum.user_status.'.$this->value);
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }

    public function isActive(): bool
    {
        return $this == self::ACTIVE;
    }
}
