<x-modal id="role-form-modal" :title="$this->title" wire:submit="save" size="modal-xl" wire:loading form livewire>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <x-form.input name="name" :label="__('domains/identity.fields.role.name')" wire:model="name" />
            <x-form.select name="guard_name" :label="__('domains/identity.fields.role.guard_name')" wire:model="guard_name">
                <option value="web">Web</option>
                <option value="api">Api</option>
            </x-form.select>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <label class="form-label">{{ __('domains/identity.fields.role.permissions') }}</label>
                <div class="row gap-1">
                    @foreach ($this->permissions->groupBy('group') as $group)
                        <div class="col-md-12">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold small text-uppercase">{{ $group->first()->group }}</span>
                                </div>
                                <div class="card-body py-2">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($group as $permission)
                                            <li class="small d-flex align-items-center mb-1">
                                                <x-form.checkbox id="selected_permissions.{{ $permission->name }}"
                                                    :label="__($permission->description)" wire:model="selected_permissions"
                                                    :value="$permission->name" />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </x-slot:footer>
</x-modal>
