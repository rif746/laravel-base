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
        'restore' => 'Restore :resource',
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
    ],
    'button' => [
        'save' => 'Save Changes',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'close' => 'Close',
        'create' => 'Create',
        'update' => 'Update',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'yes' => 'Yes',
        'no' => 'No',
        'lookup' => 'Search...',
        'logout' => 'Logout',
    ],
    'crud' => [
        'success' => [
            'created' => ':resource has been created successfully.',
            'updated' => ':resource has been updated successfully.',
            'deleted' => ':resource has been removed.',
            'uploaded' => ':resource has been uploaded successfully.',
        ],
        'error' => [
            'forbidden' => 'You do not have permission to perform this action.',
            'validation_failed' => 'The given data was invalid.',
            'generic' => 'Something went wrong. Please try again.',
        ],
        'confirmation' => [
            'delete' => 'Are you sure you want to delete this :resource? This action cannot be undone.',
        ],
    ],
    'loading' => 'Loading...',
    'logout' => 'Are you sure want to logout?',
    'notification' => [
        'empty' => 'No notifications',
        'read_all' => 'Read all notifications',
        'unread' => 'unread messages',
    ],
];
