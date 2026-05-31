<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\ResetPasswordDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetUserPassword
{
    public function execute(ResetPasswordDTO $dto): string
    {
        return Password::reset(
            [
                'token'                 => $dto->token,
                'email'                 => $dto->email,
                'password'              => $dto->password,
                'password_confirmation' => $dto->password_confirmation,
            ],
            function (User $user) use ($dto) {
                $user->forceFill([
                    'password'       => $dto->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
