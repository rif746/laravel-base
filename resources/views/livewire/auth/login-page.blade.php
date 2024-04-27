<form method="POST" wire:submit="send">

    <x-element.layout.vertical name="form.email" label="Email">
        <x-element.input.line wire:model="form.email" />
    </x-element.layout.vertical>

    <x-element.layout.vertical name="form.password" label="Password">
        <x-element.input.line type="password" wire:model="form.password" />
    </x-element.layout.vertical>

    <!-- Remember Me -->
    <div class="block mt-4">
        <x-element.select.checkbox wire:model="form.remember" label="Remember Me" />
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <x-element.anchor :href="route('password.request')" class="underline">
                {{ __('Forgot your password?') }}
            </x-element.anchor>
        @endif

        <x-element.button.primary class="ml-3">
            {{ __('Log in') }}
        </x-element.button.primary>
    </div>
</form>
