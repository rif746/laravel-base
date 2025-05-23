<div>
    <x-header :title="__($this->title)" separator progress-indicator>
        <x-slot:middle class="justify-end!">
            <x-input icon="o-bolt" placeholder="Search..." wire:model.live.debounce.500ms="search" />
        </x-slot:middle>
        <x-slot:actions>
            @can('role create')
                <x-button icon="o-plus" class="btn-outline" wire:modal.show="role-form-modal" />
            @endcan
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$this->headers" :rows="$this->roles" with-pagination per-page="perPage" :per-page-values="[5, 10, 25, 50]"
            :sort-by="$this->sortBy">
            @scope('actions', $role)
                <div class="join">
                    @can('update', $role)
                        <x-button icon="o-pencil" class="btn-xs join-item btn-accent"
                            wire:modal.show="role-form-modal, {{ $role->id }}" />
                    @endcan
                    @can('delete', $role)
                        <x-button icon="o-trash" class="btn-xs join-item btn-error"
                            wire:delete="Are you sure to delete role {{ $role->name }}?, {{ $role->id }}" />
                    @endcan
                </div>
            @endscope
        </x-table>
    </x-card>
    <livewire:role.role-form-modal />
</div>
