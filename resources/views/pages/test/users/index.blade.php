<x-layouts.app>
    <x-card>
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::system.audit.audit-view-modal key-name="ulid" model="\App\Domains\Test\Models\User::class"
                                                   translation="domains/test/field.user."/>
    <livewire:datatables.delete-action key-name="ulid" :model="\App\Domains\Test\Models\User::class"
                                       :action="\App\Domains\Test\Actions\Governance\RemoveUser::class"/>
    <livewire:datatables.excel-manager :export-class="\App\Domains\Test\Exports\UserExport::class"
                                       :import-class="\App\Http\Ingestion\Excel\Test\UserImport::class"
                                       resource-name="user"/>

    @push('page-scripts')
        @vite(['resources/js/plugin/datatables.js', 'resources/js/plugin/select2.js'])
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
