<x-modal id="role-view-modal" :title="$this->title" size="modal-xl" wire:loading livewire>

    <!-- Role Details Summary Tiles -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="p-3 rounded bg-body-tertiary border border-body-tertiary">
                <small class="text-body-secondary fw-semibold text-uppercase d-block mb-1 fs-7">
                    {{ __('domains/identity/field.role.name') }}
                </small>
                <span class="fw-bold text-body fs-6">{{ $this->role?->name ?? '—' }}</span>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="p-3 rounded bg-body-tertiary border border-body-tertiary d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-body-secondary fw-semibold text-uppercase d-block mb-1 fs-7">
                        {{ __('domains/identity/field.role.guard_name') }}
                    </small>
                    <span class="fw-semibold text-body">{{ $this->role?->guard_name ?? '—' }}</span>
                </div>
                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill font-monospace text-uppercase">
                    {{ $this->role?->guard_name }}
                </span>
            </div>
        </div>
    </div>

    <!-- Assigned Permissions Grid -->
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <label class="form-label fw-bold text-body mb-0">
                {{ __('domains/identity/field.role.permissions') }}
            </label>
            <span class="text-body-secondary fs-7">
                Assigned permissions for this role
            </span>
        </div>

        @if($this->role?->permissions->isEmpty())
            <div class="text-center py-4 rounded bg-body-tertiary">
                <x-tabler-shield-x class="text-secondary opacity-50 mb-2" width="32" height="32" />
                <p class="mb-0 text-body-secondary small">{{ __('No permissions assigned to this role.') }}</p>
            </div>
        @else
            <div class="row g-3">
                @foreach ($this->role->permissions->groupBy('group') as $groupName => $group)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 mb-0 rounded-3 overflow-hidden">
                            <!-- Group Header -->
                            <div class="card-header bg-body-tertiary border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                                <span class="fw-bold small text-uppercase text-primary cursor-pointer tracking-wider mb-0">
                                    {{ str($group->first()->group)->replace('-', ' ') }}
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-7">
                                    {{ $group->count() }}
                                </span>
                            </div>

                            <!-- Permission Items -->
                            <div class="card-body p-2">
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($group as $permission)
                                        <div class="p-2 rounded bg-body-tertiary-hover transition-all d-flex align-items-center gap-2">
                                            <span class="badge bg-success-subtle text-success rounded-circle p-1 d-flex justify-content-center flex-shrink-0">
                                                <x-tabler-check width="12" height="12" />
                                            </span>
                                            <span class="small text-body fw-medium" title="{{ __($permission->description) }}">
                                                {{ __($permission->description) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <x-slot:footer>
        <x-button type="button" theme="secondary" data-bs-dismiss="modal" :label="__('ui/button.close')" />
    </x-slot:footer>
</x-modal>
