<?php

return [
    'dashboard' => [
        'group-name' => 'Dashboard',
        'index'      => 'Grants authority to access and view the data summaries on the main application dashboard.',
        'admin'      => 'Provides specialized access to the administrator control panel for comprehensive system monitoring.',
        'user'       => 'Provides access to a personalized dashboard containing information relevant to general users.',
    ],
    'user' => [
        'group-name' => 'User Management',
        'index'      => 'Allows users to view and search the list of all user accounts registered in the system.',
        'create'     => 'Grants access to register and add new user accounts to the system.',
        'edit'       => 'Allows modification of profile data, status, and other detailed information for existing user accounts.',
        'delete'     => 'Provides authority to permanently remove or deactivate user accounts from the system.',
    ],
    'role' => [
        'group-name' => 'Role & Permission',
        'index'      => 'View and manage the list of role levels and access rights available within the application.',
        'create'     => 'Provides the ability to design and create new role levels with specific access permissions.',
        'edit'       => 'Allows for role name modification and readjustment of the permission list for existing roles.',
        'delete'     => 'Provides authority to remove role levels that are no longer required by the system.',
    ],
];
