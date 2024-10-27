<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" class="backdrop-blur" persistent>
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <x-input :label="__('locale/role.field.name')" wire:model="form.name" />
            <div class="py-2">
                <label for="permission" class="pt-0 label label-text font-semibold">
                    {{ __('locale/role.field.permission') }}
                </label>
                <div name="permission" class=" border rounded-md m-1 border-primary">
                    @foreach ($this->permissions as $permission)
                        <x-list-item :item="$permission">
                            <x-slot:avatar>
                                <x-checkbox wire:model="form.permissions" :value="$permission->id" />
                            </x-slot:avatar>
                            <x-slot:sub-value>
                                {{ $permission->description }}
                            </x-slot:sub-value>
                        </x-list-item>
                    @endforeach
                </div>
            </div>
            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button label="Save" type="submit" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
