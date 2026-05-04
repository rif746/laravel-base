<?php

namespace App\Http\Controllers\Web\Auth;

use App\Attributes\Seo;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    #[Seo(
        title: 'domains/auth.seo.verify_email.title',
        description: 'domains/auth.seo.verify_email.description',
        keywords: 'domains/auth.seo.verify_email.keywords'
    )]
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('pages.auth.verify-email');
    }
}
