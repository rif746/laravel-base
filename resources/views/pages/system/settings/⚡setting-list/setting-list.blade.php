@use(App\UI\Enums\InputType)

<div class="container-fluid px-0">
    <div class="row g-4">
        @foreach ($this->settings as $group)
            <div class="col-12 col-xl-6">
                <div class="d-flex flex-column gap-4">
                    @foreach ($group as $title => $section)
                        <div class="card border border-body-tertiary shadow-sm rounded-4 overflow-hidden">
                            <!-- Card Header -->
                            <div class="card-header bg-body-tertiary border-bottom border-body-tertiary py-3 px-4">
                                <h2 class="card-title fw-bold text-body fs-6 mb-0">
                                    {{ $title }}
                                </h2>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                @foreach ($section as $field)
                                    <div class="p-3 bg-body-tertiary border border-body-tertiary rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold text-body-secondary small">
                                                {{ $field->label() }}
                                            </span>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary rounded-2 d-inline-flex align-items-center justify-content-center p-1"
                                                style="width: 28px; height: 28px;"
                                                data-bs-target="#update-setting-modal"
                                                data-bs-toggle="modal"
                                                data-id="{{ $field->value }}"
                                                title="{{ __('ui/button.edit') }} {{ $field->label() }}"
                                            >
                                                <x-tabler-pencil width="15" height="15" stroke-width="2" />
                                            </button>
                                        </div>

                                        <div class="text-body fw-medium">
                                            @if ($field->getSchema()->type->isFile())
                                                @if(!empty($this->settingsValue[$field->value]))
                                                    <div class="position-relative d-inline-block mt-1">
                                                        <img
                                                            class="img-fluid rounded border border-body-tertiary shadow-sm"
                                                            style="max-height: 120px; object-fit: contain;"
                                                            src="{{ $this->settingsValue[$field->value] }}"
                                                            alt="{{ $field->label() }}"
                                                        />
                                                    </div>
                                                @else
                                                    <span class="text-body-tertiary fst-italic small">{{ __('ui/common.no_file_uploaded') }}</span>
                                                @endif
                                            @else
                                                <span class="text-break fs-6">
                                                    {{ $this->settingsValue[$field->value] ?? '-' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <livewire:pages::system.settings.update-setting-modal />
</div>
