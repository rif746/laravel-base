<?php

namespace App\Domains\Identity\DTOs\AccessControl;

readonly class UpdateRoleDTO
{
    public function __construct(
        public array $permissions,
    ) {}
}
