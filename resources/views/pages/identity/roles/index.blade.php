<x-layouts.app :title="__('ui.title.index', ['resource' => __('resources.role')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.role')])">
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.roles.form-modal />
    <livewire:pages::identity.roles.view-modal />
    <livewire:datatables.delete-action :model="\App\Domains\Identity\Models\Role::class" :action="\App\Domains\Identity\Actions\Roles\RemoveSystemRole::class" />

    @push('page-scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
