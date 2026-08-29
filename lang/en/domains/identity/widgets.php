<?php

return [
    'user_activities' => [
        'title' => 'Activity Logs',
        'description' => 'Monitor recent account sign-in and security activity.',
        'events' => [
            'login' => 'User signed in',
            'logout' => 'User signed out',
            'password_reset' => 'Password reset requested',
        ],
        'empty' => 'No activity logs recorded yet.',
    ],
];
