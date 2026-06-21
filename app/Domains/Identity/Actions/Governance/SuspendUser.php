<?php

namespace App\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class SuspendUser
{
    public function __construct(protected UpdateUserStatus $action) {}

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        if ($user->hasRole([RoleType::SYSTEM_ADMIN, RoleType::ADMIN])) {
            throw new Exception('You can\'t suspend an admin user.');
        }

        if(!$user->status->isActive()) {
            throw new Exception('This user was suspended.');
        }

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        $this->action->execute($user, UserStatus::INACTIVE);
        UserWasSuspended::dispatch(email: $user->email);
    }
}
