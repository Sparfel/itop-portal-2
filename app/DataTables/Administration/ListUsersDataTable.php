<?php

namespace App\DataTables\Administration;

use App\DataTables\DataTableTrait;
//use App\Models\ListUser;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ListUsersDataTable extends DataTable
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
            ->editColumn('action', function ($User) {
                return '<a href="#" class="edit btn btn-danger btn-sm" target="_self" onclick="delUser('.$User->id.')"><i class="fas fa-trash-alt"></i></a>'
                    . ' '.
                    '<a href="#" class="edit btn btn-info btn-sm" target="_self" onclick="majUser('.$User->id.')"><i class="fas fa-user-edit"></i></a>'
                    ;
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->map(function ($role) {
                    // Ajouter une condition pour attribuer une classe spécifique selon le rôle
                    $badgeClass = '';

                    if ($role->name == 'Administrator') {
                        $badgeClass = 'badge-danger';  // Couleur rouge pour Administrateur
                    } elseif ($role->name == 'User') {
                        $badgeClass = 'badge-info';  // Couleur bleue pour User
                    } else {
                        $badgeClass = 'badge-secondary';  // Par défaut, couleur grise
                    }

                    return "<span class='badge {$badgeClass}'>{$role->name}</span>";
                })->implode(' ');
            })
            ->addColumn('checkboxes', '<div class="custom-control custom-checkbox">
                          <input class="custom-control-input custom-control-input-primary custom-control-input-outline text-center"
                                type="checkbox" id="customCheckbox{{ $id }}" value="{{ $id }}" name="customCheckbox" >
                          <label for="customCheckbox{{ $id }}" class="custom-control-label"></label>
                        </div>')
            ->editColumn('is_active', function($Colldatas) {
                if ($Colldatas->is_active == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
                    $link = '<div class="active"></div><i class="fas fa-minus-circle text-danger"></i></div>';}
                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
                    $link =  '<div class="active"><i class="fas fa-check text-success"></i></div>';}
                {return $link;}
            })
//            ->editColumn('is_in_itop', function($Colldatas) {
//                if ($Colldatas->is_in_itop == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
//                    $link = '<i class="fas fa-minus-circle text-danger"></i>';}
//                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
//                    $link =  '<i class="fas fa-check text-success"></i>';}
//                {return $link;}
//            })
//            ->editColumn('has_itop_account', function($Colldatas) {
//                if ($Colldatas->has_itop_account == 0) {//$link = '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-minus-circle text-danger"></i></a>';}
//                    $link = '<i class="fas fa-minus-circle text-danger"></i>';}
//                else {//$link =  '<a href="' . route('team.activeuser', $Colldatas->itop_id) . '" target="_self"><i class="fas fa-check text-success"></i></a>';}
//                    $link =  '<i class="fas fa-check text-success"></i>';}
//                {return $link;}
//            })
->rawColumns(['id','checkboxes','action','is_active','roles']);;
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        //Jointure externe mais nécessite un import iTop
//        $Colldatas = User::join('roles','roles.id','=','users.role_id')
//            ->leftjoin('itop_users', 'itop_users.itop_id','=','users.itop_id')
//            ->leftjoin('organizations', 'itop_users.org_id','=','organizations.id')
//            ->leftjoin('locations', 'itop_users.location_id','=','locations.id')
//            ->select('organizations.name as org_name',
//                    'locations.name as location_name',
//                    'roles.name as role_name',
//                    'users.*');
//        return $Colldatas;

//        $Colldatas = User::leftJoin('model_has_roles as mhr_users', 'mhr_users.model_id', '=', 'users.id')
//            ->leftJoin('roles', 'mhr_users.role_id', '=', 'roles.id')
//            //->leftJoin('organizations', 'users.org_id', '=', 'organizations.id')
//            //->leftJoin('locations', 'users.loc_id', '=', 'locations.id')
//            ->select(
//                //'organizations.name as org_name',
//                //'locations.name as location_name',
//                'roles.name as role_name',
//                'users.*'
//            );
//        $Colldatas = User::select('users.*');
        $Colldatas = User::with('roles');
        return $Colldatas;

//        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('listusers-table')
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
                    'text' =>'<i class="far fa-check-square"></i> ' . __('Check/Uncheck All'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {checkAll(); }',
                ]),
                [
                    'text' =>'<i class="far fa-trash-alt text-danger"></i> ' . __('Delete'),
                    'className' => '',
                    'action' => 'function(e, dt, node, config) {delUsers(); }',
                ],
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
        return [

            Column::make('id')->hidden(true),
            Column::make('checkboxes')->title('')->orderable(false)->addClass('align-middle text-center'),
            Column::make('email'),
            Column::make('first_name'),
            Column::make('last_name'),
            Column::make('itop_id'),
            //Column::make('org_name')->name('organizations.name'), //nommage nécessaire pour la recherche dans la grille car jointure
           // Column::make('location_name')->name('locations.name'),
            Column::make('domain'),
            Column::make('roles'),
//            Column::make('role_name')->name('roles.name'),
            Column::make('is_active')->addClass('align-middle text-center'),
//            Column::make('created_at'),
//            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() : String
    {
        return 'ListUsers_' . date('YmdHis');
    }
}
