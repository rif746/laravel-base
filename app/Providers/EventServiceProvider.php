<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->buildDomainEvent();
    }

    public function buildDomainEvent(): void
    {
        $domainProviders = glob(app_path('Domains/*/Providers/*ServiceProvider.php'));

        foreach ($domainProviders as $file) {
            // Convert file path directly to its full PHP Namespace
            // Example: /var/www/app/Domains/Identity/Providers/IdentityServiceProvider.php
            // Becomes: App\Domains\Identity\Providers\IdentityServiceProvider
            $relativePath = str_replace(app_path(), 'App', $file);
            $className = str_replace(['/', '.php'], ['\\', ''], $relativePath);

            // If the class exists and contains our static $listen array, register it
            if (class_exists($className) && property_exists($className, 'listen')) {
                foreach ($className::$listen as $event => $listeners) {
                    foreach ((array) $listeners as $listener) {
                        Event::listen($event, $listener);
                    }
                }
            }
        }
    }
}
