<?php

namespace App\Domains\System\Providers;

use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\System\Events\ExportCompleted;
use App\Domains\System\Events\ImportCompleted;
use App\Domains\System\Listeners\Excel\SendExportReportEmail;
use App\Domains\System\Listeners\Excel\SendImportReportEmail;
use App\Domains\System\Listeners\Files\RemoveUserFiles;
use App\Domains\System\Queries\GetSystemSettings;
use App\Domains\System\Traits\Provider\RegistersDomainEvents;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    use RegistersDomainEvents;

    protected array $listen = [
        ExportCompleted::class => [
            SendExportReportEmail::class,
        ],
        ImportCompleted::class => [
            SendImportReportEmail::class,
        ],
        UserWasPurged::class => [
            RemoveUserFiles::class,
        ],
    ];

    public function register(): void
    {
        $this->app->register(RelationshipServiceProvider::class);

        // Tell Laravel: "Whenever someone asks for GetSystemSettings,
        // give them the exact same object instance for the entire request."
        $this->app->singleton(GetSystemSettings::class, function ($app) {
            return new GetSystemSettings;
        });
    }

    public function boot(): void
    {
        $this->registerEvents();

        Carbon::macro('toUserTz', fn () => $this->copy()
            ->tz(config('app.display_timezone', 'UTC')));
    }
}
