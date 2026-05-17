<x-modal id="user-form-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <x-form.input name="name" wire:model="name" :label="__('domains/identity.fields.user.name')" />
    <x-form.input name="email" wire:model="email" :label="__('domains/identity.fields.user.email')" />
    <x-form.select name="role_name" wire:model="role_name" :label="__('domains/identity.fields.role.name')">
        @foreach ($this->roles as $role)
            <option value="{{ $role }}">{{ $role }}</option>
        @endforeach
    </x-form.select>

    @unless ($id)
        <x-form.input name="password" wire:model="password" :label="__('domains/identity.fields.user.password')" />
        <x-form.input name="password_confirmation" wire:model="password_confirmation" :label="__('domains/identity.fields.user.password_confirmation')" />
    @endunless

    <x-slot:footer>
        <x-button type="button" theme="secondary" :label="__('ui.button.cancel')" data-bs-dismiss="modal" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>
</x-modal>
