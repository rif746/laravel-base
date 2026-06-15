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

    'pages' => [
        'user_detail' => [
            'account_info' => 'Info Akun',
            'user_info' => 'Info Pengguna',
            'confirmation' => [
                'toggle_status' => 'Apakah Anda yakin ingin mengubah status pengguna ini?',
                'status_changed' => 'Status pengguna berhasil diperbarui.',
                'status_unchanged' => 'Status pengguna tidak diubah.',
                'send_password_reset' => 'Apakah Anda yakin ingin mengirimkan tautan setel ulang kata sandi kepada pengguna ini?',
                'password_reset_success' => 'Tautan setel ulang kata sandi berhasil dikirim.',
            ],
        ],
    ],

    'seo' => [
        'user' => [
            'title' => 'Manajemen Pengguna',
            'description' => 'Kelola akun pengguna, peran, dan izin.',
            'keywords' => 'manajemen pengguna, pengguna, identitas',
        ],
        'user_detail' => [
            'title' => 'Detail dari :name',
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

    'messages' => [
        'exceptions' => [
            'user_already_status' => 'Pengguna sudah dalam status :status.',
            'user_cannot_be_edited' => 'Pengguna ini tidak dapat diubah.',
        ],
    ],
];
