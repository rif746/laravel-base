<x-container.card :$title :permission="$permissions['create']" :$search>
    <x-element.table :$cols :rows="$this->users" :$sort_direction :$sort_by :$permissions :$modals />

    <livewire:user.user-form-modal />

    <x-placeholder.offline-states />
    <x-placeholder.loading-states />
</x-container.card>
