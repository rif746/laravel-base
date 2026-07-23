<?php

use App\Domains\System\Enums\SystemSettingKey;

return [
    'system_setting_key' => [
        SystemSettingKey::WEB_NAME->value => 'Nama Situs Web',
        SystemSettingKey::WEB_DESCRIPTION->value => 'Deskripsi Situs Web',
        SystemSettingKey::WEB_LOGO->value => 'Logo Situs Web',
        SystemSettingKey::WEB_FAVICON->value => 'Favicon Situs Web',
        SystemSettingKey::WEB_PHONE->value => 'Telepon Situs Web',
        SystemSettingKey::WEB_EMAIL->value => 'Email Situs Web',
        SystemSettingKey::WEB_ADDRESS->value => 'Alamat Situs Web',
        SystemSettingKey::DEFAULT_LANGUAGE->value => 'Bahasa Default',
        SystemSettingKey::TIMEZONE->value => 'Zona Waktu',
        SystemSettingKey::GOOGLE_TAG_MANAGER_ID->value => 'ID Google Tag Manager',
        SystemSettingKey::GOOGLE_WEBMASTER_ID->value => 'ID Google Webmaster',
        'options' => [
            'default_language' => [
                'en' => 'Bahasa Inggris',
                'id' => 'Bahasa Indonesia',
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
