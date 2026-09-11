# Cookbook: Cara Menggunakan Fitur Inti

Panduan ini berisi contoh langkah demi langkah yang praktis untuk menggunakan fitur-fitur inti dari arsitektur Antigravity.

---

## 1. Bikin Fitur Domain Baru

**Skenario:** Kita butuh fitur buat nge-ban user di domain `Identity`.

**Langkah 1: Bikin DTO**
Kita butuh DTO buat nampung data dari request (misal, alasan ban).
```bash
php artisan domain:make dto Identity Security/BanUserDTO
```
```php
// app/Domains/Identity/DTOs/Security/BanUserDTO.php
namespace App\Domains\Identity\DTOs\Security;

readonly class BanUserDTO
{
    public function __construct(
        public string $reason,
        public int $bannedByAdminId,
    ) {}
}
```

**Langkah 2: Bikin Action**
Action ini yang bakal ngeksekusi mutasi database-nya.
```bash
php artisan domain:make action Identity Security/BanUser
```
```php
// app/Domains/Identity/Actions/Security/BanUser.php
namespace App\Domains\Identity\Actions\Security;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\DTOs\Security\BanUserDTO;
use App\Domains\Identity\Events\Security\UserWasBanned;

class BanUser
{
    public function execute(User $user, BanUserDTO $dto): User
    {
        $user->update([
            'is_banned' => true,
            'banned_reason' => $dto->reason,
            'banned_by' => $dto->bannedByAdminId,
        ]);

        UserWasBanned::dispatch($user);

        return $user;
    }
}
```

**Langkah 3: Panggil dari Gateway**
Di HTTP Controller atau Livewire component kamu:
```php
public function store(Request $request, User $user, BanUser $action)
{
    $validated = $request->validate(['reason' => 'required|string']);

    $action->execute($user, new BanUserDTO(
        reason: $validated['reason'],
        bannedByAdminId: auth()->id(),
    ));

    return redirect()->back()->with('success', 'User berhasil di-ban.');
}
```

---

## 2. Kelola File Pake `HasFile`

**Skenario:** Nambahin foto profil ke User dan nampilinnya.

**Langkah 1: Setup Model**
Pastiin modelnya pake trait `HasFile` dan ada relasinya.
```php
use App\Domains\System\Concerns\HasFile;

class User extends Model {
    use HasFile;

    public function avatar() {
        return $this->singleFile('avatar'); // 'avatar' itu relation_name
    }
}
```

**Langkah 2: Upload/Ganti File**
Pake action bawaan dari domain System biar rapi.
```php
use App\Domains\System\Actions\Files\ReplaceSingleFile;
use App\Domains\System\DTOs\FileDTO;

public function uploadAvatar(Request $request, User $user, ReplaceSingleFile $action)
{
    $action->execute(
        newFile: $request->file('avatar'),
        dto: new FileDTO(
            modelType: $user->getMorphClass(),
            modelId: $user->id,
            relationName: 'avatar',
            disk: 'public',
            directory: 'avatars',
        )
    );
}
```

**Langkah 3: Tunjukin Filenya di Blade**
Pake relasinya buat dapetin URL dengan aman.
```blade
@if($user->avatar)
    <img src="{{ $user->avatar->url() }}" alt="Avatar">
@else
    <img src="{{ asset_static('images/default-avatar.png') }}" alt="Avatar Default">
@endif
```

---

## 4. Bikin UI Dinamis pake Renderable Enums

**Skenario:** Halaman settingan yang tipe input-nya beda-beda (Text, Toggle, Select).

**Langkah 1: Bikin Enum-nya**
```php
namespace App\UI\Enums;

enum InputType: string
{
    case TEXT = 'text';
    case TOGGLE = 'toggle';
    case SELECT = 'select';

    // Balikin nama komponen Blade-nya
    public function getComponentView(): string
    {
        return match($this) {
            self::TEXT => 'inputs.text-field',
            self::TOGGLE => 'inputs.toggle-switch',
            self::SELECT => 'inputs.select-dropdown',
        };
    }
}
```

**Langkah 2: Render dinamis di Blade**
```blade
<!-- Looping settingan di view Livewire/Blade -->
@foreach($settings as $setting)
    <div class="form-group">
        <label>{{ $setting->name }}</label>
        
        <!-- Render komponen otomatis tanpa if/else yang ribet -->
        <x-dynamic-component 
            :component="$setting->inputType->getComponentView()" 
            wire:model="form.{{ $setting->key }}" 
        />
    </div>
@endforeach
```

---

## 5. Pake Mesin Ekspor Excel

**Skenario:** Nambahin tombol ekspor di DataTable Livewire kamu.

**Langkah 1: Generate Kelas Ekspor-nya**
```bash
php artisan domain:make export Identity UserExport --model=User
```

**Langkah 2: Isi Logika Ekspor (Domain Layer)**
```php
namespace App\Domains\Identity\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromQuery, WithHeadings
{
    public function query()
    {
        // Harus balikin query builder, BUKAN collection, biar chunking jalan
        return User::query()->select('id', 'name', 'email', 'created_at');
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Email', 'Tanggal Gabung'];
    }
}
```

**Langkah 3: Pasang di View DataTable**
Di file `resources/views/pages/identity/users/index.blade.php`:
```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    resource-name="users"
/>
```

**Langkah 4: Panggil dari DataTable**
Di kelas PHP DataTable kamu, tambahin tombol buat ngirim event Livewire:
```php
Button::make('excel')
    ->text('Ekspor ke Excel')
    ->action("Livewire.dispatch('export-excel')"),
```
Pas diklik, ekspor bakal jalan di *background queue*, otomatis dikasih gaya visual cantik lewat `StyledExport`, dan dikirim ke email user kalau udah selesai.
