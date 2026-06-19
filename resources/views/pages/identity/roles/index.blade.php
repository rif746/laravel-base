<x-layouts.app>
    <x-card>
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.roles.form-modal />
    <livewire:pages::identity.roles.view-modal />
    <livewire:pages::system.audit.audit-view-modal :model="\App\Domains\Identity\Models\Role::class" translation="domains/identity/field.role." />
    <livewire:datatables.delete-action :model="\App\Domains\Identity\Models\Role::class" :action="\App\Domains\Identity\Actions\AccessControl\RemoveSystemRole::class" />

    @push('page-scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
