<?php

return [
    'fields' => [
        'login' => [
            'email' => 'Email address',
            'password' => 'Password',
            'remember' => 'Remember me',
            'forgot_password' => 'Forgot Password?',
            'submit' => 'Sign in',
        ],
        'register' => [
            'name' => 'Full Name',
            'email' => 'Email address',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'submit' => 'Sign up',
        ],
        'forgot_password' => [
            'email' => 'Email address',
            'submit' => 'Email Password Reset Link',
        ],
        'reset_password' => [
            'email' => 'Email address',
            'password' => 'New Password',
            'confirm_password' => 'Confirm Password',
            'submit' => 'Reset Password',
        ],
        'verify_email' => [
            'submit' => 'Resend Verification Email',
            'logout' => 'Log Out',
        ],
        'confirm_password' => [
            'password' => 'Password',
            'submit' => 'Confirm',
        ],
        'oauth' => [
            'authorize' => [
                'submit' => 'Authorize',
                'cancel' => 'Deny',
            ],
            'device' => [
                'submit' => 'Authorize Device',
                'user_code' => 'Device Code',
                'continue' => 'Continue',
            ],
        ],
    ],

    'pages' => [
        'login' => [
            'header' => 'Sign in to your account',
            'no_account' => "Don't have an account?",
            'register_link' => 'Sign up',
        ],
        'register' => [
            'header' => 'Create a new account',
            'has_account' => 'Already have an account?',
            'login_link' => 'Sign in',
        ],
        'forgot_password' => [
            'header' => 'Forgot your password?',
            'subheader' => 'No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
            'back_to_login' => 'Back to sign in',
        ],
        'reset_password' => [
            'header' => 'Reset Password',
        ],
        'verify_email' => [
            'header' => 'Verify Email',
            'subheader' => "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
            'resend_link' => 'A new verification link has been sent to the email address you provided during registration.',
        ],
        'confirm_password' => [
            'header' => 'Confirm Password',
            'subheader' => 'This is a secure area of the application. Please confirm your password before continuing.',
        ],
        'oauth' => [
            'authorize' => [
                'header' => 'Authorization Request',
                'subheader' => 'Review the permissions before continuing',
                'is_requesting' => 'is requesting access to your account',
                'requested_permissions' => 'Requested Permissions',
            ],
            'device' => [
                'header' => 'Device Authorization',
                'subheader' => 'Review the permissions before connecting your device',
                'connect_header' => 'Connect Your Device',
                'connect_subheader' => 'Enter the code displayed on your device',
                'user_code_hint' => 'The code is shown on your device\'s screen',
                'success_header' => 'Success!',
                'success_subheader' => 'The device has been successfully authorized.',
            ],
        ],
    ],

    'seo' => [
        'login' => [
            'title' => 'Sign In',
            'description' => 'Sign in to your account to access the dashboard.',
            'keywords' => 'login, sign in, auth',
        ],
        'register' => [
            'title' => 'Sign Up',
            'description' => 'Create a new account to get started.',
            'keywords' => 'register, sign up, auth',
        ],
        'forgot_password' => [
            'title' => 'Forgot Password',
            'description' => 'Recover your account password.',
            'keywords' => 'forgot password, recover, auth',
        ],
        'reset_password' => [
            'title' => 'Reset Password',
            'description' => 'Set a new password for your account.',
            'keywords' => 'reset password, auth',
        ],
        'verify_email' => [
            'title' => 'Verify Email',
            'description' => 'Verify your email address to secure your account.',
            'keywords' => 'verify email, auth',
        ],
        'confirm_password' => [
            'title' => 'Confirm Password',
            'description' => 'Please confirm your password before continuing.',
            'keywords' => 'confirm password, auth',
        ],
    ],
];
