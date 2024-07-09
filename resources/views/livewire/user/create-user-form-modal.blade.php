<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">

    <x-element.layout.vertical name="form.name" :label="__('locale/user.field.name')">
        <x-element.input.line wire:model="form.name" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.email" :label="__('locale/user.field.email')">
        <x-element.input.line wire:model="form.email" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.password" :label="__('locale/user.field.password')">
        <x-element.input.line type="password" wire:model="form.password" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.role" :label="__('locale/user.field.role')">
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
