<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">

    <x-element.layout.vertical name="form.name" label="Name">
        <x-element.input.line wire:model="form.name" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.email" label="Email">
        <x-element.input.line wire:model="form.email" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.role" label="Role">
        <x-element.select.dropdown wire:model="form.role">
            <option></option>
            @foreach ($this->roles as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </x-element.select.dropdown>
    </x-element.layout.vertical>

    <x-slot:button>
        <x-element.button.primary wire:loading.attr="disabled" type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
