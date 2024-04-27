<form wire:submit="register">


    <x-element.layout.vertical name="form.name" label="Name">
        <x-element.input.line wire:model="form.name" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.email" label="Email">
        <x-element.input.line type="email" wire:model="form.email" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.password" label="Email">
        <x-element.input.line type="password" wire:model="form.password" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.password_confirmation" label="Email">
        <x-element.input.line type="password" wire:model="form.password_confirmation" />
    </x-element.layout.vertical>

    <div class="flex items-center justify-end mt-4">
        <x-element.anchor :href="route('login')" class="underline">
            {{ __('Already have an account?') }}
        </x-element.anchor>

        <x-element.button.primary class="ml-4">
            {{ __('Register') }}
        </x-element.button.primary>
    </div>
</form>
