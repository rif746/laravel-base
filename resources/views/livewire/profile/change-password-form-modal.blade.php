<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" class="backdrop-blur" persistent>
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <x-password :label="__('Password')" wire:model="current_password" />
            <x-password :label="__('New Password')" wire:model="new_password" />
            <x-password :label="__('New Password Confirmation')" wire:model="new_password_confirmation" />

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button label="Save" type="submit" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
