<?php

namespace App\DataTables\Administration;

use App\DataTables\DataTableTrait;
use App\Models\ItopUser;
use App\Models\ListItopPerson;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ListItopPersonsDataTable extends DataTable
{

    use DataTableTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('action', function ($itopUser) {
//                return $this->button(
//                    'listitopusers',
//                    $itopUser->id,
//                    'primary',
//                    __('Create account'),
//                    'user',
//                    __('Really delete this contact?')
//                    ) .
//                    $this->button(
//                        'edititopuser',
//                        $itopUser->id,
//                        'primary',
//                        __('Edit'),
//                        'edit'
//                    )
                return '<a href="#" class="edit btn btn-danger btn-sm" target="_self" onclick="delUser('.$itopUser->id.')"><i class="fas fa-trash-alt"></i></a>'
                    . ' '.
                   '<a href="#" class="edit btn btn-info btn-sm" target="_self" onclick="majUser('.$itopUser->id.')"><i class="fas fa-user-edit"></i></a>'
                    ;
            })

            ->addColumn('checkboxes', '<div class="custom-control custom-checkbox">
                          <input class="custom-control-input custom-control-input-primary custom-control-input-outline text-center"
                                type="checkbox" id="customCheckbox{{ $id }}" value="{{ $id }}" name="customCheckbox" >
                          <label for="customCheckbox{{ $id }}" class="custom-control-label"></label>
                        </div>')
            ->editColumn('is_local', function($Colldatas) {
                if ($Colldatas->is_local == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
                    $link = '<i class="fas fa-minus-circle text-danger"></i>';}
                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
                    $link =  '<i class="fas fa-check text-success"></i>';}
                {return $link;}
            })
            ->editColumn('is_in_itop', function($Colldatas) {
                if ($Colldatas->is_in_itop == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
                    $link = '<i class="fas fa-minus-circle text-danger"></i>';}
                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
                    $link =  '<i class="fas fa-check text-success"></i>';}
                {return $link;}
            })
            ->editColumn('has_itop_account', function($Colldatas) {
                if ($Colldatas->has_itop_account == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
                    $link = '<i class="fas fa-minus-circle text-danger"></i>';}
                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
                    $link =  '<i class="fas fa-check text-success"></i>';}
                {return $link;}
            })->rawColumns(['id','checkboxes','action','is_local','is_in_itop','has_itop_account']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ListItopPerson $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ItopUser $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('listitoppersons-table')
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
                //Button::make(array('extend'=>'create', 'text'=>'<i class="fas fa-plus"></i> '.__('New'))),
//                Button::make(array('extend'=>'export', 'text'=>'<i class="fa fa-download"></i> '.__('Export'))),
//                Button::make(array('extend'=>'print', 'text'=>'<i class="fas fa-print"></i> '.__('Print'))),
//                Button::make(array('extend'=>'reload', 'text'=>'<i class="fas fa-redo"></i> '.__('Reload'))),
//                Button::make(array('extend'=>'colvis', 'text'=>'<i class="fas fa-eye"></i> '.__('Column visibility'))),
//              [
//                    'text' =>'<i class="fas fa-cloud-download-alt"></i> ' . __('Import from iTop !'),
//                    'className' => '',
//                    'action' => 'function(e, dt, node, config) {console.log(Aselected); }',
//              ],

                Button::make([
                    'text' =>'<i class="fas fa-cloud-download-alt"></i> ' . __('Import from iTop'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {importPerson(); }',
                ]),
                [
                    'text' =>'<i class="fas fa-trash-alt"></i> ' . __('Delete All'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {truncatePerson(); }',
                ],
                [
                    'text' =>'<i class="far fa-trash-alt"></i> ' . __('Delete'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {delUsers(); }',
                ],
                [
                    'text' =>'<i class="far fa-check-square"></i> ' . __('Check/Uncheck All'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {checkAll(); }',
                ],
                [
                    'text' =>'<i class="fas fa-plus"></i> ' . __('New'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {newUser(); }',
                ],
                [
                    'text' =>'<i class="fas fa-user-plus"></i> ' . __('Create Account'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {createUsers(); }',
                ],
                [
                    'text' =>'<i class="fas fa-envelope"></i> ' . __('Notify'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {notifyUsers(); }',
                ]

            );

    }

    public function myCustomAction()
    {
        //...your code here.
        Log::info('myCustomAction');
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            //Column::make('ref2')->title(__('Ref'))
        ];
        array_push($columns,
            Column::make('id')->hidden(true),
            Column::make('checkboxes')->title('')->orderable(false),
            Column::make('first_name')->title(__('First name')),
            Column::make('last_name')->title(__('Last name')),
            Column::make('email')->title(__('Mail')),
//            Column::make('itop_id')->title(__('ID Helpdesk'))->hidden(true),
            Column::make('org_name')->title(__('Organization')),
            Column::make('location_name')->title(__('Site')),
//            Column::make('phone')->title(__('Phone')),
//            Column::make('mobile_phone')->title(__('Mobile')),
//            Column::make('is_active')->title(__('Active'))->addClass('align-middle text-center'),
            Column::make('is_local')->title(__('Local Account'))->addClass('align-middle text-center'),
            Column::make('is_in_itop')->title(__('Is in iTop'))->addClass('align-middle text-center'),
            Column::make('has_itop_account')->title(__('Remote Account'))->addClass('align-middle text-center'),
            //  Column::make('module_name')->title(__('Module'))->hidden(true),
            Column::computed('action')->title(__('Action'))->addClass('align-middle text-center')
//                ->orderable(false)
//                ->searchable(false)
//                ->exportable(false)
        );
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() : String
    {
        return 'ListItopPersons_' . date('YmdHis');
    }
}
