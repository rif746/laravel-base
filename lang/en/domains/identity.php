<?php

use App\Domains\Identity\Enums\UserStatus;

return [
    'fields' => [
        'user' => [
            'label' => 'User',
            'name' => 'Full Name',
            'email' => 'Email Address',
            'status' => 'Status',
            'password' => 'Secure Password',
            'current_password' => 'Current Password',
            'new_password' => 'New Password',
            'password_confirmation' => 'Confirm Password',
            'verified' => 'Verified',
            'unverified' => 'Unverified',
        ],
        'role' => [
            'label' => 'Role',
            'name' => 'Role Name',
            'permission_count' => 'Permission Count',
            'guard_name' => 'Guard Name',
            'group' => 'Group',
            'permissions' => 'Permissions',
        ],
    ],

    'enum' => [
        'user_status' => [
            UserStatus::ACTIVE->value => 'Active',
            UserStatus::INACTIVE->value => 'Inactive',
        ],
    ],

    'pages' => [
        'user_detail' => [
            'account_info' => 'Account Info',
            'user_info' => 'User Info',
            'menu' => [
                'role_update' => 'Update Role',
                'toggle_status' => 'Toggle Status',
                'password_reset' => 'Send Password Reset',
            ],
            'confirmation' => [
                'toggle_status' => 'Are you sure you want to change the status of this user?',
                'status_changed' => 'User status has been successfully updated.',
                'status_unchanged' => 'User status was not changed.',
                'send_password_reset' => 'Are you sure you want to send a password reset link to this user?',
                'password_reset_success' => 'Password reset link has been successfully sent.',
            ],
        ],
    ],

    'seo' => [
        'user' => [
            'title' => 'User Management',
            'description' => 'Manage user accounts, roles, and permissions.',
            'keywords' => 'user management, users, identity',
        ],
        'user_detail' => [
            'title' => 'Detail of :name',
            'description' => 'Manage user accounts, roles, and permissions.',
            'keywords' => 'user management, users, identity',
        ],
        'role' => [
            'title' => 'Role Management',
            'description' => 'Manage user roles and permissions.',
            'keywords' => 'role management, roles, permissions, identity',
        ],
    ],

    'notifications' => [
        'user_registered' => [
            'subject' => 'Welcome!',
            'body' => 'Your account is ready.',
            'action' => 'Login',
        ],
    ],

    'messages' => [
        'exceptions' => [
            'user_already_status' => 'User is already :status.',
            'user_cannot_be_edited' => 'This user can\'t be edited.',
        ],
    ],
];
