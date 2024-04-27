<x-container.card :$title :permission="$permissions['create']" :modal="$modals['create']" :$search>
    <x-element.table :$cols :rows="$this->users" :$sort_direction :$sort_by :$permissions :$modals :$import :$export />

    <livewire:user.create-user-form-modal />
    <livewire:user.update-user-form-modal />

    <x-placeholder.offline-states />
    <x-placeholder.loading-states />
</x-container.card>
