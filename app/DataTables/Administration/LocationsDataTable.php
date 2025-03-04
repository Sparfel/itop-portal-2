<?php

namespace App\DataTables\Administration;

use App\DataTables\DataTableTrait;
use App\Models\Location;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LocationsDataTable extends DataTable
{
    use DataTableTrait;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('action', function ($itopLoc) {
              return '<a href="#" class="edit btn btn-danger btn-sm" target="_self" onclick="delLoc('.$itopLoc->id.')"><i class="fas fa-trash-alt"></i></a>'
                    . ' '.
                    '<a href="#" class="edit btn btn-info btn-sm" target="_self" onclick="editLoc('.$itopLoc->id.')"><i class="fas fa-user-edit"></i></a>'
                    ;
            })

            ->addColumn('checkboxes', '<div class="custom-control custom-checkbox">
                          <input class="custom-control-input custom-control-input-primary custom-control-input-outline text-center"
                                type="checkbox" id="customCheckbox{{ $id }}" value="{{ $id }}" name="customCheckbox" >
                          <label for="customCheckbox{{ $id }}" class="custom-control-label"></label>
                        </div>')
            ->rawColumns(['id','checkboxes','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Location $model) : QueryBuilder
    {
        return $model->newQuery()
            ->select('locations.*', 'organizations.name as organization_name')
            ->leftJoin('organizations', 'locations.org_id', '=', 'organizations.id');

    }


    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->hidden(true),
            Column::make('checkboxes')->title('')->orderable(false)->addClass('align-middle text-center'),
            Column::make('organization_name')
                ->title(__('Organization Name'))
                ->name('organizations.name') // Spécifie la colonne réelle en DB
                ->orderColumn('organizations.name', function ($query, $direction) {
                    $query->orderBy('organizations.name', $direction);
                })
                ->filterColumn('organization_name', function ($query, $keyword) {
                    $query->where('organizations.name', 'like', "%{$keyword}%");
                }),
            Column::make('id')->title(__('Id')),
            Column::make('name')->title(__('Name')),
            Column::computed('action')->title(__('Action'))->addClass('align-middle text-center'),
        ];
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('listlocations-table')
            ->columns($this->getColumns())
            ->parameters([
                'buttons'      => [ 'myCustomAction'],
            ])
            ->minifiedAjax()
            ->dom("<'row'<'col-sm-6'B><'col-sm-2 text-center'l><'col-sm-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>")
            ->orderBy(1,'desc')
            ->colReorder(true)
            ->stateSave(true)
            ->stateDuration(0)
            ->serverSide(true)
            ->parameters(  ['responsive' => true,
                'autoWidth' => false])
            ->scroller( 20)
            //            ->pagingType('first_last_numbers')
            ->lengthMenu([[25,100, 200, 400, -1], [25,100, 200, 400, 'All']])
            ->buttons(
                Button::make([
                    'text' =>'<i class="fas fa-cloud-download-alt"></i> ' . __('Import from iTop'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {importLoc(); }',
                ]),
//                [
//                    'text' =>'<i class="fas fa-trash-alt"></i> ' . __('Delete All'),
//                    'className' => '',
//                    'action' => 'function(e, dt, node, config) {truncateOrg(); }',
//                ],
                [
                    'text' =>'<i class="far fa-trash-alt"></i> ' . __('Delete'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {delLocs(); }',
                ],
                [
                    'text' =>'<i class="far fa-check-square"></i> ' . __('Check/Uncheck All'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {checkAll(); }',
                ],

            );
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Locations_' . date('YmdHis');
    }
}
