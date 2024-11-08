<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" class="backdrop-blur" persistent>
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <x-input :label="__('locale/user.field.name')" wire:model="form.name" />
            <x-input :label="__('locale/user.field.email')" wire:model="form.email" />
            <x-markdown wire:model="form.bio" label="Description" />
            <x-radio :label="__('locale/user.field.gender')" wire:model="form.gender" :options="$gender" />
            <x-choices-offline :label="__('locale/user.field.province')" :options="$this->provinces" option-value="name" option-label="name"
                label="Pilih Provinsi" wire:model.live="form.province" single searchable />
            <x-choices-offline :label="__('locale/user.field.city')" :options="$this->cities" option-value="name" option-label="name"
                label="Pilih Kabupaten" wire:model.live="form.city" single searchable />
            <x-choices-offline :label="__('locale/user.field.district')" :options="$this->districts" option-value="name" option-label="name"
                label="Pilih Kecamatan" wire:model.live="form.district" single searchable />
            <x-choices-offline :label="__('locale/user.field.village')" :options="$this->villages" option-value="name" option-label="name"
                label="Pilih Desa" wire:model="form.village" single searchable />

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button label="Save" type="submit" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
        <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    @endpush
</div>
