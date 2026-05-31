<?php

namespace App\Domains\Account\Actions;

use App\Domains\Account\DTOs\UpdateProfileDTO;
use App\Domains\Identity\Models\User;

class UpdateProfile
{
    public function execute(UpdateProfileDTO $dto): void
    {
        /** @var User $user */
        $user = auth('web')->user();

        $user->fill([
            'name'  => $dto->name,
            'email' => $dto->email,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->update();

        $user->profile()->updateOrCreate(
            ['user_id' => $dto->userId],
            [
                'gender'        => $dto->gender,
                'date_of_birth' => $dto->date_of_birth,
                'phone_number'  => $dto->phone_number,
            ]
        );
    }
}
