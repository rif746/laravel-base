# Layanan Sistem (System Services)

Dokumen ini mencakup layanan lintas disiplin yang disediakan oleh domain `System`: Manajemen File Universal, UI Dinamis dengan Renderable Enums, dan Mesin Impor & Ekspor Excel.

---

## 1. Manajemen File Universal (Trait `HasFile`)

Urusan file (upload, lampiran, crop gambar, hapus file) itu fitur yang dipake bareng-bareng. Biar nggak setiap domain bikin logika sendiri, semua file fisik diurus sama mesin terpusat di domain **`System`**.

### Mesin Polimorfik

Kita nggak nyimpen path file langsung di tabel bisnis (misal: nggak ada kolom `avatar_path` di tabel `users`). Kita pake tabel `files` terpusat dan model `System\Models\File`.

* File ditempelin secara **polimorfik** ke entitas apa pun di aplikasi.
* Tabel `files` punya kolom `relation_name` (misal: `'avatar'`) biar jenis file yang beda di model yang sama nggak bentrok.

### Trait HasFile (Konsumen)

Kalo ada Domain Model (kayak `User` atau `Invoice`) yang butuh lampiran file, tinggal pake trait `HasFile`. Trait ini udah nyediain fungsi buat bikin relasinya.

```php
namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Concerns\HasFile;
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

### Helper Asset Sistem

Biar nggak ngotorin namespace global pake file `app/helpers.php` yang isinya sampah, kita punya helper khusus domain di `app/Domains/System/Helpers/asset.php`. File ini otomatis di-load via `composer.json` dan nyediain fungsi `asset_static()` buat akses file publik atau privat di Blade dengan rapi.

### Rekonsiliasi File (Pruning)

Seiring berjalannya waktu, database mungkin berisi record untuk file yang sudah tidak ada di disk, atau disk mungkin berisi file yang sudah tidak lagi direferensikan di database. Gunakan perintah prune untuk merekonsiliasi ini:

```bash
php artisan system:prune-files --disk=public --directory=uploads
```

---

## 2. UI Dinamis & Renderable Enums

Pas bikin form yang dinamis (kayak settingan), kita pake pola **Renderable Enum** bareng komponen dinamis Laravel.

* Enum jadi **Penyedia Metadata** (ngasih tau nama komponen Blade-nya).
* Pake `<x-dynamic-component>` di Blade buat ganti-ganti elemen UI secara otomatis.
* **PENTING:** Enum nggak boleh balikin string HTML mentah. Itu bisa bikin dom-diffing Livewire rusak dan binding `wire:model` jadi putus.

---

## 3. Mesin Impor & Ekspor Excel

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

### Bikin Kelas Export, Mapper & Kelas Domain Lainnya

Pake perintah `domain:make` buat bikin kelas pendukungnya:

```bash
# Bikin kelas Export di domain
php artisan domain:make export Identity UserExport --model=User

# Bikin Mapper integrasi (otomatis dapet akhiran DataMapper)
php artisan domain:make mapper Identity User
# → app/Domains/Identity/Integration/Mappers/UserDataMapper.php

# Bikin Cast Eloquent kustom (dengan hint @implements bertipe)
php artisan domain:make cast System AsMoneyAmount --model=Money
# → app/Domains/System/Casts/AsMoneyAmount.php

# Bikin Value Object (Stringable)
php artisan domain:make value-object System Money
# → app/Domains/System/Support/ValueObjects/Money.php

# Bikin Registry Domain statis (otomatis dapet akhiran Registry)
php artisan domain:make registry System Feature
# → app/Domains/System/Support/Registry/FeatureRegistry.php

# Bikin Observer Eloquent (otomatis dapet akhiran Observer)
php artisan domain:make observer System Backup
# → app/Domains/System/Observers/BackupObserver.php

# Bikin Integration Interface
php artisan domain:make integration-interface System ExternalPaymentGateway
# → app/Domains/System/Support/Integration/ExternalPaymentGateway.php
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
