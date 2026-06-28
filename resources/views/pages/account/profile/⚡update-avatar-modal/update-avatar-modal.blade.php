<x-modal id="update-avatar-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>
    <x-filepond::upload :label="__('domains/account/field.profile.avatar')" wire:model.live="file"
                        :chunk-uploads="true" chunk-size="10000" allow-revert="true" allow-remove="true"
                        allow-image-crop allow-image-resize
                        allow-image-transform
                        image-crop-aspect-ratio="1:1" image-resize-target-width="500"
                        image-resize-target-height="500"/>
    <x-slot:footer>
        <x-button type="button" data-bs-dismiss="modal" :label="__('ui.button.close')"/>
        <x-button type="submit" theme="primary" :label="__('ui.button.save')"/>
    </x-slot:footer>

    @push('scripts')
        @vite('resources/js/plugin/filepond.js')
    @endpush
</x-modal>
