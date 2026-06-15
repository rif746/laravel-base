<?php

namespace App\Domains\Identity\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function labels(): string
    {
        return __('domains/identity.enum.user_status.'.$this->value);
    }

    public function badge(): string
    {
        $color = match ($this) {
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-danger',
        };

        return "<span class='badge {$color}'>{$this->labels()}</span>";
    }

    public function isActive(): bool
    {
        return $this == self::ACTIVE;
    }
}
