@props([
    'view' => [],
    'edit' => [],
    'delete' => [],
    'table_name' => '',
    'id' => 0,
])

<div class="btn-group" role="group" aria-label="DataTableActionGroup" x-data>
    @if (!empty($view))
        @if (isset($view['permission']) ? auth('web')->user()->can($view['permission']) : true)
            @if (isset($view['modal']))
                <button class="btn btn-sm btn-info" data-id="{{ $id }}" data-bs-toggle="modal"
                    data-bs-target="#{{ $view['modal'] }}" data-bs-toggle="tooltip"
                    title="{{ trans('ui.button.view') }}">
                    @svg('tabler-eye', ['width' => 16, 'height' => 16])
                </button>
            @else
                <a href="{{ $view['url'] ?? 'javascript:void(0)' }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                    title="{{ trans('domains/system.actions.view') }}">
                    @svg('tabler-eye', ['width' => 16, 'height' => 16])
                </a>
            @endif
        @endcan
    @endif

    @if (!empty($edit))
        @if (isset($edit['permission']) ? auth('web')->user()->can($edit['permission']) : true)
            @if (isset($edit['modal']))
                <button class="btn btn-sm btn-warning" data-id="{{ $id }}" data-bs-toggle="modal"
                    data-bs-target="#{{ $edit['modal'] }}" data-bs-toggle="tooltip"
                    title="{{ trans('ui.button.edit') }}">
                    @svg('tabler-pencil', ['width' => 16, 'height' => 16])
                </button>
            @else
                <a href="{{ $edit['url'] ?? 'javascript:void(0)' }}" class="btn btn-sm btn-warning"
                    data-bs-toggle="tooltip" title="{{ trans('domains/system.actions.edit') }}">
                    @svg('tabler-pencil', ['width' => 16, 'height' => 16])
                </a>
            @endif
        @endcan
    @endif

    @if (!empty($delete))
        @if (isset($delete['permission']) ? auth('web')->user()->can($delete['permission']) : true)
            <button class="btn btn-sm btn-danger" data-message="{{ $delete['message'] }}"
                data-title="{{ trans('ui.button.delete') }}" data-text="{{ $delete['message'] }}"
                data-confirm-text="{{ trans('ui.button.yes') }}"
                data-cancel-text="{{ trans('ui.button.no') }}"
                x-remove-data="async () => {
                    await $delete('{{ $delete['url'] }}');
                    LaravelDataTables['{{ $table_name }}'].draw();
                    toast('{{ trans('ui.button.delete') }}', 'success');
                }">
                @svg('tabler-trash', ['width' => 16, 'height' => 16])
            </button>
        @endcan
    @endif
</div>
