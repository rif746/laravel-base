<?php

namespace App\Domains\Identity\DTOs\Users;

readonly class UpdateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $role = null,
    ) {}
}
