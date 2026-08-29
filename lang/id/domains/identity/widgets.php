<?php

return [
    'user_activities' => [
        'title' => 'Log Aktivitas',
        'description' => 'Pantau riwayat masuk dan aktivitas keamanan akun terkini.',
        'events' => [
            'login' => 'Pengguna berhasil masuk',
            'logout' => 'Pengguna keluar',
            'password_reset' => 'Permintaan atur ulang kata sandi',
        ],
        'empty' => 'Belum ada catatan aktivitas.',
    ],
];
