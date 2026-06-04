<?php

namespace App\Domains\Identity\Actions\Passwords;

use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLink
{
    public function execute(ForgotPasswordDTO $dto): string
    {
        return Password::sendResetLink(['email' => $dto->email]);
    }
}
