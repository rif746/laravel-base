<?php

use App\Domains\System\Enums\SystemSettingKey;

return [
    'system_setting_key' => [
        SystemSettingKey::WEB_NAME->value => 'Website Name',
        SystemSettingKey::WEB_DESCRIPTION->value => 'Website Description',
        SystemSettingKey::WEB_LOGO->value => 'Website Logo',
        SystemSettingKey::WEB_FAVICON->value => 'Website Favicon',
        SystemSettingKey::WEB_PHONE->value => 'Website Phone',
        SystemSettingKey::WEB_EMAIL->value => 'Website Email',
        SystemSettingKey::WEB_ADDRESS->value => 'Website Address',
        SystemSettingKey::DEFAULT_LANGUAGE->value => 'Default Language',
        SystemSettingKey::TIMEZONE->value => 'Timezone',
        SystemSettingKey::GOOGLE_TAG_MANAGER_ID->value => 'Google Tag Manager ID',
        SystemSettingKey::GOOGLE_WEBMASTER_ID->value => 'Google Webmaster ID',
        'options' => [
            'default_language' => [
                'en' => 'English',
                'id' => 'Indonesian',
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
