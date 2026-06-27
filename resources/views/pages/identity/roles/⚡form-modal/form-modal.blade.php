<x-modal id="role-form-modal" :title="$this->title" wire:submit="save" size="modal-xl" wire:loading form livewire>

    <div class="row mb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form.input name="form.name" :disabled="$id" :label="__('domains/identity/field.role.name')" wire:model="form.name" />
                </div>
                <div class="col-12 col-md-6">
                    <x-form.select name="form.guard_name" :disabled="$id" :label="__('domains/identity/field.role.guard_name')" wire:model="form.guard_name">
                        <option value="web">Web</option>
                        <option value="api">Api</option>
                    </x-form.select>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label font-bold">{{ __('domains/identity/field.role.permissions') }}</label>
        <div class="row g-3">
            @foreach ($this->permissions->groupBy('group') as $group)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-card class="shadow-none border h-100 mb-0">
                        <div class="mb-3 border-bottom pb-2">
                            <span class="fw-bold small text-uppercase text-primary">{{ $group->first()->group }}</span>
                        </div>
                        <ul class="list-unstyled mb-0">
                            @foreach ($group as $permission)
                                <li class="small d-flex align-items-center mb-1">
                                    <x-form.checkbox id="form.selected_permissions.{{ $permission->name }}"
                                        :label="__($permission->description)" wire:model="form.selected_permissions"
                                        :value="$permission->name" />
                                </li>
                            @endforeach
                        </ul>
                    </x-card>
                </div>
            @endforeach
        </div>
    </div>

    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </x-slot:footer>
</x-modal>
