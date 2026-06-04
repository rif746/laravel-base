<?php

namespace App\Domains\Identity\DTOs\Roles;

readonly class UpdateRoleDTO
{
    public function __construct(
        public array $permissions,
    ) {}
}
