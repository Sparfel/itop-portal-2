<?php

namespace App\Http\Controllers\Backend\Itop;

use App\DataTables\Administration\OrganizationsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(OrganizationsDataTable  $dataTable){

        return $dataTable->render('backend.itop.list-org');
    }


    public function editItopOrg(Request $request){
        //Info sur l'organization'
//        $id = $request->itop_org_id;
//        $itoporg = Organization::find($id);
//        return response()->json(['itoporg'=> $itoporg]);


        // Vérifie que l'ID est bien présent
        $id = $request->itop_org_id;
//        \Log::debug($request);
        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID de l’organisation manquant.'], 400);
        }

        // Recherche de l'organisation
        $itoporg = Organization::find($id);

        if (!$itoporg) {
            return response()->json(['success' => false, 'message' => 'Organisation non trouvée.'], 404);
        }

        // Retourne l'organisation en JSON
        return response()->json(['success' => true, 'itoporg' => $itoporg]);
    }

    public function deleteItopOrg(Request $request){
        if ($request->input('id') !== null) {
            $ItopOrg = Organization::find($request->input('id'));
            $ItopOrg->delete();
            return response()->json(['iditoporg'=>$request->input('id')]);
        }
    }

    public function deleteItopOrgs (Request $request){
        $result = '';
        //Log::debug($request);
        if ($request->input('listId') !== null) {
            foreach ($request->input('listId') as $id) {
                $itopOrg = Organization::find($id);
                $itopOrg->delete();
            }
        }
        return response()->json($result);
    }
}
