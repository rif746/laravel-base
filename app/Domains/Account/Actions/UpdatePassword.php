<?php

namespace App\Domains\Account\Actions;

use App\Domains\Account\DTOs\UpdatePasswordDTO;
use App\Domains\Identity\Models\User;

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
