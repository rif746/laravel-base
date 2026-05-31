<?php

namespace App\Domains\Identity\DTOs;

readonly class RoleDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $guard_name,
        public array $selected_permissions,
    ) {}
}
