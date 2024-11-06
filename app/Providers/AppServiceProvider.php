<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Livewire\Livewire::forceAssetInjection();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::buildTemporaryUrlsUsing(function ($path, $expiration, $options = []) {
            return URL::temporarySignedRoute(
                name: 'temp.files',
                expiration: $expiration,
                parameters: array_merge($options, ['path' => $path])
            );
        });
    }
}
