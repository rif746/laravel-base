<?php

return [
    'title' => [
        'table' => 'Data Pengguna',
        'modal' => [
            'create' => 'Tambah Pengguna Baru',
            'update' => 'Sunting Pengguna',
            'detail' => 'Detail Pengguna',
        ],
    ],
    'detail' => 'Lihat semua pengguna yang terdaftar pada sistem.',
    'field' => [
        'name' => 'Nama',
        'email' => 'E-mail',
        'role' => 'Peran',
        'gender' => 'Jenis Kelamin',
        'email_status' => 'Status E-mail',
        'bio' => 'Bio',
        'village' => 'Desa',
        'district' => 'Kecamatan',
        'city' => 'Kabupaten/Kota',
        'province' => 'Provinsi',
        'password' => 'Password',
        'current_password' => 'Password Saat Ini',
        'new_password' => 'Password Baru',
        'password_confirmation' => 'Konfirmasi Password',
        'new_password_confirmation' => 'Konfirmasi Password Baru',
    ],
    'email' => [
        'verified' => 'Terverifikasi',
        'unverified' => 'Belum Diverifikasi',
    ],
    'alert' => [
        'deletion' => 'Anda yakin ingin menghapus pengguna :name?',
        'created' => 'Pengguna berhasil dibuat!',
        'updated' => 'Pengguna berhasil diubah!',
        'deleted' => 'Pengguna berhasil dihapus!',
        'toggle_status' => '{0} Aktifkan pengguna :name?|{1} Nonaktifkan pengguna :name?',
        'status_toggled' => '{0} Pengguna di nonaktifkan!|{1} Pengguna di aktifkan!',
    ],
];
