<?php

namespace App\Domains\Account\DTOs;

readonly class UpdateProfileDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public ?string $gender,
        public ?string $date_of_birth,
        public ?string $phone_number,
    ) {}
}
