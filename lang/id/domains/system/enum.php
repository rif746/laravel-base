<?php

use App\Domains\System\Enums\LifecycleStatus;
use App\Domains\System\Enums\SystemSettingKey;

return [
    'lifecycle_status' => [
        LifecycleStatus::ACTIVE->value => 'Aktif',
        LifecycleStatus::INACTIVE->value => 'Nonaktif',
    ],
    'system_setting_key' => [
        SystemSettingKey::WEB_NAME->value => 'Nama Website',
        SystemSettingKey::WEB_DESCRIPTION->value => 'Deskripsi Website',
        SystemSettingKey::WEB_LOGO->value => 'Logo Website',
        SystemSettingKey::WEB_FAVICON->value => 'Favicon Website',
        SystemSettingKey::WEB_PHONE->value => 'Telepon Website',
        SystemSettingKey::WEB_EMAIL->value => 'Email Website',
        SystemSettingKey::WEB_ADDRESS->value => 'Alamat Website',
        SystemSettingKey::DEFAULT_LANGUAGE->value => 'Bahasa Default',
        SystemSettingKey::TIMEZONE->value => 'Zona Waktu',
        SystemSettingKey::GOOGLE_TAG_MANAGER_ID->value => 'ID Google Tag Manager',
        SystemSettingKey::GOOGLE_WEBMASTER_ID->value => 'ID Google Webmaster',
    ],
    'system_setting_key_options' => [
        'default_language' => [
            'en' => 'Inggris',
            'id' => 'Indonesia',
        ],
        'timezone' => [
            'UTC' => 'UTC',
            'Asia/Jakarta' => 'Waktu Indonesia Barat (Jakarta)',
            'Asia/Makassar' => 'Waktu Indonesia Tengah (Makassar)',
            'Asia/Jayapura' => 'Waktu Indonesia Timur (Jayapura)',
        ],
    ],
];
