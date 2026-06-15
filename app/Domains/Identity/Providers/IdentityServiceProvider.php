<?php

namespace App\Domains\Identity\Providers;

use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Domains\Identity\Listeners\Authentication\SendSignInActivityNotification;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GetAuthenticatedUserContext::class, fn () => new GetAuthenticatedUserContext());
    }

    public function boot(): void
    {
        Event::listen(UserLoggedIn::class, SendSignInActivityNotification::class);
    }
}
