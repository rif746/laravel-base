<?php

namespace App\Domains\System\Traits\Provider;

use Illuminate\Support\Facades\Event;

/**
 * Provides self-contained event registration for domain service providers.
 *
 * Usage: define a protected (or public) $listen array on the using class,
 * mapping event class-strings to arrays of listener class-strings, then call
 * registerEvents() — typically inside boot().
 *
 * Example:
 *
 *   protected array $listen = [
 *       OrderPlaced::class => [
 *           SendOrderConfirmation::class,
 *           UpdateInventory::class,
 *       ],
 *   ];
 */
trait RegistersDomainEvents
{
    /**
     * Register all events declared in the $listen property.
     *
     * Iterates over $this->listen (which may be declared as public, protected,
     * or static on the consuming class) and delegates each event–listener pair
     * to Laravel's Event facade. No filesystem scanning is performed.
     */
    public function registerEvents(): void
    {
        /** @var array<class-string, list<class-string>> $listen */
        $listen = $this->listen ?? [];

        foreach ($listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
