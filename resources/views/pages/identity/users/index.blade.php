<x-layouts.app :title="__('ui.title.index', ['resource' => __('resources.user')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.user')])">
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.users.form-modal />
    <livewire:pages::identity.users.view-modal />
    <livewire:datatables.delete-action :model="\App\Models\Identity\User::class" />

    @push('scripts')
        @vite('resources/js/plugin/datatables.js')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-layouts.app>
