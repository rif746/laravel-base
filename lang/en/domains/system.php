<?php

return [
    'seo' => [
        'default_title' => 'Laravel Base',
        'default_description' => 'Laravel Base using Livewire v4, Bootstrap, and DataTables.net by Syarif Ubaidillah.',
        'dashboard' => [
            'title' => 'Dashboard',
            'description' => 'Dashboard analytic for this application.',
            'keywords' => 'dashboard, analytics, review',
        ],
        'settings' => [
            'title' => 'System Settings',
            'description' => 'System settings and configurations.',
            'keywords' => 'system, settings, configurations',
        ],
        'backups' => [
            'title' => 'System Backups',
            'description' => 'Backup and Restore database and asset.',
            'keywords' => 'backup, restore, database, asset',
        ],
    ],
    'backups' => [
        'title' => 'System Backup Catalogs',
        'backup' => 'Back Up',
        'upload_backup' => 'Upload Backup File',
        'delete_confirm' => 'Delete this backup data?',
        'delete_success' => 'Backup data deleted successfully',
        'empty_state' => 'No Backup File Stored',
    ],
    'settings' => [
        'sections' => [
            'web' => 'Web',
            'general' => 'General',
            'webmaster' => 'Webmaster',
        ],
        'web' => [
            'name' => 'Web Name',
            'address' => 'Web Address',
            'phone' => 'Web Phone',
            'email' => 'Web Email',
            'logo' => 'Web Logo',
            'favicon' => 'Web Favicon',
        ],
        'default_language' => 'Default Language',
        'timezone' => 'Timezone',
        'google' => [
            'tag_manager_id' => 'Google Tag Manager ID',
            'webmaster_id' => 'Google Webmaster ID',
        ],
    ],
];
