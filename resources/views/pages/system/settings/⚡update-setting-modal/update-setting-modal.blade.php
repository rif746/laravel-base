@use(App\UI\Enums\InputType)

<x-modal id="update-setting-modal" :title="$this->title" form wire:submit="save" livewire>
    <div class="row g-3 py-2">
        <div class="col-12" wire:key="{{ $settingKey }}">
            @if ($this->inputField)
                <div class="p-3 bg-body-tertiary border border-body-tertiary rounded-3">
                    <x-dynamic-component
                        :component="$this->inputField?->type->component()"
                        :attributes="new Illuminate\View\ComponentAttributeBag($this->inputField?->attributes)"
                        wire:model="settingValue"
                    />
                </div>
            @endif
        </div>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-end gap-2 w-100">
            <x-button
                theme="secondary"
                data-bs-dismiss="modal"
                :label="__('ui/button.cancel')"
                class="px-3"
            />
            <x-button
                theme="primary"
                type="submit"
                :label="__('ui/button.update')"
                class="px-4 fw-medium"
                wire:loading.attr="disabled"
            />
        </div>
    </x-slot:footer>

    @push('scripts')
        @filepondScripts
    @endpush
</x-modal>
