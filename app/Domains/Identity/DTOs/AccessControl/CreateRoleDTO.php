<?php

namespace App\Domains\Identity\DTOs\AccessControl;

readonly class CreateRoleDTO
{
    public function __construct(
        public string $name,
        public string $guard_name,
        public array $permissions,
    ) {}
}
