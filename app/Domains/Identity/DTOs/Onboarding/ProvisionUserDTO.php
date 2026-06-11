<?php

namespace App\Domains\Identity\DTOs\Onboarding;

readonly class ProvisionUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role
    ) {}
}
