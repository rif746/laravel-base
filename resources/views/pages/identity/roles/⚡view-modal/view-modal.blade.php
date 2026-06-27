<x-modal id="role-view-modal" :title="$this->title" size="modal-xl" wire:loading livewire>
    <div class="row mb-4">
        <div class="col-12">
            <div class="row mx-4">
                <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">
                    {{ __('domains/identity/field.role.name') }}</div>
                <div class="col-sm-12 col-md-8 border-bottom px-4 py-2">{{ $this->role->name }}</div>

                <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">
                    {{ __('domains/identity/field.role.guard_name') }}</div>
                <div class="col-sm-12 col-md-8 border-bottom px-4 py-2">{{ $this->role->guard_name }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @foreach ($this->role->permissions->groupBy('group') as $group)
            <div class="col-12 col-md-6 col-lg-4">
                <x-card class="shadow-none border h-100 mb-0">
                    <div class="mb-3 border-bottom pb-2">
                        <span class="fw-bold small text-uppercase text-primary">{{ $group->first()->group }}</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @foreach ($group as $permission)
                            <li class="small d-flex align-items-center mb-1">
                                <span>
                                    <x-tabler-check class="text-success me-2 mt-1" width="16" height="16" />
                                </span>
                                <span class="text-muted">{{ __($permission->description) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            </div>
        @endforeach
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot:footer>
</x-modal>
