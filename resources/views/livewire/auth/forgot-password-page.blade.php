<x-card title="Forgot Password" shadow separator progress-indicator="send_reset">
    <span>
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </span>

    <x-form class="mt-2" method="POST" wire:submit="send_reset">
        <x-input label="Email" wire:model="email" icon="o-envelope" inline />

        <x-slot:actions>
            <x-button :label="__('Cancel')" class="btn-ghost" type="button" :link="route('login')" />
            <x-button :label="__('Submit')" type="submit" spinner="send" />
        </x-slot:actions>
    </x-form>
</x-card>
