<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Domains\Identity\Actions\Onboarding\VerifyUserEmail;
use App\Http\Controllers\Controller;
use App\Http\Resources\Support\MessageResource;
use App\Http\Resources\Support\SuccessResource;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class VerifyEmailController extends Controller
{
    /**
     * Email Verification.
     *
     * The Gateway delegates the database write + event dispatching entirely
     * to VerifyUserEmail — it owns only the redirect logic.
     */
    public function __invoke(
        Request $request,
        VerifyUserEmail $action,
    ) {
        // Action handles markEmailAsVerified(), Verified event, and UserEmailVerified event.
        $action->execute($request->user());

        return new MessageResource(__('ui/crud.success.retrieved', ['resource' => 'Token']));
    }
}
