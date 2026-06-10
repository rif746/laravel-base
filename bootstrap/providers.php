<?php

use App\Domains\Identity\Providers\IdentityServiceProvider;
use App\Domains\System\Providers\BladeServiceProvider;
use App\Domains\System\Providers\SystemServiceProvider;
use App\Providers\AppServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;

return [
    AppServiceProvider::class,
    IdentityServiceProvider::class,
    SystemServiceProvider::class,
    BladeServiceProvider::class,
    AuditingServiceProvider::class,
];
