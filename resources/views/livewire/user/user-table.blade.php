<x-card :title="__($this->title)" shadow separator>
    <x-slot:menu>
        <x-input placeholder="Search..." wire:model.live.debounce="search" />
        @can('user create')
            <x-button icon="o-plus" class="btn-outline" wire:modal.show="create-user-form-modal" />
        @endcan
    </x-slot:menu>

    <x-table :headers="$this->headers" with-pagination per-page="perPage" :per-page-values="[5, 10, 25, 50]" :rows="$this->users" :sort-by="$this->sortBy">
        @scope('actions', $user)
            <div class="join">
                @can('update', $user)
                    <x-button icon="o-pencil" class="btn-xs join-item btn-accent"
                        wire:modal.show="update-user-form-modal,{{ $user->id }}" />
                @endcan
                @can('delete', $user)
                    <x-button icon="o-trash" class="btn-xs join-item btn-error"
                        wire:delete="{{ __('locale/user.alert.deletion', ['name' => $user->name]) }}, {{ $user->id }}" />
                @endcan
            </div>
        @endscope
    </x-table>
    <livewire:user.create-user-form-modal />
    <livewire:user.update-user-form-modal />
</x-card>
