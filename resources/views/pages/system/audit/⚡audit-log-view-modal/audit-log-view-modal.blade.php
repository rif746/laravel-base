<x-modal id="audit-log-view-modal" size="modal-lg" :title="$this->title" form wire:submit="save" livewire>
    <div class="accordion accordion-flush d-flex flex-column gap-2" id="auditAccordion">
        @forelse ($this->audit as $audit)
            <div class="accordion-item border border-body-tertiary rounded-3 overflow-hidden shadow-sm">
                <!-- Accordion Header -->
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed bg-body-tertiary py-3 px-4"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#event-{{ $loop->index }}"
                        aria-expanded="false"
                        aria-controls="event-{{ $loop->index }}"
                    >
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 text-capitalize">
                                    {{ __('event.' . $audit->event) }}
                                </span>
                                <span class="fw-semibold text-body small">
                                    {{ $audit->user->name ?? __('ui/label.system') }}
                                </span>
                            </div>
                            <span class="text-body-secondary small font-monospace">
                                {{ $audit->updated_at->toUserTz()->format('d M Y | H:i') }}
                            </span>
                        </div>
                    </button>
                </h2>

                <!-- Accordion Body -->
                <div
                    id="event-{{ $loop->index }}"
                    class="accordion-collapse collapse"
                    data-bs-parent="#auditAccordion"
                >
                    <div class="accordion-body p-4 bg-body">
                        <!-- Metadata Card & Action -->
                        <div class="p-3 bg-body-tertiary border border-body-tertiary rounded-3 mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="fw-bold text-body mb-0 small text-uppercase tracking-wider">
                                    {{ __('domains/system/field.audit.metadata') }}
                                </h6>
                                <x-button
                                    size="sm"
                                    :label="__('ui/button.restore')"
                                    x-on:click="$js.restore"
                                    data-id="{{ $audit->id }}"
                                    theme="warning"
                                    icon="tabler-restore"
                                    :icon-property="['width' => 16, 'height' => 16]"
                                    class="px-2.5 py-1"
                                />
                            </div>

                            <div class="row g-2 small">
                                <div class="col-sm-4 text-body-secondary fw-medium">
                                    {{ __('resources.user') }}
                                </div>
                                <div class="col-sm-8 text-body fw-semibold mb-1 mb-sm-0">
                                    {{ $audit->user->name ?? '-' }}
                                </div>

                                <div class="col-sm-4 text-body-secondary fw-medium">
                                    {{ __('domains/system/field.audit.ip_address') }}
                                </div>
                                <div class="col-sm-8 text-body font-monospace mb-1 mb-sm-0">
                                    {{ $audit->ip_address ?? '-' }}
                                </div>

                                <div class="col-sm-4 text-body-secondary fw-medium">
                                    {{ __('domains/system/field.audit.browser') }}
                                </div>
                                <div class="col-sm-8 text-body-secondary text-break">
                                    {{ $audit->user_agent ?? '-' }}
                                </div>
                            </div>
                        </div>

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
                                @forelse ($audit->old_values as $key => $value)
                                    <tr>
                                        <td class="fw-semibold text-body px-3 py-2 position-sticky start-0 z-1 bg-body border-end shadow-sm">
                                            {{ __($translation . $key) }}
                                        </td>
                                        <td class="px-3 py-2 text-danger text-break bg-danger-subtle bg-opacity-10">
                                            {{ is_array($value) ? json_encode($value) : ($value ?? '-') }}
                                        </td>
                                        <td class="px-3 py-2 text-success text-break bg-success-subtle bg-opacity-10">
                                            @php $newValue = $audit->new_values[$key] ?? null; @endphp
                                            {{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? '-') }}
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
                </div>
            </div>
        @empty
            <div class="p-5 text-center text-body-secondary bg-body-tertiary border border-body-tertiary rounded-4">
                <x-tabler-history-off width="32" height="32" class="mb-2 opacity-50" />
                <p class="mb-0 small fw-medium">{{ __('ui/label.no_data') }}</p>
            </div>
        @endforelse
    </div>

    @script
    <script>
        $js.restore = (e) => {
            const btn = e.target.closest('[data-id]');
            const id = btn ? btn.dataset.id : null;

            if (!id) return;

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
                    return $wire.restore(id);
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
