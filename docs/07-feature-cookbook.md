# Cookbook: How to Use Core Features

This guide provides step-by-step, practical examples for using the core features of the Antigravity architecture.

---

## 1. Creating a New Domain Feature

**Scenario:** We need a feature to ban a user in the `Identity` domain.

**Step 1: Create the DTO**
We need a DTO to hold the incoming request data (e.g., the reason for banning).
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

**Step 2: Create the Action**
The action performs the database mutation.
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

**Step 3: Call it from the Gateway**
In your HTTP Controller or Livewire component:
```php
public function store(Request $request, User $user, BanUser $action)
{
    $validated = $request->validate(['reason' => 'required|string']);

    $action->execute($user, new BanUserDTO(
        reason: $validated['reason'],
        bannedByAdminId: auth()->id(),
    ));

    return redirect()->back()->with('success', 'User banned successfully.');
}
```

---

## 2. Managing Files with `HasFile`

**Scenario:** Attaching a profile picture to a User, and displaying it.

**Step 1: Setup the Model**
Ensure the model uses the `HasFile` trait and defines the relationship.
```php
use App\Domains\System\Concerns\HasFile;

class User extends Model {
    use HasFile;

    public function avatar() {
        return $this->singleFile('avatar'); // 'avatar' is the relation_name
    }
}
```

**Step 2: Uploading/Replacing the File**
Use the System domain's base actions to handle the upload cleanly.
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

**Step 3: Displaying the File in Blade**
Use the domain helper or the relation to safely get the URL.
```blade
@if($user->avatar)
    <img src="{{ $user->avatar->url() }}" alt="Avatar">
@else
    <img src="{{ asset_static('images/default-avatar.png') }}" alt="Default Avatar">
@endif
```

---

## 3. Translating Content with `HasTranslation`

**Scenario:** You have a `Product` model that needs an English and Indonesian title.

**Step 1: Save Translations**
The trait handles saving to the `product_translations` table automatically.
```php
$product = new Product();

// Save multiple languages at once via array notation
$product->fill([
    'en' => ['title' => 'Laptop'],
    'id' => ['title' => 'Komputer Jinjing'],
]);
$product->save();
```

**Step 2: Retrieve Translations**
When you access the property, it automatically resolves based on `App::getLocale()`.
```php
App::setLocale('id');
echo $product->title; // Outputs: "Komputer Jinjing"

App::setLocale('en');
echo $product->title; // Outputs: "Laptop"
```

---

## 4. Building Dynamic UIs with Renderable Enums

**Scenario:** A settings page where different setting types require different input fields (Text, Toggle, Select).

**Step 1: Create the Enum**
```php
namespace App\UI\Enums;

enum InputType: string
{
    case TEXT = 'text';
    case TOGGLE = 'toggle';
    case SELECT = 'select';

    // Returns the name of the Blade component
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

**Step 2: Render in Blade dynamically**
```blade
<!-- Iterating over settings in a Livewire or Blade view -->
@foreach($settings as $setting)
    <div class="form-group">
        <label>{{ $setting->name }}</label>
        
        <!-- Dynamically resolve the correct component without if/else spaghetti -->
        <x-dynamic-component 
            :component="$setting->inputType->getComponentView()" 
            wire:model="form.{{ $setting->key }}" 
        />
    </div>
@endforeach
```

---

## 5. Using the Excel Engine

**Scenario:** Adding an export button to your Livewire DataTable.

**Step 1: Generate the Export Class**
```bash
php artisan domain:make export Identity UserExport --model=User
```

**Step 2: Define the Export Logic (Domain Layer)**
```php
namespace App\Domains\Identity\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromQuery, WithHeadings
{
    public function query()
    {
        // Must return a query builder, NOT a collection, for chunking to work
        return User::query()->select('id', 'name', 'email', 'created_at');
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Joined At'];
    }
}
```

**Step 3: Add to DataTable View**
In your `resources/views/pages/identity/users/index.blade.php`:
```blade
<livewire:datatables.excel-manager
    :export-class="\App\Domains\Identity\Exports\UserExport::class"
    resource-name="users"
/>
```

**Step 4: Trigger from DataTable**
In your DataTable PHP class, add a button that dispatches the Livewire event:
```php
Button::make('excel')
    ->text('Export to Excel')
    ->action("Livewire.dispatch('export-excel')"),
```
When clicked, the export runs in the background queue, applies beautiful styling automatically via `StyledExport`, and emails the user when ready.
