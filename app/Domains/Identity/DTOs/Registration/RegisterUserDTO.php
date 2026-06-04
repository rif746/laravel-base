<?php

namespace App\Domains\Identity\DTOs\Registration;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
