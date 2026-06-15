<x-modal id="user-form-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <x-form.input name="form.name" wire:model="form.name" :label="__('domains/identity.fields.user.name')" />
    <x-form.input name="form.email" wire:model="form.email" :label="__('domains/identity.fields.user.email')" />

    @unless ($id)
        <x-form.input name="form.password" wire:model="form.password" :label="__('domains/identity.fields.user.password')" />
        <x-form.input name="form.password_confirmation" wire:model="form.password_confirmation" :label="__('domains/identity.fields.user.password_confirmation')" />
    @endunless

    <x-slot:footer>
        <x-button type="button" theme="secondary" :label="__('ui.button.cancel')" data-bs-dismiss="modal" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>
</x-modal>
