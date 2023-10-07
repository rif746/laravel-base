<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">
    <x-group.form.line-input type="password" wire:model="current_password" label="Current Password" />
    <x-group.form.line-input type="password" wire:model="new_password" label="New Password" />
    <x-group.form.line-input type="password" wire:model="new_password_confirmation" label="Confirm Password" />

    <x-slot:button>
        <x-element.button.primary type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
