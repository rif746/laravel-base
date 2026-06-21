<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Models\User;

class RemoveUser
{
    public function __construct(
        protected PurgeUser $purgeUser,
        protected SuspendUser $suspendUser
    )
    {}
    public function execute(User $user): void
    {
        if($user->status->isActive()) {
            $this->suspendUser->execute($user);
        } else {
            $this->purgeUser->execute($user);
        }
    }
}
