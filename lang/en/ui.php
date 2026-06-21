<?php

return [
    'menu' => [
        'dashboard' => 'Dashboard',
        'identity' => 'User Management',
        'users' => 'Users',
        'roles' => 'Roles & Permissions',
        'settings' => 'System Settings',
        'system_backup' => 'System Backup',
    ],
    'title' => [
        'index' => ':resource Data',
        'create' => 'Create New :resource',
        'update' => 'Update :resource',
        'delete' => 'Delete :resource',
        'view' => 'View :resource',
        'upload' => 'Upload :resource',
        'restore' => 'Restore :resource',
        'import' => 'Import :resource',
    ],
    'greetings' => [
        'morning' => 'Good morning, :name',
        'welcome' => 'Welcome back to the dashboard',
    ],
    'label' => [
        'id' => 'ID',
        'actions' => 'Actions',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'status' => 'Status',
        'no_data' => 'No Data',
    ],
    'button' => [
        'save' => 'Save Changes',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'close' => 'Close',
        'create' => 'Create',
        'update' => 'Update',
        'upload' => 'Upload',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'suspend' => 'Suspend',
        'view' => 'View',
        'log' => 'Log',
        'yes' => 'Yes',
        'no' => 'No',
        'lookup' => 'Search...',
        'logout' => 'Logout',
    ],
    'confirmation' => [
        'logout' => 'Are you sure you want to logout?',
        'delete' => 'Are you sure you want to delete this :resource? This action cannot be undone.',
        'suspend' => 'Are you sure you want to suspend this :resource?',
    ],
    'crud' => [
        'success' => [
            'created' => ':resource has been created successfully.',
            'updated' => ':resource has been updated successfully.',
            'deleted' => ':resource has been removed.',
            'suspended' => ':resource has been suspended.',
            'uploaded' => ':resource has been uploaded successfully.',
        ],
        'error' => [
            'forbidden' => 'You do not have permission to perform this action.',
            'validation_failed' => 'The given data was invalid.',
            'generic' => 'Something went wrong. Please try again.',
        ],
    ],
    'loading' => 'Loading...',
    'errors' => [
        'oops' => 'Oops… You just found an error page',
        '404' => 'We are sorry but the page you are looking for was not found.',
        '500' => 'We are sorry but our server encountered an internal error.',
        'take_me_home' => 'Take me home',
    ],
    'notification' => [
        'empty' => 'No notifications',
        'read_all' => 'Read all notifications',
        'unread' => 'unread messages',
    ],
    'excel' => [
        'import' => [
            'file_label' => 'Excel File',
            'success' => 'Import queued. You will receive an email when it is complete.',
        ],
        'export' => [
            'success' => 'Export queued. You will receive an email with the file when it is ready.',
        ],
    ],
];
