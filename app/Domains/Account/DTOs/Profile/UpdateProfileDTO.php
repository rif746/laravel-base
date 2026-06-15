<?php

namespace App\Domains\Account\DTOs\Profile;

use App\Domains\Account\Enums\GenderOption;

readonly class UpdateProfileDTO
{
    public function __construct(
        public int $userId,
        public GenderOption $gender,
        public string $date_of_birth,
        public string $phone_number,
    ) {}
}
