<?php

namespace App\Domains\Identity\DTOs\Passwords;

readonly class UpdatePasswordDTO
{
    public function __construct(
        public string $new_password,
    ) {}
}
