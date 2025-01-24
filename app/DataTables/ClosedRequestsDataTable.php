<?php

namespace App\DataTables;


use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Support\Collection;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ClosedRequestsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    use DataTableTrait;

    public function dataTable(ItopWebserviceRepository $itopWS)
    {
        $Colldatas = $this->query($itopWS);
        return datatables($Colldatas)
            ->editColumn('ref2', function($Colldatas) {
                $link =  '<a href="' . route('closedrequest', $Colldatas->id) . '" target="_self">' .  $Colldatas->ref . '</a>';
                //return $this->badge('<a href="' . route('openedrequest', $Colldatas->id) . '" target="_blank">' .  $Colldatas->id . '</a>','secondary');
                {return $link;}
            })
            ->editColumn('action', function ($Colldatas) {
                $buttons = $this->button(
                    'closedrequest',
                    $Colldatas->id,
                    'primary',
                    __('Show'),
                    'eye',
                    '',
                    '_self'
                );

                return $buttons;
//                    . $this->button(
//                        'openedrequest',
//                        1,
//                        'warning',
//                        __('Edit'),
//                        'edit'
//                    );
//. $this->button(
//                        'posts.create',
//                        1,
//                        'info',
//                        __('Clone'),
//                        'clone'
//                    ). $this->button(
//                        'posts.destroy',
//                        1,
//                        'danger',
//                        __('Delete'),
//                        'trash-alt',
//                        __('Really delete this post?')
//                    );
            })->rawColumns(['ref2','action']);;
    }

    /**
     * Return a Collection from iTop Webservice
     */
    public function query(ItopWebserviceRepository $itopWS)
    {
        //$itopWS = new ItopWebserviceRepository();
        $datas = $itopWS->getListClosedRequest();
        $parsed_json= json_decode($datas,false);
//        \Log::debug($parsed_json->{'objects'});
        if (is_null($parsed_json->{'objects'})) { return collect([]);}
        $objects = $parsed_json->{'objects'};
        $Colldatas =  new Collection;
        $i = 0;
        foreach ($objects as $object){
            $Colldatas->push($object->fields);
            $i++;
            //if ($i > 9000) {break;}
        }
//        \Log::debug($Colldatas);
        Return $Colldatas;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('closedrequests-table')
            ->columns($this->getColumns())
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
            //Button::make('create'),
                Button::make(array('extend'=>'export', 'text'=>'<i class="fa fa-download"></i> '.__('Export'))),
                Button::make(array('extend'=>'print', 'text'=>'<i class="fas fa-print"></i> '.__('Print'))),
                Button::make(array('extend'=>'reload', 'text'=>'<i class="fas fa-redo"></i> '.__('Reload'))),
                Button::make(array('extend'=>'colvis', 'text'=>'<i class="fas fa-eye"></i> '.__('Column visibility')))
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            Column::make('ref2')->title(__('Ref'))
        ];

//        if(auth()->user()->role === 'admin') {
//            array_push($columns,
//                Column::make('user.name')->title(__('Author'))
//            );
//        }
        array_push($columns,
            Column::make('id')->hidden(true),
            Column::make('title')->title(__('Title')),
            Column::make('start_date')->title(__('Start Date')),
            Column::make('last_update')->title(__('Last Update'))->hidden(true),
            Column::make('close_date')->title(__('Close date')),
            Column::make('caller_id_friendlyname')->title(__('Caller')),
            Column::make('agent_id_friendlyname')->title(__('Agent')),
            Column::make('status')->title(__('Status'))->hidden(true),
//            Column::make('site_name')->title(__('Location')),
//            Column::make('service_name')->title(__('Service'))->hidden(true),
//            Column::make('module_name')->title(__('Module'))->hidden(true),
//            Column::computed('action')->title(__('Action'))->addClass('align-middle text-center')
//                ->orderable(false)
//                ->searchable(false)
//                ->exportable(false)
        );
        return $columns;

//        ref,title,status,priority,start_date,last_update,
//									caller_id_friendlyname,agent_id_friendlyname,
//									id,request_type,service_name,serviceelement_name,
//									resolution_date,resolution_code,last_pending_date,resolution_date,
//									qualified_date,assignment_date,
//									origin, parent_request_id_friendlyname,pending_reason,site_name,module_name
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() : String
    {
        return 'ClosedRequests_' . date('YmdHis');
    }

}
