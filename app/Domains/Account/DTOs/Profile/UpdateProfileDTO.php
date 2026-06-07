<?php

namespace App\Domains\Account\DTOs\Profile;

readonly class UpdateProfileDTO
{
    public function __construct(
        public int $userId,
        public string $gender,
        public string $date_of_birth,
        public string $phone_number,
    ) {}
}
