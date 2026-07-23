<?php

namespace App\UI\Enums\Contracts;

interface HasLabel
{
    /**
     * Get the human-readable localized label.
     */
    public function label(): string;

    /**
     * Get array of values pair for option select
     */
    public static function options(): array;
}
