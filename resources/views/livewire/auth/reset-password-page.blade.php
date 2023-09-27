<form wire:submit="store">
    @csrf

    <x-group.form.line-input type="email" wire:model="email" label="Email" />
    <x-group.form.line-input type="password" wire:model="password" label="Password" />
    <x-group.form.line-input type="password" wire:model="password_confirmation" label="Confirm Password" />

    <div class="flex items-center justify-end mt-4">
        <x-element.button.primary>
            {{ __('Reset Password') }}
        </x-element.button.primary>
    </div>
</form>
