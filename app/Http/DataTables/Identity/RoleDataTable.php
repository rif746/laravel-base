<?php

namespace App\Http\DataTables\Identity;

use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Role>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn(
                'action',
                fn ($role) => view('components.datatables.action-button', [
                    'log' => true,
                    'view' => [
                        'modal' => 'role-view-modal',
                        'permission' => auth()->user()->can('view', $role),
                    ],
                    'edit' => [
                        'modal' => 'role-form-modal',
                        'permission' => $role->name == RoleType::SYSTEM_ADMIN->value ? false : auth()->user()->can('update', $role),
                    ],
                    'delete' => [
                        'url' => null,
                        'permission' => $role->name == RoleType::SYSTEM_ADMIN->value ? false : auth()->user()->can('delete', $role),
                        'message' => __('ui.confirmation.delete', ['resource' => __('resources.role')]),
                        'success_message' => __('ui.crud.success.deleted', ['resource' => __('resources.role')]),
                    ],
                    'table_name' => 'role-table',
                    'id' => $role->ulid,
                ])
            )
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->withCount('permissions')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('role-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(-1)
            ->layout([
                'topStart' => [
                    'className' => 'col-md-auto me-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-start align-items-center align-items-md-start gap-1',
                    'features' => ['buttons', 'pageLength']
                ],
                'topEnd' => [
                    'className' => 'col-md-auto ms-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-end align-items-center align-items-md-start gap-1',
                    'features' => ['search']
                ],

                'bottomStart' => 'info',
                'bottomEnd' => 'paging',
            ])
            ->parameters([
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => __('ui.button.lookup'),
                ],
            ])
            ->buttons([
                Button::make('add')
                    ->action('$("#role-form-modal").modal("show");')
                    ->text(svg('tabler-plus', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm'),
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
                ->title('#')
                ->searchable(false)
                ->orderable(false),
            Column::make('name')
                ->title(__('domains/identity/field.role.name')),
            Column::computed('permissions_count')
                ->title(__('domains/identity/field.role.permission_count')),
            Column::computed('guard_name')
                ->title(__('domains/identity/field.role.guard_name')),
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
        return 'Role_'.date('YmdHis');
    }
}
