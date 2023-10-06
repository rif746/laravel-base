<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">
    <x-group.form.line-input wire:loading.attr="disabled" label="Name" wire:model="form.name" />
    <x-group.form.line-input wire:loading.attr="disabled" label="Email" wire:model="form.email" />
    <x-slot:button>
        <x-element.button.primary wire:loading.attr="disabled" type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
