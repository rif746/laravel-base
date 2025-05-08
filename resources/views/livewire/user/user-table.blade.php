<div x-data="users">
    <x-header :title="__($this->title)" separator progress-indicator>
        <x-slot:middle class="justify-end!">
            <x-input icon="o-bolt" placeholder="Search..." wire:model.live.debounce.500ms="search" />
        </x-slot:middle>
        <x-slot:actions>
            @can('user create')
                <x-button icon="o-plus" class="btn-outline" wire:modal.show="create-user-form-modal" />
            @endcan
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$this->headers" with-pagination per-page="perPage" :per-page-values="[5, 10, 25, 50]" :rows="$this->users"
            :sort-by="$this->sortBy">
            @scope('cell_status', $user)
                @if ($user->status)
                    <x-badge value="Aktif" class="badge-primary" />
                @else
                    <x-badge value="Nonaktif" class="badge-warning" />
                @endif
            @endscope
            @scope('actions', $user)
                <div class="join">
                    <x-button icon="o-eye" class="btn-xs join-item btn-info"
                        wire:modal.show="user-detail-modal,{{ $user->id }}" />
                    @can('update', $user)
                        <x-button icon="o-pencil" class="btn-xs join-item btn-accent"
                            wire:modal.show="update-user-form-modal,{{ $user->id }}" />
                        <x-button :icon="$user->status ? 'o-shield-check' : 'o-shield-exclamation'" x-on:click="toggleStatus" id="{{ $user->id }}"
                            message="{{ trans_choice('locale/user.alert.toggle_status', $user->status, ['name' => $user->name]) }}"
                            class="btn-xs join-item {{ $user->status ? 'btn-primary' : 'btn-warning' }}" />
                    @endcan
                    @can('delete', $user)
                        <x-button icon="o-trash" class="btn-xs join-item btn-error"
                            wire:delete="{{ __('locale/user.alert.deletion', ['name' => $user->name]) }}, {{ $user->id }}" />
                    @endcan
                </div>
            @endscope
        </x-table>
    </x-card>
    <livewire:user.create-user-form-modal />
    <livewire:user.update-user-form-modal />
    <livewire:user.user-detail-modal />
</div>
@script
    <script>
        Alpine.data('users', () => ({
            toggleStatus(e) {
                id = e.target.getAttribute('id')
                message = e.target.getAttribute('message')
                window.confirm({
                    title: 'Status Toggle',
                    message: message
                }).then(e => {
                    if (e.isConfirmed) {
                        $wire.toggleStatus(id)
                    }
                })
            }
        }))
    </script>
@endscript
