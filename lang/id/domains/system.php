<?php

return [
    'fields' => [
        'backup' => [
            'file' => 'Berkas Cadangan',
        ],
        'settings' => [
            'web' => [
                'name' => 'Nama Web',
                'description' => 'Desckripsi Web',
                'address' => 'Alamat Web',
                'phone' => 'Telepon Web',
                'email' => 'Email Web',
                'logo' => 'Logo Web',
                'favicon' => 'Favicon Web',
            ],
            'default_language' => 'Bahasa Default',
            'timezone' => 'Zona Waktu',
            'google' => [
                'tag_manager_id' => 'ID Google Tag Manager',
                'webmaster_id' => 'ID Google Webmaster',
            ],
        ],
        'audit' => [
            'ip_address' => 'Alamat IP',
            'browser' => 'Peramban',
            'field' => 'Kolom',
            'old' => 'Lama',
            'new' => 'Baru',
        ],
    ],

    'pages' => [
        'backup' => [
            'title' => 'Katalog Cadangan Sistem',
            'backup_button' => 'Cadangkan',
            'upload_button' => 'Unggah Berkas Cadangan',
            'upload_modal_title' => 'Unggah Berkas Cadangan',
            'empty_state' => 'Tidak Ada Berkas Cadangan yang Disimpan',
            'delete_confirm' => 'Hapus data cadangan ini?',
        ],
        'settings' => [
            'update_title' => 'Perbarui Pengaturan',
            'sections' => [
                'web' => 'Web',
                'general' => 'Umum',
                'webmaster' => 'Webmaster',
            ],
        ],
    ],

    'messages' => [
        'backup' => [
            'delete_success' => 'Data cadangan berhasil dihapus',
            'backup_success' => 'Sistem berhasil dicadangkan',
            'backup_error' => 'Gagal mencadangkan sistem',
            'restored_success' => 'Sistem berhasil dipulihkan',
            'restored_error' => 'Gagal memulihkan sistem',
            'download_error' => 'Gagal mengunduh :path',
        ],
    ],

    'seo' => [
        'default_title' => 'Laravel',
        'default_description' => 'Dasar Laravel menggunakan Livewire v4, Bootstrap, dan DataTables.net oleh Syarif Ubaidillah.',
        'dashboard' => [
            'title' => 'Dasbor',
            'description' => 'Dasbor analitik untuk aplikasi ini.',
            'keywords' => 'dasbor, analitik, ulasan',
        ],
        'settings' => [
            'title' => 'Pengaturan Sistem',
            'description' => 'Pengaturan dan konfigurasi sistem.',
            'keywords' => 'sistem, pengaturan, konfigurasi',
        ],
        'backup' => [
            'title' => 'Cadangan Sistem',
            'description' => 'Cadangkan dan Pulihkan basis data dan aset.',
            'keywords' => 'cadangkan, pulihkan, basis data, aset',
        ],
    ],

    'event' => [
        'created' => 'Dibuat',
        'updated' => 'Diperbarui',
        'deleted' => 'Dihapus',
        'permissions_synced' => 'Perizinan disinkronisasi',
    ],
];
