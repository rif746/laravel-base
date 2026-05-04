<?php

return [
    'fields' => [
        'profile' => [
            'gender' => 'Jenis Kelamin',
            'date_of_birth' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
        ],
    ],

    'pages' => [
        'profile' => [
            'title' => 'Informasi Profil',
            'description' => "Perbarui informasi profil akun dan alamat email Anda.",
        ],
        'password' => [
            'title' => 'Perbarui Kata Sandi',
            'description' => 'Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.',
        ],
        'delete_account' => [
            'title' => 'Hapus Akun',
            'description' => 'Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.',
        ],
        'user_settings' => [
            'title' => 'Pengaturan Pengguna',
            'description' => 'Kelola preferensi aplikasi Anda seperti tema dan bahasa.',
        ],
    ],

    'enum' => [
        'user_settings' => [
            'notification' => 'Notifikasi',
            'language' => 'Bahasa',
            'timezone' => 'Zona Waktu',
            'options' => [
                'language' => [
                    'en' => 'Inggris',
                    'id' => 'Indonesia',
                ],
                'notification' => [
                    'on' => 'Aktif',
                    'off' => 'Nonaktif',
                ],
            ],
        ],
    ],

    'seo' => [
        'profile' => [
            'title' => 'Informasi Profil',
            'description' => "Perbarui informasi profil akun dan alamat email Anda.",
            'keywords' => 'profil, pengaturan akun, perbarui email',
        ],
    ],
];
