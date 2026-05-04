<x-layout.app :title="__('ui.title.index', ['resource' => __('resources.role')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.role')])">
        {{ $dataTable->table() }}
    </x-card>

    @include('pages.identity.roles._modal-form')
    @include('pages.identity.roles._modal-view')

    @push('scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layout.app>
