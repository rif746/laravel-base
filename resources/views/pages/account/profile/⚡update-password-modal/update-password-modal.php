<?php

use App\Concerns\Livewire\Shared\WithModal;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;

    #[Validate(as: 'domains/identity.fields.user.current_password')]
    public string $current_password;

    #[Validate(as: 'domains/identity.fields.user.new_password')]
    public string $new_password;

    #[Validate(as: 'domains/identity.fields.user.confirm_password')]
    public string $new_password_confirmation;

    protected string $mode = 'update';

    protected string $resourceName = 'password';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    public function show(int|string $id): void {}

    public function save(): void
    {
        $this->validate();
        $user = auth('web')->user();
        $user->update([
            'password' => $this->new_password,
        ]);
        $this->dispatch('hide-update-password-modal');
        $this->js("toast('".__('ui.crud.success.updated', ['resource' => __('resources.password')])."','success');");
    }

    public function hide(): void
    {
        $this->reset();
    }
};
