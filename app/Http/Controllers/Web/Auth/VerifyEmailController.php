<?php

namespace App\Http\Controllers\Web\Auth;

use App\Domains\Identity\Actions\Registration\VerifyUserEmail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * The Gateway delegates the database write + event dispatching entirely
     * to VerifyUserEmail — it owns only the redirect logic.
     */
    public function __invoke(
        EmailVerificationRequest $request,
        VerifyUserEmail $action,
    ): RedirectResponse {
        // Action handles markEmailAsVerified(), Verified event, and UserEmailVerified event.
        $action->execute($request->user());

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
