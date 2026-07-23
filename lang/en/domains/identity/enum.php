<?php

use App\Domains\Identity\Enums\UserStatus;

return [
    'user_status' => [
        UserStatus::ACTIVE->value => 'Active',
        UserStatus::INACTIVE->value => 'Inactive',
    ],
    'user_setting_key' => [
        'notification' => 'Notification',
        'language' => 'Language',
        'timezone' => 'Timezone',
        'options' => [
            'language' => [
                'en' => 'English',
                'id' => 'Indonesian',
            ],
            'notification' => [
                'on' => 'On',
                'off' => 'Off',
            ],
            'timezone' => [
                'UTC' => 'UTC',
                'Asia/Jakarta' => 'Western Indonesia Time (Jakarta)',
                'Asia/Makassar' => 'Central Indonesia Time (Makassar)',
                'Asia/Jayapura' => 'Eastern Indonesia Time (Jayapura)',
            ],
        ],
    ],
];
