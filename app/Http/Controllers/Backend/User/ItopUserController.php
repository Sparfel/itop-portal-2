<?php

namespace App\Http\Controllers\Backend\User;

use App\DataTables\Administration\ListItopPersonsDataTable;
use App\Http\Controllers\Controller;
use App\Mail\NewUser;
use App\Models\ItopUser;

use App\Models\User;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
//use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Log;
use App\Repositories\Itop\User\Account;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ItopUserController  extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function massAction($ids, $comingFrom)
    {
        // Do something with the IDs
        return redirect($comingFrom);
    }

    public function syncitop(Request $request) {
        //if ($request->ajax())
        {
            $itopWS = new ItopWebserviceRepository();
            //On liste les personnes référencées (client).
            $persons = $itopWS->getCustomerContacts();


            if ($request['query'] =='count') {
                return count($persons);
            }
            else {
                //On va récupérer les comptes iTop
                $Object = 'UserLocal';
                $Afields = array('id','last_name','first_name','email','login','contactid');
                $where = 'status = "enabled" AND org_id !=1'; //On prend tout le monde sauf Fives (qui est géré par l'AD)
                $ItopAccounts = $itopWS->getObjects($Object,$Afields,$where);
                //Si on a un compte iTop, c'est que la Person est référencée dans iTop
                //on constitue donc un premier tableau avec les comptes que l'on comparera aux Persons

                $AItopAccount = array();
                foreach ($ItopAccounts as $ItopAccount) {
                    $AItopAccount[$ItopAccount->contactid]['contactid'] = $ItopAccount->contactid;
                    $AItopAccount[$ItopAccount->contactid]['login'] = $ItopAccount->login;
                    $AItopAccount[$ItopAccount->contactid]['email'] = strtolower($ItopAccount->email);
                    $AItopAccount[$ItopAccount->contactid]['last_name'] = $ItopAccount->last_name;
                    $AItopAccount[$ItopAccount->contactid]['first_name'] = $ItopAccount->first_name;
                }
                //dd($AItopAccount);
                $DBItopUser = new ItopUser();
                $DBUser = new User();
                foreach ($persons as $person)
                {
                    //Mappage des champs qui changent
                    $person->itop_id = $person->id;
                    $person->id = null;
                    $person->last_name = $person->name;
                    $person->location_id = $person->site_id;
                    $person->location_name = $person->site_name;
                    $person->is_in_itop = 1;

                    //On recherche ensuite l'éventuelle compte local au portail
                    $User = $DBUser::where('itop_id',$person->itop_id)->first();

                    if (!isset($User->id)) { // pas trouvé de compte local
                        $person->is_local = 0;
                        $person->role_id = null;
                        $person->portal_id = null;
                    }
                    else { // on a un compte local
                       // dd($User);
                        $person->is_local = 1;
                        $person->role_id = $User->role_id;
                        $person->portal_id = $User->id;

                    }
                    //On vérifie ensuite si la personne a un compte iTop.
                    if (isset($AItopAccount[$person->itop_id])) {
                        //Ok, on a un compte iTop avec ce login, on complète alors la fiche
                        $person->has_itop_account = 1;
                        //on supprime l'élément du tableau
                        unset($AItopAccount[$person->itop_id]);
                    }
                    else {$person->has_itop_account = 0;}

                    //dd($person);
                    $ItopUser = $DBItopUser::updateOrCreate(
                        ['itop_id'=>$person->itop_id],
                        json_decode(json_encode($person), true) // pour transformer StdClass en Array
                    );
                    $ItopUser->save();
                }

                return json_encode(count($persons));
            }
        }
        abort(404);
    }


    public function createusers(Request $request) {
        $itopUser = ItopUser::where('id', \request("id"))->first();
        $account = new Account($itopUser);
        $account->next();
        return redirect(route('voyager.itop-users.index'));
    }

    public function killusers(Request $request) {
        $itopUser = ItopUser::where('id', \request("id"))->first();

        //dd($itopUser->itop_id);
        //On supprime le compte iTop et le Contact iTop
        $itopWS = new ItopWebserviceRepository();
        $res = $itopWS->DeleteAccount($itopUser->itop_id);
        Log::debug('Delete iTop Account: '.$res);
        $res = $itopWS->DeleteContact($itopUser->itop_id);
        Log::debug('Delete iTop Contact '.$res);
        //On efface le compte local au portail
        User::destroy($itopUser->portal_id);

        $itopUser->is_local = 0;
        $itopUser->portal_id = null;
        $itopUser->is_in_itop = 0;
        $itopUser->itop_id = null;
        $itopUser->has_itop_account = 0;
        $itopUser->save();

        return redirect(route('voyager.itop-users.index'));
    }


    /*
     *  /Administration/
     *  utilisé dans la partie Admin hors Voyager
     *
     */
    public function listitopusers(ListItopPersonsDataTable  $dataTable){
        $DBRole = new Role();
        $Roles = $DBRole::all();

        return $dataTable->render('backend.users.list-itop-persons',compact('Roles'));
    }


//    public function editItopUser($id){
//        //dd($id);
//        $itopuser = ItopUser::findOrFail($id);
//        $DBRole = new Role();
//        $Roles = $DBRole::all();
//        //dd($itopuser);
//        return view('backend.users.new-itop-user', compact('itopuser','Roles'));
//    }

    public function editItopUser2(Request $request){
        //Info sur le itop_user
        $id = $request->itop_user_id;
//        $itopuser = ItopUser::where('itop_id', '=', $id)->first();
        $itopuser = ItopUser::find($id);
        //on liste es sites de l'organisation du user
        $itopWS = new ItopWebserviceRepository();
        $listLocation = $itopWS->getOrganizationLocations($itopuser->org_id);
        //On liste les différents rôles possibles
        $DBRole = new Role();
        $Roles = $DBRole::all();
//        Log::debug($itopuser);
        return response()->json(['itopuser'=> $itopuser, 'Roles'=> $Roles, 'Locations'=>$listLocation]);
        //return view('backend.users.new-itop-user', compact('itopuser','Roles'));
    }

    public function deleteItopUser(Request $request){
        if ($request->input('id') !== null) {
            $ItopUser = ItopUser::find($request->input('id'));
            $ItopUser->delete();
            return response()->json(['iditopuser'=>$request->input('id')]);
        }
    }

    public function deleteItopUsers (Request $request){
        $result = '';
        //Log::debug($request);
        if ($request->input('listId') !== null) {
            foreach ($request->input('listId') as $id) {
                $itopUser = ItopUser::find($id);
                $itopUser->delete();
            }
        }
        return response()->json($result);
    }


    public function notifyItopUsers (Request $request){
        $result = array();

        if ($request->input('listId') !== null) {
            foreach ($request->input('listId') as $id) {
                $itopUser = ItopUser::find($id);
                $portal_id =  $itopUser->portal_id;
                //On recherche alors le compte correspondant
                if (!is_null($portal_id)) {
                    $User = User::find($portal_id);
                    //On notifie le user
                    $this->notifyNewUser($User);
                    //On récupère alors les infos nécessaire
                    array_push($result ,$User->email);
                }
            }
        }
        return response()->json($result);
    }


    private function notifyNewUser($User) {
        //    dd($request);
        //dump($request->agent_email);
        //dump($this->getContacts($request->id));
        $subject = 'Compte d\'accès au portail Services Fives';
        //$to = $User->email;
        $to = 'emmanuel.lozachmeur@fivesgroup.com';
        $cc = ''; //Auth::user()->email;

        $newUser = array('subject' =>$subject,
            'first_name' => $User->first_name,
            'last_name' => $User->last_name,
            'email' => $User->email,
            'pwd' => $User->guid,
            'target' => $to,
            'contacts' => $cc);

        Mail::send(new NewUser($newUser));
    }

}
