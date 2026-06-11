<?php

namespace App\Domains\Identity\Actions\Onboarding;

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\DTOs\Onboarding\UpdateUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateUser
{
    public function __construct(protected UpdateUserRole $updateUserRole) {}

    /**
     * @throws Throwable
     */
    public function execute(User $user, UpdateUserDTO $dto): User
    {
        return DB::transaction(function () use ($user, $dto) {
            $user->fill([
                'name'  => $dto->name,
                'email' => $dto->email,
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            $user->save();

            if (! is_null($dto->role) && $dto->role !== $user->role_name) {
                $this->updateUserRole->execute($user, [$dto->role]);
            }

            return $user;
        });
    }
}
