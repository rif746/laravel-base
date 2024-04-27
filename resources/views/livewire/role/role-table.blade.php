<x-container.card :$title :permission="$permissions['create']" :modal="$modals['create']" :$search>
    <x-element.table :$cols :rows="$this->rows" :$sort_direction :$sort_by :$permissions :$modals :$import :$export />

    <livewire:role.role-form-modal />
    <x-placeholder.offline-states />
    <x-placeholder.loading-states />
</x-container.card>
