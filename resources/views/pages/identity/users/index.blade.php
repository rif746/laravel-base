<x-layouts.app :title="__('ui.title.index', ['resource' => __('resources.user')])">
    <x-card :title="__('ui.title.index', ['resource' => __('resources.user')])">
        {{ $dataTable->table() }}
    </x-card>

    <livewire:pages::identity.users.form-modal />
    <livewire:pages::identity.users.view-modal />
    <livewire:datatables.delete-action :model="\App\Domains\Identity\Models\User::class" :action="\App\Domains\Identity\Actions\Users\SuspendUser::class" />

    @push('scripts')
        @vite('resources/js/plugin/datatables.js')
    @endpush
    {{ $dataTable->scripts(attributes: ['type' => 'module', '']) }}
</x-layouts.app>
