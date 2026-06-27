<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\UiServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    OwenIt\Auditing\AuditingServiceProvider::class,
    App\Domains\Identity\Providers\IdentityServiceProvider::class,
    App\Domains\System\Providers\SystemServiceProvider::class,
];
