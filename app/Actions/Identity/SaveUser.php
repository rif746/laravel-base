<?php

namespace App\Actions\Identity;

use App\DTOs\Identity\UserDTO;
use App\Models\Identity\User;
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
