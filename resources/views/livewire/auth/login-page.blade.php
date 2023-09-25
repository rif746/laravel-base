<form method="POST" wire:submit="send">

    <x-group.form.line-input type="email" wire:model="form.email" label="Email" />
    <x-group.form.line-input type="password" wire:model="form.password" label="Password" />

    <!-- Remember Me -->
    <div class="block mt-4">
        <x-element.select.checkbox wire:model="form.remember" />
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <x-element.link.anchor :href="route('password.request')">
                {{ __('Forgot your password?') }}
            </x-element.link.anchor>
        @endif

        <x-element.button.primary class="ml-3">
            {{ __('Log in') }}
        </x-element.button.primary>
    </div>
</form>
