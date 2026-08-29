<?php

namespace App\Domains\Identity\Providers;

use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Domains\Identity\Events\Authentication\UserLoggedOut;
use App\Domains\Identity\Events\Governance\UserWasActivated;
use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Listeners\Authentication\RecordSignInActivity;
use App\Domains\Identity\Listeners\Authentication\RecordSignOutActivity;
use App\Domains\Identity\Listeners\Authentication\SendSignInActivityNotification;
use App\Domains\Identity\Listeners\Governance\SendUserActivatedNotification;
use App\Domains\Identity\Listeners\Governance\SendUserPurgedNotification;
use App\Domains\Identity\Listeners\Governance\SendUserSuspendedNotification;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use App\Domains\System\Traits\Provider\RegistersDomainEvents;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    use RegistersDomainEvents;

    protected array $listen = [
        UserLoggedIn::class => [
            SendSignInActivityNotification::class,
            RecordSignInActivity::class
        ],
        UserLoggedOut::class => [
            RecordSignOutActivity::class
        ],
        UserWasActivated::class => [
            SendUserActivatedNotification::class,
        ],
        UserWasPurged::class => [
            SendUserPurgedNotification::class,
        ],
        UserWasSuspended::class => [
            SendUserSuspendedNotification::class,
        ],
    ];

    public function register(): void
    {
        $this->app->register(RelationshipServiceProvider::class);
    }

    public function boot(): void
    {
        $this->registerEvents();
    }
}
