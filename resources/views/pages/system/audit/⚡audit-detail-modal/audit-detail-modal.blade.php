<x-modal id="audit-detail-modal" size="modal-lg" :title="$this->title" form wire:submit="save" livewire>
    <div class="d-flex flex-column gap-3">
        <!-- Metadata Card -->
        <div class="p-3 bg-body-tertiary border border-body-tertiary rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-body mb-0 small text-uppercase tracking-wider">
                    {{ __('domains/system/field.audit.metadata') }}
                </h6>
                <x-button
                    size="sm"
                    :label="__('ui/button.restore')"
                    x-on:click="$js.restore"
                    theme="warning"
                    icon="tabler-restore"
                    :icon-property="['width' => 16, 'height' => 16]"
                    class="px-2.5 py-1"
                />
            </div>

            <div class="row g-2 small">
                <div class="col-sm-4 text-body-secondary fw-medium">
                    {{ __('domains/system/field.audit.user_name') }}
                </div>
                <div class="col-sm-8 text-body fw-semibold mb-1 mb-sm-0">
                    {{ $this->audit?->user?->name ?? '-' }}
                </div>

                <div class="col-sm-4 text-body-secondary fw-medium">
                    {{ __('domains/system/field.audit.ip_address') }}
                </div>
                <div class="col-sm-8 text-body font-monospace mb-1 mb-sm-0">
                    {{ $this->audit?->ip_address ?? '-' }}
                </div>

                <div class="col-sm-4 text-body-secondary fw-medium">
                    {{ __('domains/system/field.audit.browser') }}
                </div>
                <div class="col-sm-8 text-body-secondary text-break">
                    {{ $this->audit?->browser ?? $this->audit?->user_agent ?? '-' }}
                </div>

                <div class="col-sm-4 text-body-secondary fw-medium">
                    {{ __('resources.'.$this->audit?->auditable_type) }}
                </div>
                <div class="col-sm-8 text-body-secondary text-break">
                    {{ $this->audit?->auditable?->name ?? $this->audit?->auditable_id ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Merge Unique Attribute Keys for Created, Updated, Deleted, and Restored Events -->
        @php
            $oldValues = $this->audit?->old_values ?? [];
            $newValues = $this->audit?->new_values ?? [];

            // Extract distinct merged keys from both old and new payload records
            $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
        @endphp

            <!-- Scrollable Audit Changes Table with Sticky Field Column -->
        <div class="table-responsive rounded-3 border border-body-tertiary" style="max-height: 350px;">
            <table class="table table-hover table-striped mb-0 align-middle small" style="min-width: 600px;">
                <thead class="table-light position-sticky top-0 z-2 shadow-sm">
                <tr>
                    <th class="py-2.5 px-3 position-sticky start-0 z-3 bg-light" style="width: 25%;">
                        {{ __('domains/system/field.audit.field') }}
                    </th>
                    <th class="py-2.5 px-3 text-danger-emphasis" style="width: 37.5%;">
                        {{ __('domains/system/field.audit.old') }}
                    </th>
                    <th class="py-2.5 px-3 text-success-emphasis" style="width: 37.5%;">
                        {{ __('domains/system/field.audit.new') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse ($allKeys as $key)
                    @php
                        $oldVal = $oldValues[$key] ?? null;
                        $newVal = $newValues[$key] ?? null;

                        // Format array payloads safely for JSON rendering
                        $formattedOld = is_array($oldVal) ? json_encode($oldVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $oldVal;
                        $formattedNew = is_array($newVal) ? json_encode($newVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $newVal;
                    @endphp
                    <tr>
                        <td class="fw-semibold text-body px-3 py-2 position-sticky start-0 z-1 bg-body border-end shadow-sm">
                            {{ __($key) }}
                        </td>
                        <td class="px-3 py-2 text-break bg-danger-subtle bg-opacity-10">
                            {{ $formattedOld ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-break bg-success-subtle bg-opacity-10">
                            {{ $formattedNew ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-3 text-body-secondary">
                            {{ __('domains/system/pages.audit.no_changes_recorded') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @script
    <script>
        $js.restore = (e) => {
            Swal.fire({
                title: '{{ __('ui/title.restore', ['resource' => __('resources.audit')]) }}',
                text: '{{ __('ui/confirmation.restore', ['resource' => __('resources.audit')]) }}',
                icon: 'warning',
                showLoaderOnConfirm: true,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: '{{ __('ui/button.restore') }}',
                cancelButtonText: '{{ __('ui/button.cancel') }}',
                customClass: {
                    confirmButton: 'btn btn-warning me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                preConfirm: () => {
                    return $wire.restore();
                },
            });
        };
    </script>
    @endscript

    <x-slot:footer>
        <div class="d-flex justify-content-end w-100">
            <x-button
                theme="secondary"
                data-bs-dismiss="modal"
                :label="__('ui/button.cancel')"
                class="px-4"
            />
        </div>
    </x-slot:footer>
</x-modal>
