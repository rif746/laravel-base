<?php

namespace App\Domains\Identity\Actions\Users;

use App\Domains\Identity\Actions\Roles\UpdateUserRole;
use App\Domains\Identity\DTOs\Users\ProvisionUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProvisionNewUser
{
    public function __construct(protected UpdateUserRole $updateUserRole)
    {
    }

    /**
     * @throws Throwable
     */
    public function execute(ProvisionUserDTO $dto): bool
    {
        return DB::transaction(function () use ($dto) {
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
            ]);
            $this->updateUserRole->execute($user, [$dto->role]);

            return true;
        });
    }
}
