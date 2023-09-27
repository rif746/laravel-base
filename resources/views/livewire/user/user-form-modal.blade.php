<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">
    <x-group.form.line-input label="Name" wire:model="form.name" value="{{ $form->name }}" />
    <x-group.form.line-input label="Email" wire:model="form.email" value="{{ $form->email }}" />
    <x-slot:button>
        <x-element.button.primary type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
