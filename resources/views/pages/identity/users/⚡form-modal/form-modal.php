<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Models\Identity\Role;
use App\Models\Identity\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;

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

    protected string $mode = 'create';

    protected string $resourceName = 'user';

    public function rules()
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
    public function roles()
    {
        return Role::orderBy('name', 'asc')->get(['name'])->pluck('name', 'name');
    }

    public function save()
    {
        $this->validate();

        $update = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ((bool) $this->password) {
            $update['password'] = $this->password;
        }

        User::updateOrCreate(
            ['id' => $this->id],
            $update
        )->syncRoles($this->role_name);

        $this->dispatch('hide-user-form-modal');
        $this->js("LaravelDataTables['user-table'].ajax.reload()");
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
        $this->mode = 'update';
        $user = User::findOrFail($id);
        $this->fill($user);
        $this->role_name = $user->roles->first()?->name;
    }

    public function hide(): void
    {
        $this->reset();
        $this->resetValidation();
    }
};
