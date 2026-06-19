<?php

use App\Domains\Identity\Enums\UserStatus;

return [
    'user_status' => [
        UserStatus::ACTIVE->value => 'Active',
        UserStatus::INACTIVE->value => 'Inactive',
    ],
];
