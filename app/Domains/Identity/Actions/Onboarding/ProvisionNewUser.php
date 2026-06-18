<?php

namespace App\Domains\Identity\Actions\Onboarding;

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Events\Onboarding\UserWasProvisioned;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProvisionNewUser
{
    public function __construct(protected UpdateUserRole $updateUserRole) {}

    /**
     * @throws Throwable
     */
    public function execute(ProvisionUserDTO $dto): User
    {
        $user = DB::transaction(function () use ($dto) {
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
            ]);
            $this->updateUserRole->execute($user, [$dto->role]);

            return $user;
        });

        UserWasProvisioned::dispatch($user);

        return $user;
    }
}
