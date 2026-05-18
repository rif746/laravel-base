<div class="row justify-content-center">
    <div class="col-8">
        <div class="row">
            @foreach ($this->settings as $section => $items)
                <div class="col-6">
                    <x-card :title="$section" class="mb-4">
                        @foreach ($items as $item)
                            <div x-data="{ editMode: false }" x-on:refresh-{{ $item->value }}.window="editMode = false">
                                <div class="mb-3" x-show="!editMode" x-cloak>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="fw-bold">{{ $item->label() }}</h6>
                                        <button class="btn btn-sm btn-primary" @click="editMode = true">
                                            <x-icon name="tabler-edit" width="14" height="14" />
                                        </button>
                                    </div>
                                    @if ($item->isImage())
                                        <div class="p-2">
                                            <img src="{{ temp_asset($this->settingsValue[$item->value]) }}"
                                                alt="{{ $item->label() }}" class="w-100">
                                        </div>
                                    @else
                                        <p class="text-muted">{{ $this->settingsValue[$item->value] }}</p>
                                    @endif
                                </div>
                                <div class="mb-3" x-show="editMode" x-cloak>
                                    @if ($item->inputType() === 'file')
                                        <x-filepond::upload wire:model.live="form.{{ $item->value }}" :chunk-uploads="true"
                                            chunk-size="10000" allow-revert="true" allow-remove="true"
                                            :label="$item->label()" />
                                    @elseif ($item->inputType() == 'options')
                                        <x-form.select :label="$item->label()" :name="$item->value"
                                            wire:model="form.{{ $item->value }}">
                                            @foreach ($item->options() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </x-form.select>
                                    @else
                                        <x-form.input :label="$item->label()" :name="$item->value"
                                            wire:model="form.{{ $item->value }}" type="text" />
                                    @endif
                                    <div class="btn-group d-flex justify-content-end mt-2">
                                        <button class="btn btn-sm btn-danger" @click="editMode = false">
                                            <x-icon name="tabler-x" width="14" height="14" />
                                        </button>
                                        <button class="btn btn-sm btn-primary"
                                            @click="$wire.save('{{ $item->value }}')">
                                            <x-icon name="tabler-check" width="14" height="14" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </x-card>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        @filepondScripts
    @endpush
</div>
