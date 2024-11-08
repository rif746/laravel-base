<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" class="backdrop-blur" persistent>
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <x-password :label="__('locale/user.field.current_password')" wire:model="current_password" />
            <x-password :label="__('locale/user.field.new_password')" wire:model="new_password" />
            <x-password :label="__('locale/user.field.new_password_confirmation')" wire:model="new_password_confirmation" />

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button label="Save" type="submit" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
