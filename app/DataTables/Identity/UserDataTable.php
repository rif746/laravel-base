<?php

namespace App\DataTables\Identity;

use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn(
                'action',
                fn ($user) => view('datatables.action-button', [
                    'view' => [
                        'modal' => 'user-view-modal',
                        'permission' => 'user index',
                    ],
                    'edit' => [
                        'modal' => 'user-form-modal',
                        'permission' => 'user edit',
                    ],
                    'delete' => [
                        'url' => route('users.destroy', $user->id),
                        'message' => __('ui.crud.confirmation.delete', ['resource' => __('resources.user')]),
                    ],
                    'table_name' => 'user-table',
                    'id' => $user->id,
                ])
            )
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->with(['roles' => fn ($mdl) => $mdl->first()])
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(-1)
            ->parameters([
                'dom' => config('datatables-buttons.parameters.dom'),
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search...',
                ],
            ])
            ->buttons([
                Button::make('add')
                    ->action('$("#user-form-modal").modal("show");')
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
                ->title('#'),
            Column::make('name')
                ->title(__('domains/identity.fields.user.name'))
                ->searchable(true),
            Column::make('email')
                ->title(__('domains/identity.fields.user.email'))
                ->searchable(true),
            Column::computed('roles[0].name')
                ->title(__('resources.role')),
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
