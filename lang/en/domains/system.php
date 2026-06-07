<?php

return [
    'fields' => [
        'backup' => [
            'file' => 'Backup File',
        ],
        'settings' => [
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
    ],

    'pages' => [
        'backup' => [
            'title' => 'System Backup Catalogs',
            'backup_button' => 'Back Up',
            'upload_button' => 'Upload Backup File',
            'upload_modal_title' => 'Upload Backup File',
            'empty_state' => 'No Backup File Stored',
            'delete_confirm' => 'Delete this backup data?',
        ],
        'settings' => [
            'update_title' => 'Update Settings',
            'sections' => [
                'web' => 'Web',
                'general' => 'General',
                'webmaster' => 'Webmaster',
            ],
        ],
    ],

    'messages' => [
        'backup' => [
            'delete_success' => 'Backup data deleted successfully',
            'backup_success' => 'System Backup successfully',
            'backup_error' => 'Failed to Backup System',
            'restored_success' => 'System Restore successfully',
            'restored_error' => 'Failed to Restore System',
            'download_error' => 'Failed to download :path',
        ],
    ],

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
        'backup' => [
            'title' => 'System Backups',
            'description' => 'Backup and Restore database and asset.',
            'keywords' => 'backup, restore, database, asset',
        ],
    ],
];
