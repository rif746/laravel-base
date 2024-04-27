<x-container.modal maxWidth="sm" :name="$this->modal_name" :title="$this->title" method="save">

    <x-element.layout.vertical name="form.name" label="Name">
        <x-element.input.line wire:model="form.name" />
    </x-element.layout.vertical>
    <x-element.layout.vertical name="form.permission" label="Permission">
        <ul class="flex flex-col gap-1">
            @foreach ($this->permissions as $permission)
                <li class="flex flex-row gap-2 px-2 border rounded">
                    <x-element.select.checkbox wire:model='form.permissions' value="{{ $permission->id }}" />
                    <div class="flex flex-col">
                        <span>{{ $permission->name }}</span>
                        <span>{{ $permission->description }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </x-element.layout.vertical>

    <x-slot:button>
        <x-element.button.primary wire:loading.attr="disabled" type="submit">Save</x-element.button.primary>
    </x-slot:button>
</x-container.modal>
