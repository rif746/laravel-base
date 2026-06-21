<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\Identity\Models\User;
use Exception;

class PurgeUser
{

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        if ($user->hasRole([RoleType::SYSTEM_ADMIN, RoleType::ADMIN])) {
            throw new Exception('You can\'t suspend an admin user.');
        }

        $user->delete();
        UserWasPurged::dispatch(email: $user->email, user_id: $user->id, model: User::class);
    }
}
