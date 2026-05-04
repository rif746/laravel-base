<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
        Passport::authorizationView('pages.auth.oauth.authorize');
        Passport::deviceUserCodeView('pages.auth.oauth.device-user-code');
        Passport::deviceAuthorizationView('pages.auth.oauth.device-authorization');
        Passport::loadKeysFrom(storage_path('/secret/oauth'));
    }
}
