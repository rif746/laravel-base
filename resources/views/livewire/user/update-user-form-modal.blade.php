<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" class="backdrop-blur">
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <x-input :label="__('locale/user.field.name')" wire:model="form.name" />
            <x-input :label="__('locale/user.field.email')" wire:model="form.email" type="email" />
            <x-group :label="__('locale/user.field.role')" wire:model="form.role_name"
                :options="$this->roles" option-value="name" option-label="name" />
            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button label="Save" type="submit" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
