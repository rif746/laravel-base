<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('markdown', function($expr) {
            return "<?php echo str($expr)->markdown(['html_input' => 'strip', 'allow_unsafe_links' => false]) ?>";
        });
    }
}
