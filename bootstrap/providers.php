<?php

use App\Domains\Identity\Providers\IdentityServiceProvider;
use App\Domains\System\Providers\SystemServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\UiServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;

return [
    AppServiceProvider::class,
    IdentityServiceProvider::class,
    SystemServiceProvider::class,
    UiServiceProvider::class,
    AuditingServiceProvider::class,
];
