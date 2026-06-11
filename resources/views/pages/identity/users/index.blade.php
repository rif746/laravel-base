<x-layouts.app :title="__('ui.title.index', ['resource' => __('resources.user')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.user')])">
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.users.form-modal />
    <livewire:pages::identity.users.view-modal />
    <livewire:pages::system.audit.audit-view-modal :model="\App\Domains\Identity\Models\User::class" translation="domains/identity.fields.user." />
    <livewire:datatables.delete-action :model="\App\Domains\Identity\Models\User::class" :action="\App\Domains\Identity\Actions\Governance\SuspendUser::class" />
    <livewire:datatables.excel-manager :export-class="\App\Domains\Identity\Exports\UserExport::class"
                                       :import-class="\App\Http\Ingestion\Excel\Identity\UserImport::class"
                                       resource-name="user"  />

    @push('scripts')
        @vite('resources/js/plugin/datatables.js')
    @endpush
    {{ $dataTable->scripts(attributes: ['type' => 'module', '']) }}
</x-layouts.app>
