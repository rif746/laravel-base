<?php

namespace App\Http\DataTables\System;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Domains\System\Models\Audit;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AuditDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Audit> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('event', fn($audit) => __('event.'.$audit->event))
            ->editColumn('auditable_type', fn($audit) => __('resources.'.$audit->auditable_type))
            ->addColumn(
                'action',
                fn ($audit) => view('components.datatables.action-button', [
                    'log' => false,
                    'view' => [
                        'modal' => 'audit-detail-modal',
                        'permission' => true,
                    ],
                    'table_name' => 'audit-table',
                    'id' => $audit->id,
                ])
            )
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Audit>
     */
    public function query(Audit $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(['users.name as user_name', 'audits.*'])
            ->join('users', 'users.id', '=', 'audits.user_id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('audit-table')
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
                    'rowClass' => 'row gap-1',
                    'className' => 'col-md-auto me-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-start align-items-center align-items-md-start gap-1',
                    'features' => ['buttons', 'pageLength'],
                ],
                'topEnd' => [
                    'className' => 'col-md-auto ms-auto d-flex flex-sm-row flex-column justify-content-center justify-content-md-end align-items-center align-items-md-start gap-1',
                    'features' => [
                        [
                            'custom-features' => [
                                'targetId' => 'template-role-filter',
                                'style' => 'width: 200px;',
                            ],
                        ],
                        'search',
                    ],
                ],

                'bottomStart' => 'info',
                'bottomEnd' => 'paging',
            ])
            ->parameters([
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => __('ui/button.lookup'),
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
                    ->titleAttr(__('ui/title.import', ['resource' => 'Excel']))
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
                ->title('#')
                ->searchable(false)
                ->orderable(false),
            Column::computed('user_name')
                ->title(__('domains/system/field.audit.user_name')),
            Column::make('event')
                ->title(__('domains/system/field.audit.event')),
            Column::computed('auditable_type')
                ->title(__('domains/system/field.audit.auditable_type')),
            Column::computed('ip_address')
                ->title(__('domains/system/field.audit.ip_address')),
            Column::computed('user_agent')
                ->title(__('domains/system/field.audit.browser')),
            Column::computed('action')
                ->title(__('ui/label.actions'))
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
        return 'Audit_' . date('YmdHis');
    }
}
