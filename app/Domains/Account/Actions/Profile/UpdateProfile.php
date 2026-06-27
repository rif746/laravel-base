<?php

namespace App\Domains\Account\Actions\Profile;

use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Models\Profile;

class UpdateProfile
{
    public function execute(UpdateProfileDTO $dto): void
    {
        Profile::updateOrCreate(
            ['user_id' => $dto->userId],
            [
                'gender' => $dto->gender,
                'date_of_birth' => $dto->dateOfBirth,
                'phone_number' => $dto->phoneNumber,
            ]
        );
    }
}
