<x-modal id="update-password-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <x-form.input name="current_password" type="password" :label="__('domains/identity.fields.user.current_password')" wire:model="current_password" />
    <x-form.input name="new_password" type="password" :label="__('domains/identity.fields.user.new_password')" wire:model="new_password" />
    <x-form.input name="new_password_confirmation" type="password" :label="__('domains/identity.fields.user.password_confirmation')"
        wire:model="new_password_confirmation" />

    <x-slot:footer>
        <x-button type="button" data-bs-dismiss="modal" :label="__('ui.button.close')" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>
</x-modal>
