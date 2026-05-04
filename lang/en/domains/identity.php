<?php

return [
    'fields' => [
        'user' => [
            'label' => 'User',
            'name' => 'Full Name',
            'email' => 'Email Address',
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
        'gender' => [
            'male' => 'Male',
            'female' => 'Female',
        ],
    ],
    'seo' => [
        'user' => [
            'title' => 'User Management',
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
];
