<div class="d-flex flex-column gap-4">
    <x-card>
        <x-slot:actions>
            <x-button
                :label="__('domains/system/pages.backup.backup_button')"
                class="btn-sm"
                wire:click="backup"
                wire:loading
                wire:target="backup"
                theme="primary"
                :icon-property="[
                    'width' => 16,
                    'height' => 16,
                ]"
                icon="tabler-database-export"
            />
            <x-button
                :label="__('domains/system/pages.backup.upload_button')"
                class="btn-sm"
                :icon-property="[
                'width' => 16,
                'height' => 16,
            ]"
                theme="info"
                icon="tabler-cloud-upload"
                data-bs-toggle="modal"
                data-bs-target="#backup-file-upload-modal"
            />
        </x-slot:actions>
        <div class="list-group">
            @forelse ($this->backups_data as $backup)
                <x-link href="#" class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex justify-content-between w-100">
                        <h5 class="mb-1">{{ $backup->file_name }}</h5>
                        <div class="btn-group btn-group-sm">
                            <x-button
                                icon="tabler-download"
                                wire:click="download({{ $backup->id }})"
                                theme="primary"
                                :icon-property="[
                                    'width' => 16,
                                    'height' => 16,
                                ]"
                                wire:loading
                                wire:target="download({{ $backup->id }})"
                            />
                            <x-button
                                icon="tabler-restore"
                                x-on:click="$js.restore"
                                data-id="{{ $backup->id }}"
                                theme="warning"
                                :icon-property="[
                                    'width' => 16,
                                    'height' => 16,
                                ]"
                            />
                            <x-button
                                icon="tabler-trash"
                                theme="danger"
                                :icon-property="[
                                'width' => 16,
                                'height' => 16,
                            ]"
                                x-on:click="$ask.livewire('delete-data', {
                                    title: '{{ __('ui/button.delete') }}',
                                    textMessage: '{{ __('domains/system/pages.backup.confirmation.delete') }}',
                                    confirmText: '{{ __('ui/button.yes') }}',
                                    cancelText: '{{ __('ui/button.no') }}',
                                    successMessage: '{{ __('domains/system/messages.backup.delete_success') }}',
                                    id: '{{ $backup->id }}',
                                    onSuccess: () => {
                                        $dispatch('$refresh')
                                    }
                                })"
                            />
                        </div>
                    </div>
                    <p class="mb-1">{{ $backup->type }} (<small>{{ $backup->size }}</small>)</p>
                </x-link>
            @empty
                <x-link href="#" class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex justify-content-center w-100">
                        <p class="mb-1">{{ __('domains/system/pages.backup.empty_state') }}</p>
                    </div>
                </x-link>
            @endforelse
        </div>
    </x-card>

    @script
        <script>
            $js.restore = (e) => {
                const id = e.target.dataset.id;
                Swal.fire({
                    title: '{{ __('ui/title.restore', ['resource' => __('resources.backup')]) }}',
                    text: '{{ __('ui/confirmation.restore', ['resource' => __('resources.backup')]) }}',
                    showLoaderOnConfirm: true,
                    showConfirmButton: true,
                    showCancelButton: true,
                    preConfirm: () => {
                        return $wire.restore(id);
                    },
                });
            };
        </script>
    @endscript
    <livewire:pages::system.backups.upload-backup-modal />
</div>
