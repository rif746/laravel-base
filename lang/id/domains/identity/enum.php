<?php

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;

return [
    'user_status' => [
        UserStatus::ACTIVE->value => 'Aktif',
        UserStatus::INACTIVE->value => 'Nonaktif',
    ],
    'role_type' => [
        RoleType::SYSTEM_ADMIN->value => 'Administrator Sistem',
        RoleType::ADMIN->value => 'Administrator',
        RoleType::USER->value => 'Pengguna',
    ],
    'user_setting_key' => [
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
            'timezone' => [
                'UTC' => 'UTC',
                'Asia/Jakarta' => 'Waktu Indonesia Barat (Jakarta)',
                'Asia/Makassar' => 'Waktu Indonesia Tengah (Makassar)',
                'Asia/Jayapura' => 'Waktu Indonesia Timur (Jayapura)',
            ],
        ],
    ],
];
