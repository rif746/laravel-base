@php use App\Domains\Identity\Enums\UserStatus; @endphp
<x-layouts.app>
    <x-card>
        {{ $dataTable->table() }}
    </x-card>

    <template id="template-role-filter">
        <x-form.select id="role-filter" no-label class="form-select-sm"
                       x-select2="{allowClear: true, placeholder: 'Role Filter', url: '{{ route('api.v1.lookups.roles') }}'}"
                       x-on:change="LaravelDataTables['user-table'].ajax.reload()"/>
    </template>
    <template id="template-status-filter">
        <x-form.select id="status-filter"
                       no-label class="form-select form-select-sm"
                       x-select2="{allowClear: true, placeholder: 'Status Filter'}"
                       :options="collect(UserStatus::cases())
                                    ->mapWithKeys(fn($stats) => [$stats->value => $stats->label()])"
                       x-on:change="LaravelDataTables['user-table'].ajax.reload()"/>
    </template>

    <livewire:pages::identity.users.form-modal/>
    <livewire:pages::system.audit.audit-view-modal key-name="ulid" model="\App\Domains\Identity\Models\User::class"
                                                   translation="domains/identity/field.user."/>
    <livewire:datatables.delete-action key-name="ulid" :model="\App\Domains\Identity\Models\User::class"
                                       :action="\App\Domains\Identity\Actions\Governance\RemoveUser::class"/>
    <livewire:datatables.excel-manager :export-class="\App\Domains\Identity\Exports\UserExport::class"
                                       :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
                                       resource-name="user"/>

    @push('page-scripts')
        @vite(['resources/js/plugin/datatables.js', 'resources/js/plugin/select2.js'])
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
