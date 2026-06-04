<?php

namespace App\Domains\Identity\Actions\Passwords;

use App\Domains\Identity\DTOs\Passwords\UpdatePasswordDTO;
use App\Domains\Identity\Models\User;

class UpdatePassword
{
    public function execute(User $user, UpdatePasswordDTO $dto): void
    {
        $user->update([
            'password' => $dto->new_password,
        ]);
    }
}
