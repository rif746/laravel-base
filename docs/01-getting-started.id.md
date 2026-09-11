# Memulai (Getting Started)

Panduan ini mencakup semua yang kamu butuhkan untuk menjalankan **Antigravity** Laravel Modular Monolith di mesin lokal.

---

## 1. Stack Teknis

| Layer | Teknologi |
|---|---|
| **Backend** | PHP 8.4+ & Laravel 13.0 |
| **Frontend** | Vite, AlpineJS, Livewire, Tailwind CSS |
| **Database** | SQLite (bawaan), MySQL, atau PostgreSQL |
| **Testing** | Pest PHP |
| **Package Managers** | Composer (PHP), NPM (JS) |

---

## 2. Persyaratan

- **PHP:** `^8.4`
- **Node.js:** Rekomendasi LTS terbaru
- **Composer:** `^2.0`
- **Ekstensi:** `ext-zip`, `ext-pdo_sqlite` (kalo pake SQLite)

---

## 3. Setup & Instalasi

Proyek ini udah ada skrip setup otomatis di `composer.json`.

```bash
# 1. Clone repo
git clone <repo-url>
cd laravel-base

# 2. Jalankan setup otomatis
# Ini bakal instal library PHP/JS, bikin file .env, generate key, dan jalanin migrasi
composer setup
```

---

## 4. Jalanin Aplikasi

Pake perintah ini buat jalanin server, queue listener, log, dan Vite barengan:

```bash
composer dev
```

Aplikasi bisa diakses di `http://localhost:8000`.

---

## 5. Skrip & Perintah

### Composer Scripts

| Perintah | Deskripsi |
|---|---|
| `composer setup` | Setup awal proyek otomatis. |
| `composer dev` | Jalanin server + queue + log + Vite barengan. |
| `composer test` | Jalanin semua tes (Pest). |

### Perintah Artisan

| Perintah | Deskripsi |
|---|---|
| `php artisan domain:make` | Generator khusus buat arsitektur DDD. Lihat [Alat Pengembangan](./05-development-tools.id.md). |
| `php artisan domain:new` | Scaffold domain baru. Lihat [Alat Pengembangan](./05-development-tools.id.md). |
| `php artisan domain:datatable` | Generate domain-bound DataTable dan view-nya. Lihat [Alat Pengembangan](./05-development-tools.id.md). |
| `php artisan domain:make-page` | Generate Blade view atau Livewire modal di dalam domain. Lihat [Alat Pengembangan](./05-development-tools.id.md). |
| `php artisan system:prune-files` | Membersihkan record database tanpa relasi (orphaned) dan file disk yang terdampar. Lihat [Layanan Sistem](./04-system-services.id.md). |

### NPM Scripts

| Perintah | Deskripsi |
|---|---|
| `npm run dev` | Jalanin dev server Vite. |
| `npm run build` | Build aset buat produksi. |

---

## 6. Variabel Environment

Variabel penting di file `.env`:

| Variabel | Deskripsi |
|---|---|
| `APP_NAME` | Nama aplikasi. |
| `APP_ENV` | Lingkungan aplikasi (`local`, `production`, dll). |
| `APP_KEY` | Kunci enkripsi aplikasi. |
| `DB_CONNECTION` | Driver database (`sqlite`, `mysql`, `pgsql`). |
| `QUEUE_CONNECTION` | Driver antrean (default: `database`). |
| `MAIL_MAILER` | Driver email (default: `log`). |

Cek `.env.example` buat liat daftar lengkapnya.
