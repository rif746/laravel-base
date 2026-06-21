<?php

namespace App\Domains\Identity\Providers;

use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Domains\Identity\Events\Governance\UserWasActivated;
use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Listeners\Authentication\SendSignInActivityNotification;
use App\Domains\Identity\Listeners\Governance\SendUserActivatedNotification;
use App\Domains\Identity\Listeners\Governance\SendUserPurgedNotification;
use App\Domains\Identity\Listeners\Governance\SendUserSuspendedNotification;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GetAuthenticatedUserContext::class, fn () => new GetAuthenticatedUserContext);
    }

    public function boot(): void
    {
        Event::listen(UserLoggedIn::class, SendSignInActivityNotification::class);
        Event::listen(UserWasSuspended::class, SendUserSuspendedNotification::class);
        Event::listen(UserWasPurged::class, SendUserPurgedNotification::class);
        Event::listen(UserWasActivated::class, SendUserActivatedNotification::class);
    }
}
