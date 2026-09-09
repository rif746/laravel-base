<?php

namespace App\Providers;

use App\Attributes\Model\ModelAttribute;
use App\Domains\System\Support\Registry\ModelAttributeRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->eloquentListener();
    }

    /**
     * Listen eloquent event change
     */
    public function eloquentListener(): void
    {
        $registry = $this->app->make(ModelAttributeRegistry::class);
        Event::listen('eloquent.*', function (string $eventName, array $data) use ($registry) {
            $model = $data[0] ?? null; // Model instance (e.g., User)

            // Extracting the payload passed from $data[1]
            $payload = $data[1] ?? null; // Is NULL because standard Eloquent events only pass $data[0]

            $hook = str_replace(['eloquent.', ': ' . get_class($model)], '', $eventName);

            $registry->dispatch(model: $model, hook: $hook, payload: $payload);
        });
    }
}
