<?php

namespace App\Http\DataTables\Identity;

use App\Domains\Identity\Exports\UserExport;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

use function __;

class UserDataTable extends DataTable
{
    public bool $fastExcel = false;

    public string $exportClass = UserExport::class;

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('status', fn ($model) => view('components.badge', [
                'label' => $model->status->label(),
                'variant' => $model->status->badgeVariant(),
            ]))
            ->addColumn(
                'action',
                fn ($user) => view('components.datatables.action-button', [
                    'log' => true,
                    'view' => [
                        'url' => route('users.view', ['user_id' => $user->ulid]),
                        'permission' => auth()->user()->can('view', $user),
                    ],
                    'edit' => [
                        'modal' => 'user-form-modal',
                        'permission' => auth()->user()->can('update', $user),
                    ],
                    'delete' => [
                        'url' => null,
                        'title' => $user->status->isActive()
                            ? __('ui.button.suspend')
                            : __('ui.button.delete'),
                        'message' => $user->status->isActive()
                            ? __('ui.confirmation.suspend', ['resource' => __('resources.user')])
                            : __('ui.confirmation.delete', ['resource' => __('resources.user')]),
                        'success_message' => $user->status->isActive()
                            ? __('ui.crud.success.suspended', ['resource' => __('resources.user')])
                            : __('ui.crud.success.deleted', ['resource' => __('resources.user')]),
                        'permission' => auth()->user()->can('delete', $user),
                    ],
                    'table_name' => 'user-table',
                    'id' => $user->ulid,
                ])
            )
            ->rawColumns(['action', 'status'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->with(['roles'])
            ->newQuery();

        if (request()->has('role') && request('role') != '') {
            $query->whereHas('roles', fn ($query) => $query
                ->where('name', request('role')));
        }

        if (request()->has('status') && request('status') != '') {
            $query->where('status', request('status'));
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                    d.role = $("#role-filter").val()
                    d.status = $("#status-filter").val()
                }',
            ])
            ->orderBy(-1)
            ->layout([
                'topStart' => [
                    'className' => 'col-md-auto me-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-start align-items-center align-items-md-start gap-1',
                    'features' => ['buttons', 'pageLength'],
                ],
                'topEnd' => [
                    'className' => 'col-md-auto ms-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-end align-items-center align-items-md-start gap-1',
                    'features' => ['status-filter', 'role-filter', 'search'],
                ],

                'bottomStart' => 'info',
                'bottomEnd' => 'paging',
            ])
            ->parameters([
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => __('ui.button.lookup'),
                ],
                'fixedColumns' => [
                    'start' => 2,
                ],
                'scrollX' => true,
                'scrollCollapse' => true,
                'responsive' => true,
            ])
            ->buttons([
                Button::make('add')
                    ->action('$("#user-form-modal").modal("show");')
                    ->text(svg('tabler-plus', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm'),
                Button::make('excel')
                    ->text(svg('tabler-file-excel', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm')
                    ->action("Livewire.dispatch('export-excel')"),
                Button::make('excel')
                    ->text(svg('tabler-table-import', ['width' => 16, 'height' => 16])->toHtml())
                    ->titleAttr(__('ui.title.import', ['resource' => 'Excel']))
                    ->addClass('btn-sm')
                    ->action("$('#excel-import-modal').modal('show')"),
                Button::make('reload')
                    ->text(svg('tabler-reload', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#'),
            Column::make('name')
                ->title(__('domains/identity/field.user.name'))
                ->searchable(true),
            Column::make('email')
                ->title(__('domains/identity/field.user.email'))
                ->searchable(true),
            Column::computed('roles[0].name')
                ->title(__('resources.role')),
            Column::computed('status')
                ->title(__('domains/identity/field.user.status')),
            Column::computed('action')
                ->title(__('ui.label.actions'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_'.date('YmdHis');
    }
}
