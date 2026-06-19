<?php

use App\Domains\Account\Enums\GenderOption;

return [
    'gender' => [
        GenderOption::MALE->value => 'Laki-laki',
        GenderOption::FEMALE->value => 'Perempuan',
    ],
    'user_settings' => [
        'notification' => 'Notifikasi',
        'language' => 'Bahasa',
        'timezone' => 'Zona Waktu',
        'options' => [
            'language' => [
                'en' => 'Inggris',
                'id' => 'Indonesia',
            ],
            'notification' => [
                'on' => 'Aktif',
                'off' => 'Nonaktif',
            ],
        ],
    ],
];
