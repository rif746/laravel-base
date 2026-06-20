<?php

return [
    'dashboard' => [
        'group-name' => 'Dasbor',
        'index' => 'Memberikan wewenang untuk mengakses dan melihat ringkasan data pada dasbor utama aplikasi.',
        'admin' => 'Memberikan akses khusus ke panel kontrol administrator untuk pemantauan sistem yang komprehensif.',
        'user' => 'Memberikan akses ke dasbor pribadi yang berisi informasi yang relevan bagi pengguna umum.',
    ],
    'user' => [
        'group-name' => 'Manajemen Pengguna',
        'viewAny' => 'Memungkinkan pengguna untuk melihat dan mencari daftar semua akun pengguna yang terdaftar di sistem.',
        'view' => 'Memungkinkan pengguna untuk melihat informasi rinci dari akun pengguna.',
        'create' => 'Memberikan akses untuk mendaftarkan dan menambah akun pengguna baru ke sistem.',
        'update' => 'Memungkinkan modifikasi data profil, status, dan informasi rinci lainnya untuk akun pengguna yang ada.',
        'delete' => 'Memberikan wewenang untuk menghapus atau menonaktifkan akun pengguna secara permanen dari sistem.',
    ],
    'role' => [
        'group-name' => 'Peran & Izin',
        'viewAny' => 'Memungkinkan pengguna untuk melihat dan mencari daftar semua peran yang tersedia di dalam aplikasi.',
        'view' => 'Memungkinkan pengguna untuk melihat informasi rinci dari peran.',
        'create' => 'Memberikan kemampuan untuk merancang dan membuat tingkat peran baru dengan izin akses tertentu.',
        'update' => 'Memungkinkan modifikasi nama peran dan penyesuaian kembali daftar izin untuk peran yang ada.',
        'delete' => 'Memberikan wewenang untuk menghapus tingkat peran yang tidak lagi diperlukan oleh sistem.',
    ],
    'system-setting' => [
        'group-name' => 'Pengaturan Sistem',
        'manage' => 'Memberikan akses pengaturan sistem.',
    ],
    'system-backup' => [
        'group-name' => 'Cadangkan Sistem',
        'manage' => 'Memberikan akses untuk membuat cadangan dan memulihkan sistem.',
    ],
];
