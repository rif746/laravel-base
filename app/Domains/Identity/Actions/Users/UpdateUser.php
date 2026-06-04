<?php

namespace App\Domains\Identity\Actions\Users;

use App\Domains\Identity\Actions\Roles\UpdateUserRole;
use App\Domains\Identity\DTOs\Users\UpdateUserDTO;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateUser
{
    public function __construct(protected UpdateUserRole $updateUserRole)
    {
    }

    /**
     * @throws Throwable
     */
    public function execute(User $user, UpdateUserDTO $dto): bool
    {
        return DB::transaction(function () use ($user, $dto) {
            $user->fill([
                'name' => $dto->name,
                'email' => $dto->email,
            ]);

            if($user->isDirty('email')) {
                $user->email_validated_at = null;
                $user->sendEmailVerificationNotification();
            }

            $user->save();

            if (!is_null($dto->role) && $dto->role !== $user->role_name) {
                $this->updateUserRole->execute($user, [$dto->role]);
            }
            return true;
        });
    }
}
