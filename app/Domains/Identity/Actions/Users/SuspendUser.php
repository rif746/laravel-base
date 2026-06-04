<?php

namespace App\Domains\Identity\Actions\Users;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Models\User;
use Exception;

class SuspendUser
{
    public function __construct(public UpdateUserStatus $action)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        if($user->hasRole(RoleType::ADMIN)) {
            throw new Exception('You can\'t suspend an admin user.');
        }

        if($user->status === UserStatus::ACTIVE) {
            $this->action->execute($user, UserStatus::INACTIVE);
        } else {
            $user->delete();
        }
    }
}
