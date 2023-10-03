<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">
    <div wire:loading.flex>
        <x-placeholder.modal />
    </div>
    <div wire:loading.remove>
        <x-group.form.line-input label="Name" wire:model="form.name" />
        <x-group.form.line-input label="Email" wire:model="form.email" />
        <x-slot:button>
            <x-element.button.primary type=" submit">Save</x-element.button.primary>
        </x-slot:button>
    </div>
</x-container.modal>
