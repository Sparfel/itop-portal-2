<?php

namespace App\Http\Controllers\Backend\Itop;

use App\DataTables\Administration\LocationsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(LocationsDataTable  $dataTable){
//        $model = new Location;
//        $query = $model->newQuery()->with('organization');
////
//        dd($query->toSql(), $query->getBindings());
        return $dataTable->render('backend.itop.list-loc');
    }


    public function editItopLoc(Request $request){
        $id = $request->itop_loc_id;
        if (!$id) {
             return response()->json(['success' => false, 'message' => 'ID du site manquant.'], 400);
        }
        // Recherche de l'organisation
        $itoploc = Location::find($id);
        if ($itoploc) {
            // Ajout du nom de l'organisation
            $itoploc->organization_name = $itoploc->organization ? $itoploc->organization->name : null;
        }
        if (!$itoploc) {
            return response()->json(['success' => false, 'message' => 'Localisation non trouvée.'], 404);
        }
        // Retourne l'organisation en JSON
        return response()->json(['success' => true, 'itoploc' => $itoploc]);
    }

    public function deleteItopOrg(Request $request){
        if ($request->input('id') !== null) {
            $ItopLoc = Location::find($request->input('id'));
            $ItopLoc->delete();
            return response()->json(['iditoploc'=>$request->input('id')]);
        }
    }

    public function deleteItopLocs (Request $request){
        $result = '';
        //Log::debug($request);
        if ($request->input('listId') !== null) {
            foreach ($request->input('listId') as $id) {
                $itopLoc = Location::find($id);
                $itopLoc->delete();
            }
        }
        return response()->json($result);
    }
}
