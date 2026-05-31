<div class="d-flex gap-4 flex-column">
    <x-modal id="backup-file-upload-modal" :title="trans('domains/system.backups.upload_title')">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-input mb-3">
                    <label class="form-control-label">{{ trans('domains/system.backups.backup_file') }}</label>
                    <x-filepond::upload wire:model="file" @class(['is-invalid' => $errors->first('file'), 'm-0', 'border-0']) />
                    @error('file')
                        <small class="text-danger">{{ $errors->first('file') }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-button theme="success" wire:click="uploadFile" :label="__('ui.button.upload')" />
            <x-button theme="secondary" data-bs-toggle="modal" :label="__('ui.button.cancel')" />
        </x-slot:footer>
    </x-modal>
    <x-card :title="trans('domains/system.backups.title')">
        <x-slot:actions>
            <x-button :label="trans('domains/system.backups.backup')" class="btn-sm" wire:click="backup" wire:loading wire:target="backup"
                theme="primary" :icon-property="[
                    'width' => 16,
                    'height' => 16,
                ]" icon="tabler-database-export" />
            <x-button :label="trans('domains/system.backups.upload_backup')" class="btn-sm" :icon-property="[
                'width' => 16,
                'height' => 16,
            ]" theme="info" icon="tabler-cloud-upload"
                data-bs-toggle="modal" data-bs-target="#backup-file-upload-modal" />
        </x-slot:actions>
        <div class="list-group">
            @forelse($this->backups_data as $backup)
                <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $backup->file_name }}</h5>
                        <div class="btn-group btn-group-sm">
                            <x-button icon="tabler-download" wire:click="download({{ $backup->id }})" theme="primary"
                                :icon-property="[
                                    'width' => 16,
                                    'height' => 16,
                                ]" wire:loading wire:target="download({{ $backup->id }})" />
                            <x-button icon="tabler-restore" wire:click="restore({{ $backup->id }})" theme="warning"
                                :icon-property="[
                                    'width' => 16,
                                    'height' => 16,
                                ]" wire:loading wire:target="restore({{ $backup->id }})" />
                            <x-button icon="tabler-trash" theme="danger" :icon-property="[
                                'width' => 16,
                                'height' => 16,
                            ]"
                                x-on:click="$remove.livewire('delete-data', {
                                    title: '{{ trans('ui.button.delete') }}',
                                    textMessage: '{{ trans('domains/system.backups.delete_confirm') }}',
                                    confirmText: '{{ trans('ui.button.yes') }}',
                                    cancelText: '{{ trans('ui.button.no') }}',
                                    successMessage: '{{ trans('domains/system.backups.delete_success') }}',
                                    id: '{{ $backup->id }}',
                                    successFunction: () => {
                                        $dispatch('$refresh')
                                    }
                                })" />
                        </div>
                    </div>
                    <p class="mb-1">{{ $backup->type }} (<small>{{ $backup->size }}</small>)</p>
                </a>
            @empty

                <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex w-100 justify-content-center">
                        <p class="mb-1">{{ trans('domains/system.backups.empty_state') }}</p>
                    </div>
                </a>
            @endforelse
        </div>
    </x-card>
</div>
@push('scripts')
    @filepondScripts
@endpush
</div>
