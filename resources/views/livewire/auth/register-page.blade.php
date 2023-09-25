<form wire:submit="register">

    <x-group.form.line-input type="text" wire:model="form.name" label="Name" />
    <x-group.form.line-input type="email" wire:model="form.email" label="Email" />
    <x-group.form.line-input type="password" wire:model="form.password" label="Password" />
    <x-group.form.line-input type="password" wire:model="form.password_confirmation" label="Password Confirmation" />

    <div class="flex items-center justify-end mt-4">
        <x-element.link.anchor :href="route('login')">
            {{ __('Already have an account?') }}
        </x-element.link.anchor>

        <x-element.button.primary class="ml-4">
            {{ __('Register') }}
        </x-element.button.primary>
    </div>
</form>
