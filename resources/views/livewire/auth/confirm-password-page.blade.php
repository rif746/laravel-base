<x-card shadow separator progress-indicator="send" title="Confirm Password" subtitle="Confirm your password here">
    <span>
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </span>
    <x-form class="mt-3" method="POST" wire:submit="send">
        <x-input label="Password" wire:model="password" icon="o-lock-closed" type="password" inline />

        <x-slot:actions>
            <x-button :label="__('Confirm')" type="submit" spinner="send" />
        </x-slot:actions>
    </x-form>
</x-card>
