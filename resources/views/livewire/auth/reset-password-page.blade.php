<form wire:submit="store">
    @csrf

    <x-element.layout.vertical name="email" label="Email">
        <x-element.input.line wire:model="email" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="password" label="Email">
        <x-element.input.line type="password" wire:model="Password" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="password_confirmation" label="Email">
        <x-element.input.line type="password" wire:model="password_confirmation" />
    </x-element.layout.vertical>
    
    <div class="flex items-center justify-end mt-4">
        <x-element.button.primary>
            {{ __('Reset Password') }}
        </x-element.button.primary>
    </div>
</form>
