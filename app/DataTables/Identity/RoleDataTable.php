<?php

namespace App\DataTables\Identity;

use App\Models\Identity\Role;
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
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn(
                'action',
                fn($role) => view('datatables.action-button', [
                    'view' => [
                        'modal' => 'role-view-modal',
                        'permission' => 'role index',
                    ],
                    'edit' => [
                        'modal' => 'role-form-modal',
                        'permission' => 'role edit',
                    ],
                    'delete' => [
                        'url' => route('roles.destroy', $role->id),
                        'message' => __('ui.crud.confirmation.delete', ['resource' => __('resources.role')]),
                    ],
                    'table_name' => 'role-table',
                    'id' => $role->id,
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
            ->parameters([
                'dom' => config('datatables-buttons.parameters.dom'),
            ])
            ->buttons([
                Button::make('add')
                    ->action('$("#role-form-modal").modal("show");')
                    ->text(svg('tabler-plus', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm'),
                Button::make('excel')
                    ->text(svg('tabler-file-excel', ['width' => 16, 'height' => 16])->toHtml())
                    ->addClass('btn-sm'),
                Button::make('pdf')
                    ->text(svg('tabler-file-type-pdf', ['width' => 16, 'height' => 16])->toHtml())
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
                ->title(__('domains/identity.fields.role.name')),
            Column::computed('permissions_count')
                ->title(__('domains/identity.fields.role.permission_count')),
            Column::computed('guard_name')
                ->title(__('domains/identity.fields.role.guard_name')),
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
        return 'Role_' . date('YmdHis');
    }
}
