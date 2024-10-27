<x-card :title="$this->title" shadow separator>
    <x-slot:menu>
        <x-input placeholder="Search..." wire:model.live.debounce="search" />
        <x-button icon="o-plus" class="btn-outline" wire:modal.show="create-user-form-modal" />
    </x-slot:menu>

    <x-table :headers="$headers" with-pagination per-page="perPage" :per-page-values="[5, 10, 25, 50]" :rows="$this->users" :sort-by="$this->sortBy">
        @scope('actions', $user)
            <div class="join">
                <x-button icon="o-pencil" class="btn-xs join-item btn-accent"
                    wire:modal.show="update-user-form-modal,{{ $user->id }}" />
                <x-button icon="o-trash" class="btn-xs join-item btn-error"
                    wire:delete="Are you sure to delete user {{ $user->name }}?, {{ $user->id }}" />
            </div>
        @endscope
    </x-table>
    <livewire:user.create-user-form-modal />
    <livewire:user.update-user-form-modal />
</x-card>
