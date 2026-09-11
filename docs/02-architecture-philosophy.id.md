# Arsitektur & Filosofi

Dokumen ini menjelaskan filosofi arsitektur inti, struktur direktori, dan konvensi penamaan yang mengatur **Antigravity** Modular Monolith.

---

## 1. Filosofi Inti

Arsitektur ini bikin batasan fisik yang tegas antara **Delivery** (cara user interaksi sama aplikasi) dan **Business Logic** (apa yang sebenarnya aplikasi lakuin).

* **The Gateway (Lapisan HTTP):** Controller, komponen Livewire, dan Volt itu murni cuma "polisi lalu lintas". Mereka ngurusin sesi web, cookie, redirect, dan validasi form.
* **The Domain (The Vault):** Action, DTO, dan Model di dalam `app/Domains/` yang nanganin aturan bisnis beneran, mutasi database, dan manggil API luar.

> **Aturan Emas:** Domain harus bener-bener bersih dari urusan web. Nggak boleh pake helper `request()`, `session()`, atau nge-throw HTTP exception di dalem direktori `app/Domains/`.

---

## 2. Struktur Direktori

Aplikasi ini dibagi berdasarkan **Konsep Bisnis**, bukan fitur teknis.

```text
app/
├── Attributes/               <-- Atribut PHP 8 (misal, #[Seo], #[LayoutData])
│   ├── Form/
│   ├── Model/
│   └── Ui/
├── Console/
│   ├── Commands/             <-- Perintah Artisan kustom (DomainMakeCommand, CleanOrphanedFiles)
│   └── stubs/                <-- Stub buat generate kode
├── Domains/                  <-- The Vault (Logika Bisnis)
│   ├── Account/              <-- Konsep Bisnis: Profil & Tagihan
│   │   ├── Actions/
│   │   ├── DTOs/
│   │   ├── Enums/
│   │   ├── Listeners/
│   │   ├── Models/
│   │   └── Providers/
│   ├── Identity/             <-- Konsep Bisnis: Autentikasi & User
│   │   ├── Actions/          <-- Mutasi data
│   │   ├── DTOs/             <-- Data Transfer Objects
│   │   ├── Enums/
│   │   ├── Events/           <-- Sesuatu yang udah terjadi
│   │   ├── Exports/
│   │   ├── Integration/      <-- Mapper sistem luar
│   │   ├── Listeners/        <-- Yang nanggapin event
│   │   ├── Mail/
│   │   ├── Models/           <-- User, Role, Permission
│   │   ├── Notifications/
│   │   ├── Policies/
│   │   ├── Providers/
│   │   └── Queries/          <-- Baca data yang ribet
│   └── System/               <-- Konsep Bisnis: Infrastruktur Umum
│       ├── Actions/
│       ├── Casts/            <-- Cast Eloquent kustom (CastsAttributes)
│       ├── Concerns/         <-- Trait domain (HasFile)
│       ├── Console/
│       ├── DTOs/
│       ├── Enums/
│       ├── Events/
│       ├── Helpers/          <-- Helper domain (asset.php)
│       ├── Jobs/             <-- Background job domain
│       ├── Listeners/
│       ├── Mail/             <-- Mailable domain
│       ├── Models/           <-- File, SystemSettings, Backup
│       ├── Observers/        <-- Observer model Eloquent
│       ├── Policies/
│       ├── Providers/        <-- SystemServiceProvider
│       ├── Queries/          <-- GetSystemSettings, GetModelAuditLog
│       └── Support/          <-- Kontrak integrasi, Registry, ValueObjects
├── Http/                     <-- The Gateway (Lapisan HTTP)
│   ├── Controllers/
│   │   ├── Api/              <-- API controller ber-versi
│   │   └── Web/              <-- Controller Web (Auth, Identity, Account)
│   ├── DataTables/           <-- Konfigurasi DataTable Livewire
│   ├── Ingestion/            <-- Kelas buat impor Excel
│   ├── Middleware/           <-- HandlePreferredLanguage, HandleSeoSetting, dll.
│   ├── Requests/             <-- Form request API dan Web
│   └── Resources/            <-- Resource API (LookupResource, SuccessResource, dll.)
├── Livewire/                 <-- Komponen & Form Livewire
│   ├── Concerns/             <-- Trait Livewire (WithModal, WithToast)
│   └── Forms/                <-- Objek Form Livewire
├── Providers/                <-- AppServiceProvider, UiServiceProvider
└── UI/                       <-- Logika khusus UI
    ├── Actions/              <-- Action di lapisan UI (SetSeoMetadata, ApplyLayoutMetadata)
    ├── Enums/                <-- Enum khusus UI (FileType, InputType)
    └── Support/              <-- Helper UI (LayoutState, StyledExport)
```

---

## 3. Konvensi Penamaan

Arsitektur ini punya aturan nama yang ketat. Setiap nama harus nunjukin **Niat Bisnis (Business Intent)**, bukan urusan teknis database.

### 3.1 Folder Domain (`app/Domains/{Name}/`)

Nama domain itu **Konsep Bisnis**, bukan lapisan teknis. Pake kata benda tunggal yang jelasin *bounded context*.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `Identity` | `Users` | Identity itu nyangkut auth, role, dan siklus hidup user — bukan cuma nama tabel. |
| `Account` | `Profile` | Account itu ngurusin semua soal akun user, bukan cuma satu model. |
| `System` | `Utils` / `Helpers` | System itu emang konteks bisnis buat hal-hal teknis yang dipake bareng-bareng. |

### 3.2 Folder Kapabilitas (Subdirektori Action / DTO / Event / Listener)

Subfolder di dalem `Actions/`, `DTOs/`, `Events/`, dan `Listeners/` harus dinamain pake **Kapabilitas Bisnis**, bukan kata benda database.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `Onboarding/` | `Users/` | Jelasin tahapannya, bukan tabelnya. |
| `AccessControl/` | `Roles/` | Jelasin kemampuannya, bukan nama resource-nya. |
| `Governance/` | `Admin/` | Jelasin niat kepatuhannya. |
| `Passwords/` | `Auth/` | Lebih sempit dan pas. |
| `Registration/` | `Signup/` | Pake bahasa formal sistem. |

**Aturan:** Kalo nama foldernya sama kayak nama Model Eloquent, berarti salah.

### 3.3 Nama Kelas Action

Action harus dinamain sesuai **Niat Bisnis yang spesifik**. Pake pola: kata kerja aktif + kata benda bisnis.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `ProvisionNewUser` | `CreateUser` | Jelasin *siapa* yang mulai dan *kenapa*. |
| `SuspendUser` | `DeleteUser` | Ngasih tau efek bisnisnya (cuma dinonaktifin, bukan dihapus permanen). |
| `UpdateUserRole` | `SaveRole` | Eksplisit soal apa yang diubah. |
| `RegisterUser` | `StoreUser` | Pake bahasa domain, bukan bahasa database/HTTP. |
| `SendPasswordResetLink` | `ResetPassword` | Sesuai sama efek samping yang beneran kejadian. |

Nama CRUD standar (`CreateCategory`, `UpdateSetting`) cuma boleh buat tabel sepele yang **nggak punya efek samping apa-apa**.

### 3.4 Nama Kelas DTO

DTO dinamain sesuai Action yang dilayanin, ditambah akhiran `DTO`.

| Action | DTO |
|---|---|
| `ProvisionNewUser` | `ProvisionUserDTO` |
| `UpdateUser` | `UpdateUserDTO` |
| `CreateSystemRole` | `CreateRoleDTO` |

### 3.5 Nama Kelas Event

Event itu **fakta masa lalu (past-tense)** soal hal yang udah kejadian. Namanya harus nunjukin kejadian yang udah beres.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `UserWasProvisioned` | `UserProvisioned` | Pake *past-tense* biar nggak bingung. |
| `UserWasSuspended` | `UserSuspended` | Kalo tanpa `was`, kayak status, bukan kejadian. |
| `UserLoggedIn` | `LoginEvent` | Pola subjek + predikat; nggak perlu pake embel-embel `Event`. |
| `UserEmailVerified` | `EmailVerification` | Jelasin aksi yang udah kelar. |

**Aturan:** Jangan pernah pake akhiran `Event` (misal, `UserRegisteredEvent` itu salah). Namespace `Events\` udah jelasin tipenya.

### 3.6 Nama Kelas Listener

Listener jelasin **reaksi aktif** terhadap suatu event pake kata kerja perintah.

| ✅ Bener | ❌ Salah | Alasan |
|---|---|---|
| `SendSignInActivityNotification` | `UserLoggedInListener` | Jelasin apa yang *dilakuin*, bukan apa yang ditungguin. |
| `DispatchWelcomeNotification` | `WelcomeListener` | Pake kata kerja perintah biar niatnya makin jelas. |

**Aturan:** Jangan pernah pake akhiran `Listener` di nama kelasnya. Namespace `Listeners\` udah jelasin tipenya.

---

## 4. Panduan Kata Kerja Action & Niat Bisnis

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
