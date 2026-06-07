<?php

namespace App\Domains\Account\Actions\Profile;

use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Models\Profile;

class UpdateProfile
{
    public function execute(Profile $profile, UpdateProfileDTO $dto): void
    {
        $profile->updateOrCreate(
            ['user_id' => $dto->userId],
            [
                'gender' => $dto->gender,
                'date_of_birth' => $dto->date_of_birth,
                'phone_number' => $dto->phone_number,
            ]
        );
    }
}
