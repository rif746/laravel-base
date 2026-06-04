<?php

namespace App\Domains\Identity\DTOs\Passwords;

readonly class ResetPasswordDTO
{
    public function __construct(
        public string $token,
        public string $email,
        public string $password,
        public string $password_confirmation,
    ) {}
}
