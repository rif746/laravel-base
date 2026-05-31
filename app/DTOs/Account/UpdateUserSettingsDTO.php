<?php

namespace App\DTOs\Account;

readonly class UpdateUserSettingsDTO
{
    public function __construct(
        public array $settings,
    ) {}
}
