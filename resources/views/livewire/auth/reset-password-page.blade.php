<form wire:submit="store">
    @csrf

    <x-group.form.text-input type="email" wire:model="email" label="Email" />
    <x-group.form.text-input type="password" wire:model="password" label="Password" />
    <x-group.form.text-input type="password" wire:model="password_confirmation" label="Confirm Password" />

    <div class="flex items-center justify-end mt-4">
        <x-element.button.primary-button>
            {{ __('Reset Password') }}
        </x-element.button.primary-button>
    </div>
</form>
