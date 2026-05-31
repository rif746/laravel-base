<?php

namespace App\DTOs\Auth;

readonly class LoginUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
    ) {}
}
