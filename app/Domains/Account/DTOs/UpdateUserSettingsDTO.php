<?php

namespace App\Domains\Account\DTOs;

readonly class UpdateUserSettingsDTO
{
    public function __construct(
        public array $settings,
    ) {}
}
