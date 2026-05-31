<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLink
{
    public function execute(ForgotPasswordDTO $dto): string
    {
        return Password::sendResetLink(['email' => $dto->email]);
    }
}
