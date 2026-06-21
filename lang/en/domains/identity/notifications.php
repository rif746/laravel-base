<?php

return [
    'user_registered' => [
        'subject' => 'Welcome!',
        'body' => 'Your account is ready.',
        'action' => 'Login',
    ],
    'governance' => [
        'user_suspended' => [
            'subject' => 'Account Suspended',
            'intro' => 'We are writing to inform you that your account has been suspended.',
            'body' => 'This action was taken due to a violation of our terms of service or a security concern regarding your account.',
            'outro' => 'If you believe this is an error, please contact our support team.',
        ],
        'user_purged' => [
            'subject' => 'Account Permanently Removed',
            'intro' => 'We are writing to inform you that your account and all associated data have been permanently removed.',
            'body' => 'This action was taken either per your request or due to a severe violation of our policies.',
            'outro' => 'If you have any questions, please contact our support team.',
        ],
        'user_activated' => [
            'subject' => 'Account Activated',
            'intro' => 'We are pleased to inform you that your account has been successfully activated.',
            'body' => 'You can now log in and access all the features of your account.',
            'outro' => 'If you have any questions, please contact our support team.',
        ],
    ],
];
