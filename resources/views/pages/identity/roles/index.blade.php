<x-layouts.app>
    <x-card>
        {{ $dataTable->table() }}
    </x-card>

    @canany(['role.create', 'role.update'])
        <livewire:pages::identity.roles.form-modal />
    @endcanany

    @can('role.view')
        <livewire:pages::identity.roles.view-modal />
    @endcan

    <livewire:pages::system.audit.audit-view-modal key-name="ulid" :model="\App\Domains\Identity\Models\Role::class"
                                                   translation="domains/identity/field.role." />

    @can('role.delete')
        <livewire:datatables.delete-action key-name="ulid" :model="\App\Domains\Identity\Models\Role::class"
                                           :action="\App\Domains\Identity\Actions\AccessControl\RemoveSystemRole::class" />
    @endcan

    @push('page-scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
