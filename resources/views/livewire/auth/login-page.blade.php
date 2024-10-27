<div class="flex justify-center items-center">
    <x-card shadow separator progress-indicator="send" title="Login" subtitle="Login to your account.">
        <x-form method="POST" wire:submit="send">
            <x-input label="Email" wire:model="form.email" icon="o-envelope" inline />
            <x-input label="Password" wire:model="form.password" icon="o-lock-closed" type="password" inline />

            <x-slot:actions>
                <x-button :label="__('Not have an account?')" class="btn-ghost" :link="route('register')" />
                <x-button :label="__('Login')" type="submit" spinner="send" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
