<div class="d-flex gap-4 flex-column">
    <div class="row gap-4">
        <div class="col-sm-12">
            <x-card :title="trans('domains/system.backups.title')">
                <x-slot:actions>
                    <x-button :label="trans('domains/system.backups.backup')" class="btn-sm" wire:click="backup" wire:loading theme="primary"
                        :icon-property="[
                            'width' => 16,
                            'height' => 16,
                        ]" icon="tabler-database-export" />
                    <x-button :label="trans('domains/system.backups.upload_backup')" class="btn-sm" :icon-property="[
                        'width' => 16,
                        'height' => 16,
                    ]" theme="info"
                        icon="tabler-cloud-upload" />
                </x-slot:actions>
                <div class="list-group">
                    @forelse($this->backups_data as $backup)
                        <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $backup->file_name }}</h5>
                                <div class="btn-group btn-group-sm">
                                    <x-button icon="tabler-download" wire:click="download({{ $backup }})"
                                        theme="primary" :icon-property="[
                                            'width' => 16,
                                            'height' => 16,
                                        ]" />
                                    <x-button icon="tabler-restore" wire:click="restore({{ $backup }})"
                                        theme="warning" :icon-property="[
                                            'width' => 16,
                                            'height' => 16,
                                        ]" />
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
    </div>
</div>
