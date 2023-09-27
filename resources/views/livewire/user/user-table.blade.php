<x-container.card>
    <div class="flex justify-between flex-col md:flex-row">
        <h2 class="text-2xl font-bold">{{ __('Data Users') }}</h2>
        <div class="flex gap-1">
            <x-element.input.line class="" wire:model.live="search" placeholder="Search..." />
            <x-element.button.primary x-on:click="$dispatch('open-modal', {name: 'user-form-modal'})">
                <x-heroicon-s-plus width="16" />
            </x-element.button.primary>
        </div>
    </div>
    <x-element.table :cols="$this->cols" :rows="$this->users" :sortDirection="$sort_direction" :sortField="$sort_by" />

    <livewire:user.user-form-modal />
</x-container.card>
