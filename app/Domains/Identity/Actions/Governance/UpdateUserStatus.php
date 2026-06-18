<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Enums\RoleType;
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
            throw new Exception(__('domains/identity.messages.exceptions.user_already_status', ['status' => $status->value]));
        }

        if ($user->hasRole([RoleType::SYSTEM_ADMIN, RoleType::ADMIN])) {
            throw new Exception(__('domains/identity.messages.exceptions.user_cannot_be_edited'));
        }

        $user->update(['status' => $status]);
    }
}
