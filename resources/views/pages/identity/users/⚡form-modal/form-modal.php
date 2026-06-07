<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Identity\Actions\Users\ProvisionNewUser;
use App\Domains\Identity\Actions\Users\UpdateUser;
use App\Domains\Identity\DTOs\Users\ProvisionUserDTO;
use App\Domains\Identity\DTOs\Users\UpdateUserDTO;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public ?int $id = null;

    #[Validate(as: 'domains/identity.fields.user.email')]
    public ?string $email = null;

    #[Validate(as: 'domains/identity.fields.user.name')]
    public ?string $name = null;

    #[Validate(as: 'domains/identity.fields.role.name')]
    public ?string $role_name = null;

    #[Validate(as: 'domains/identity.fields.user.password')]
    public ?string $password = null;

    #[Validate(as: 'domains/identity.fields.user.password_confirmation')]
    public ?string $password_confirmation = null;

    #[Locked]
    public string $mode = 'create';

    protected string $resourceName = 'user';

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($this->id)],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($this->id)],
            'role_name' => ['required'],
            'password' => [Password::default(), 'required', 'confirmed'],
        ];

        if (isset($this->id)) {
            unset($rules['password']);
        }

        return $rules;
    }

    #[Computed]
    public function roles(): Collection
    {
        return Role::orderBy('name', 'asc')->get(['name'])->pluck('name', 'name');
    }

    public function save(ProvisionNewUser $create, UpdateUser $update): void
    {
        $this->validate();

        if ($this->mode === 'create') {
            $create->execute(new ProvisionUserDTO(
                name: $this->name,
                email: $this->email,
                password: $this->password,
                role: $this->role_name,
            ));
        } elseif ($this->mode === 'update') {
            $update->execute($this->user, new UpdateUserDTO(
                name: $this->name,
                email: $this->email,
                role: $this->role_name,
            ));
        }

        $this->success($this->message);
        $this->dispatch('hide-user-form-modal');
        $this->js("LaravelDataTables['user-table'].ajax.reload()");
    }

    #[Computed]
    public function user(): ?User
    {
        return $this->id ? User::findOrFail($this->id) : null;
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
        $this->mode = 'update';
        $this->fill($this->user);
        $this->role_name = $this->user->role_name;
    }

    public function hide(): void
    {
        $this->reset();
        $this->resetValidation();
    }
};
