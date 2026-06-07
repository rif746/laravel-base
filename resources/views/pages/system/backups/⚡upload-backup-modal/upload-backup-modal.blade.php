<x-modal id="backup-file-upload-modal" :title="__('domains/system.pages.backup.upload_modal_title')" form wire:submit="save" livewire>
    <div class="row">
        <div class="col-sm-12">
            <x-filepond::upload wire:model="file" :label="__('domains/system.fields.backup.file')" />
        </div>
    </div>
    <x-slot:footer>
        <x-button theme="success" type="submit" :label="__('ui.button.upload')" />
        <x-button theme="secondary" data-bs-dismiss="modal" :label="__('ui.button.cancel')" />
    </x-slot:footer>
    @push('scripts')
        @filepondScripts
    @endpush
</x-modal>
