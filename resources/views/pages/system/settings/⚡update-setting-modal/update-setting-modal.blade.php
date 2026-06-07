@use(App\Domains\System\Enums\InputType)
<x-modal id="update-setting-modal" :title="$this->title" form wire:submit="save" livewire>
    <div class="row">
        <div class="col-sm-12" wire:key="{{ $settingKey }}">
            @if ($this->settingEnum?->inputType() == InputType::FILE)
                <x-filepond::upload wire:model="settingValue" :label="$this->settingEnum?->label()" allow-image-crop allow-image-resize
                    allow-image-transform image-crop-aspect-ratio="1:1" image-resize-target-width="500"
                    image-resize-target-height="500" />
            @elseif($this->settingEnum?->inputType() == InputType::SELECT)
                <x-form.select :label="$this->settingEnum?->label()" :name="$this->settingEnum?->value" wire:model="settingValue">
                    @foreach ($this->settingEnum?->options() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-form.select>
            @else
                <x-form.input :label="$this->settingEnum?->label()" wire:model="settingValue" />
            @endif
        </div>
    </div>
    <x-slot:footer>
        <x-button theme="success" type="submit" :label="__('ui.button.update')" />
        <x-button theme="secondary" data-bs-dismiss="modal" :label="__('ui.button.cancel')" />
    </x-slot:footer>
    @push('scripts')
        @filepondScripts
    @endpush
</x-modal>
