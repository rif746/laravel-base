<?php

namespace App\Domains\Account\DTOs;

readonly class UpdatePasswordDTO
{
    public function __construct(
        public string $new_password,
    ) {}
}
