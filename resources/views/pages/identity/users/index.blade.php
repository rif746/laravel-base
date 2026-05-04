<x-layout.app :title="__('ui.title.index', ['resource' => __('resources.user')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.user')])">
        {{ $dataTable->table() }}
    </x-card>

    @include('pages.identity.users._modal-form')
    @include('pages.identity.users._modal-view')

    @push('scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layout.app>
