<?php

return [
    'fields' => [
        'profile' => [
            'gender' => 'Gender',
            'date_of_birth' => 'Date of Birth',
            'phone_number' => 'Phone Number',
        ],
    ],

    'pages' => [
        'profile' => [
            'title' => 'Profile Information',
            'description' => "Update your account's profile information and email address.",
        ],
        'password' => [
            'title' => 'Update Password',
            'description' => 'Ensure your account is using a long, random password to stay secure.',
        ],
        'delete_account' => [
            'title' => 'Delete Account',
            'description' => 'Once your account is deleted, all of its resources and data will be permanently deleted.',
        ],
        'user_settings' => [
            'title' => 'User Settings',
            'description' => 'Manage your application preferences like theme and language.',
        ],
    ],

    'enum' => [
        'user_settings' => [
            'notification' => 'Notification',
            'language' => 'Language',
            'timezone' => 'Timezone',
            'options' => [
                'language' => [
                    'en' => 'English',
                    'id' => 'Indonesian',
                ],
                'notification' => [
                    'on' => 'On',
                    'off' => 'Off',
                ],
            ],
        ],
    ],

    'seo' => [
        'profile' => [
            'title' => 'Profile Information',
            'description' => "Update your account's profile information and email address.",
            'keywords' => 'profile, account settings, email update',
        ],
    ],
];
