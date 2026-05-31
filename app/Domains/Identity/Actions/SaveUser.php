<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\DTOs\UserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Model;

class SaveUser
{
    public function execute(UserDTO $dto): User|Model
    {
        $data = [
            'name'  => $dto->name,
            'email' => $dto->email,
        ];

        if ($dto->password) {
            $data['password'] = $dto->password;
        }

        $user = User::updateOrCreate(['id' => $dto->id], $data);

        $user->syncRoles($dto->role_name);

        return $user;
    }
}
