<?php

namespace App\Domains\Identity\Actions\Passwords;

use App\Domains\Identity\DTOs\Passwords\ResetPasswordDTO;
use App\Domains\Identity\Events\Passwords\UserPasswordReset;
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
                'token' => $dto->token,
                'email' => $dto->email,
                'password' => $dto->password,
                'password_confirmation' => $dto->password_confirmation,
            ],
            function (User $user) use ($dto) {
                $user->forceFill([
                    'password' => $dto->password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Framework event: triggers session token invalidation etc.
                event(new PasswordReset($user));

                // Domain event: for application-level listeners (audit log, security alert).
                UserPasswordReset::dispatch($user);
            }
        );
    }
}
