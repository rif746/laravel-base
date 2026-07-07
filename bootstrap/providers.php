<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\UiServiceProvider::class,
    OwenIt\Auditing\AuditingServiceProvider::class,

    // Domain: Account
    App\Domains\Account\Providers\AccountServiceProvider::class,

    // Domain: Identity
    App\Domains\Identity\Providers\IdentityServiceProvider::class,

    // Domain: System
    App\Domains\System\Providers\SystemServiceProvider::class,

];
