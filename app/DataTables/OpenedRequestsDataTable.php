<?php

namespace App\DataTables;

//use App\Models\OpenedRequest;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class OpenedRequestsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    use DataTableTrait;

    protected $Colldatas;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function dataTable(ItopWebserviceRepository $itopWS)
    {


//        $Colldatas = $this->query($itopWS);
        $Colldatas = $this->data;

        return datatables($Colldatas)
            ->editColumn('ref2', function($Colldatas) {
                $link =  '<a href="' . route('openedrequest',$Colldatas->id) . '" target="_self">' .  $Colldatas->ref . '</a>';
                //return $this->badge('<a href="' . route('openedrequest', $Colldatas->id) . '" target="_blank">' .  $Colldatas->id . '</a>','secondary');
                {return $link;}
            })
            ->editColumn('action', function ($Colldatas) {
                $buttons = $this->button(
                    'openedrequest',
                    $Colldatas->id,
                    'primary',
                    __('Show'),
                    'eye',
                    '',
                    '_self'
                );

                {return $buttons;}
            })
            ->editColumn('priority', function ($Colldatas) {
                // Remplace les chiffres par des badges AdminLTE
                $priorityLabels = [
                    1 => ['label' => 'Critical', 'class' => 'danger'],
                    2 => ['label' => 'High', 'class' => 'warning'],
                    3 => ['label' => 'Medium', 'class' => 'info'],
                    4 => ['label' => 'Low', 'class' => 'secondary'],
                ];

                $priority = $priorityLabels[$Colldatas->priority] ?? ['label' => 'Unknown', 'class' => 'dark'];

                return '<span class="badge bg-' . $priority['class'] . '">' . $priority['label'] . '</span>';
            })
            ->editColumn('status', function ($Colldatas) {
                // Remplace les chiffres par des badges AdminLTE
                $statusLabels = [
                    'new' => ['fontAwesome' => 'fa-solid fa-regular fa-comment-dots', 'class' => 'danger', 'title' => __('new')],
                    'assigned' => ['fontAwesome' => 'fa-solid fa-user-check', 'class' => 'info', 'title' => __('assigned')],
                    'pending' => ['fontAwesome' => 'fa-solid fa-hourglass-half', 'class' => 'warning', 'title' => __('pending')],
                    'resolved' => ['fontAwesome' => 'fa-regular fa-thumbs-up', 'class' => 'success', 'title' => __('resolved')],
                    'closed' => ['fontAwesome' => 'fa-solid fa-check-circle', 'class' => 'secondary', 'title' => __('closed')],
                   ];
                $status = $statusLabels[$Colldatas->status] ?? ['fontAwesome' => 'fa-solid fa-question', 'class' => 'dark', 'title' => __('unknown')];

                //version avec des icones, moins parlant
                //return '<i class="' .$status['fontAwesome'] . ' text-' . $status['class'] . '" title="'. $status['title'] .'"></i>';
                return '<span class="badge bg-' . $status['class'] . '">' . $status['title'] . '</span>';
            })
            ->rawColumns(['ref2','action','priority','status']);
    }



    /**
     * Return a Collection from iTop Webservice
     */
//    public function query(ItopWebserviceRepository $itopWS)
//    {
////        $itopWS = new ItopWebserviceRepository();
//        $datas = $itopWS->getListOpenedRequest();
//        $parsed_json= json_decode($datas,false);
//        $objects = $parsed_json->{'objects'};
//        $Colldatas =  new Collection;
//        foreach ($objects as $object){
//            $Colldatas->push($object->fields);
//        }
//        $this->Colldatas = $Colldatas;
//        \Log::debug('Appel de Datatable query' );
//        Return $Colldatas;
//    }
//

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('openedrequests-table')
            ->columns($this->getColumns())
            //->minifiedAjax()
            ->dom("<'row'<'col-sm-6'B><'col-sm-2 text-center'l><'col-sm-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>")
            ->orderBy(1,'desc')
            ->colReorder(true)
            ->stateSave(true)
            ->stateDuration(0)
            ->lengthMenu([[25,100, 200, 400, -1], [25,100, 200, 400, 'All']])
            ->parameters(  ['responsive' => true,
                'autoWidth' => false])
            ->buttons(
            //Button::make('create'),
                Button::make(array('extend'=>'export', 'text'=>'<i class="fa fa-download"></i> '.__('Export'))),
                Button::make(array('extend'=>'print', 'text'=>'<i class="fas fa-print"></i> '.__('Print'))),
                Button::make(array('extend'=>'reload', 'text'=>'<i class="fas fa-redo"></i> '.__('Reload'))),
                Button::make(array('extend'=>'colvis', 'text'=>'<i class="fas fa-eye"></i> '.__('Column visibility')))
            //Button::make('reset'),


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
            Column::make('priority')->title(__('Priority')),
            Column::make('request_type')->title(__('Type')),
            Column::make('start_date')->title(__('Start Date')),
            Column::make('last_update')->title(__('Last Update'))->hidden(true),
            Column::make('caller_id_friendlyname')->title(__('Caller')),
            Column::make('agent_id_friendlyname')->title(__('Agent')),
            Column::make('status')->title(__('Status')),
          //  Column::make('site_name')->title(__('Location'))->hidden(true),
          //  Column::make('service_name')->title(__('Service'))->hidden(true),
          //  Column::make('module_name')->title(__('Module'))->hidden(true),
            Column::make('request_type')->title(__('Request type'))->hidden(true),
//            Column::computed('action')->title(__('Action'))->addClass('align-middle text-center')
//                ->orderable(false)
//                ->searchable(false)
//                ->exportable(false)
        );

//        $user = Auth::user();
////        if ($user->can('view_request')) {
//            array_push($columns,Column::computed('action')->title(__('Action'))->addClass('align-middle text-center')
//                ->orderable(false)
//                ->searchable(false)
//                ->exportable(false));
////        }

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
    protected function filename(): string
    {
        return 'OpenedRequests_' . date('YmdHis');
    }
}

