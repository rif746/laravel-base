<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLink
{
    public function execute(ForgotPasswordDTO $dto): string
    {
        return Password::sendResetLink(['email' => $dto->email]);
    }
}
