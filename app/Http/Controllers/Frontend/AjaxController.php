<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ItopUser;
use App\Models\Location;
use App\Models\Office;
use App\Models\Organization;
use App\Models\Software;
use App\Models\User;
use App\Models\Version;
use App\Repositories\Itop\User\Account;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Ce controller contient les différentes fonction appelé Ajax dans les différentes pages

class AjaxController extends Controller
{
    //
    // On sécurise l'accès, il faut être connecter pour ouvrir un instance
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function getServices(Request $request){
        if ($request->input('location_id') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listServices = $itopWS->getServices($request->input('location_id'));
            //dd(response()->json($listServices));
            //dd($listServices);
          //  Log::info($listServices);
          //  Log::info(json_encode($listServices));
            //return $listServices;
            return response()->json($listServices);
        }
        else {return null;}
    }

    /*
     * Version avezc VUEJS
     */

    public function getLocations(){
        /*$pref = session('preferences');
        $Locations = $pref->locations_list;
        $user_loc_id = $pref->loc_id;
        return response()->json($Locations);*/

        $itopWS = new ItopWebserviceRepository();
        $listLocation = $itopWS->getLocations();
        return response()->json($listLocation);
    }

    public function getLocationServices(Request $request){
        //Log::debug($request);
        //Log::debug($request->input('location'));
        if ($request->input('location') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listServices = $itopWS->getServices($request->input('location'));
            //dd(response()->json($listServices));
            //dd($listServices);
//            Log::info($request->input('location'));
//            Log::info(json_encode($listServices));
            //return $listServices;
            return response()->json($listServices);
        }
        else {return null;}
    }

    public function getServiceModules(Request $request) {
        if ($request->input('service') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listModules = $itopWS->getModules($request->input('location'),$request->input('service'));
            //dd(response()->json($listServices));
            //dd($listServices);
            //Log::info($listModules);
            // Log::info(json_encode($listServices));
            //return $listServices;
            return response()->json($listModules);
        }
        else {return null;}
    }

    public function getComponentCreatingIssue(Request $request){
        if ($request->input('service') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listComponents = $itopWS->getComponentCreatingIssue($request->input('service'));
            return response()->json($listComponents);
        }
        else {return null;}
    }

    public function getFailureMode(Request $request){
        if ($request->input('component') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listFailures = $itopWS->getFailureMode($request->input('component'));
            return response()->json($listFailures);
        }
        else {return null;}
    }


    public function getOrganizations(){
        $itopWS = new ItopWebserviceRepository();
        $listOrganization = $itopWS->getOrganizations();
//        \Log::debug($listOrganization);
        return response()->json($listOrganization);
    }

    public function getOrganizationLocations(Request $request){
        if ($request->input('organization') !== null) {
            $itopWS = new ItopWebserviceRepository();
            $listLocation = $itopWS->getOrganizationLocations($request->input('organization'));
            return response()->json($listLocation);
        }
    }

    /*
     * Ajax call for version and software
     */
    public function getSoftwares(){
       $Softwares = Software::all();
       return response()->json($Softwares);
    }

    public function getSoftwareVersions(Request $request){
        if ($request->input('software') !== null) {
            $Version = Version::where('software_id', $request->input('software'))->get();
            return response()->json($Version);
        }
    }

    /*
     * Ajax call for users creations
     */

//    public function createUsers(Request $request){
//        $result = '';
//        if ($request->input('listId') !== null) {
//            foreach ($request->input('listId') as $id) {
//                $itopUser = ItopUser::where('id', $id)->first();
//                $account = new Account($itopUser);
//                $result = $account->next();
//                Log::debug($result);
//            }
//        }
//        return response()->json($result);
//    }

    public function createUsers(Request $request) {
        try {
            $results = [];

            if ($request->has('listId') && is_array($request->input('listId'))) {
                foreach ($request->input('listId') as $id) {
                    $itopUser = ItopUser::where('id', $id)->first();

                    if (!$itopUser) {
                        throw new \Exception("Utilisateur avec l'ID $id introuvable.");
                    }

                    $account = new Account($itopUser);
                    $result = $account->next();
                    $results[] = $result;

                    Log::debug("Utilisateur $id traité : " . json_encode($result));
                }
            } else {
                throw new \Exception("La liste des IDs est vide ou invalide.");
            }

            return response()->json([
                'success' => true,
                'message' => 'Tous les utilisateurs ont été traités avec succès.',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur dans createUsers : " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => "Erreur : " . $e->getMessage()
            ], 400); // Code HTTP 400 pour signaler une erreur côté client
        }
    }


    public function storeItopUser(Request $request){
        $ItopUser = new ItopUser();
        $ItopUser = ItopUser::firstOrNew(array('id' => $request->input('id')));

        if ($request->input('id') !== null) {
            $ItopUser->id = $request->id;
        }
        $ItopUser->first_name = $request->first_name;
        $ItopUser->last_name = $request->last_name;
        $ItopUser->email = $request->email;
        $ItopUser->login = $request->email;
        $ItopUser->phone = $request->phone;
        $ItopUser->mobile_phone = $request->mobile;
        $ItopUser->org_id = $request->organization;
        $ItopUser->location_id = $request->location;

        $DBorg = new Organization();
        $organisationRow = $DBorg::find($request->organization);
        $ItopUser->org_name = $organisationRow->name;

        $DBloc = new Location();
        $locationRow = $DBloc::find($request->location);
        $ItopUser->location_name = $locationRow ? $locationRow->name : null;

        $ItopUser->role_id = $request->role;
        $ItopUser->comment = $request->comment;
        if ($request->input('is_local') !== null) {$ItopUser->is_local = $request->is_local;}
        else {$ItopUser->is_local = 0;}
        if ($request->input('is_in_itop') !== null) {
            $ItopUser->is_in_itop = $request->is_in_itop;
            //MAJ côté iTop des données du Contact, car il existe
            $this->PostStoreItopUser($ItopUser);
        }
        else {$ItopUser->is_in_itop = 0;}
        if ($request->input('has_itop_account') !== null) {$ItopUser->has_itop_account = $request->has_itop_account;}
        else {$ItopUser->has_itop_account = 0;}
        $result = $ItopUser->save();


        return response()->json($result);

        /*
         * 'email' : $("#email").val(),
                        'phone' : $("#phone").val(),
                        'mobile' : $("#mobile").val(),
                        'organization' : $("#organization").val(),
                        'location' : $("#location").val(),
                        'role' : $("#role").val(),
                        'comment' : $("#comment").val(),
         */

    }

    private function PostStoreItopUser(ItopUser $ItopUser){
        $itopWS = new ItopWebserviceRepository();
//        \Log::debug('Location ID '.$ItopUser->location_id);
//        $itopPerson = $itopWS->updateUser($ItopUser->itop_id,
//                            $ItopUser->login,
//                            $ItopUser->first_name,
//                            $ItopUser->last_name,
//                            $ItopUser->email,
//                            $ItopUser->org_id,
//                            $ItopUser->location_id,
//                            $ItopUser->phone,
//                            $ItopUser->mobile_phone,
//                            $ItopUser->comment);
        try {
            $itopPerson = $itopWS->updateUser(
                $ItopUser->itop_id,
                $ItopUser->login,
                $ItopUser->first_name,
                $ItopUser->last_name,
                $ItopUser->email,
                $ItopUser->org_id,
                $ItopUser->location_id,
                $ItopUser->phone,
                $ItopUser->mobile_phone,
                $ItopUser->comment
            );

            // Vérification de la réponse si nécessaire
            if (!$itopPerson) {
                throw new Exception("La mise à jour de l'utilisateur a échoué.");
            }

        } catch (Exception $e) {
            // Log de l'erreur (important pour le debugging)
            \Log::error("Erreur lors de la mise à jour de l'utilisateur sur iTop : " . $e->getMessage());

            // Optionnel : Afficher un message utilisateur
            echo "Erreur : Impossible de mettre à jour l'utilisateur. Veuillez réessayer plus tard.";
        }
    }

    private function PostStoreItopLocation(Location $location){
        $itopWS = new ItopWebserviceRepository();
//        \Log::debug('Location ID '.$ItopUser->location_id);

        try {
            $itopLocation = $itopWS->updateLocation($location->id,
                $location->address,
                $location->city,
                $location->postal_code,
                $location->country,
                $location->status);

            // Vérification de la réponse si nécessaire
            if (!$itopLocation) {
                throw new Exception("La mise à jour du site a échoué.");
            }

        } catch (Exception $e) {
            // Log de l'erreur (important pour le debugging)
            \Log::error("Erreur lors de la mise à jour du site sur iTop : " . $e->getMessage());

            // Optionnel : Afficher un message utilisateur
            echo "Erreur : Impossible de mettre à jour du site. Veuillez réessayer plus tard.";
        }
    }

    public function storeItopOrg(Request $request){
        $ItopOrg = Organization::firstOrNew(array('id' => $request->input('id')));
        if ($request->input('id') !== null) {
            $ItopOrg->id = $request->id;
        }
        $ItopOrg->deliverymodel_id_friendlyname = $request->deliverymodel_id_friendlyname;
        $result = $ItopOrg->save();
        return response()->json($result);
 }

    public function storeItopLoc(Request $request)
    {
        $itopLoc = Location::firstOrNew(['id' => $request->input('id')]);

        if ($request->input('id') !== null) {
            $itopLoc->id = $request->id;
        }

        $itopLoc->name = $request->name;
        //$itopLoc->org_id = $request->org_id;
        $itopLoc->phonecode = $request->phonecode;
        $itopLoc->address = $request->address;
        $itopLoc->postal_code = $request->postal_code;
        $itopLoc->city = $request->city;
        $itopLoc->country = $request->country;
        $itopLoc->latitude = $request->latitude;
        $itopLoc->longitude = $request->longitude;
        $itopLoc->is_active = $request->has('is_active') ? 1 : 0;
        $itopLoc->is_localized = $request->has('is_localized') ? 1 : 0;
        $itopLoc->deliverymodel_id = $request->deliverymodel_id;
        $itopLoc->deliverymodel_id_friendlyname = $request->deliverymodel_id_friendlyname;

        $result = $itopLoc->save();
        $this->PostStoreItopLocation($itopLoc);
        return response()->json(['success' => $result, 'itopLoc' => $itopLoc]);
    }


    public function ChangeOrgUser2(Request $request){
        //dump($request);
        $pref = session('preferences');
        $org_id = $request->organization;
        //dump($org_id);
        $org_name = Organization::find($org_id)->name;
        //dump($org_name);
        $pref->setUserOrganization($org_id,$org_name);
        $pref->initOrgLocationFilter();
//
        return response()->json($pref);
    }

    public function ChangeOrgUser($org_id){
        //On va vérifier qu'on autorise ce changement d'organisation
        $pref = session('preferences');
        $changeOrg = false;
        $current_org_id = $pref->org_id;
        //Ceinture et bretelle, on revérifie que l'utilisateur est mult-org.
        if (Auth::user()->is_multi_organization == 1)
        {
            $DBOrg = new Organization();
            if (Auth::user()->is_staff == 1) // User Fives => on propose tous les clients
                {$LstOrg = $DBOrg->getListCustOrg(); //liste des clients accessibles
                }
            else{// autre utilisateur multi_org, on affiche les organisation soeur
                $pref = session('preferences');
                $LstOrg = $DBOrg->getListSisterOrg($current_org_id);
            }

            if ($LstOrg->contains('id',$org_id)) { // l'organisation cible fait partie de la liste, donc OK
                $org_name = Organization::find($org_id)->name;
                $changeOrg = true;
            }

            if ($changeOrg) {
                //On modifie les préférences
                $pref->setUserOrganization($org_id,$org_name);
                $pref->initOrgLocationFilter();
                return back();
            }
            else {
                // pas autorisé à changer d'organisation, on aborde !
                abort(499, 'Something went wrong');
            }

        }
    }


}
