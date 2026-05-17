<div class="row justify-content-center">
    <div class="col-8">
        <div class="row">
            @foreach ($this->settings as $section => $items)
                <div class="col-6">
                    <x-card :title="$section" class="mb-4">
                        @foreach ($items as $item)
                            <div class="mb-3">
                                @if ($item->inputType() === 'file')
                                    <x-filepond::upload wire:model.live="form.{{ $item->value }}" :chunk-uploads="true"
                                        chunk-size="10000" allow-revert="true" allow-remove="true" :label="$item->label()" />
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
