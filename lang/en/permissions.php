<?php

return [
    'dashboard' => [
        'group-name' => 'Dashboard',
        'index' => 'Grants authority to access and view the data summaries on the main application dashboard.',
        'admin' => 'Provides specialized access to the administrator control panel for comprehensive system monitoring.',
        'user' => 'Provides access to a personalized dashboard containing information relevant to general users.',
    ],
    'user' => [
        'group-name' => 'User Management',
        'viewAny' => 'Allows users to view and search the list of all user accounts registered in the system.',
        'view' => 'Allows users to view the profile and details of a specific user account.',
        'create' => 'Grants access to register and add new user accounts to the system.',
        'update' => 'Allows modification of profile data, status, and other detailed information for existing user accounts.',
        'delete' => 'Provides authority to permanently remove or deactivate user accounts from the system.',
    ],
    'role' => [
        'group-name' => 'Role & Permission',
        'viewAny' => 'Allows users to view and search the list of all role levels available within the application.',
        'view' => 'Allows users to view the details and assigned permissions of a specific role.',
        'create' => 'Provides the ability to design and create new role levels with specific access permissions.',
        'update' => 'Allows for role name modification and readjustment of the permission list for existing roles.',
        'delete' => 'Provides authority to remove role levels that are no longer required by the system.',
    ],
    'system-setting' => [
        'group-name' => 'System Setting',
        'manage' => 'Allows to view and manage system setting',
    ],
    'system-backup' => [
        'group-name' => 'System Backup',
        'manage' => 'Allows to view and manage system backup',
    ],
];
