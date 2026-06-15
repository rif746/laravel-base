<?php

namespace App\Domains\Identity\DTOs\Onboarding;

readonly class RegisterSelfServiceUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
