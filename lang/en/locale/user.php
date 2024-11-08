<?php

return [
    'title' => [
        'table' => 'User Data',
        'modal' => [
            'create' => 'Create User',
            'update' => 'Update User',
            'detail' => 'Detail User',
        ],
    ],
    'field' => [
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'gender' => 'Gender',
        'email_status' => 'Email Status',
        'bio' => 'Bio',
        'village' => 'Village',
        'district' => 'District',
        'city' => 'City',
        'province' => 'Province',
        'password' => 'Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'password_confirmation' => 'Password Confirmation',
        'new_password_confirmation' => 'New Password Confirmation',
    ],
    'email' => [
        'verified' => 'Verified',
        'unverified' => 'Unverified',
    ],
    'alert' => [
        'deletion' => 'Are you sure want to remove user :name?',
        'created' => 'User created successfully!',
        'updated' => 'User updated successfully!',
        'deleted' => 'User removed successfully!',
        'toggle_status' => '{0} User is deactivated!|{1} User is activated!'
    ],
    'button' => [
        'toggle_status' => '{0} Activate|{1} Deactivate'
    ]
];
