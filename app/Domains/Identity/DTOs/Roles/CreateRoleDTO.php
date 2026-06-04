<?php

namespace App\Domains\Identity\DTOs\Roles;

readonly class CreateRoleDTO
{
    public function __construct(
        public string $name,
        public string $guard_name,
        public array $permissions,
    ) {}
}
