<?php

use App\Domains\System\Providers\SystemServiceProvider;
use App\Providers\AppServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;

return [
    AppServiceProvider::class,
    SystemServiceProvider::class,
    AuditingServiceProvider::class,
];
