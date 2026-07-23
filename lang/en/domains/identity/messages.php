<?php

return [
    'success' => [
        'password_reset_link_sent' => 'Password reset link has been sent.',
        'user_role_updated' => 'User role has been updated.',
        'user_status_updated' => 'User status has been updated.',
    ],
    'exceptions' => [
        'failed_to_send_password_reset_link' => 'Failed to send password reset link.',
        'failed_to_update_user_role' => 'Failed to update user role.',
        'failed_to_update_user_status' => 'Failed to update user status.',
        'user_already_active' => 'This user is already active.',
        'user_already_suspended' => 'This user is already suspended.',
        'user_already_in_status' => 'User is already :status.',
        'user_cannot_be_edited' => 'This user can\'t be edited.',
        'user_cannot_be_purged' => 'You can\'t purge an admin user.',
        'user_cannot_be_suspended' => 'You can\'t suspend an admin user.',
        'cannot_remove_system_role' => 'Can\'t remove system role.',
        'role_has_users' => 'This role has a users attached to it.',
    ],
];
