<?php

namespace App\Actions\Account;

use App\DTOs\Account\UpdatePasswordDTO;
use App\Models\Identity\User;

class UpdatePassword
{
    public function execute(UpdatePasswordDTO $dto): void
    {
        /** @var User $user */
        $user = auth('web')->user();

        $user->update([
            'password' => $dto->new_password,
        ]);
    }
}
