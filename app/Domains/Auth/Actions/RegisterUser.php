<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterUser
{
    public function execute(RegisterUserDTO $dto): User
    {
        $user = User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }
}
