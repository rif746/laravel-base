<?php

namespace App\Domains\System\Providers;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Register a view composer for presentation glue.
         *
         * This binds data to specific layout assets (e.g., sidebar) using
         * a decoupled query pattern, keeping the domain's root provider pristine.
         */
        View::composer(['components.layouts.*'], function ($view) {
            $view->with('logo', GetSystemSettings::get(SystemSettingKey::WEB_LOGO));
            $view->with('favicon', GetSystemSettings::get(SystemSettingKey::WEB_FAVICON));
        });
    }
}
