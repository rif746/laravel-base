<x-modal id="role-form-modal" :title="$this->title" wire:submit="save" size="modal-xl" wire:loading form livewire>
    <!-- Role Name & Guard Inputs -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-12">
            <x-form.input
                name="form.name"
                :disabled="$id"
                :label="__('domains/identity/field.role.name')"
                wire:model="form.name"
            />
        </div>
        <div class="col-md-6 col-12">
            <x-form.select
                name="form.guard_name"
                :disabled="$id"
                :label="__('domains/identity/field.role.guard_name')"
                wire:model="form.guard_name"
            >
                <option value="web">Web</option>
                <option value="api">API</option>
            </x-form.select>
        </div>
    </div>

    <!-- Permissions Grid -->
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <label class="form-label fw-bold text-body mb-0">
                {{ __('domains/identity/field.role.permissions') }}
            </label>
            <span class="text-body-secondary fs-7"> Select permissions to assign to this role </span>
        </div>

        <div class="row g-3">
            @foreach ($this->permissions->groupBy('group') as $groupName => $group)
                @php
                    $groupSlug = \Illuminate\Support\Str::slug($group->first()->group);
                @endphp

                <div
                    class="col-md-6 col-lg-4 col-12"
                    x-data="{
                        allChecked: false,

                        updateHeaderState() {
                            this.$nextTick(() => {
                                const checkboxes = Array.from(
                                    $el.querySelectorAll('input[type=\'checkbox\']:not(.group-header-checkbox)'),
                                );
                                if (checkboxes.length > 0) {
                                    this.allChecked = checkboxes.every((cb) => cb.checked);
                                }
                            });
                        },

                        toggleAll(checked) {
                            const checkboxes = $el.querySelectorAll(
                                'input[type=\'checkbox\']:not(.group-header-checkbox)',
                            );
                            checkboxes.forEach((cb) => {
                                if (cb.checked !== checked) {
                                    cb.checked = checked;
                                    cb.dispatchEvent(new Event('input', { bubbles: true }));
                                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            });
                        },
                    }"
                    x-init="
                        // Run on load / modal open
                        updateHeaderState();

                        // Re-evaluate whenever Livewire morphs the DOM (e.g. edit modal loaded with DB data)
                        Livewire.hook('morph.updated', () => updateHeaderState());

                        // Modal hidden cleanup
                        const modal = document.getElementById('role-form-modal');
                        if (modal) {
                            modal.addEventListener('hidden.bs.modal', () => {
                                allChecked = false;
                            });
                            modal.addEventListener('shown.bs.modal', () => updateHeaderState());
                        }
                    "
                    @change="updateHeaderState()"
                >
                    <div class="card rounded-3 mb-0 h-100 overflow-hidden border-0 shadow-sm">
                        <!-- Group Header with Group Checkbox Toggle -->
                        <div class="card-header bg-body-tertiary border-bottom d-flex align-items-center justify-content-between px-3 py-2">
                            <div class="form-check d-flex align-items-center mb-0 gap-2">
                                <input
                                    type="checkbox"
                                    class="form-check-input group-header-checkbox my-0 cursor-pointer"
                                    id="group-toggle-{{ $groupSlug }}"
                                    x-model="allChecked"
                                    @change="toggleAll($event.target.checked)"
                                />
                                <label
                                    class="form-check-label fw-bold small text-uppercase text-primary mb-0 cursor-pointer tracking-wider"
                                    for="group-toggle-{{ $groupSlug }}"
                                >
                                    {{ str($group->first()->group)->replace('-', ' ') }}
                                </label>
                            </div>

                            <span class="badge bg-body-secondary text-body-secondary rounded-pill fs-7">
                                {{ $group->count() }}
                            </span>
                        </div>

                        <!-- Checkbox List -->
                        <div class="card-body p-2">
                            <div class="d-flex flex-column gap-1">
                                @foreach ($group as $permission)
                                    <div class="bg-body-tertiary-hover rounded p-2 transition-all">
                                        <x-form.checkbox
                                            id="form.selected_permissions.{{ $permission->name }}"
                                            :label="__($permission->description)"
                                            wire:model="form.selected_permissions"
                                            :value="$permission->name"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-slot:footer>
        <x-button type="button" theme="secondary" data-bs-dismiss="modal" :label="__('ui/button.cancel')" />
        <x-button type="submit" theme="primary" :label="__('ui/button.save')" />
    </x-slot:footer>
</x-modal>
