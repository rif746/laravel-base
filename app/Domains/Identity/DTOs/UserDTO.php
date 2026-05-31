<?php

namespace App\Domains\Identity\DTOs;

readonly class UserDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $password,
        public ?string $role_name,
    ) {}
}
