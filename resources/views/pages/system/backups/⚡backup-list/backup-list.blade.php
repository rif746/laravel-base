<div class="d-flex gap-4 flex-column">
    <x-card>
        <x-slot:actions>
            <x-button :label="trans('domains/system/pages.backup.backup_button')" class="btn-sm" wire:click="backup" wire:loading wire:target="backup"
                theme="primary" :icon-property="[
                    'width' => 16,
                    'height' => 16,
                ]" icon="tabler-database-export" />
            <x-button :label="trans('domains/system/pages.backup.upload_button')" class="btn-sm" :icon-property="[
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
                                x-on:click="$swal.livewire('delete-data', {
                                    title: '{{ trans('ui.button.delete') }}',
                                    textMessage: '{{ trans('domains/system/pages.backup.confirmation.delete') }}',
                                    confirmText: '{{ trans('ui.button.yes') }}',
                                    cancelText: '{{ trans('ui.button.no') }}',
                                    successMessage: '{{ trans('domains/system/messages.backup.delete_success') }}',
                                    id: '{{ $backup->id }}',
                                    onSuccess: () => {
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
                        <p class="mb-1">{{ trans('domains/system/pages.backup.empty_state') }}</p>
                    </div>
                </a>
            @endforelse
        </div>
    </x-card>
    <livewire:pages::system.backups.upload-backup-modal />
</div>
