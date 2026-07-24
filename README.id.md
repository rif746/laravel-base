# Arsitektur Antigravity: Laravel Modular Monolith

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-4.x-FF6B6B?logo=pest&logoColor=white)](https://pestphp.com)

Selamat datang di repo ini. Aplikasi ini dibangun pakai arsitektur **Pragmatic Domain-Driven Design (DDD)** yang ketat. Kita nyebutnya arsitektur "Antigravity" karena bisa jagain basis kode biar nggak berantakan atau gampang hancur pas aplikasi makin gede (*scaling*).

---

## 1. Stack Teknis

- **Backend:** PHP 8.4+ & Laravel 13.0
- **Frontend:** Vite, AlpineJS, Livewire, Tailwind CSS
- **Database:** SQLite (bawaan), MySQL, atau PostgreSQL
- **Testing:** Pest PHP
- **Package Managers:** Composer (PHP), NPM (JS)

---

## 2. Persyaratan

- **PHP:** ^8.4
- **Node.js:** Rekomendasi LTS terbaru
- **Composer:** ^2.0
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

Tersedia via Composer:
- `composer setup`: Setup awal proyek otomatis.
- `composer dev`: Jalanin server + queue + log + Vite barengan.
- `composer test`: Jalanin semua tes (Pest).
- `php artisan domain:make`: Generator khusus buat arsitektur DDD (cek Bagian 12).
- `php artisan domain:new`: Scaffold domain baru (cek Bagian 12).
- `php artisan domain:datatable`: Generate domain-bound DataTable dan view-nya (cek Bagian 12).
- `php artisan domain:make-page`: Generate Blade view atau Livewire modal di dalam domain (cek Bagian 12).
- `php artisan system:prune-files`: Membersihkan record database tanpa relasi (orphaned) dan file disk yang terdampar (cek Bagian 13).

Tersedia via NPM:
- `npm run dev`: Jalanin dev server Vite.
- `npm run build`: Build aset buat produksi.

---

## 6. Struktur Proyek

```text
.
├── app/
│   ├── Attributes/       <-- Atribut PHP 8 (SEO, Layout)
│   ├── Domains/          <-- Logika Bisnis Inti (The Vault)
│   ├── Http/             <-- Gateway Web/API (Controller, Request, DataTable)
│   ├── Livewire/         <-- Komponen & Form Livewire
│   ├── Providers/        <-- Service Provider
│   └── UI/               <-- Logika khusus UI (Action, Enum)
├── bootstrap/            <-- Logika bootstrap aplikasi
├── config/               <-- File konfigurasi Laravel
├── database/             <-- Migrasi, factory, dan seeder
├── public/               <-- Pintu masuk web (index.php) dan aset statis
├── resources/
│   ├── lang/             <-- File bahasa (lokalisasi)
│   ├── views/            <-- Template Blade & komponen Livewire
│   └── css/js/           <-- Aset frontend
├── routes/               <-- Rute Web, API, dan Console
├── tests/                <-- Suite pengetesan Pest
├── storage/              <-- Log, upload file, dan cache
└── vite.config.js        <-- Konfigurasi Vite
```

---

## 7. Filosofi Inti

Arsitektur ini bikin batasan fisik yang tegas antara **Delivery** (cara user interaksi sama aplikasi) dan **Business Logic** (apa yang sebenarnya aplikasi lakuin).

* **The Gateway (Lapisan HTTP):** Controller, komponen Livewire, dan Volt itu murni cuma "polisi lalu lintas". Mereka ngurusin sesi web, cookie, redirect, dan validasi form.
* **The Domain (The Vault):** Action, DTO, dan Model di dalam `app/Domains/` yang nanganin aturan bisnis beneran, mutasi database, dan manggil API luar.

> **Aturan Emas:** Domain harus bener-bener bersih dari urusan web. Nggak boleh pake helper `request()`, `session()`, atau nge-throw HTTP exception di dalem direktori `app/Domains/`.

---

## 8. Struktur Direktori

Aplikasi ini dibagi berdasarkan **Konsep Bisnis**, bukan fitur teknis.

```text
app/
├── Attributes/               <-- Atribut PHP 8 (misal, #[Seo], #[LayoutData])
├── Console/
│   ├── Commands/             <-- Perintah Artisan kustom (DomainMakeCommand, CleanOrphanedFiles)
│   └── stubs/                <-- Stub buat generate kode
├── Http/                     <-- The Gateway (Lapisan HTTP)
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/           <-- API controller ber-versi
│   │   └── Web/
│   │       ├── Auth/         <-- Controller buat login dll
│   │       ├── Identity/     <-- Controller buat user & role
│   │       └── Account/      <-- Controller buat profil
│   ├── DataTables/           <-- Konfigurasi DataTable Livewire
│   ├── Ingestion/            <-- Kelas buat impor Excel
│   ├── Middleware/           <-- HandlePreferredLanguage, HandleSeoSetting, dll.
│   ├── Requests/
│   │   ├── Api/              <-- Form request buat API
│   │   └── Web/              <-- Form request buat Web
│   └── Resources/            <-- Resource API (LookupResource, SuccessResource, dll.)
├── Livewire/
│   ├── Concerns/             <-- Trait Livewire (WithModal, WithToast)
│   └── Forms/                <-- Objek Form Livewire
├── Providers/                <-- AppServiceProvider, UiServiceProvider
├── UI/
│   ├── Actions/              <-- Action di lapisan UI (SetSeoMetadata, ApplyLayoutMetadata)
│   ├── Enums/                <-- Enum khusus UI (FileType, InputType)
│   └── Support/              <-- Helper UI (LayoutState, StyledExport)
└── Domains/
    ├── Identity/             <-- Konsep Bisnis: Autentikasi & User
    │   ├── Actions/          <-- Mutasi data
    │   ├── DTOs/             <-- Data Transfer Objects
    │   ├── Enums/
    │   ├── Events/           <-- Sesuatu yang udah terjadi
    │   ├── Exports/
    │   ├── Integration/      <-- Mapper sistem luar
    │   ├── Listeners/        <-- Yang nanggapin event
    │   ├── Models/           <-- User, Role, Permission
    │   ├── Notifications/
    │   ├── Policies/
    │   ├── Providers/
    │   ├── Queries/          <-- Baca data yang ribet
    │   └── Scopes/           <-- Global Scopes Eloquent
    ├── Account/              <-- Konsep Bisnis: Profil & Tagihan
    │   ├── Actions/
    │   ├── DTOs/
    │   ├── Enums/
    │   ├── Listeners/
    │   ├── Models/
    │   └── Providers/
    └── System/               <-- Konsep Bisnis: Infrastruktur Umum
        ├── Actions/
        ├── Casts/            <-- Cast Eloquent kustom
        ├── DTOs/
        ├── Enums/
        ├── Events/
        ├── Helpers/          <-- Helper domain (asset.php)
        ├── Jobs/             <-- Background job domain
        ├── Listeners/
        ├── Mail/             <-- Mailable domain
        ├── Models/           <-- File, SystemSettings, Backup
        ├── Policies/
        ├── Providers/        <-- SystemServiceProvider
        ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
        ├── Support/
        ├── Traits/           <-- Trait domain (HasFile)
        └── ...
```

---

## 9. Aturan Main (Rules of Engagement)

### DTO (Data Transfer Objects)

Semua data dari luar (Gateway) yang nggak bisa dipercaya 100% harus dibungkus ke DTO *readonly* dengan tipe data yang ketat sebelum masuk ke Domain.

* DTO cuma boleh berisi data yang emang mau dipake buat ubah state.
* Jangan masukin model Eloquent ke dalem DTO. Kirim Model-nya sebagai parameter terpisah pas manggil Action.

### Action (Sang Eksekutor)

Action adalah satu-satunya tempat buat mutasi database (`create`, `update`, `delete`, `syncRoles`).

* Satu Action cuma boleh punya satu tanggung jawab (*single responsibility*).
* Pake `DB::transaction()` di dalem Action kalo ada beberapa proses tulis database yang harus sukses bareng atau gagal bareng.
* Pake **Action Composition** (masukin Action ke Action lain lewat constructor) buat pake ulang logika tanpa perlu *copy-paste* kode.

### Event & Listener

Pake Arsitektur Event-Driven buat semua efek samping kayak kirim email, log, atau proses di background.

* Gateway ngirim Event buat hal-hal yang nggak ngerubah data (misal, `UserLoggedIn`).
* Action ngirim Event tepat setelah data berhasil diubah (misal, `UserWasProvisioned`).
* Listener yang nanganin reaksinya di luar proses HTTP utama. Di sini satu-satunya tempat boleh manggil `Notification::send()`, `Mail::send()`, atau logging sebagai respon dari Event Domain.
* Event dan Listener harus **dikelompokin di folder kapabilitas yang sama** kayak Action-nya (misal, `Events/Onboarding/`, `Listeners/Onboarding/`).

---

## 10. Konvensi Penamaan

Arsitektur ini punya aturan nama yang ketat. Setiap nama harus nunjukin **Niat Bisnis (Business Intent)**, bukan urusan teknis database.

### 10.1 Folder Domain (`app/Domains/{Name}/`)

Nama domain itu **Konsep Bisnis**, bukan lapisan teknis. Pake kata benda tunggal yang jelasin *bounded context*.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `Identity` | `Users` | Identity itu nyangkut auth, role, dan siklus hidup user — bukan cuma nama tabel. |
| `Account` | `Profile` | Account itu ngurusin semua soal akun user, bukan cuma satu model. |
| `System` | `Utils` / `Helpers` | System itu emang konteks bisnis buat hal-hal teknis yang dipake bareng-bareng. |

### 10.2 Folder Kapabilitas (Subdirektori Action / DTO / Event / Listener)

Subfolder di dalem `Actions/`, `DTOs/`, `Events/`, dan `Listeners/` harus dinamain pake **Kapabilitas Bisnis**, bukan kata benda database.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `Onboarding/` | `Users/` | Jelasin tahapannya, bukan tabelnya. |
| `AccessControl/` | `Roles/` | Jelasin kemampuannya, bukan nama resource-nya. |
| `Governance/` | `Admin/` | Jelasin niat kepatuhannya. |
| `Passwords/` | `Auth/` | Lebih sempit dan pas. |
| `Registration/` | `Signup/` | Pake bahasa formal sistem. |

**Aturan:** Kalo nama foldernya sama kayak nama Model Eloquent, berarti salah.

### 10.3 Nama Kelas Action

Action harus dinamain sesuai **Niat Bisnis yang spesifik**. Pake pola: kata kerja aktif + kata benda bisnis.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Jelasin *siapa* yang mulai dan *kenapa*. |
| `SuspendUser` | `DeleteUser` | Ngasih tau efek bisnisnya (cuma dinonaktifin, bukan dihapus permanen). |
| `UpdateUserRole` | `SaveRole` | Eksplisit soal apa yang diubah. |
| `RegisterUser` | `StoreUser` | Pake bahasa domain, bukan bahasa database/HTTP. |
| `SendPasswordResetLink` | `ResetPassword` | Sesuai sama efek samping yang beneran kejadian. |

Nama CRUD standar (`CreateCategory`, `UpdateSetting`) cuma boleh buat tabel sepele yang **nggak punya efek samping apa-apa**.

### 10.4 Nama Kelas DTO

DTO dinamain sesuai Action yang dilayanin, ditambah akhiran `DTO`.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 10.5 Nama Kelas Event

Event itu **fakta masa lalu (past-tense)** soal hal yang udah kejadian. Namanya harus nunjukin kejadian yang udah beres.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Pake *past-tense* biar nggak bingung. |
| `UserWasSuspended` | `UserSuspended` | Kalo tanpa `was`, kayak status, bukan kejadian. |
| `UserLoggedIn` | `LoginEvent` | Pola subjek + predikat; nggak perlu pake embel-embel `Event`. |
| `UserEmailVerified` | `EmailVerification` | Jelasin aksi yang udah kelar. |

**Aturan:** Jangan pernah pake akhiran `Event` (misal, `UserRegisteredEvent` itu salah). Namespace `Events\` udah jelasin tipenya.

### 10.6 Nama Kelas Listener

Listener jelasin **reaksi aktif** terhadap suatu event pake kata kerja perintah.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Jelasin apa yang *dilakuin*, bukan apa yang ditungguin. |
| `DispatchWelcomeNotification` | `WelcomeListener` | Pake kata kerja perintah biar niatnya makin jelas. |

**Aturan:** Jangan pernah pake akhiran `Listener` di nama kelasnya. Namespace `Listeners\` udah jelasin tipenya.

### 10.7 Panduan Kata Kerja Action & Niat Bisnis

Untuk menjaga konsistensi dalam penamaan Action, gunakan awalan kata kerja standar berikut untuk menjelaskan siklus hidup dan dampak spesifik dari operasi tersebut.

| Awalan Kata Kerja | Lingkup Niat (Intent) | Contoh Konteks Nyata |
| --- | --- | --- |
| **`Initialize`** / **`Register`** | Mendeklarasikan persiapan jalur bisnis operasional atau engine. | `RegisterSelfServiceUser`, `InitializeSystemCluster` |
| **`Draft`** | Membuat baris entitas baru tetapi menguncinya agar tidak terlihat di sistem live (sebagai draf yang tertunda). | `DraftSystemNotification`, `DraftIdentityAccessPolicy` |
| **`Publish`** / **`Activate`** | Menangani transisi status untuk mengubah record yang ada menjadi status live/produksi. | `ActivateUserStatus`, `PublishAnnouncement` |
| **`Define`** | Mengonfigurasi data lookup statis atau elemen referensi struktural. | `CreateSystemRole`, `DefineIdentityPermission` |
| **`Adjust`** / **`Modify`** | Melakukan modifikasi parsial yang presisi atau penyesuaian data pada record aktif. | `UpdateUserSettings`, `AdjustBackupFrequency` |
| **`Replace`** / **`Overwrite`** | Melakukan penggantian destruktif penuh atas seluruh tata letak data suatu entitas. | `ReplaceSingleFile`, `OverwriteDomainSetting` |
| **`Synchronize`** | Memaksa penyamaan status penuh dengan registri sistem eksternal yang otoritatif. | `SyncBackupCatalog`, `SynchronizeIdentityData` |
| **`Suspend`** / **`Pause`** | Menghentikan akses untuk sementara sambil membiarkan struktur dasar tetap utuh. | `SuspendUser`, `PauseSystemJob` |
| **`Archive`** | Menjalankan penghapusan lunak (soft-deletion), memindahkan record secara permanen ke ledger riwayat. | `ArchiveAuditLog`, `ArchiveProcessedImport` |

---

**Buat Developer:** Copy-paste blok di bawah ini ke chat agen AI kamu atau ke instruksi sistem sebelum minta AI-nya buat nulis atau ngerubah kode di repo ini.

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
- NEVER use standard Laravel generators (e.g., `php artisan make:model`) for Domain classes.
- ALWAYS use the custom `domain:make` command to create Domain files.
- Example: `php artisan domain:make action Identity Onboarding/ProvisionNewUser`
- Supported types: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `query`, `provider`, `export`, `mapper`, `scope`, `trait`, `mailable`.
- Examples for the Integration layer:
  - `php artisan domain:make export Identity UserExport --model=User`
  - `php artisan domain:make mapper Identity User` → generates `Integration/Mappers/UserDataMapper.php`
- Excel ingestion (Import) classes live in the **Gateway layer** at `app/Http/Ingestion/Excel/` — do NOT generate them with `domain:make`.
- Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

### 6. Testing Strategy
- Always write tests using Pest PHP.
- When generating new features (Actions/DTOs/Models), create corresponding tests in `tests/Feature/` or `tests/Unit/`.
- Ensure 100% adherence to Architectural tests (check `tests/Architecture/` for existing rules).
- When writing tests, use `Event::fake()`, `Queue::fake()`, `Notification::fake()` to isolate side effects.
- For dependency-injected services, use `$this->mock()` to define expected behaviors.

Write modern PHP 8.4+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.
```

---

## 12. Alat Pengembangan & Generator

Biar struktur folder Antigravity tetep rapi, **jangan pake perintah `make:` standar (kayak `make:model`) buat file Domain.** Pake perintah kustom `domain:make` biar kelasnya masuk ke namespace `app/Domains/` yang bener.

### Perintah `domain:make`

Tanda Tangan (Signature):

```bash
php artisan domain:make {type} {domain} {name} [options]
```

Argumen:

* `type`: Jenis file yang mau dibuat. Support: `model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `scope`, `trait`, `query`, `provider`, `export`, `mapper`, `mailable`, `view-provider`.
* `domain`: Nama Domain-nya (misal, `Identity`, `Account`, `System`).
* `name`: Nama kelasnya. Bisa pake sub-direktori (misal, `Management/ProvisionNewUser`).

Opsi:

* `--factory`: Bikin factory database sekalian (buat Model).
* `--migration`: Bikin file migrasi database sekalian (buat Model).
* `--model=`: Hubungin kelas ekspor sama model Eloquent (buat Export).

### Perintah `domain:datatable`

Menghasilkan class service Yajra DataTable di dalam `app/Http/DataTables/{Domain}/` dan index Blade view yang sesuai di dalam `resources/views/pages/{domainLower}/{capability}/`.

**Signature:**

```bash
php artisan domain:datatable {name} {domain} [--model=]
```

### Perintah `domain:make-page`

Menghasilkan Blade view atau komponen Livewire modal yang terpadu di dalam `resources/views/pages/{domain}/{capability}/`.

**Signature:**

```bash
php artisan domain:make-page {domain} {capability} {name} [--modal]
```

* Gunakan `--modal` untuk menghasilkan "Lightning Component" (⚡) yang mencakup class PHP dan Blade view di direktori yang sama.

### Perintah `domain:new`

Perintah ini bakal bikin struktur domain baru dan otomatis nyediain `ServiceProvider` utama (udah ada trait `RegistersDomainEvents`) dan `RelationshipServiceProvider` (khusus buat mapping `Model::resolveRelationUsing()`), terus otomatis didaftarin ke `bootstrap/providers.php`.

**Signature:**

```bash
php artisan domain:new {domain}
```

### Tujuan & Penggunaan Domain Providers

Biar logika bisnis nggak kecampur sama urusan tampilan atau relasi antar-domain, setiap modul domain pake hierarki Service Provider bertingkat:

1. **`{Domain}ServiceProvider`**: Pintu masuk utama domain. Pake trait `RegistersDomainEvents` buat mapping event lokal. Provider ini juga yang nendaftarin provider internal di bawahnya (kayak `RelationshipServiceProvider` dan `ViewServiceProvider`).
2. **`RelationshipServiceProvider`**: Khusus buat nanganin relasi lintas-domain secara dinamis pake `Model::resolveRelationUsing()`. Ini biar nggak ada ketergantungan yang kaku antar domain pas proses compile.
3. **`ViewServiceProvider`**: Khusus buat urusan tampilan (View Composers) biar nggak ngotorin logika domain utama. Udah didaftarin otomatis di ServiceProvider utama domain. Kalo belum ada, bisa bikin lewat generator:
   ```bash
   php artisan domain:make view-provider {domain} ViewServiceProvider
   ```
   Di dalem method `boot()`, tinggal daftarin view composer kustom kamu:
   ```php
   View::composer('components.layouts.sidebar', function ($view) {
       $view->with('navigationItems', [ ... ]);
   });
   ```

### Custom Stub (Template Kode)

Semua template `domain:make` ada di folder `app/Console/stubs/domain-make/` dalam bentuk file `.stub`. Kamu bebas edit file-file ini kalo mau ngerubah *boilerplate* default-nya biar makin pas sama kebutuhan proyekmu.

---

## 13. Manajemen File Universal (Domain System)

Urusan file (upload, lampiran, crop gambar, hapus file) itu fitur yang dipake bareng-bareng. Biar nggak setiap domain bikin logika sendiri, semua file fisik diurus sama mesin terpusat di domain **`System`**.

### Mesin Polimorfik

Kita nggak nyimpen path file langsung di tabel bisnis (misal: nggak ada kolom `avatar_path` di tabel `users`). Kita pake tabel `files` terpusat dan model `System\Models\File`.

* File ditempelin secara **polimorfik** ke entitas apa pun di aplikasi.
* Tabel `files` punya kolom `relation_name` (misal: `'avatar'`) biar jenis file yang beda di model yang sama nggak bentrok.

### Trait HasFile

Kalo ada Domain Model (kayak `User` atau `Invoice`) yang butuh lampiran file, tinggal pake trait `HasFile`. Trait ini udah nyediain fungsi buat bikin relasinya.

```php
namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Traits\HasFile;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model
{
    use HasFile; 

    // String 'avatar' ini yang ngebedain file-nya di database
    public function avatar(): MorphOne
    {
        return $this->singleFile('avatar'); 
    }
}

```

### Proses File (Metadata via DTO)

Karena objek `UploadedFile` Laravel itu ribet, kita kirim bareng `FileDTO` yang isinya metadata (model target, disk, nama relasi). Ini biar Gateway tetep bersih, tapi Domain dapet semua info yang dibutuhin.

* **`UploadAndAttachFile`**: Action dasar buat simpen file ke disk dan bikin record di database.
* **`ReplaceSingleFile`**: Buat ganti file (kayak ganti avatar). Ini bakal hapus file lama dulu baru upload yang baru pake action dasar tadi.

**Contoh di Gateway:**

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

### Helper Asset

Biar nggak ngotorin namespace global pake file `app/helpers.php` yang isinya sampah, kita punya helper khusus domain di `app/Domains/System/Helpers/asset.php`. File ini otomatis di-load via `composer.json` dan nyediain fungsi `asset_static()` buat akses file publik atau privat di Blade dengan rapi.

### Rekonsiliasi File (Pruning)

Seiring berjalannya waktu, database mungkin berisi record untuk file yang sudah tidak ada di disk, atau disk mungkin berisi file yang sudah tidak lagi direferensikan di database. Gunakan perintah prune untuk merekonsiliasi ini:

```bash
php artisan system:prune-files --disk=public --directory=uploads
```

---

## 14. Model Multi-Bahasa (Trait HasTranslation)

Untuk model yang butuh konten dalam beberapa bahasa (misal: Kategori atau Produk), kita pake trait `HasTranslation`. Ini bikin tabel utama tetep bersih dan ngikutin skema ternormalisasi buat konten yang bisa diterjemahin.

### Skema Terjemahan

Terjemahan disimpan di tabel khusus yang dinamain `{singular_table}_translations` (misal: `category_translations`). Tabel ini harus punya:
* `locale`: Kode bahasa (misal: `en`, `id`).
* `{singular_table}_id`: Foreign key ke model induk.
* Kolom yang diterjemahin (misal: `name`, `description`).

### Implementasi

1. Pake trait `HasTranslation` di model kamu.
2. Definisiin array `$translatable` yang isinya nama-nama field yang mau diterjemahin.

```php
namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Traits\Model\HasTranslation;

class Category extends Model
{
    use HasTranslation;

    protected array $translatable = ['name', 'description'];
}
```

### Cara Pake

Trait ini otomatis nanganin getter dan setter sesuai sama locale aplikasi yang lagi aktif.

```php
$category = Category::first();

// Balikin nama sesuai App::getLocale() yang aktif
echo $category->name; 

// Set nama buat locale yang aktif
$category->name = 'Electronic';

// Isi beberapa bahasa sekaligus
$category->fill([
    'en' => ['name' => 'Electronic'],
    'id' => ['name' => 'Elektronik'],
]);

$category->save(); // Otomatis simpen ke category_translations
```

---

## 15. Setting Global & State Aplikasi

Settingan yang nentuin status jalan aplikasi (Zona Waktu, Bahasa, Tag SEO) diurus sama domain `System` biar cepet dan kontekstual.

* **Memoization & Singletons:** Query `GetSystemSettings` didaftarin sebagai Singleton di `SystemServiceProvider`. Data diambil dari database/cache *cuma sekali* (menggunakan kunci cache yang ditentukan dalam `SystemSettings::$cacheName`) dan disimpan di memori PHP selama request itu jalan.
* **Middleware Kontekstual:** Kita pake middleware khusus (`HandlePreferredLanguage`, `HandlePreferredTimezone`) buat ngecek preferensi user, kalo nggak ada baru pake settingan global.
* **View Composers:** Variabel layout (kayak Logo atau Nama Web) disuntikkan otomatis lewat View Composers di `ViewServiceProvider` (didaftarkan oleh `SystemServiceProvider`), jadi nggak perlu ribet pake `@inject` terus-terusan.

---

## 16. Audit Log & Pelacakan

Semua perubahan database yang penting dicatat biar ada riwayatnya.

* **Model Auditing:** Kita pake Eloquent event buat catat otomatis setiap ada baris data yang berubah.
* **Relasi yang Ribet:** Kalo perubahan relasi *many-to-many* (kayak `syncPermissions` Spatie) yang nggak kedeteksi Eloquent event, kita kirim Custom Audit Event manual di dalem Action-nya (misal: `UpdateRolePermissions`). Jadi status "Sebelum" dan "Sesudah" tetep kecatat rapi dalam satu transaksi.

---

## 17. UI Dinamis & Livewire

Pas bikin form yang dinamis (kayak settingan), kita pake pola **Renderable Enum** bareng komponen dinamis Laravel.

* Enum jadi **Penyedia Metadata** (ngasih tau nama komponen Blade-nya).
* Pake `<x-dynamic-component>` di Blade buat ganti-ganti elemen UI secara otomatis.
* **PENTING:** Enum nggak boleh balikin string HTML mentah. Itu bisa bikin dom-diffing Livewire rusak dan binding `wire:model` jadi putus.

---

## 18. Impor & Ekspor Excel

Komponen `⚡excel-manager.blade.php` (pakenya `<livewire:datatables.excel-manager>`) nyediain fitur impor/ekspor Excel yang jalan di background (*queue*) buat halaman DataTable apa pun. Ini pake pola **single-file component** — logika PHP dan Blade-nya jadi satu di file yang sama, pake simbol `⚡` sesuai standar komponen di proyek ini.

### Arsitektur

Fitur ini punya tiga lapisan:

1. **Komponen Laravel** (`⚡excel-manager.blade.php`) — Ngurusin tampilan, upload file via FilePond, validasi, dan event Livewire. Semua properti dikunci pake `#[Locked]` biar nggak bisa diotak-atik dari luar.
2. **Dekorator `StyledExport`** (`App\UI\Support\Excel\StyledExport`) — Pembungkus (wrapper) buat nambahin gaya visual standar (header beku, landscape, border tipis, rata tengah, auto-size) biar kelas Export di domain tetep bersih dari urusan tampilan.
3. **Notifikasi via Event** — Abis file selesai dibuat, job `NotifyExportReady` bakal kirim event `ExportCompleted`, terus direspon sama listener `SendExportReportEmail` buat kirim filenya lewat email.

### Cara Kerja

* **Impor:** User upload file `.xlsx`. File disimpan ke folder lokal terus proses impornya masuk ke antrean (*queue*) pake `Excel::queueImport()`. Kelas Ingesti-nya ada di `app/Http/Ingestion/` (lapisan Gateway) dan pake sistem *chunk* (200 baris sekali jalan) biar hemat memori. Begitu masuk antrean, langsung muncul notifikasi sukses.

* **Ekspor:** Tombol Export di DataTable bakal kirim event `export-excel`. Kelas Export domain bakal dibungkus `StyledExport` terus masuk antrean. Kalo udah kelar, filenya dikirim ke email user. Notifikasi sukses juga muncul begitu proses ekspornya mulai masuk antrean.

### Properti Komponen (Props)

| Prop | Tipe | Deskripsi |
| --- | --- | --- |
| `importClass` | `string` | Nama kelas Ingesti di Gateway (misal: `App\Http\Ingestion\Excel\Identity\UserImport`). |
| `exportClass` | `string` | Nama kelas Export di domain (misal: `App\Domains\Identity\Exports\UserExport`). |
| `resourceName` | `string` | Nama pendek (slug) buat namain file yang disimpan (misal: `user`). |

### Cara Pake

Tinggal pasang komponennya di halaman DataTable kamu. Semua prop harus diisi pake nama kelas lengkap:

```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
    resource-name="user"
/>
```

Tombol Export di DataTable harus kirim event `export-excel`, dan tombol Import harus buka modal `#excel-import-modal`:

```php
// Di builder html() DataTable kamu:
Button::make('excel')
    ->action("Livewire.dispatch('export-excel')"),

Button::make('excel')
    ->action("$('#excel-import-modal').modal('show')"),
```

### Bikin Kelas Export & Mapper

Pake perintah `domain:make` buat bikin kelas pendukungnya:

```bash
# Bikin kelas Export di domain
php artisan domain:make export Identity UserExport --model=User

# Bikin Mapper integrasi (otomatis dapet akhiran DataMapper)
php artisan domain:make mapper Identity User
# → app/Domains/Identity/Integration/Mappers/UserDataMapper.php
```

Kelas Export di domain wajib pake interface `FromQuery & WithHeadings & WithMapping & WithColumnFormatting`. Gaya visualnya nanti diurus otomatis sama `StyledExport`, jadi **nggak perlu** pasang `WithStyles` manual di kelas domain.

> **Catatan Gateway:** Kelas Ingesti (Impor) Excel itu ada di `app/Http/Ingestion/Excel/` dan **nggak** dibuat pake `domain:make`. Bikin manual aja atau pake `make:class` biasa.

### Alur Notifikasi

Proses notifikasi ekspor itu full pake sistem event yang masuk antrean:

```
Excel::queue(StyledExport, $path)
  └─> NotifyExportReady (Job)        [app/Domains/System/Jobs/]
        └─> ExportCompleted::dispatch (Event)  [app/Domains/System/Events/]
              └─> SendExportReportEmail (Listener)  [app/Domains/System/Listeners/]
                    └─> ExcelExportEmail (Mailable)  [app/Domains/System/Mail/]
```

Kalo notifikasi impor, dikirim langsung dari kelas Impor domain-nya pas udah kelar, pake `ExcelImportEmail` dari namespace yang sama.

### Mailable (Email)

Semua kelas Mailable ada di **domain System**, bukan di namespace `App\Mail\` biasa:

* **`App\Domains\System\Mail\ExcelImportEmail`** — Dikirim pas impor selesai.
* **`App\Domains\Identity\Mail\Registration\WelcomeEmail`** — Contoh email khusus domain.
* **`App\Domains\System\Mail\ExcelExportEmail`** — Dikirim pas file ekspor siap, filenya dilampirin dari disk lokal.

### Kunci Terjemahan

| File | Kunci | Kegunaan |
| --- | --- | --- |
| `lang/{locale}/ui.php` | `ui.excel.import.file_label` | Label upload di modal impor. |
| `lang/{locale}/ui.php` | `ui.excel.import.success` | Notifikasi pas impor mulai masuk antrean. |
| `lang/{locale}/ui.php` | `ui.excel.export.success` | Notifikasi pas ekspor mulai masuk antrean. |
| `lang/{locale}/domains/system.php` | `notifications.excel.import_email.*` | Isi email buat info impor kelar. |
| `lang/{locale}/domains/system.php` | `notifications.excel.export_email.*` | Isi email buat info ekspor siap. |

---

## 19. Testing

Kita pake [Pest PHP](https://pestphp.com) buat ngetes kode. Silakan cek [TESTING.md](TESTING.md) buat instruksi lengkap soal cara jalanin, struktur, dan nulis tes.

---

## 20. Variabel Environment

Variabel penting di file `.env`:

- `APP_NAME`: Nama aplikasi.
- `APP_ENV`: Lingkungan aplikasi (`local`, `production`, dll).
- `APP_KEY`: Kunci enkripsi aplikasi.
- `DB_CONNECTION`: Driver database (`sqlite`, `mysql`, `pgsql`).
- `QUEUE_CONNECTION`: Driver antrean (default: `database`).
- `MAIL_MAILER`: Driver email (default: `log`).

Cek `.env.example` buat liat daftar lengkapnya.

---

## 21. Lisensi

Proyek ini pake **Lisensi MIT**.
