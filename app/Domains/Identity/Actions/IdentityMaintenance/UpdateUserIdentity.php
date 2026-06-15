<?php

namespace App\Domains\Identity\Actions\IdentityMaintenance;

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateUserIdentity
{
    public function __construct(protected UpdateUserRole $updateUserRole) {}

    /**
     * @throws Throwable
     */
    public function execute(User $user, UpdateUserIdentityDTO $dto): User
    {
        $user->fill([
            'name'  => $dto->name,
            'email' => $dto->email,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        return $user;
    }
}
