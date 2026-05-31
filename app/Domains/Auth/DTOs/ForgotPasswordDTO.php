<?php

namespace App\Domains\Auth\DTOs;

readonly class ForgotPasswordDTO
{
    public function __construct(
        public string $email,
    ) {}
}
