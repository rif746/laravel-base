<?php

use App\Domains\Account\Enums\GenderOption;

return [
    'gender' => [
        GenderOption::MALE->value => 'Male',
        GenderOption::FEMALE->value => 'Female',
    ],
    'user_settings' => [
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
        ],
    ],
];
