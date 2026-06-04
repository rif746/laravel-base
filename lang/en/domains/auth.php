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
        ],
        'confirm_password' => [
            'password' => 'Password',
            'submit' => 'Confirm',
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
    ],

    'messages' => [
        'password_reset' => 'Your password has been successfully reset.',
        'invalid_token' => 'This password reset token is invalid.',
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

    'notifications' => [
        'sign_in_activity' => [
            'subject' => 'New sign-in to your account',
            'greeting' => 'Hello :name!',
            'intro' => 'We noticed a new sign-in to your :app account.',
            'time' => 'Time: :time',
            'ip' => 'IP Address: :ip',
            'browser' => 'Browser: :browser',
            'outro' => 'If this was you, you can safely ignore this email. If you did not make this request, please secure your account immediately.',
            'action' => 'Go to Dashboard',
            'title' => 'Sign In Activity',
            'message' => 'You have successfully signed in to your account from :ip.',
        ],
        'verify_email' => [
            'subject' => 'Verify your email address',
            'intro' => 'Please click the button below to verify your email address.',
            'action' => 'Verify Email Address',
            'outro' => 'If you did not create an account, no further action is required.',
        ],
    ],
];
