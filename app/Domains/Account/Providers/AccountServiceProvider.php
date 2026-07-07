<?php

namespace App\Domains\Account\Providers;

use App\Domains\Account\Listeners\SyncImportedUserProfile;
use App\Domains\Identity\Events\Integration\UserImportWasProcessed;
use App\Domains\System\Traits\Provider\RegistersDomainEvents;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    use RegistersDomainEvents;

    protected array $listen = [
        UserImportWasProcessed::class => [
            SyncImportedUserProfile::class,
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
