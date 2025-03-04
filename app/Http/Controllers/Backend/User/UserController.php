<?php

namespace App\Http\Controllers\Backend\User;

use App\DataTables\Administration\ListUsersDataTable;
use App\Http\Controllers\Controller;
//use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

//use TCG\Voyager\Models\Role;

class UserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(ListUsersDataTable  $dataTable)
    {
        $user = Auth::user();
        //dd($user);
        if (is_null($user)) {
           $itop_cfg = 0; // on spécifie le premier par défaut
        } else {$itop_cfg = $user->itop_cfg;}

        $DBRole = new Role();
        $Roles = $DBRole::all();
        //$DBOffice = new Office();
        $Offices = null; //$DBOffice->listOffice();
        $itopCfg = config('itop');
        return $dataTable->render('backend.users.list-users',compact('Roles','Offices','itopCfg'));
    }

    public function editUser(Request $request){
        //Info sur le user
        $id = $request->id;
//        $User = User::find($id);
        $User = User::with('roles')->find($id);
//        Log::debug($User);
        //On liste les différents rôles possibles
        $DBRole = new Role();
        $Roles = $DBRole::all();
//        \Log::debug($Roles);
        return response()->json(['user'=> $User, 'Roles'=> $Roles]);
    }

    public function storeUser(Request $request){
//        Log::debug($request);
        $User = User::find( $request->input('id'));
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->gender = $request->gender;
        $User->email = $request->email;
        $User->itop_id = $request->itop_id;

//        \Log::debug($request->role);
//        $User->role_id = $request->role;
        if ($request->has('role')) {
            // Récupérer les noms des rôles à partir des IDs
            $roleNames = Role::whereIn('id', $request->role)->pluck('name')->toArray();
            // Synchroniser les rôles avec les noms
            $User->syncRoles($roleNames);
        }

        $User->guid = $request->guid;
        if (!is_null($request->input('password'))) // si modification de password !
        {   $User->password = Hash::make($request->password);
            $User->guid = $request->password; // on garde le pwd en clair ? à virer à terme
        }
        $User->domain = $request->domain;
        $User->pc1 = $request->pc1;
        $User->pc2 = $request->pc2;
        $User->pc3 = $request->pc3;
        if ($request->input('is_active') !== null && filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)) {$User->is_active = 1;}
        else {$User->is_active = 0;}
        if ($request->input('is_staff') !== null && filter_var($request->is_staff, FILTER_VALIDATE_BOOLEAN)) {$User->is_staff = 1; }
        else {$User->is_staff = 0;}
        if ($request->input('is_multi_organization') !== null && filter_var($request->is_multi_organization, FILTER_VALIDATE_BOOLEAN)) {$User->is_multi_organization = 1;}
        else {$User->is_multi_organization = 0;}
        $User->itop_cfg = $request->itop_cfg;
        //
        $User->department = $request->department;
        $User->service = $request->service;
        $User->internal_phone = $request->internal_phone;
        $User->office_id = $request->office_id;
        $User->internal_phone_2 = $request->internal_phone_2;
        $User->office_id_2 = $request->office_id_2;
        $User->dect_phone = $request->dect_phone;
        $User->sda_phone = $request->sda_phone;
        $User->mobile = $request->mobile;
        $User->about = $request->about;
        //
        $User->address = $request->address;
        $User->postal_code = $request->postal_code;
        $User->city = $request->city;
        $User->country = $request->country;
        if ($request->input('is_localized') !== null && filter_var($request->is_localized, FILTER_VALIDATE_BOOLEAN)) {$User->is_localized = 1;}
        else {$User->is_localized = 0;}
        if ($request->input('is_address_visible') !== null && filter_var($request->is_address_visible, FILTER_VALIDATE_BOOLEAN)) {$User->is_address_visible = 1;}
        else {$User->is_address_visible = 0;}
        //Reste l'avatar a gérer
        $result = $User->save();
        return response()->json($result);
    }

    public function deleteUser(Request $request){
        if ($request->input('id') !== null) {
            $User = User::find($request->input('id'));
            $User->delete();
            return response()->json(['iduser'=>$request->input('id')]);
        }
    }

    public function deleteUsers (Request $request){
        $result = '';
        //Log::debug($request);
        if ($request->input('listId') !== null) {
            foreach ($request->input('listId') as $id) {
                $User = User::find($id);
                $User->delete();
            }
        }
        return response()->json($result);
    }

}
