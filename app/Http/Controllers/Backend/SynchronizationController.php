<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ItopUser;
use App\Models\Location;
use App\Models\Organization;
use App\Models\User;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SynchronizationController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view( 'vendor.voyager.synchronization.browse');
    }

    public function truncatePerson(Request $request) {
        if ($request->ajax())
        {
            $DBItopUser = new ItopUser();
            //on vide la table au préalable
            $result = $DBItopUser::truncate();

            return json_encode($result);
        }
        abort(404);
    }
    public function importPerson(Request $request) {

            if ($request->ajax())
            {
                $itopWS = new ItopWebserviceRepository();
                //On liste les personnes référencées (client).
                //On va récupérer les comptes iTop
                $persons = $itopWS->getCustomerContacts();
//                \Log::debug($persons);
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
                //on vide la table au préalable
                $DBItopUser::truncate();
                foreach ($persons as $person)
                {
                    //Mappage des champs qui changent
                    $person->itop_id = $person->id;
                    $person->id = null;
                    $person->last_name = $person->name;
                    $person->location_id = $person->location_id;
                    $person->location_name = $person->location_name;
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

            abort(404);
        }


    public function importOrg(Request $request) {
    //if ($request->ajax())
    {
        $itopWS = new ItopWebserviceRepository();
        //On liste les organisations référencées (client).
        $lstOrg = $itopWS->getOrganizations();
//        foreach ($lstOrg as $org) {
//            $Org = Organization::updateOrCreate(
//                ['id' => $org->id],
//                ['name' => $org->name,
//                    'parent_id' => $org->parent_id ,
//                    'type' => 'customer']
//            );
//        }

        if ($request['query'] =='count') {
            return count($lstOrg);
        }
        else {
            foreach ($lstOrg as $org) {
                $Org = Organization::updateOrCreate(
                    ['id' => $org->id],
                    ['name' => $org->name,
                        'parent_id' => $org->parent_id ,
                        'type' => 'customer']
                );
            }
            return json_encode(count($lstOrg));
        }
    }
//    abort(404);
    }

    public function importLoc(Request $request) {
        //if ($request->ajax())
        {
            $itopWS = new ItopWebserviceRepository();
            //On liste les organisations référencées (client).
//            $lstOrg = $itopWS->getOrganizations();
            $Atype_org = array('both','customer');
            $lstLoc = $itopWS->getAllSyncLocations($Atype_org);


            if ($request['query'] =='count') {
                return count($lstLoc);
            }
            else {
                foreach ($lstLoc as $loc) {
                    if ($loc->status == 'active') {$is_active = 1;}
                    else {$is_active = 0;}
                    $Loc = Location::updateOrCreate(
                        ['id' => $loc->id],
                        ['name' => $loc->name,
                            'org_id' => $loc->org_id,
                            'is_active' => $is_active,
                            'address' => $loc->address,
                            'postal_code' => $loc->postal_code,
                            'city' => $loc->city,
                            'country' => $loc->country,
//                    'deliverymodel_id' => $loc->deliverymodel_id,
//                    'deliverymodel_id_friendlyname' => $loc->deliverymodel_id_friendlyname
                        ]
                    );
                    if ($Loc->is_localized == 0) {
                        $Loc->localize();
                        //\Log::info("Le site ".$Loc->name." n'est pas localisé.");
                    }
                }
                return json_encode(count($lstLoc));
            }
        }
//    abort(404);
    }
}
