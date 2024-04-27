<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">

    <x-element.layout.vertical name="current_password" label="Password">
        <x-element.input.line type="password" wire:model="current_password" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="new_password" label="New Password">
        <x-element.input.line type="password" wire:model="new_password" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="new_password_confirmation" label="New Password Confirmation">
        <x-element.input.line type="password" wire:model="new_password_confirmation" />
    </x-element.layout.vertical>

    <x-slot:button>
        <x-element.button.primary type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
