# Arsitektur Antigravity: Laravel Modular Monolith

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-4.x-FF6B6B?logo=pest&logoColor=white)](https://pestphp.com)

Selamat datang di repositori ini. Aplikasi ini dibangun menggunakan arsitektur **Pragmatic Domain-Driven Design (DDD)** yang ketat. Kami menyebutnya sebagai arsitektur "Antigravity" karena ia mencegah basis kode menjadi terlalu kompleks saat aplikasi berkembang pesat (scaling).

---

## 1. Stack Teknis

- **Backend:** PHP 8.4+ & Laravel 13.0
- **Frontend:** Vite, AlpineJS, Livewire, Tailwind CSS
- **Database:** SQLite (default), MySQL, atau PostgreSQL
- **Testing:** Pest PHP
- **Package Managers:** Composer (PHP), NPM (JS)

---

## 2. Persyaratan

- **PHP:** ^8.4
- **Node.js:** Disarankan LTS terbaru
- **Composer:** ^2.0
- **Ekstensi:** `ext-zip`, `ext-pdo_sqlite` (jika menggunakan SQLite)

---

## 3. Setup & Instalasi

Proyek ini menyediakan skrip setup terpadu di `composer.json`.

```bash
# 1. Clone repositori
git clone <repo-url>
cd laravel-base

# 2. Jalankan setup otomatis
# Ini akan menginstal dependensi PHP/JS, membuat file .env, generate key, dan menjalankan migrasi
composer setup
```

---

## 4. Menjalankan Aplikasi

Gunakan perintah pengembangan untuk menjalankan server, listener antrean, log, dan Vite secara bersamaan:

```bash
composer dev
```

Aplikasi akan tersedia di `http://localhost:8000`.

---

## 5. Skrip & Perintah

Tersedia via Composer:
- `composer setup`: Bootstrap awal proyek.
- `composer dev`: Memulai lingkungan pengembangan (Server + Queue + Logs + Vite).
- `composer test`: Menjalankan suite pengujian (test suite).
- `php artisan domain:make`: Generator kustom untuk arsitektur DDD (lihat Bagian 12).
- `php artisan domain:new`: Membuat scaffold domain baru lengkap dengan ServiceProvider dan RelationshipServiceProvider (lihat Bagian 12).

Tersedia via NPM:
- `npm run dev`: Memulai dev server Vite.
- `npm run build`: Build aset untuk produksi.

---

## 6. Struktur Proyek

```text
.
├── app/
│   ├── Attributes/       <-- Atribut PHP 8 (SEO, Layout)
│   ├── Domains/          <-- Logika Bisnis Inti (The Vault)
│   ├── Http/             <-- Gateway Web/API (Controllers, Requests, DataTables)
│   ├── Livewire/         <-- Komponen & Form Livewire
│   ├── Providers/        <-- Service Providers
│   └── UI/               <-- Logika spesifik UI (Actions, Enums)
├── bootstrap/            <-- Logika bootstrap aplikasi
├── config/               <-- File konfigurasi Laravel
├── database/             <-- Migrasi, factory, dan seeder
├── public/               <-- Entry point server web (index.php) dan aset statis
├── resources/
│   ├── lang/             <-- File lokalisasi
│   ├── views/            <-- Template Blade & komponen Livewire
│   └── css/js/           <-- Aset sumber frontend
├── routes/               <-- Rute Web, API, dan Konsol
├── tests/                <-- Pest test suite
├── storage/              <-- Log, unggahan file, dan cache
└── vite.config.js        <-- Konfigurasi Vite
```

---

## 7. Filosofi Inti

Arsitektur ini memberlakukan batasan fisik yang keras antara **Delivery** (bagaimana pengguna berinteraksi dengan aplikasi) dan **Business Logic** (apa yang sebenarnya dilakukan aplikasi).

* **The Gateway (Lapisan HTTP):** Controllers, komponen Livewire, dan Volt murni bertindak sebagai pengatur lalu lintas. Mereka menangani sesi web, cookie, pengalihan (redirect), dan validasi form.
* **The Domain (The Vault):** Actions, DTOs, dan Models di dalam `app/Domains/` menangani aturan bisnis yang sebenarnya, mutasi database, dan pemanggilan API eksternal.

> **Aturan Emas:** Domain harus benar-benar terisolasi dari lapisan web. Anda tidak diperbolehkan menggunakan helper `request()`, `session()`, atau melempar eksepsi HTTP di dalam direktori `app/Domains/`.

---

## 8. Struktur Direktori

Aplikasi ini dibagi berdasarkan **Konsep Bisnis**, bukan fitur teknis.

```text
app/
├── Attributes/               <-- Atribut PHP 8 (misal, #[Seo], #[LayoutData])
├── Console/
│   ├── Commands/             <-- Perintah Artisan kustom (DomainMakeCommand, CleanOrphanedFiles)
│   └── stubs/                <-- Stub pembuatan kode kustom
├── Http/                     <-- The Gateway (Lapisan HTTP)
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/           <-- Kontroler API berversi
│   │   └── Web/
│   │       ├── Auth/         <-- Kontroler autentikasi
│   │       ├── Identity/     <-- Kontroler manajemen pengguna & peran
│   │       └── Account/      <-- Kontroler manajemen profil
│   ├── DataTables/           <-- Konfigurasi DataTable Livewire
│   ├── Ingestion/            <-- Kelas Impor/Ingesti Excel
│   ├── Middleware/           <-- HandlePreferredLanguage, HandleSeoSetting, dll.
│   ├── Requests/
│   │   ├── Api/              <-- Form request untuk API
│   │   └── Web/              <-- Form request untuk Web
│   └── Resources/            <-- Resource API (LookupResource, SuccessResource, dll.)
├── Livewire/
│   ├── Concerns/             <-- Trait Livewire yang digunakan bersama (WithModal, WithToast)
│   └── Forms/                <-- Objek Form Livewire
├── Providers/                <-- AppServiceProvider, EventServiceProvider, UiServiceProvider
├── UI/
│   ├── Actions/              <-- Actions di lapisan UI (SetSeoMetadata, ApplyLayoutMetadata)
│   ├── Enums/                <-- Enum spesifik UI (FileType, InputType)
│   └── Support/              <-- Kelas pembantu UI (LayoutState, StyledExport)
└── Domains/
    ├── Identity/             <-- Konsep Bisnis: Autentikasi & Pengguna
    │   ├── Actions/          <-- Mutasi yang dikelompokkan berdasarkan kapabilitas
    │   ├── DTOs/             <-- Data Transfer Objects yang dikelompokkan berdasarkan kapabilitas
    │   ├── Enums/
    │   ├── Events/           <-- Fakta masa lalu
    │   ├── Exports/
    │   ├── Integration/      <-- Mapper sistem eksternal
    │   ├── Listeners/        <-- Handler kata kerja aktif
    │   ├── Models/           <-- User, Role, Permission
    │   ├── Notifications/
    │   ├── Policies/
    │   ├── Providers/
    │   ├── Queries/          <-- Bacaan Kompleks
    │   └── Scopes/           <-- Global Scopes Eloquent
    ├── Account/              <-- Konsep Bisnis: Profil & Tagihan
    │   ├── Actions/
    │   ├── DTOs/
    │   ├── Enums/
    │   ├── Listeners/
    │   ├── Models/
    │   └── Providers/
    └── System/               <-- Konsep Bisnis: Infrastruktur Lintas Domain
        ├── Actions/
        ├── Casts/            <-- Casts Eloquent kustom
        ├── DTOs/
        ├── Enums/
        ├── Events/
        ├── Helpers/          <-- Helper spesifik domain (asset.php)
        ├── Jobs/             <-- Background job spesifik domain
        ├── Listeners/
        ├── Mail/             <-- Mailable spesifik domain
        ├── Models/           <-- File, SystemSettings, Backup
        ├── Policies/
        ├── Providers/        <-- SystemServiceProvider
        ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
        ├── Support/
        ├── Traits/           <-- Trait spesifik domain (HasFile)
        └── ...
```

---

## 9. Aturan Pelaksanaan (Rules of Engagement)

### DTOs (Data Transfer Objects)

Semua data yang tidak dapat dipercaya dari Gateway harus dikemas ke dalam DTO *readonly* dengan pengetikan yang ketat (strictly typed) sebelum memasuki Domain.

* DTO hanya berisi data yang ditujukan untuk mengubah state.
* Jangan masukkan model Eloquent ke dalam DTO. Lewatkan Model sebagai parameter terpisah ke Action.

### Actions (Sang Eksekutor)

Actions adalah satu-satunya tempat di mana mutasi database (`create`, `update`, `delete`, `syncRoles`) diizinkan.

* Actions harus memiliki tanggung jawab tunggal (single responsibility).
* Gunakan `DB::transaction()` di dalam Actions saat beberapa penulisan database (misalnya, membuat pengguna dan menetapkan peran Spatie) harus berhasil atau gagal secara bersamaan.
* Gunakan **Action Composition** (menyuntikkan satu Action ke Action lainnya melalui konstruktor) untuk menggunakan kembali logika tanpa menduplikasi kode.

### Events & Listeners

Gunakan Arsitektur Event-Driven untuk semua efek samping (side effects) seperti email, logging, dan pemrosesan latar belakang (background processing).

* Gateway mendispatch Events untuk fakta sesi yang tidak memutasi (misalnya, `UserLoggedIn`).
* Actions mendispatch Events segera setelah mutasi status berhasil (misalnya, `UserWasProvisioned`).
* Listeners menangani reaksi di luar siklus hidup HTTP utama dan merupakan **satu-satunya** tempat di mana pemanggilan `Notification::send()`, `Mail::send()`, atau logging dilakukan sebagai respons terhadap Event Domain.
* Events dan Listeners harus **dikelompokkan di bawah folder kapabilitas yang sama** dengan Action yang mendispatch mereka (misalnya, `Events/Onboarding/`, `Listeners/Onboarding/`).

---

## 10. Konvensi Penamaan

Arsitektur ini menggunakan bahasa penamaan yang ketat dan disengaja. Setiap nama harus mengomunikasikan **Niat Bisnis (Business Intent)**, bukan operasi database.

### 10.1 Folder Domain (`app/Domains/{Name}/`)

Nama domain adalah **Konsep Bisnis**, bukan lapisan teknis. Harus berupa kata benda tunggal yang mendeskripsikan *bounded context*.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `Identity` | `Users` | Identity mencakup autentikasi, peran, dan siklus hidup pengguna — bukan sekadar nama tabel. |
| `Account` | `Profile` | Account memiliki seluruh permukaan akun pengguna, bukan hanya satu model. |
| `System` | `Utils` / `Helpers` | System adalah konteks bisnis nyata untuk infrastruktur lintas-potong (cross-cutting). |

### 10.2 Folder Kapabilitas (Subdirektori Action / DTO / Event / Listener)

Subdirektori di dalam `Actions/`, `DTOs/`, `Events/`, and `Listeners/` harus dinamai berdasarkan **Kapabilitas Bisnis**, bukan kata benda database.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `Onboarding/` | `Users/` | Mendeskripsikan tahap siklus hidup, bukan tabel DB. |
| `AccessControl/` | `Roles/` | Mendeskripsikan kapabilitas, bukan nama resource. |
| `Governance/` | `Admin/` | Mendeskripsikan niat kepatuhan/pengawasan. |
| `Passwords/` | `Auth/` | Ruang lingkup yang sempit dan presisi. |
| `Registration/` | `Signup/` | Menggunakan bahasa formal sistem. |

**Aturan:** Jika nama folder juga merupakan nama Model Eloquent yang valid, maka penamaan tersebut salah.

### 10.3 Nama Kelas Action

Actions harus dinamai berdasarkan **Niat Bisnis yang spesifik** yang mereka penuhi. Gunakan pola kata kerja aktif + kata benda bisnis.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Mendeskripsikan *siapa* yang memicu dan *mengapa*. |
| `SuspendUser` | `DeleteUser` | Mengungkapkan konsekuensi bisnis (pencabutan lunak, bukan penghancuran). |
| `UpdateUserRole` | `SaveRole` | Eksplisit mengenai subjek dan properti yang diubah. |
| `RegisterUser` | `StoreUser` | Bahasa domain, bukan bahasa HTTP verb. |
| `SendPasswordResetLink` | `ResetPassword` | Mencerminkan efek samping sebenarnya yang dipicu. |

Nama-nama CRUD (`CreateCategory`, `UpdateSetting`) hanya dapat diterima untuk tabel pencarian sepele (lookup tables) yang **tidak memiliki efek samping**.

### 10.4 Nama Kelas DTO

DTO dinamai berdasarkan Action yang mereka layani, dengan akhiran `DTO`.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 10.5 Nama Kelas Event

Events adalah **fakta masa lalu (past-tense)** mengenai sesuatu yang telah terjadi dalam domain. Nama kelas harus secara gramatikal menyatakan kebenaran yang sudah selesai.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Bentuk *past-tense* eksplisit menghilangkan ambiguitas. |
| `UserWasSuspended` | `UserSuspended` | Membaca sebagai state, bukan sebagai fakta yang sudah selesai. |
| `UserLoggedIn` | `LoginEvent` | Pola kata benda + kata kerja; menghindari akhiran `Event`. |
| `UserEmailVerified` | `EmailVerification` | Mendeskripsikan tindakan yang telah selesai. |

**Aturan:** Jangan pernah mengakhiri Events dengan `Event` (misal, `UserRegisteredEvent` itu salah). Namespace `Events\` sudah mengomunikasikan jenisnya.

### 10.6 Nama Kelas Listener

Listeners mendeskripsikan **reaksi aktif** terhadap sebuah event menggunakan frasa kata kerja imperatif.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Mendeskripsikan apa yang *dilakukan* listener, bukan apa yang diresponsnya. |
| `DispatchWelcomeNotification` | `WelcomeListener` | Kata kerja imperatif membuat niat menjadi sangat jelas. |

**Aturan:** Jangan pernah mengakhiri Listeners dengan `Listener` di nama kelasnya. Namespace `Listeners\` sudah mengomunikasikan jenisnya.

---

## 11. Prompt Agen AI (Instruksi Sistem)

**Untuk Pengembang:** Salin dan tempel blok di bawah ini ke obrolan agen AI Anda atau instruksi sistem sebelum memintanya untuk menulis atau merefaktor kode di repositori ini.

```text
You are an autonomous Senior Laravel Architect specializing in Pragmatic Domain-Driven Design (DDD) and Event-Driven Architecture. You must strictly obey the "Antigravity" rules of this repository.

### 1. The HTTP Gateway (Delivery Layer)
- Lives in `app/Http/Controllers/`, `app/Http/DataTables/`, or `app/Livewire/`.
- Responsibilities: HTTP validation, rate limiting, session management (`Auth::login`, `session()->regenerate()`), and redirects.
- HARD RESTRICTION: The Gateway MUST NEVER call `Model::create()`, `Model::update()`, or `Hash::make()`. It must map validated data into a DTO and pass it to a Domain Action.

### 2. The Domain (Business Logic)
- Lives inside `app/Domains/{Concept}/`.
- Responsibilities: DTO definitions, Actions (database writes), Models, and Events.
- HARD RESTRICTION: The Domain MUST NEVER read from the `request()` helper, manipulate cookies/sessions, or throw HTTP-specific exceptions (like `ValidationException`).

### 3. Execution & Workflow
- DTOs: Must be strictly typed readonly classes.
- Actions: Must represent a specific Business Intent (e.g., `ProvisionNewUser` not `SaveUser`). Actions that perform multiple database writes must wrap them in a `DB::transaction()`.
- Action Composition: Inject Actions into other Actions via the constructor to reuse logic (e.g., injecting `AccessControl\UpdateUserRole` into `Onboarding\ProvisionNewUser`).
- Events: Use Events to decouple side effects (Notifications, Activity Logs). Events must be **past-tense** (e.g., `UserWasProvisioned` not `UserProvisioned`).

### 4. Naming Rules
- **Domains**: Singular Business Concepts, never database nouns (✅ `Identity` ❌ `Users`).
- **Capability Folders**: Name subdirectories after business capabilities, never after models (✅ `Onboarding/`, `AccessControl/`, `Governance/` ❌ `Users/`, `Roles/`).
- **Actions**: Active verb + business noun (✅ `ProvisionNewUser` ❌ `CreateUser`).
- **Events**: Past-tense completed facts, no `Event` suffix (✅ `UserWasProvisioned` ❌ `UserProvisionedEvent`).
- **Listeners**: Imperative active-verb phrases, no `Listener` suffix (✅ `SendSignInActivityNotification` ❌ `UserLoggedInListener`).

### 5. File Generation Rules
* NEVER use standard Laravel generators (e.g., `php artisan make:model`) for Domain classes.
* ALWAYS use the custom `domain:make` command to create Domain files.
* Example: `php artisan domain:make action Identity Onboarding/ProvisionNewUser`
* Supported types: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `query`, `provider`, `export`, `mapper`, `scope`, `trait`, `mailable`.
* Examples for the Integration layer:
  * `php artisan domain:make export Identity UserExport --model=User`
  * `php artisan domain:make mapper Identity User` → generates `Integration/Mappers/UserDataMapper.php`
* Excel ingestion (Import) classes live in the **Gateway layer** at `app/Http/Ingestion/Excel/` — do NOT generate them with `domain:make`.
* Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

### 6. Testing Strategy
* Always write tests using Pest PHP.
* When generating new features (Actions/DTOs/Models), create corresponding tests in `tests/Feature/` or `tests/Unit/`.
* Ensure 100% adherence to Architectural tests (check `tests/Architecture/` for existing rules).
* When writing tests, use `Event::fake()`, `Queue::fake()`, `Notification::fake()` to isolate side effects.
* For dependency-injected services, use `$this->mock()` to define expected behaviors.

Write modern PHP 8.4+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.
```

---

## 12. Alat Pengembangan & Generator

Untuk mempertahankan struktur folder yang ketat dari arsitektur Antigravity, **jangan gunakan perintah `make:` standar (seperti `make:model`) untuk file Domain.** Gunakan perintah kustom `domain:make` untuk menghasilkan kelas di namespace `app/Domains/` yang benar.

### Perintah `domain:make`

**Signature:**

```bash
php artisan domain:make {type} {domain} {name} [options]

```

**Argumen:**

* `type`: Jenis file yang akan dibuat. Didukung: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `scope`, `trait`, `query`, `provider`, `export`, `mapper`, `mailable`.
* `domain`: Folder Domain target (misal, `Identity`, `Account`, `System`).
* `name`: Nama kelas. Mendukung pengelompokan sub-direktori (misal, `Management/ProvisionNewUser`).

**Opsi:**

* `--factory`: Menghasilkan factory database terkait (Hanya Models).
* `--migration`: Menghasilkan file migrasi database (Hanya Models).
* `--model=`: Mengaitkan kelas ekspor dengan model Eloquent (Hanya Exports).

### Perintah `domain:new`

Perintah ini akan membuat struktur domain baru dengan langsung menyediakan `ServiceProvider` utama (sudah dilengkapi trait `RegistersDomainEvents`) dan `RelationshipServiceProvider` pendamping (khusus untuk mapping `Model::resolveRelationUsing()`), serta mendaftarkan provider utama tersebut ke `bootstrap/providers.php` secara otomatis.

**Signature:**

```bash
php artisan domain:new {domain}
```

### Tujuan & Penggunaan Domain Providers

Untuk menjaga agar logika bisnis tetap terpisah (decoupled) dari lapisan presentasi (UI glue) dan relasi antar-domain, setiap modul domain menggunakan hierarki Service Provider bertingkat:

1. **`{Domain}ServiceProvider`**: Titik masuk utama untuk domain tersebut. Menggunakan trait `RegistersDomainEvents` untuk melakukan mapping event lokal dari properti `$listen`. Provider ini juga bertanggung jawab mendaftarkan provider internal di bawah ini.
2. **`RelationshipServiceProvider`**: Dikhususkan untuk menangani relasi lintas-domain secara dinamis menggunakan `Model::resolveRelationUsing()`. Dengan memuat relasi dinamis di sini, Anda menghindari ketergantungan compile-time yang keras antar domain.
3. **`ViewServiceProvider`** (UI glue opsional): Digunakan untuk melakukan *binding* data ke komponen antarmuka pengguna tanpa mencemari logika domain utama. Buat file ini melalui generator jika diperlukan:
   ```bash
   php artisan domain:make view-provider {domain} ViewServiceProvider
   ```
   Di dalam method `boot()`, Anda dapat mendaftarkan view composer kustom:
   ```php
   View::composer('components.layouts.sidebar', function ($view) {
       $view->with('navigationItems', [ ... ]);
   });
   ```

### Menyesuaikan Generator (Stubs)

Semua template `domain:make` disimpan sebagai file `.stub` di `app/Console/stubs/domain-make/`. Anda dapat dengan bebas mengedit stub ini untuk menyesuaikan *boilerplate* default dengan kebutuhan spesifik proyek Anda (misalnya, mengubah metode default di DTO).

---

## 13. Manajemen File Universal (Domain System)

Penanganan file (unggahan, lampiran, pemangkasan gambar, dan penghapusan) adalah kemampuan yang dibagikan secara universal. Untuk mencegah setiap domain menulis logika penyimpanan filenya sendiri, semua file fisik dikelola oleh mesin terpusat di dalam domain **`System`**.

### Mesin Polimorfik

Kami tidak menambahkan path file secara langsung ke tabel bisnis (misalnya, tidak ada kolom `avatar_path` di tabel `users`). Alih-alih, kami menggunakan tabel `files` terpusat dan model `System\Models\File`.

* File dilampirkan secara **polimorfik** ke entitas apa pun di dalam aplikasi.
* Tabel `files` menyertakan kolom string `relation_name` bertipe ketat (misalnya, `'avatar'`) untuk mencegah terjadinya tabrakan antar jenis file yang dilampirkan ke model yang sama.

### Trait Konsumen

Ketika Domain Model (seperti `User` atau `Invoice`) perlu menerima lampiran file, model tersebut akan menarik trait `HasFile`. Trait ini menyediakan pembuat relasi terisolasi.

```php
namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Traits\HasFile;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model
{
    use HasFile; 

    // String 'avatar' secara sempurna mengisolasi file ini di dalam database
    public function avatar(): MorphOne
    {
        return $this->singleFile('avatar'); 
    }
}

```

### File Action (Metadata via DTO)

Karena objek `Illuminate\Http\UploadedFile` milik Laravel merupakan objek yang kompleks, kami melewatkannya bersama dengan `FileDTO` yang diketik secara ketat (strictly-typed) yang berisi metadata (model target, disk, dan nama relasi). Ini memastikan Gateway tetap bersih sementara Domain menerima semua konteks yang diperlukan.

* **`UploadAndAttachFile`**: Action dasar. Ini menyimpan file fisik ke disk dan membuat rekaman database polimorfik.
* **`ReplaceSingleFile`**: Digunakan untuk penggantian 1-ke-1 (seperti mengganti avatar). Ia menghapus file lama secara aman sebelum mendelegasikan unggahan baru kembali ke Action dasar.

**Contoh Gateway:**

```php
$action->execute(
    newFile: $request->file('photo'),
    dto: new FileDTO(
        modelType: $user->getMorphClass(),
        modelId: $user->id,
        relationName: 'avatar',
        disk: 'local',
        directory: 'avatars',
        options: [],
        uploaderId: auth()->id(),
    )
);

```

### Helper Asset Sistem

Untuk menghindari polusi namespace global Laravel dengan file `app/helpers.php` sampah, kami mengelola file helper yang terikat ketat pada domain di `app/Domains/System/Helpers/asset.php`. File ini di-autoload via `composer.json` dan menyediakan fungsi `asset_static()` untuk menyelesaikan (resolve) file publik dan privat di view Blade kita secara elegan.

---

## 14. Pengaturan Global & State Aplikasi

Pengaturan yang mendikte *state* (status) *runtime* aplikasi (Zona Waktu, Lokalisasi, Tag SEO) dikelola oleh domain `System` untuk memastikan performa tinggi dan kesadaran konteks.

* **Memoization & Singletons:** Query `GetSystemSettings` didaftarkan sebagai Singleton di `SystemServiceProvider`. Ia mengambil data dari database/cache *sekali* saja dan menyimpannya di memori lokal PHP selama durasi request berjalan.
* **Contextual Middlewares:** Kami menggunakan *middleware* terdedikasi (`HandlePreferredLanguage`, `HandlePreferredTimezone`) untuk memeriksa preferensi pengguna yang terautentikasi secara dinamis, lalu menggunakan (fallback ke) pengaturan global jika tidak ada preferensi yang ditemukan.
* **View Composers:** Variabel *layout* global (seperti Logo dan Nama Web) disuntikkan ke global via View Composers di dalam Service Provider, untuk mencegah penggunaan direktif `@inject` yang berulang.

---

## 15. Pencatatan Audit (Audit Logging) & Pelacakan

Semua mutasi database yang penting dilacak untuk mempertahankan buku besar (ledger) historis yang patuh (compliant).

* **Model Auditing:** Kami memanfaatkan Eloquent event untuk secara otomatis mencatat perubahan baris.
* **Complex Relation Auditing:** Melacak perubahan hubungan many-to-many (seperti `syncPermissions` milik Spatie) akan melewati (bypass) standar Eloquent event. Oleh karena itu, kami secara eksplisit mendispatch Custom Audit Event langsung di dalam Domain Action terkait (misalnya, `UpdateRolePermissions`). Ini menjamin state "Sebelum" dan "Sesudah" terekam dengan rapi di dalam satu baris transaksional tunggal.

---

## 16. UI Dinamis & Interoperabilitas Livewire

Saat membangun antarmuka berbasis data (seperti formulir pengaturan dinamis), kami memanfaatkan pola **Renderable Enum** dikombinasikan dengan komponen dinamis bawaan Laravel.

* Enum bertindak sebagai **Penyedia Metadata** (mengembalikan nama string dari komponen Blade target).
* Kami menggunakan `<x-dynamic-component>` di dalam file Blade untuk menukar elemen UI secara dinamis.
* **KRITIS:** Enum tidak boleh sama sekali mengembalikan string HTML mentah yang dikompilasi melalui *facade* `Blade`. Melakukan hal tersebut akan merusak mesin DOM-diffing milik Livewire dan memutuskan binding `wire:model`.

---

## 17. Ekspor & Impor Excel

Komponen Laravel `resources/views/components/datatables/⚡excel-manager.blade.php` (didaftarkan sebagai `<livewire:datatables.excel-manager>`) menyediakan mekanisme berbasis *queue* (antrean) yang dapat digunakan kembali untuk mengimpor dan mengekspor file Excel pada halaman DataTable apa pun. Ini adalah **komponen Laravel file tunggal terpadu** — logika kelas PHP dan templat Blade berdampingan pada file yang sama, mengikuti konvensi penamaan `⚡` yang digunakan di seluruh komponen Laravel dalam proyek ini.

### Gambaran Arsitektur

Komponen ini bergantung pada tiga lapisan yang berkolaborasi:

1. **Komponen Laravel** (`⚡excel-manager.blade.php`) — Menangani *state* UI, unggahan file melalui `WithFilePond`, validasi, dan Livewire *event listeners*. Semua *props* diamankan menggunakan `#[Locked]` untuk mencegah perusakan (tampering) di sisi klien.
2. **Dekorator `StyledExport`** (`App\UI\Support\Excel\StyledExport`) — *Wrapper* lapisan UI yang memperkaya *Export* domain apa pun dengan penataan gaya (styling) visual yang terstandar (baris *header* yang dibekukan, orientasi *landscape*, tepi yang tipis, penyejajaran tengah, dan kolom *auto-sized*) tanpa mencemari kelas *Export* domain dengan logika presentasi.
3. **Pipeline Pemberitahuan Event-Driven** — Setelah file ekspor ditulis ke *disk*, tugas antrean `NotifyExportReady` mendispatch event `ExportCompleted`, yang ditangani oleh *listener* `SendExportReportEmail` untuk mengirimkan file melalui email.

### Cara Kerjanya

* **Impor:** Pengguna mengunggah file `.xlsx` melalui modal FilePond. File disimpan ke `local/excel/import/{resourceName}` dan sebuah instansiasi impor baru dikonstruksi menggunakan UUID (`$importId`) dan ID pengguna yang terautentikasi (`$initiatorId`) sebelum didispatch ke antrean menggunakan `Excel::queueImport()`. Kelas Ingesti (Ingestion) berada di `app/Http/Ingestion/` (Lapisan Gateway) dan mengimplementasikan `WithChunkReading` (ukuran *chunk*: 200 baris) agar tetap berada di dalam batas memori *shared-hosting*. Sebuah toast peringatan (`ui.excel.import.success`) dimunculkan seketika setelah masuk antrean.

* **Ekspor:** Sebuah Livewire browser event (`export-excel`) — didispatch oleh tombol Export DataTable — memicu metode `export()` melalui atribut `#[On('export-excel')]`. Kelas Export domain dibungkus (wrapped) di dalam `StyledExport` lalu didispatch menggunakan `Excel::queue()`. Job chain menambahkan `NotifyExportReady`, yang kemudian mendispatch event `ExportCompleted`, yang pada akhirnya ditangani oleh listener `SendExportReportEmail` untuk mengirim file tersebut sebagai lampiran email ke pengguna. Sebuah toast sukses (`ui.excel.export.success`) dimunculkan dengan segera setelah masuk antrean.

### Properti Komponen (Props)

| Prop | Tipe | Deskripsi |
| --- | --- | --- |
| `importClass` | `string` | Nama kelas kualifikasi penuh (fully-qualified) dari Gateway Ingestion class (contoh, `App\Http\Ingestion\Excel\Identity\UserImport`). |
| `exportClass` | `string` | Nama kelas kualifikasi penuh dari domain Export (contoh, `App\Domains\Identity\Exports\UserExport`). |
| `resourceName` | `string` | Sebuah kata (slug) yang digunakan untuk menamai file impor yang disimpan serta nama file ekspor yang bertanda waktu (contoh, `user`). |

### Penggunaan

Sematkan komponen di dalam halaman DataTable (Blade view) mana pun. Semua props harus disediakan berupa string nama kelas PHP yang terkualifikasi secara penuh:

```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
    resource-name="user"
/>
```

Tombol Export milik DataTable harus mendispatch event Livewire `export-excel`, sedangkan tombol Import harus membuka Bootstrap modal `#excel-import-modal`:

```php
// Di dalam file builder html() DataTable Anda:
Button::make('excel')
    ->action("Livewire.dispatch('export-excel')"),

Button::make('excel')
    ->action("$('#excel-import-modal').modal('show')"),
```

### Menghasilkan Kelas Export & Mapper

Gunakan perintah `domain:make` untuk membuat kelas lapisan Export dan Integration:

```bash
# Menghasilkan kelas Export domain
php artisan domain:make export Identity UserExport --model=User

# Menghasilkan Integration Mapper (akan meng-append akhiran DataMapper secara otomatis)
php artisan domain:make mapper Identity User
# → app/Domains/Identity/Integration/Mappers/UserDataMapper.php
```

Kelas Export Domain wajib mengimplementasikan `FromQuery & WithHeadings & WithMapping & WithColumnFormatting`. Dekorator `StyledExport` akan menerapkan semua format visual secara otomatis saat diproses antrean — **jangan** mengimplementasikan `WithStyles` secara langsung di kelas Export domain.

> **Lapisan Gateway:** Kelas Excel Ingestion (Import) berada di dalam `app/Http/Ingestion/Excel/` dan **tidak** dihasilkan oleh generator `domain:make`. Buatlah secara manual atau gunakan `make:class` sebagai kelas PHP standar yang mengimplementasikan `ToCollection`, `WithHeadingRow`, dan `WithChunkReading`.

### Pipeline Pemberitahuan

Alur pemberitahuan ekspor mengikuti rantai *event-driven* berbasis antrean secara ketat:

```
Excel::queue(StyledExport, $path)
  └─> NotifyExportReady (Job)        [app/Domains/System/Jobs/]
        └─> ExportCompleted::dispatch (Event)  [app/Domains/System/Events/]
              └─> SendExportReportEmail (Listener)  [app/Domains/System/Listeners/]
                    └─> ExcelExportEmail (Mailable)  [app/Domains/System/Mail/]
```

Pemberitahuan impor akan dikirim langsung oleh kelas Import domain itu sendiri sesaat setelah penyelesaian, dengan menggunakan `ExcelImportEmail` dari namespace `App\Domains\System\Mail\` yang sama.

### Mailables

Kedua kelas Mailable berada pada **domain System**, bukan di namespace utama `App\Mail\`:

* **`App\Domains\System\Mail\ExcelImportEmail`** — Dikirim saat unggahan *queued import* telah selesai. Menggunakan terjemahan dari `domains/system.notifications.excel.import_email.*`.
* **`App\Domains\Identity\Mail\Registration\WelcomeEmail`** — Contoh mailable spesifik domain.
* **`App\Domains\System\Mail\ExcelExportEmail`** — Dikirim saat *queued export* siap, dilampirkan menggunakan file dari disk `local`. Menggunakan terjemahan dari `domains/system.notifications.excel.export_email.*`.

### Kunci Terjemahan (Translation Keys)

| File | Kunci (Key) | Tujuan |
| --- | --- | --- |
| `lang/{locale}/ui.php` | `ui.excel.import.file_label` | Label unggahan FilePond di dalam modal impor. |
| `lang/{locale}/ui.php` | `ui.excel.import.success` | Pesan Toast muncul setelah impor masuk antrean. |
| `lang/{locale}/ui.php` | `ui.excel.export.success` | Pesan Toast muncul setelah ekspor masuk antrean. |
| `lang/{locale}/domains/system.php` | `notifications.excel.import_email.*` | Isi (body) email untuk pemberitahuan selesai impor. |
| `lang/{locale}/domains/system.php` | `notifications.excel.export_email.*` | Isi (body) email untuk pemberitahuan ekspor sudah siap. |

---

## 18. Testing

Proyek ini menggunakan **Pest PHP** untuk pengujian.

```bash
composer test
```

Tes terletak di direktori `tests/` dan mengikuti konvensi standar Laravel (Feature dan Unit).

---

## 19. Variabel Lingkungan (Environment Variables)

Variabel utama yang digunakan di `.env`:

- `APP_NAME`: Nama aplikasi.
- `APP_ENV`: Lingkungan aplikasi (`local`, `production`, dll.).
- `APP_KEY`: Kunci enkripsi aplikasi.
- `DB_CONNECTION`: Driver database (`sqlite`, `mysql`, `pgsql`).
- `QUEUE_CONNECTION`: Driver antrean (default: `database`).
- `MAIL_MAILER`: Driver email (default: `log`).

Lihat `.env.example` untuk daftar lengkap opsi yang tersedia.

---

## 20. Lisensi

Proyek ini dilisensikan di bawah **Lisensi MIT**.
