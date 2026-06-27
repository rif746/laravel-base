<x-modal id="audit-view-modal" size="modal-lg" :title="$this->title" form wire:submit="save" livewire>
    <div class="accordion" id="auditAccordion">
        @forelse($this->audit as $audit)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#event-{{ $loop->index }}" aria-expanded="false" aria-controls="event-{{ $loop->index }}">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span>{{ __('event.'.$audit->event) }}</span>
                            <small>{{ $audit->updated_at->toUserTz()->format('d M Y | H:i') }}</small>
                        </div>
                    </button>
                </h2>
                <div id="event-{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div>
                            <div class="row g-3">
                                <div class="col-sm-12 col-md-4 fw-bold">{{ __('resources.user') }}</div>
                                <div class="col-sm-12 col-md-8">{{ $audit->user->name }}</div>
                                <div class="col-sm-12 col-md-4 fw-bold">{{ __('domains/system/field.audit.ip_address') }}</div>
                                <div class="col-sm-12 col-md-8">{{ $audit->ip_address }}</div>
                                <div class="col-sm-12 col-md-4 fw-bold">{{ __('domains/system/field.audit.browser') }}</div>
                                <div class="col-sm-12 col-md-8">{{ $audit->user_agent }}</div>
                                <div class="col-sm-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('domains/system/field.audit.field') }}</th>
                                                <th>{{ __('domains/system/field.audit.old') }}</th>
                                                <th>{{ __('domains/system/field.audit.new') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($audit->old_values as $key => $value)
                                            <tr>
                                                <td>{{ __($translation.$key) }}</td>
                                                <td>{{ $value }}</td>
                                                <td>{{ $audit->new_values[$key] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="accordion-item d-flex justify-content-center align-items-center p-4">
                {{ __('ui.label.no_data') }}
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <x-button theme="secondary" data-bs-dismiss="modal" :label="__('ui.button.cancel')" />
    </x-slot:footer>
</x-modal>
