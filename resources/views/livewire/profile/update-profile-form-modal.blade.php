<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">

    <x-element.layout.vertical name="form.name" label="Name">
        <x-element.input.line wire:model="form.name" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.email" label="Email">
        <x-element.input.line wire:model="form.email" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.gender" :label="__('locale/user.field.gender')">
        <x-element.select.dropdown wire:model="form.gender">
            <option></option>
            <option value="{{ \App\Enum\GenderType::MALE }}">{{ \App\Enum\GenderType::MALE->label() }}</option>
            <option value="{{ \App\Enum\GenderType::FEMALE }}">{{ \App\Enum\GenderType::FEMALE->label() }}</option>
        </x-element.select.dropdown>
    </x-element.layout.vertical>

    <x-slot:button>
        <x-element.button.primary type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
