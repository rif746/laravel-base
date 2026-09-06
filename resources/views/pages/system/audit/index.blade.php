<x-layouts.app>
    <x-card> {{ $dataTable->table() }} </x-card>

    <template id="template-role-filter">
        <x-form.select
            id="role-filter"
            no-label
            class="form-select-sm"
            x-select2="{allowClear: true, placeholder: '{{ __('ui/label.filter', ['resource' => __('resources.role')]) }}', url: '{{ route('api.v1.lookups.roles') }}'}"
            x-on:change="LaravelDataTables['user-table'].ajax.reload()"
        />
    </template>

    @push('page-scripts')
        @vite(['resources/js/plugin/datatables.js'])
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
    <livewire:pages::system.audit.audit-detail-modal />
</x-layouts.app>
