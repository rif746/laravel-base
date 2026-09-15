<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Domains\Identity\Actions\Passwords\ResetUserPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Authentication\ResetPasswordRequest;
use App\Http\Resources\Support\MessageResource;

/**
 * @tags Authentication
 */
class ResetPasswordController extends Controller
{
    /**
     * Reset Password.
     */
    public function __invoke(ResetPasswordRequest $request, ResetUserPassword $resetUserPassword)
    {
        $message = $request->resetPassword($resetUserPassword);
        return new MessageResource(__($message));
    }
}
