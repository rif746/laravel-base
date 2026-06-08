<?php

namespace App\Domains\System\Providers;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class SystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Tell Laravel: "Whenever someone asks for GetSystemSettings,
        // give them the exact same object instance for the entire request."
        $this->app->singleton(GetSystemSettings::class, function ($app) {
            return new GetSystemSettings();
        });
    }

    public function boot(GetSystemSettings $getSystemSettings): void
    {
        View::composer(['components.layouts.*'], function ($view) use ($getSystemSettings) {
            $view->with('logo', $getSystemSettings->get(SystemSettingKey::WEB_LOGO));
            $view->with('favicon', $getSystemSettings->get(SystemSettingKey::WEB_FAVICON));
        });

        // Google Tag Manager Head
        Blade::directive('gtmHead', function () {
            return "<?php \$gtmId = app(\App\Domains\System\Queries\GetSystemSettings::class)->get(\App\Domains\System\Enums\SystemSettingKey::GOOGLE_TAG_MANAGER_ID); if (!empty(\$gtmId)): ?>
                <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','<?php echo \$gtmId; ?>');</script>
                <?php endif; ?>";
        });

        // Google Tag Manager Body
        Blade::directive('gtmBody', function () {
            return "<?php \$gtmId = app(\App\Domains\System\Queries\GetSystemSettings::class)->get(\App\Domains\System\Enums\SystemSettingKey::GOOGLE_TAG_MANAGER_ID); if (!empty(\$gtmId)): ?>
            <noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=<?php echo \$gtmId; ?>\"
            height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
            <?php endif; ?>";
        });

        // Google Tag Manager Meta
        Blade::directive('webmasterMeta', function () {
            return "<?php \$webmasterId = app(\App\Domains\System\Queries\GetSystemSettings::class)->get(\App\Domains\System\Enums\SystemSettingKey::GOOGLE_WEBMASTER_ID); if (!empty(\$webmasterId)): ?>
            <meta name=\"google-site-verification\" content=\"<?php echo \$webmasterId; ?>\" />
            <?php endif; ?>";
        });
    }
}
