<?php

namespace App\Domains\Identity\Actions\Users;

use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Models\User;
use Exception;

class UpdateUserStatus
{
    /**
     * @throws Exception
     */
    public function execute(User $user, UserStatus $status): void
    {
        if ($user->status === $status) {
            throw new Exception("User is already {$status}.");
        }

        $user->update(['status' => $status]);
    }
}
