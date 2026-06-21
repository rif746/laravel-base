# Arsitektur Antigravity: Laravel Modular Monolith

Selamat datang di repositori ini. Aplikasi ini dibangun menggunakan arsitektur **Pragmatic Domain-Driven Design (DDD)** yang ketat. Kami menyebutnya sebagai arsitektur "Antigravity" karena ia mencegah basis kode runtuh di bawah bebannya sendiri saat aplikasi berkembang pesat (scaling).

Dokumen ini berfungsi sebagai sumber kebenaran utama (source of truth) untuk pengembang manusia maupun agen AI yang bekerja pada basis kode ini.

---

## 1. Filosofi Inti

Arsitektur ini memberlakukan batasan fisik yang keras antara **Delivery** (bagaimana pengguna berinteraksi dengan aplikasi) dan **Business Logic** (apa yang sebenarnya dilakukan aplikasi).

* **The Gateway (Lapisan HTTP):** Controllers, komponen Livewire, dan Volt murni bertindak sebagai pengatur lalu lintas. Mereka menangani sesi web, cookie, pengalihan (redirect), dan validasi form.
* **The Domain (The Vault):** Actions, DTOs, dan Models di dalam `app/Domains/` menangani aturan bisnis yang sebenarnya, mutasi database, dan pemanggilan API eksternal.

> **Aturan Emas:** Domain harus tetap sepenuhnya tidak tahu menahu tentang web. Anda sama sekali tidak boleh menggunakan helper `request()`, `session()`, atau melempar eksepsi HTTP di dalam direktori `app/Domains/`.

---

## 2. Struktur Direktori

Aplikasi ini dibagi berdasarkan **Konsep Bisnis**, bukan fitur teknis.

```text
app/
├── Attributes/               <-- Atribut PHP 8 (misal, #[Seo])
├── Console/
│   └── Commands/             <-- Perintah Artisan kustom (DomainMakeCommand, CleanOrphanedFiles)
├── Http/                     <-- The Gateway (Lapisan HTTP)
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/           <-- Kontroler API berversi
│   │   └── Web/
│   │       ├── Auth/         <-- Kontroler autentikasi
│   │       ├── Identity/     <-- Kontroler manajemen pengguna & peran
│   │       └── System/       <-- Kontroler pengaturan sistem
│   ├── Middleware/           <-- HandlePreferredLanguage, HandlePreferredTimezone, HandleSeoSetting, dll.
│   ├── Requests/
│   │   ├── Api/              <-- Form request untuk API
│   │   └── Web/              <-- Form request untuk Web
│   └── Resources/            <-- Resource API (LookupResource, SuccessResource, dll.)
├── Livewire/
│   └── Concerns/             <-- Trait Livewire yang digunakan bersama (WithModal, WithToast, HasSeoAttributes)
├── Providers/                <-- AppServiceProvider
├── UI/
│   ├── Actions/              <-- Actions di lapisan UI (mutasi non-domain)
│   └── Enums/
└── Domains/
    ├── Identity/             <-- Konsep Bisnis: Autentikasi & Pengguna
    │   ├── Actions/          <-- Mutasi yang dikelompokkan berdasarkan kapabilitas
    │   │   ├── Onboarding/   <-- RegisterSelfServiceUser, ProvisionNewUser,
    │   │   │               <-- UpdateUser, VerifyUserEmail, ResendVerificationEmail
    │   │   ├── AccessControl/<-- CreateSystemRole, UpdateSystemRole, UpdateUserRole, RemoveSystemRole
    │   │   ├── Governance/   <-- SuspendUser, PurgeUser, RemoveUser, ActivateUserStatus
    │   │   └── Passwords/    <-- ResetUserPassword, UpdatePassword, SendPasswordResetLink
    │   ├── DTOs/             <-- DTO yang dikelompokkan berdasarkan kapabilitas
    │   │   ├── Onboarding/   <-- RegisterSelfServiceUserDTO, ProvisionUserDTO, UpdateUserDTO
    │   │   ├── AccessControl/<-- CreateRoleDTO, UpdateRoleDTO
    │   │   └── Passwords/    <-- ForgotPasswordDTO, ResetPasswordDTO, UpdatePasswordDTO
    │   ├── DataTables/
    │   ├── Enums/
    │   ├── Events/           <-- Fakta masa lalu, dikelompokkan berdasarkan kapabilitas
    │   │   ├── Authentication/<-- UserLoggedIn
    │   │   ├── Onboarding/   <-- UserWasRegistered, UserWasProvisioned, UserEmailWasVerified
    │   │   ├── Governance/   <-- UserWasSuspended, UserWasPurged, UserWasActivated
    │   ├── Exports/
    │   ├── Integration/
    │   │   └── Mappers/      <-- Implementasi DataPayloadMapper
    │   ├── Listeners/        <-- Handler kata kerja aktif, dikelompokkan berdasarkan kapabilitas
    │   │   └── Authentication/<-- SendSignInActivityNotification
    │   ├── Models/           <-- User, Role, Permission
    │   ├── Notifications/
    │   ├── Policies/
    │   └── Queries/          <-- Query bacaan kompleks (disiapkan untuk penggunaan masa depan)
    ├── Account/              <-- Konsep Bisnis: Profil & Tagihan
    │   ├── Actions/
    │   │   └── Profile/
    │   ├── DTOs/
    │   ├── Enums/
    │   └── Models/
    └── System/               <-- Konsep Bisnis: Infrastruktur Lintas Domain
        ├── Actions/
        │   ├── Backup/
        │   ├── Files/        <-- UploadAndAttachFile, ReplaceSingleFile, RemoveModelFile, PruneOrphanedFiles
        │   └── Settings/
        ├── Casts/            <-- Casts Eloquent kustom (misal, ByteHumanReadable)
        ├── DTOs/
        ├── Enums/
        ├── Helpers/          <-- asset.php (helper asset_static(), di-autoload via composer.json)
        ├── Models/           <-- File, SystemSettings, Backup
        ├── Policies/
        ├── Providers/        <-- SystemServiceProvider (Registrasi Singleton, View Composers)
        ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
        ├── Support/
        │   └── ValueObjects/
        └── Traits/
            └── Model/        <-- Trait HasFile
```

---

## 3. Aturan Pelaksanaan (Rules of Engagement)

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

## 4. Konvensi Penamaan

Arsitektur ini menggunakan bahasa penamaan yang ketat dan disengaja. Setiap nama harus mengomunikasikan **Niat Bisnis (Business Intent)**, bukan operasi database.

### 4.1 Folder Domain (`app/Domains/{Name}/`)

Nama domain adalah **Konsep Bisnis**, bukan lapisan teknis. Harus berupa kata benda tunggal yang mendeskripsikan *bounded context*.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `Identity` | `Users` | Identity mencakup autentikasi, peran, dan siklus hidup pengguna — bukan sekadar nama tabel. |
| `Account` | `Profile` | Account memiliki seluruh permukaan akun pengguna, bukan hanya satu model. |
| `System` | `Utils` / `Helpers` | System adalah konteks bisnis nyata untuk infrastruktur lintas-potong (cross-cutting). |

### 4.2 Folder Kapabilitas (Subdirektori Action / DTO / Event / Listener)

Subdirektori di dalam `Actions/`, `DTOs/`, `Events/`, dan `Listeners/` harus dinamai berdasarkan **Kapabilitas Bisnis**, bukan kata benda database.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `Onboarding/` | `Users/` | Mendeskripsikan tahap siklus hidup, bukan tabel DB. |
| `AccessControl/` | `Roles/` | Mendeskripsikan kapabilitas, bukan nama resource. |
| `Governance/` | `Admin/` | Mendeskripsikan niat kepatuhan/pengawasan. |
| `Passwords/` | `Auth/` | Ruang lingkup yang sempit dan presisi. |
| `Registration/` | `Signup/` | Menggunakan bahasa formal sistem. |

**Aturan:** Jika nama folder juga merupakan nama Model Eloquent yang valid, maka penamaan tersebut salah.

### 4.3 Nama Kelas Action

Actions harus dinamai berdasarkan **Niat Bisnis yang spesifik** yang mereka penuhi. Gunakan pola kata kerja aktif + kata benda bisnis.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Mendeskripsikan *siapa* yang memicu dan *mengapa*. |
| `SuspendUser` | `DeleteUser` | Mengungkapkan konsekuensi bisnis (pencabutan lunak, bukan penghancuran). |
| `UpdateUserRole` | `SaveRole` | Eksplisit mengenai subjek dan properti yang diubah. |
| `RegisterUser` | `StoreUser` | Bahasa domain, bukan bahasa HTTP verb. |
| `SendPasswordResetLink` | `ResetPassword` | Mencerminkan efek samping sebenarnya yang dipicu. |

Nama-nama CRUD (`CreateCategory`, `UpdateSetting`) hanya dapat diterima untuk tabel pencarian sepele (lookup tables) yang **tidak memiliki efek samping**.

### 4.4 Nama Kelas DTO

DTO dinamai berdasarkan Action yang mereka layani, dengan akhiran `DTO`.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 4.5 Nama Kelas Event

Events adalah **fakta masa lalu (past-tense)** mengenai sesuatu yang telah terjadi dalam domain. Nama kelas harus secara gramatikal menyatakan kebenaran yang sudah selesai.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Bentuk *past-tense* eksplisit menghilangkan ambiguitas. |
| `UserWasSuspended` | `UserSuspended` | Membaca sebagai state, bukan sebagai fakta yang sudah selesai. |
| `UserLoggedIn` | `LoginEvent` | Pola kata benda + kata kerja; menghindari akhiran `Event`. |
| `UserEmailVerified` | `EmailVerification` | Mendeskripsikan tindakan yang telah selesai. |

**Aturan:** Jangan pernah mengakhiri Events dengan `Event` (misal, `UserRegisteredEvent` itu salah). Namespace `Events\` sudah mengomunikasikan jenisnya.

### 4.6 Nama Kelas Listener

Listeners mendeskripsikan **reaksi aktif** terhadap sebuah event menggunakan frasa kata kerja imperatif.

| ✅ Benar | ❌ Salah | Alasan |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Mendeskripsikan apa yang *dilakukan* listener, bukan apa yang diresponsnya. |
| `DispatchWelcomeNotification` | `WelcomeListener` | Kata kerja imperatif membuat niat menjadi sangat jelas. |

**Aturan:** Jangan pernah mengakhiri Listeners dengan `Listener` di nama kelasnya. Namespace `Listeners\` sudah mengomunikasikan jenisnya.

---

## 5. Prompt Agen AI (Instruksi Sistem)

**Untuk Pengembang:** Salin dan tempel blok di bawah ini ke obrolan agen AI Anda atau instruksi sistem sebelum memintanya untuk menulis atau merefaktor kode di repositori ini.

```text
You are an autonomous Senior Laravel Architect specializing in Pragmatic Domain-Driven Design (DDD) and Event-Driven Architecture. You must strictly obey the "Antigravity" rules of this repository.

### 1. The HTTP Gateway (Delivery Layer)
- Lives in `app/Http/Controllers/` or `resources/views/` (Volt/Livewire).
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
* Examples for the Integration layer:
  * `php artisan domain:make export Identity UserExport --model=User`
  * `php artisan domain:make mapper Identity User` → generates `Integration/Mappers/UserDataMapper.php`
* Excel ingestion (Import) classes live in the **Gateway layer** at `app/Http/Ingestion/` — do NOT generate them with `domain:make`.
* Queries: For complex database reads (e.g., massive filtering or reporting), create a Query class in app/Domains/{Concept}/Queries/. Queries are read-only, do not use transactions, do not mutate state, and do not dispatch events.

Write modern PHP 8.4+ code with strict typing. Ensure all PSR-4 namespaces perfectly match the directory structure.
```

---

## 6. Alat Pengembangan & Generator

Untuk mempertahankan struktur folder yang ketat dari arsitektur Antigravity, **jangan gunakan perintah `make:` standar (seperti `make:model`) untuk file Domain.** Gunakan perintah kustom `domain:make` untuk menghasilkan kelas di namespace `app/Domains/` yang benar.

### Perintah `domain:make`

**Signature:**

```bash
php artisan domain:make {type} {domain} {name} [options]
```

**Argumen:**

* `type`: Jenis file yang akan dibuat (`model`, `action`, `dto`, `enum`, `event`, `listener`, `notification`, `policy`, `trait`, `query`, `provider`, `export`, `mapper`).
* `domain`: Folder Domain target (misal, `Identity`, `Account`, `System`).
* `name`: Nama kelas. Mendukung pengelompokan sub-direktori (misal, `Management/ProvisionNewUser`).

**Opsi:**

* `--factory`: Menghasilkan factory database terkait (Hanya Models).
* `--migration`: Menghasilkan file migrasi database (Hanya Models).
* `--model=`: Mengaitkan kelas ekspor dengan model Eloquent (Hanya Exports).

### Menyesuaikan Generator (Stubs)

Semua template `domain:make` disimpan sebagai file `.stub` di `app/Console/stubs/domain-make/`. Anda dapat dengan bebas mengedit stub ini untuk menyesuaikan *boilerplate* default dengan kebutuhan spesifik proyek Anda (misalnya, mengubah metode default di DTO).

---

## 7. Manajemen File Universal (Domain System)

Penanganan file (unggahan, lampiran, pemangkasan gambar, dan penghapusan) adalah kemampuan yang dibagikan secara universal. Untuk mencegah setiap domain menulis logika penyimpanan filenya sendiri, semua file fisik dikelola oleh mesin terpusat di dalam domain **`System`**.

### Mesin Polimorfik

Kami tidak menambahkan path file secara langsung ke tabel bisnis (misalnya, tidak ada kolom `avatar_path` di tabel `users`). Alih-alih, kami menggunakan tabel `files` terpusat dan model `System\Models\File`.

* File dilampirkan secara **polimorfik** ke entitas apa pun di dalam aplikasi.
* Tabel `files` menyertakan kolom string `relation_name` bertipe ketat (misalnya, `'avatar'`) untuk mencegah terjadinya tabrakan antar jenis file yang dilampirkan ke model yang sama.

### Trait Konsumen (The Consumer Trait)

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

### Actions File (Tanpa DTO)

Karena objek `Illuminate\Http\UploadedFile` milik Laravel sudah merupakan objek dengan tipe yang sangat ketat, kami **tidak** membungkus file di dalam DTO. Gateway melewatkan file mentah dan target `relation_name` secara langsung ke System Actions pusat.

* **`UploadAndAttachFile`**: Action dasar. Ini menyimpan file fisik ke disk dan membuat rekaman database polimorfik.
* **`ReplaceSingleFile`**: Digunakan untuk penggantian 1-ke-1 (seperti mengganti avatar). Ia menghapus file lama secara aman sebelum mendelegasikan unggahan baru kembali ke Action dasar.

**Contoh Gateway:**

```php
$action->execute(
    targetModel: auth()->user()->profile,
    relationName: 'avatar', // Cocok dengan nama metode relasi secara tepat
    uploadedFile: $request->file('photo'),
    disk: 'local',
    directory: 'avatars'
);
```

### Helper Asset Sistem

Untuk menghindari polusi namespace global Laravel dengan file `app/helpers.php` sampah, kami mengelola file helper yang terikat ketat pada domain di `app/Domains/System/Helpers/asset.php`. File ini di-autoload via `composer.json` dan menyediakan fungsi `asset_static()` untuk menyelesaikan (resolve) file publik dan privat di view Blade kita secara elegan.

---

## 8. Pengaturan Global & State Aplikasi

Pengaturan yang mendikte *state* (status) *runtime* aplikasi (Zona Waktu, Lokalisasi, Tag SEO) dikelola oleh domain `System` untuk memastikan performa tinggi dan kesadaran konteks.

* **Memoization & Singletons:** Query `GetSystemSettings` didaftarkan sebagai Singleton di `SystemServiceProvider`. Ia mengambil data dari database/cache *sekali* saja dan menyimpannya di memori lokal PHP selama durasi request berjalan.
* **Contextual Middlewares:** Kami menggunakan *middleware* terdedikasi (`HandlePreferredLanguage`, `HandlePreferredTimezone`) untuk memeriksa preferensi pengguna yang terautentikasi secara dinamis, lalu menggunakan (fallback ke) pengaturan global jika tidak ada preferensi yang ditemukan.
* **View Composers:** Variabel *layout* global (seperti Logo dan Nama Web) disuntikkan ke global via View Composers di dalam Service Provider, untuk mencegah penggunaan direktif `@inject` yang berulang.

---

## 9. Pencatatan Audit (Audit Logging) & Pelacakan

Semua mutasi database yang penting dilacak untuk mempertahankan buku besar (ledger) historis yang patuh (compliant).

* **Model Auditing:** Kami memanfaatkan Eloquent event untuk secara otomatis mencatat perubahan baris.
* **Complex Relation Auditing:** Melacak perubahan hubungan many-to-many (seperti `syncPermissions` milik Spatie) akan melewati (bypass) standar Eloquent event. Oleh karena itu, kami secara eksplisit mendispatch Custom Audit Event langsung di dalam Domain Action terkait (misalnya, `UpdateRolePermissions`). Ini menjamin state "Sebelum" dan "Sesudah" terekam dengan rapi di dalam satu baris transaksional tunggal.

---

## 10. UI Dinamis & Interoperabilitas Livewire

Saat membangun antarmuka berbasis data (seperti formulir pengaturan dinamis), kami memanfaatkan pola **Renderable Enum** dikombinasikan dengan komponen dinamis bawaan Laravel.

* Enum bertindak sebagai **Penyedia Metadata** (mengembalikan nama string dari komponen Blade target).
* Kami menggunakan `<x-dynamic-component>` di dalam file Blade untuk menukar elemen UI secara dinamis.
* **KRITIS:** Enum tidak boleh sama sekali mengembalikan string HTML mentah yang dikompilasi melalui *facade* `Blade`. Melakukan hal tersebut akan merusak mesin DOM-diffing milik Livewire dan memutuskan binding `wire:model`.

---

## 11. Ekspor & Impor Excel

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
| `importClass` | `string` | Nama kelas kualifikasi penuh (fully-qualified) dari Gateway Ingestion class (contoh, `App\Http\Ingestion\Identity\UserImport`). |
| `exportClass` | `string` | Nama kelas kualifikasi penuh dari domain Export (contoh, `App\Domains\Identity\Exports\UserExport`). |
| `resourceName` | `string` | Sebuah kata (slug) yang digunakan untuk menamai file impor yang disimpan serta nama file ekspor yang bertanda waktu (contoh, `user`). |

### Penggunaan

Sematkan komponen di dalam halaman DataTable (Blade view) mana pun. Semua props harus disediakan berupa string nama kelas PHP yang terkualifikasi secara penuh:

```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    :import-class="\App\Http\Ingestion\Identity\UserImport::class"
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

> **Lapisan Gateway:** Kelas Excel Ingestion (Import) berada di dalam `app/Http/Ingestion/` dan **tidak** dihasilkan oleh generator `domain:make`. Buatlah secara manual atau gunakan `make:class` sebagai kelas PHP standar yang mengimplementasikan `ToCollection`, `WithHeadingRow`, dan `WithChunkReading`.

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
* **`App\Domains\System\Mail\ExcelExportEmail`** — Dikirim saat *queued export* siap, dilampirkan menggunakan file dari disk `local`. Menggunakan terjemahan dari `domains/system.notifications.excel.export_email.*`.

### Kunci Terjemahan (Translation Keys)

| File | Kunci (Key) | Tujuan |
| --- | --- | --- |
| `lang/{locale}/ui.php` | `ui.excel.import.file_label` | Label unggahan FilePond di dalam modal impor. |
| `lang/{locale}/ui.php` | `ui.excel.import.success` | Pesan Toast muncul setelah impor masuk antrean. |
| `lang/{locale}/ui.php` | `ui.excel.export.success` | Pesan Toast muncul setelah ekspor masuk antrean. |
| `lang/{locale}/domains/system.php` | `notifications.excel.import_email.*` | Isi (body) email untuk pemberitahuan selesai impor. |
| `lang/{locale}/domains/system.php` | `notifications.excel.export_email.*` | Isi (body) email untuk pemberitahuan ekspor sudah siap. |
