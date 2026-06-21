<x-layouts.app>
    <x-card>
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.users.form-modal />
    <livewire:pages::system.audit.audit-view-modal key-name="ulid" :model="\App\Domains\Identity\Models\User::class"
                                                   translation="domains/identity/field.user." />
    <livewire:datatables.delete-action key-name="ulid" :model="\App\Domains\Identity\Models\User::class"
                                       :action="\App\Domains\Identity\Actions\Governance\RemoveUser::class" />
    <livewire:datatables.excel-manager :export-class="\App\Domains\Identity\Exports\UserExport::class"
                                       :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
                                       resource-name="user"  />

    @push('page-scripts')
        @vite(['resources/js/plugin/datatables.js', 'resources/js/plugin/select2.js'])
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof DataTable !== 'undefined') {
                    window.DataTable.feature.register('role-filter', function (settings, opts) {
                        let container = document.createElement('div');
                        container.style = 'width: 200px;';
                        container.innerHTML = `<x-form.select id="role-filter"
                                no-label class="form-select-sm" x-select2="{allowClear: true, placeholder: 'Role Filter'}"
                                :options="\App\Domains\Identity\Models\Role::pluck('name', 'name')"
                                x-on:change="LaravelDataTables['user-table'].ajax.reload()" />`;

                        return container;
                    });
                    window.DataTable.feature.register('status-filter', function (settings, opts) {
                        let container = document.createElement('div');
                        container.style = 'width: 200px;';
                        container.innerHTML = `<x-form.select id="status-filter"
                                no-label class="form-select form-select-sm" x-select2="{allowClear: true, placeholder: 'Status Filter'}"
                                :options="collect(\App\Domains\Identity\Enums\UserStatus::cases())->mapWithKeys(fn($stats) => [$stats->value => $stats->label()])"
                                x-on:change="LaravelDataTables['user-table'].ajax.reload()" />`;

                        return container;
                    });
                }
            });
        </script>

    @endpush
</x-layouts.app>
