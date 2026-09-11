# Aturan Main (Rules of Engagement)

Dokumen ini mendefinisikan aturan main inti untuk menulis logika bisnis di dalam direktori `app/Domains/`.

---

## 1. DTO (Data Transfer Objects)

Semua data dari luar (Gateway) yang nggak bisa dipercaya 100% harus dibungkus ke DTO *readonly* dengan tipe data yang ketat sebelum masuk ke Domain.

* DTO cuma boleh berisi data yang emang mau dipake buat ubah state.
* Jangan masukin model Eloquent ke dalem DTO. Kirim Model-nya sebagai parameter terpisah pas manggil Action.
* DTO dinamain sesuai Action yang dilayanin, ditambah akhiran `DTO` (misal, `ProvisionUserDTO` buat `ProvisionNewUser`).

---

## 2. Action (Sang Eksekutor)

Action adalah **satu-satunya** tempat buat mutasi database (`create`, `update`, `delete`, `syncRoles`).

* Satu Action cuma boleh punya satu tanggung jawab (*single responsibility*).
* Pake `DB::transaction()` di dalem Action kalo ada beberapa proses tulis database yang harus sukses bareng atau gagal bareng.
* Pake **Action Composition** (masukin Action ke Action lain lewat constructor) buat pake ulang logika tanpa perlu *copy-paste* kode.

**Contoh Action Composition:**

```php
// Injecting AccessControl\UpdateUserRole into Onboarding\ProvisionNewUser
class ProvisionNewUser
{
    public function __construct(
        private readonly UpdateUserRole $updateUserRole,
    ) {}

    public function execute(ProvisionUserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $user = User::create([...]);
            $this->updateUserRole->execute($user, $dto->roleId);
            UserWasProvisioned::dispatch($user);
            return $user;
        });
    }
}
```

---

## 3. Event & Listener

Pake Arsitektur Event-Driven buat semua efek samping kayak kirim email, log, atau proses di background.

* Gateway ngirim Event buat hal-hal yang nggak ngerubah data (misal, `UserLoggedIn`).
* Action ngirim Event tepat setelah data berhasil diubah (misal, `UserWasProvisioned`).
* Listener yang nanganin reaksinya di luar proses HTTP utama. Di sini **satu-satunya** tempat boleh manggil `Notification::send()`, `Mail::send()`, atau logging sebagai respon dari Event Domain.
* Event dan Listener harus **dikelompokin di folder kapabilitas yang sama** kayak Action-nya (misal, `Events/Onboarding/`, `Listeners/Onboarding/`).

---

## 4. Audit Log & Pelacakan

Semua perubahan database yang penting dicatat biar ada riwayatnya.

* **Model Auditing:** Kita pake Eloquent event buat catat otomatis setiap ada baris data yang berubah.
* **Relasi yang Ribet:** Kalo perubahan relasi *many-to-many* (kayak `syncPermissions` Spatie) yang nggak kedeteksi Eloquent event, kita kirim Custom Audit Event manual di dalem Action-nya (misal: `UpdateRolePermissions`). Jadi status "Sebelum" dan "Sesudah" tetep kecatat rapi dalam satu transaksi.

---

## 5. Setting Global & State Aplikasi

Settingan yang nentuin status jalan aplikasi (Zona Waktu, Bahasa, Tag SEO) diurus sama domain `System` biar cepet dan kontekstual.

* **Memoization & Singletons:** Query `GetSystemSettings` didaftarin sebagai Singleton di `SystemServiceProvider`. Data diambil dari database/cache *cuma sekali* (menggunakan kunci cache yang ditentukan dalam `SystemSettings::$cacheName`) dan disimpan di memori PHP selama request itu jalan.
* **Middleware Kontekstual:** Kita pake middleware khusus (`HandlePreferredLanguage`, `HandlePreferredTimezone`) buat ngecek preferensi user, kalo nggak ada baru pake settingan global.
* **View Composers:** Variabel layout (kayak Logo atau Nama Web) disuntikkan otomatis lewat View Composers di `ViewServiceProvider` (didaftarkan oleh `SystemServiceProvider`), jadi nggak perlu ribet pake `@inject` terus-terusan.

---

## 6. Model Multi-Bahasa (Trait HasTranslation)

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
use App\Domains\System\Concerns\Model\HasTranslation;

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
