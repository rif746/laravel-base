# Alat Pengembangan & Generator

Dokumen ini mencakup generator kode kustom (perintah `domain:make` dkk), kustomisasi stub, dan strategi pengujian Pest PHP.

---

## 1. Perintah `domain:make`

Biar struktur folder Antigravity tetep rapi, **jangan pake perintah `make:` standar (kayak `make:model`) buat file Domain.** Pake perintah kustom `domain:make` biar kelasnya masuk ke namespace `app/Domains/` yang bener.

### Tanda Tangan (Signature)

```bash
php artisan domain:make {type} {domain} {name} [options]
```

### Argumen

* `type`: Jenis file yang mau dibuat. Lihat tabel di bawah untuk semua tipe yang didukung.
* `domain`: Nama Domain-nya (misal, `Identity`, `Account`, `System`).
* `name`: Nama kelasnya. Bisa pake sub-direktori (misal, `Management/ProvisionNewUser`).

### Opsi

* `--factory`: Bikin factory database sekalian (buat Model).
* `--migration`: Bikin file migrasi database sekalian (buat Model).
* `--policy`: Bikin policy sekalian (buat Model).
* `--all`: Bikin factory, migrasi, dan policy sekaligus (buat Model).
* `--model=`: Hubungin kelas ekspor sama model Eloquent (buat Export), atau set hint `@implements` (buat Cast).

### Tipe yang Didukung

| Tipe | Direktori Output | Auto-suffix | Catatan |
|---|---|---|---|
| `model` | `Models/` | — | Support `--factory`, `--migration`, `--policy`, `--all` |
| `action` | `Actions/` | — | |
| `dto` | `DTOs/` | — | Menghasilkan kelas `readonly` |
| `enum` | `Enums/` | — | |
| `event` | `Events/` | — | |
| `listener` | `Listeners/` | — | |
| `notification` | `Notifications/` | — | |
| `policy` | `Policies/` | — | |
| `scope` | `Scopes/` | — | Implement `Illuminate\Database\Eloquent\Scope` |
| `trait` | `Traits/` | — | |
| `query` | `Queries/` | — | Untuk pembacaan data yang kompleks dan read-only |
| `provider` | `Providers/` | — | |
| `relationship-provider` | `Providers/` | — | Siap pakai untuk binding `resolveRelationUsing()` lintas-domain |
| `view-provider` | `Providers/` | — | Siap pakai dengan contoh View Composer |
| `export` | `Exports/` | — | Support `--model=` |
| `mapper` | `Integration/Mappers/` | `DataMapper` | Implement `DataPayloadMapper`; sub-direktori tetap |
| `mailable` | `Mail/` | — | |
| `cast` | `Casts/` | — | Implement `CastsAttributes`; gunakan `--model=NamaVO` untuk hint bertipe |
| `value-object` | `Support/ValueObjects/` | — | Implement `Stringable`; sub-direktori tetap |
| `registry` | `Support/Registry/` | `Registry` | Registry statis dengan `register()`, `all()`, `flush()`; sub-direktori tetap |
| `observer` | `Observers/` | `Observer` | Observer Eloquent dengan hook `created`, `updated`, `deleted`, `restored` |
| `integration-interface` | `Support/Integration/` | — | Scaffold `interface` PHP; sub-direktori tetap |

---

## 2. Perintah `domain:datatable`

Menghasilkan class service Yajra DataTable di dalam `app/Http/DataTables/{Domain}/` dan index Blade view yang sesuai di dalam `resources/views/pages/{domainLower}/{capability}/`.

### Signature

```bash
php artisan domain:datatable {name} {domain} [--model=]
```

---

## 3. Perintah `domain:make-page`

Menghasilkan Blade view atau komponen Livewire modal yang terpadu di dalam `resources/views/pages/{domain}/{capability}/`.

### Signature

```bash
php artisan domain:make-page {domain} {capability} {name} [--modal]
```

* Gunakan `--modal` untuk menghasilkan "Lightning Component" (⚡) yang mencakup class PHP dan Blade view di direktori yang sama.

---

## 4. Perintah `domain:new`

Perintah ini bakal bikin struktur domain baru dan otomatis nyediain `ServiceProvider` utama (udah ada trait `RegistersDomainEvents`) dan `RelationshipServiceProvider` (khusus buat mapping `Model::resolveRelationUsing()`), terus otomatis didaftarin ke `bootstrap/providers.php`.

### Signature

```bash
php artisan domain:new {domain}
```

---

## 5. Tujuan & Penggunaan Domain Providers

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

---

## 6. Custom Stub (Template Kode)

Semua template `domain:make` ada di folder `app/Console/stubs/domain-make/` dalam bentuk file `.stub`. Kamu bebas edit file-file ini kalo mau ngerubah *boilerplate* default-nya biar makin pas sama kebutuhan proyekmu.

---

## 7. Strategi Pengujian (Testing) Pest PHP

Kita pake [Pest PHP](https://pestphp.com) buat ngetes kode. Silakan cek [TESTING.md](../TESTING.md) buat referensi perintah cepat.

### Menjalankan Tes

| Perintah | Deskripsi |
|---|---|
| `composer test` | Jalanin semua tes. |
| `php artisan test --testsuite=Feature` | Jalanin tes Feature aja. |
| `php artisan test --testsuite=Unit` | Jalanin tes Unit aja. |
| `php artisan test --testsuite=Architecture` | Jalanin tes integritas arsitektur. |
| `php artisan test tests/Feature/Identity/LoginTest.php` | Jalanin file tes spesifik. |
| `php artisan test --filter=test_user_can_login` | Jalanin tes berdasarkan nama spesifik. |

### Struktur Direktori Pengujian

Tes mengikuti struktur direktori ketat yang mencerminkan arsitektur berbasis domain:

- `tests/Feature/`: Fitur aplikasi tingkat tinggi. Berinteraksi dengan aplikasi layaknya user (permintaan HTTP, komponen Livewire, dsb).
- `tests/Unit/`: Logika tingkat rendah, helper, dan logika spesifik domain yang harus cepat tanpa dependensi eksternal.
- `tests/Architecture/`: Memastikan integritas Modular Monolith (misal, Domain tidak boleh mengimpor dari HTTP).

### Menulis Tes Domain

Pas ngetes fitur Domain baru, buat file tes yang sesuai di direktori `tests/Feature/` atau `tests/Unit/`.

**Contoh:** Kalau kamu buat `app/Domains/Identity/Actions/CreateUser.php`, bikin tes di `tests/Unit/Domains/Identity/Actions/CreateUserTest.php`.

### Tes Event

```php
use App\Domains\Identity\Events\UserLoggedIn;
use Illuminate\Support\Facades\Event;

it('dispatches the user logged in event', function () {
    Event::fake();

    // Lakukan aksi
    $this->post('/login', [...]);

    Event::assertDispatched(UserLoggedIn::class);
});
```

### Tes Job

```php
use App\Domains\System\Jobs\NotifyExportReady;
use Illuminate\Support\Facades\Queue;

it('queues the export notification job', function () {
    Queue::fake();

    // Lakukan aksi
    // ...

    Queue::assertPushed(NotifyExportReady::class);
});
```

### Tes Notifikasi

```php
use App\Domains\Identity\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

it('sends a welcome notification', function () {
    Notification::fake();

    // Lakukan aksi
    // ...

    Notification::assertSentTo($user, WelcomeNotification::class);
});
```

### Mocking Injected Services

Buat service atau class yang di-inject lewat Dependency Injection, gunakan `mock()` atau `spy()`:

```php
use App\Domains\System\Integration\ExternalApiService;

it('uses the external api service', function () {
    $this->mock(ExternalApiService::class, function ($mock) {
        $mock->shouldReceive('call')
            ->once()
            ->andReturn(['status' => 'success']);
    });

    // Jalankan kode
});
```

### Aturan Arsitektur

Tes arsitektur ini mencegah "spaghetti code." Aturan utama ada di `tests/Architecture/`:

- **Isolasi Layer:** Direktori `Domains/` tidak boleh mengimpor apapun dari `Http/` atau `Livewire/`.
- **Debugging:** Pastikan panggilan `dd()` atau `dump()` tidak ter-commit ke repository.

### Praktik Terbaik (Best Practices)

- **Tes Perilaku, Bukan Implementasi:** Fokus pada apa yang dilakukan kode, bukan bagaimana cara kerjanya.
- **Tes Cepat:** Tes Unit harus selesai dalam hitungan milidetik.
- **Gunakan Factories:** Pake factory model Laravel buat bikin data tes dibandingin bikin data record manual.
- **Mock Service Eksternal:** Pake DI dan antarmuka untuk me-mock panggilan API eksternal.
- **Gunakan `Event::fake()`, `Queue::fake()`, `Notification::fake()`** buat memisahkan (isolate) side effects pada semua tes Feature.
