@use(App\UI\Enums\InputType)
<x-modal id="update-setting-modal" :title="$this->title" form wire:submit="save" livewire>
    <div class="row">
        <div class="col-sm-12" wire:key="{{ $settingKey }}">
            @if($this->settingEnum)
                <x-dynamic-component :component="$this->settingEnum?->inputType()->component()"
                                     :attributes="$attributes->merge($this->settingEnum?->inputAttributes())"
                                     wire:model="settingValue"/>
            @endif
        </div>
    </div>
    <x-slot:footer>
        <x-button theme="success" type="submit" :label="__('ui.button.update')"/>
        <x-button theme="secondary" data-bs-dismiss="modal" :label="__('ui.button.cancel')"/>
    </x-slot:footer>
    @push('scripts')
        @filepondScripts
    @endpush
</x-modal>
