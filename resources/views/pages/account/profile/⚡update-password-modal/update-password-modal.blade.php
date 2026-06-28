<x-modal id="update-password-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <div class="d-flex flex-column gap-3">
        <x-form.input name="form.current_password" type="password" :label="__('domains/identity/field.user.current_password')" wire:model="form.current_password" />

        <x-form.input name="form.new_password" type="password" :label="__('domains/identity/field.user.new_password')" wire:model="form.new_password" />

        <x-form.input name="form.new_password_confirmation" type="password" :label="__('domains/identity/field.user.password_confirmation')"
            wire:model="form.new_password_confirmation" />
    </div>

    <x-slot:footer>
        <x-button type="button" data-bs-dismiss="modal" :label="__('ui.button.close')" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>
</x-modal>
