<?php

namespace App\Domains\System\Providers;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Events\ExportCompleted;
use App\Domains\System\Events\ImportCompleted;
use App\Domains\System\Listeners\Excel\SendExportReportEmail;
use App\Domains\System\Listeners\Excel\SendImportReportEmail;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\Models\Audit;
use View;

class SystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Tell Laravel: "Whenever someone asks for GetSystemSettings,
        // give them the exact same object instance for the entire request."
        $this->app->singleton(GetSystemSettings::class, function ($app) {
            return new GetSystemSettings;
        });
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public function boot(GetSystemSettings $getSystemSettings): void
    {
        View::composer(['components.layouts.*'], function ($view) use ($getSystemSettings) {
            $view->with('logo', $getSystemSettings->get(SystemSettingKey::WEB_LOGO));
            $view->with('favicon', $getSystemSettings->get(SystemSettingKey::WEB_FAVICON));
        });

        Audit::creating(function (Audit $model) {
            if (empty($model->old_values) && empty($model->new_values)) {
                return false;
            }
        });

        Carbon::macro('toUserTz', fn() => $this->copy()
            ->tz(config('app.display_timezone', 'UTC')));

        Event::listen(ExportCompleted::class, SendExportReportEmail::class);
        Event::listen(ImportCompleted::class, SendImportReportEmail::class);
    }
}
