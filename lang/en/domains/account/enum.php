<?php

use App\Domains\Account\Enums\GenderOption;

return [
    'gender_option' => [
        GenderOption::MALE->value => 'Male',
        GenderOption::FEMALE->value => 'Female',
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
        ],
    ],
];
