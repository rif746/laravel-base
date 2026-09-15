<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Domains\Identity\Actions\Passwords\SendPasswordResetLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Authentication\ForgotPasswordRequest;
use App\Http\Resources\Support\MessageResource;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class ForgotPasswordController extends Controller
{
    /**
     * Forgot Password.
     */
    public function __invoke(ForgotPasswordRequest $request, SendPasswordResetLink $resetLink)
    {
        $message = $request->sendPasswordReset($resetLink);

        return new MessageResource($message);
    }
}
