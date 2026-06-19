<?php

use App\Domains\Identity\Enums\UserStatus;

return [
    'user_status' => [
        UserStatus::ACTIVE->value => 'Aktif',
        UserStatus::INACTIVE->value => 'Non Aktif',
    ],
];
