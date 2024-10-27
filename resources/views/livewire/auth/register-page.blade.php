<div class="flex justify-center items-center">
    <x-card shadow separator progress-indicator="send" title="Register" subtitle="Create new account to access this app.">
        <x-form wire:submit="register">
            <x-input label="Name" wire:model="form.name" icon="o-user" inline />
            <x-input label="Email" wire:model="form.email" icon="o-envelope" inline />
            <x-input label="Password" wire:model="form.password" icon="o-lock-closed" type="password" inline />
            <x-input label="Password" wire:model="form.password_confirmation" icon="o-lock-closed" type="password" inline />

            <x-slot:actions>
                <x-button :label="__('Already have an account?')" class="btn-ghost" :link="route('login')" />
                <x-button :label="__('Register')" type="submit" spinner="send" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
