<x-card shadow separator progress-indicator="send" title="Reset Password" subtitle="Reset your password here">
    <x-form method="POST" wire:submit="store">
        <x-input label="Email" readonly wire:model="email" icon="o-envelope" inline />
        <x-input label="Password" wire:model="password" icon="o-lock-closed" type="password" inline />
        <x-input label="Password Confirmation" wire:model="password_confirmation" icon="o-lock-closed" type="password" inline />

        <x-slot:actions>
            <x-button :label="__('Reset Password')" type="submit" spinner="send" />
        </x-slot:actions>
    </x-form>
</x-card>
