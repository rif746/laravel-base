<x-modal id="role-selection-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <x-form.select x-select2="{
        dropdownParent: $('#role-selection-modal')
    }" name="form.role_name" wire:model="role" :label="__('domains/identity/field.role.name')">
        @foreach ($this->roles as $role)
            <option value="{{ $role }}">{{ $role }}</option>
        @endforeach
    </x-form.select>

    <x-slot:footer>
        <x-button type="button" theme="secondary" :label="__('ui.button.cancel')" data-bs-dismiss="modal" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>

    @push('scripts')
        @vite(['resources/js/plugin/select2.js'])
    @endpush
</x-modal>
