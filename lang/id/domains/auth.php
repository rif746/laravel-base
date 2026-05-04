<?php

return [
    'fields' => [
        'login' => [
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi',
            'remember' => 'Ingat saya',
            'forgot_password' => 'Lupa Kata Sandi?',
            'submit' => 'Masuk',
        ],
        'register' => [
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi',
            'confirm_password' => 'Konfirmasi Kata Sandi',
            'submit' => 'Daftar',
        ],
        'forgot_password' => [
            'email' => 'Alamat Email',
            'submit' => 'Kirim Tautan Atur Ulang Kata Sandi',
        ],
        'reset_password' => [
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi Baru',
            'confirm_password' => 'Konfirmasi Kata Sandi',
            'submit' => 'Atur Ulang Kata Sandi',
        ],
        'verify_email' => [
            'submit' => 'Kirim Ulang Email Verifikasi',
            'logout' => 'Keluar',
        ],
        'confirm_password' => [
            'password' => 'Kata Sandi',
            'submit' => 'Konfirmasi',
        ],
        'oauth' => [
            'authorize' => [
                'submit' => 'Izinkan',
                'cancel' => 'Tolak',
            ],
            'device' => [
                'submit' => 'Izinkan Perangkat',
                'user_code' => 'Kode Perangkat',
                'continue' => 'Lanjutkan',
            ],
        ],
    ],

    'pages' => [
        'login' => [
            'header' => 'Masuk ke akun Anda',
            'no_account' => "Belum punya akun?",
            'register_link' => 'Daftar',
        ],
        'register' => [
            'header' => 'Buat akun baru',
            'has_account' => 'Sudah punya akun?',
            'login_link' => 'Masuk',
        ],
        'forgot_password' => [
            'header' => 'Lupa kata sandi Anda?',
            'subheader' => 'Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan atur ulang kata sandi yang memungkinkan Anda memilih yang baru.',
            'back_to_login' => 'Kembali ke halaman masuk',
        ],
        'reset_password' => [
            'header' => 'Atur Ulang Kata Sandi',
        ],
        'verify_email' => [
            'header' => 'Verifikasi Email',
            'subheader' => "Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan melalui email? Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang baru.",
            'resend_link' => 'Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.',
        ],
        'confirm_password' => [
            'header' => 'Konfirmasi Kata Sandi',
            'subheader' => 'Ini adalah area aman aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.',
        ],
        'oauth' => [
            'authorize' => [
                'header' => 'Permintaan Otorisasi',
                'subheader' => 'Tinjau izin sebelum melanjutkan',
                'is_requesting' => 'meminta akses ke akun Anda',
                'requested_permissions' => 'Izin yang Diminta',
            ],
            'device' => [
                'header' => 'Otorisasi Perangkat',
                'subheader' => 'Tinjau izin sebelum menghubungkan perangkat Anda',
                'connect_header' => 'Hubungkan Perangkat Anda',
                'connect_subheader' => 'Masukkan kode yang ditampilkan di perangkat Anda',
                'user_code_hint' => 'Kode ditampilkan di layar perangkat Anda',
                'success_header' => 'Berhasil!',
                'success_subheader' => 'Perangkat telah berhasil diotorisasi.',
            ],
        ],
    ],

    'seo' => [
        'login' => [
            'title' => 'Masuk',
            'description' => 'Masuk ke akun Anda untuk mengakses dasbor.',
            'keywords' => 'masuk, login, auth',
        ],
        'register' => [
            'title' => 'Daftar',
            'description' => 'Buat akun baru untuk memulai.',
            'keywords' => 'daftar, register, auth',
        ],
        'forgot_password' => [
            'title' => 'Lupa Kata Sandi',
            'description' => 'Pulihkan kata sandi akun Anda.',
            'keywords' => 'lupa kata sandi, pulihkan, auth',
        ],
        'reset_password' => [
            'title' => 'Atur Ulang Kata Sandi',
            'description' => 'Tetapkan kata sandi baru untuk akun Anda.',
            'keywords' => 'atur ulang kata sandi, auth',
        ],
        'verify_email' => [
            'title' => 'Verifikasi Email',
            'description' => 'Verifikasi alamat email Anda untuk mengamankan akun Anda.',
            'keywords' => 'verifikasi email, auth',
        ],
        'confirm_password' => [
            'title' => 'Konfirmasi Kata Sandi',
            'description' => 'Harap konfirmasi kata sandi Anda sebelum melanjutkan.',
            'keywords' => 'konfirmasi kata sandi, auth',
        ],
    ],
];
