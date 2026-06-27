<?php

namespace App\Domains\Account\Providers;

use App\Domains\Account\Listeners\SyncImportedUserProfile;
use App\Domains\Identity\Events\Integration\UserImportWasProcessed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public static array $listen = [
        UserImportWasProcessed::class => [
            SyncImportedUserProfile::class,
        ]
    ];

    public function boot(): void
    {
        //
    }
}
