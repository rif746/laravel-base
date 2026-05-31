<?php

namespace App\DTOs\Account;

readonly class UpdatePasswordDTO
{
    public function __construct(
        public string $new_password,
    ) {}
}
