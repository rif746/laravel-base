<?php

namespace App\Domains\Auth\DTOs;

readonly class LoginUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
    ) {}
}
