<?php

namespace App\Domains\Auth\DTOs;

readonly class ResetPasswordDTO
{
    public function __construct(
        public string $token,
        public string $email,
        public string $password,
        public string $password_confirmation,
    ) {}
}
