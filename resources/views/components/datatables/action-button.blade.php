@props([
    'log' => false,
    'view' => [],
    'edit' => [],
    'delete' => [],
    'table_name' => '',
    'id' => 0,
])

<div class="btn-group" role="group" aria-label="DataTableActionGroup" x-data>
    @if($log)
        <button class="btn btn-sm btn-secondary" data-id="{{ $id }}" data-bs-toggle="modal"
                data-bs-target="#audit-view-modal" data-bs-toggle="tooltip" title="{{ __('ui/button.log') }}">
            @svg('tabler-logs', ['width' => 16, 'height' => 16])
        </button>
    @endif
    @if (!empty($view))
        @if (isset($view['permission']) && $view['permission'])
            @if (isset($view['modal']))
                <button class="btn btn-sm btn-info" data-id="{{ $id }}" data-bs-toggle="modal"
                    data-bs-target="#{{ $view['modal'] }}" data-bs-toggle="tooltip" title="{{ __('ui/button.view') }}">
                    @svg('tabler-eye', ['width' => 16, 'height' => 16])
                </button>
            @else
                <x-link href="{{ $view['url'] ?? 'javascript:void(0)' }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                    title="{{ __('ui/button.view') }}" icon="tabler-eye" :icon-config="['width' => 16, 'height' => 16]" />
            @endif
        @endif
    @endif

    @if (!empty($edit))
        @if (isset($edit['permission']) && $edit['permission'])
            @if (isset($edit['modal']))
                <button class="btn btn-sm btn-warning" data-id="{{ $id }}" data-bs-toggle="modal"
                    data-bs-target="#{{ $edit['modal'] }}" data-bs-toggle="tooltip"
                    title="{{ __('ui/button.edit') }}">
                    @svg('tabler-pencil', ['width' => 16, 'height' => 16])
                </button>
            @else
                <x-link href="{{ $edit['url'] ?? 'javascript:void(0)' }}" class="btn btn-sm btn-warning"
                    data-bs-toggle="tooltip" title="{{ __('ui/button.edit') }}" icon="tabler-pencil"
                        :icon-config="['width' => 16, 'height' => 16]" />
            @endif
        @endif
    @endif

    @if (!empty($delete))
        @if (isset($delete['permission']) && $delete['permission'])
            <button class="btn btn-sm btn-danger"
                x-on:click="$ask.livewire('delete-data', {
                title: '{{ $delete['title'] }}',
                textMessage: '{{ $delete['message'] }}',
                confirmText: '{{ __('ui/button.yes') }}',
                cancelText: '{{ __('ui/button.no') }}',
                successMessage: '{{ $delete['success_message'] }}',
                table_name: '{{ $table_name }}',
                id: '{{ $id }}',
                onSuccess: () => {
                    LaravelDataTables['{{ $table_name }}'].ajax.reload(null, false);
                }
            })">
                @svg('tabler-trash', ['width' => 16, 'height' => 16])
            </button>
        @endif
    @endif
</div>
