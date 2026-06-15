<?php

return [
    'fields' => [
        'backup' => [
            'file' => 'Backup File',
        ],
        'settings' => [
            'web' => [
                'name' => 'Web Name',
                'description' => 'Web Description',
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
        'audit' => [
            'ip_address' => 'IP Address',
            'browser' => 'Browser',
            'field' => 'Field',
            'old' => 'Old',
            'new' => 'New',
        ],
    ],

    'pages' => [
        'backup' => [
            'title' => 'System Backup Catalogs',
            'backup_button' => 'Back Up',
            'upload_button' => 'Upload Backup File',
            'upload_modal_title' => 'Upload Backup File',
            'empty_state' => 'No Backup File Stored',
            'confirmation' => [
                'delete' => 'Are you sure you want to delete this backup?',
            ],
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
            'delete_success' => 'Backup data deleted successfully.',
            'backup_success' => 'System backup completed successfully.',
            'backup_error' => 'Failed to backup the system.',
            'verification_error' => 'Backup zip file could not be verified on target storage.',
            'restored_success' => 'System restore completed successfully.',
            'restored_error' => 'Failed to restore the system.',
            'download_error' => 'Failed to download :path.',
            'file_not_found' => 'Backup file not found.',
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
            'description' => 'Backup and restore the database and assets.',
            'keywords' => 'backup, restore, database, asset',
        ],
    ],

    'notifications' => [
        'excel' => [
            'import_email' => [
                'subject' => 'Your Excel Import Has Been Processed',
                'intro' => 'Your Excel import request has been completed.',
                'body' => 'The data from your uploaded file has been successfully imported into the system.',
                'outro' => 'If you did not initiate this import, please contact the administrator immediately.',
            ],
            'export_email' => [
                'subject' => 'Your Excel Export Is Ready',
                'intro' => 'Your Excel export request has been completed.',
                'body' => 'Please find the exported file attached to this email.',
                'outro' => 'If you did not request this export, please contact the administrator immediately.',
            ],
        ],
    ],

    'event' => [
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
        'permissions_synced' => 'Permissions synced',
    ],
];
