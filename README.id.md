# Arsitektur Antigravity: Laravel Modular Monolith

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-4.x-FF6B6B?logo=pest&logoColor=white)](https://pestphp.com)

Aplikasi ini dibangun pake arsitektur **Pragmatic Domain-Driven Design (DDD)** yang ketat. Kita nyebutnya arsitektur **"Antigravity"** karena bisa jagain basis kode biar nggak berantakan atau gampang hancur pas aplikasi makin gede (*scaling*). Ada batesan fisik yang tegas antara **Delivery Layer** (HTTP, Livewire) dan **Domain** (Business Logic, Actions, DTOs), mastiin inti logika bisnis tetep netral dari framework, gampang ditest, dan rapi dipelihara di skala se-gede apa pun.

---

## Panduan Cepat (Quick Start)

```bash
# 1. Clone repo
git clone <repo-url>
cd laravel-base

# 2. Jalankan setup otomatis
# Installs PHP/JS deps, creates .env, generates key, and runs migrations
composer setup

# 3. Jalankan lingkungan dev
# Runs Server + Queue + Logs + Vite concurrently
composer dev
```

Aplikasi bisa diakses di `http://localhost:8000`.

---

## Daftar Isi

| # | Dokumen | Deskripsi |
|---|---|---|
| 1 | [Memulai (Getting Started)](./docs/01-getting-started.id.md) | Stack Teknis, Persyaratan, Setup & Instalasi, Skrip & Perintah, Variabel Environment |
| 2 | [Arsitektur & Filosofi](./docs/02-architecture-philosophy.id.md) | Filosofi Inti, Struktur Direktori, Konvensi Penamaan, Panduan Kata Kerja Mutasi |
| 3 | [Aturan Main (Domain Rules)](./docs/03-domain-rules.id.md) | DTO, Action, Event & Listener, Audit Log, Setting Global, Trait Model Multi-Bahasa |
| 4 | [Layanan Sistem (System Services)](./docs/04-system-services.id.md) | Manajemen File Universal / Trait `HasFile`, UI Dinamis / Renderable Enums, Mesin Ekspor/Impor Excel |
| 5 | [Alat Pengembangan](./docs/05-development-tools.id.md) | Generator `domain:make`, Kustomisasi Stub, Strategi Pengujian Pest PHP |
| 6 | [Instruksi Agen AI](./docs/06-ai-agent-instructions.id.md) | System prompt AI *standalone* yang siap di-*copy-paste* untuk ngebantu di repository ini |
| 7 | [Cookbook Fitur Inti](./docs/07-feature-cookbook.id.md) | Panduan praktis langkah demi langkah cara memakai fitur-fitur inti |

---

## Lisensi

Proyek ini menggunakan **Lisensi MIT**.
