<?php

namespace App\Domains\Identity\DTOs\IdentityMaintenance;

readonly class UpdateUserIdentityDTO
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
