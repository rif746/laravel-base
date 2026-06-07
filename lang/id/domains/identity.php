<?php

use App\Domains\Identity\Enums\UserStatus;

return [
    'fields' => [
        'user' => [
            'label' => 'Pengguna',
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'status' => 'Status',
            'password' => 'Kata Sandi Aman',
            'current_password' => 'Kata Sandi Saat Ini',
            'new_password' => 'Kata Sandi Baru',
            'password_confirmation' => 'Konfirmasi Kata Sandi',
            'verified' => 'Terverifikasi',
            'unverified' => 'Belum Terverifikasi',
        ],
        'role' => [
            'label' => 'Peran',
            'name' => 'Nama Peran',
            'permission_count' => 'Jumlah Izin',
            'guard_name' => 'Nama Guard',
            'group' => 'Grup',
            'permissions' => 'Izin',
        ],
    ],
    'enum' => [
        'user_status' => [
            UserStatus::ACTIVE->value => 'Aktif',
            UserStatus::INACTIVE->value => 'Non Aktif',
        ],
    ],
    'seo' => [
        'user' => [
            'title' => 'Manajemen Pengguna',
            'description' => 'Kelola akun pengguna, peran, dan izin.',
            'keywords' => 'manajemen pengguna, pengguna, identitas',
        ],
        'role' => [
            'title' => 'Manajemen Peran',
            'description' => 'Kelola peran pengguna dan izin.',
            'keywords' => 'manajemen peran, peran, izin, identitas',
        ],
    ],
    'notifications' => [
        'user_registered' => [
            'subject' => 'Selamat Datang!',
            'body' => 'Akun Anda sudah siap.',
            'action' => 'Masuk',
        ],
    ],
];
