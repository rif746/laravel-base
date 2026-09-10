<x-layouts.app>
    <x-card> {{ $dataTable->table() }} </x-card>

    @push('page-scripts')
        @vite(['resources/js/plugin/datatables.js'])
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
    <livewire:pages::system.audit.audit-detail-modal />
</x-layouts.app>
