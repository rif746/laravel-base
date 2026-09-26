<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Models\User;
use Exception;

class RemoveUser
{
    public function __construct(
        protected PurgeUser $purgeUser,
        protected SuspendUser $suspendUser
    ) {}

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        $user->tokens()->delete();
        if ($user->status->isActive()) {
            $this->suspendUser->execute($user);
        } else {
            $this->purgeUser->execute($user);
        }
    }
}
