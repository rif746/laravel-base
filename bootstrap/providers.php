<?php

use App\Domains\Account\Providers\AccountServiceProvider;
use App\Domains\Identity\Providers\IdentityServiceProvider;
use App\Domains\System\Providers\SystemServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\UiServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;

return [
    AppServiceProvider::class,
    UiServiceProvider::class,
    AuditingServiceProvider::class,

    // Domain: Account
    AccountServiceProvider::class,

    // Domain: Identity
    IdentityServiceProvider::class,

    // Domain: System
    SystemServiceProvider::class,

];
