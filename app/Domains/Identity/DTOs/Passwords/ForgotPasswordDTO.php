<?php

namespace App\Domains\Identity\DTOs\Passwords;

readonly class ForgotPasswordDTO
{
    public function __construct(
        public string $email,
    ) {}
}
