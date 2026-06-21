<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Events\Governance\UserWasActivated;
use App\Domains\Identity\Models\User;
use Exception;

class ActivateUserStatus
{
    public function __construct(protected UpdateUserStatus $updateUserStatus)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        if($user->status->isActive()) {
            throw new Exception('This user was activated.');
        }

        $this->updateUserStatus->execute($user, UserStatus::ACTIVE);

        UserWasActivated::dispatch(email: $user->email);
    }
}
