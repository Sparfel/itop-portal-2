<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Itop\ItopWebserviceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null)
    {

        $user = Auth()->user();
        if (is_null($id)) {$id = $user->id; }
        $pref = session('preferences');

  //      dump($pref); // on doit forcément avoir des pref une fois loggé, a voir dans le longinController
//
        //On récupère la version d'iTop
        $itopWS = new ItopWebserviceRepository();
        $itop = $itopWS->getiTopVersion()->first();
        //Le User a-t-il un compte iTop ?
        $itopAccountQuery = $itopWS->getItopUser($user->itop_id);
        if (isset($itopAccountQuery)) {$itopAccount = $itopAccountQuery->first();}
        else {$itopAccount = null;}

        //Gestion des dates pour les statistiques
        //On récupère les dates mémorisées
        $AStatInTimeDates = $pref->getStatInTimePref();
        if (is_null($AStatInTimeDates->get('start_date'))){
            $startDate = Carbon::now()->addYears(-1);
            $stat_start = $startDate->format('d/m/Y');}
        else {$stat_start = $AStatInTimeDates->get('start_date');
            //dd($stat_start);
            $startDate = Carbon::createFromFormat('d/m/Y',  $stat_start);}
        if (is_null($AStatInTimeDates->get('end_date'))){
            $endDate = Carbon::now();
            $stat_end = $endDate->format('d/m/Y');}
        else {
            $stat_end = $AStatInTimeDates->get('end_date');
            $endDate = Carbon::createFromFormat('d/m/Y',  $stat_end);}
        if ($endDate < $startDate) //pas possible, on prend les 12 derniers mois
        {$stat_start = Carbon::now()->addYears(-1)->format('d/m/Y');
            $stat_end = Carbon::now()->format('d/m/Y');}

        return view('frontend.profile.profile',compact('user', 'pref','itop','itopAccount','stat_start','stat_end'));//, 'locations','preflocations'));
    }


    public function store(Request $request)
    {
        $Adatas = $request->post();
        $user = Auth()->user();
        $pref = session('preferences');

        //On récupère la version d'iTop
        $itopWS = new ItopWebserviceRepository();
        $itop = $itopWS->getiTopVersion()->last();
        //Le User a-t-il un compte iTop ?
        $itopAccountQuery = $itopWS->getItopUser($user->itop_id);
        if (isset($itopAccountQuery)) {$itopAccount = $itopAccountQuery->first();}
        else {$itopAccount = null;}

        //Onglet Preference
        //changement de préférences, on stocke le changement et on met la session à jour
        if (isset($Adatas['userFilter'])) {$pref->setUserFilter(filter_var($Adatas['userFilter'], FILTER_VALIDATE_BOOLEAN));}
        else {$pref->setUserFilter(false);}
        if (isset($Adatas['userFilterClosedRequest'])) {$pref->setUserFilterClosedRequest(filter_var($Adatas['userFilterClosedRequest'], FILTER_VALIDATE_BOOLEAN));}
        else {$pref->setUserFilterClosedRequest(false);}

//        if (isset($Adatas['userLocation'])) {
//            $userLoc = collect();
//            $Location = new Location();
//            foreach ($Adatas['userLocation'] as $loc_id) {
//                $RowLoc = $Location::find($loc_id);
//                $userLoc->put($loc_id, $RowLoc->name);
//            }
//            //On stocke en base le changement
//            $pref->setUserLocationFilter($userLoc);
//        }
//        $locations = $pref->locations_list; // Json des locations
        $locations = $pref->getOrgLocationFilter($pref->paramName_Location); // Json des locations
        if ($request->input($pref->paramName_RequestUserLocation) !== null) {
            $locations_filter = $request->input($pref->paramName_RequestUserLocation);
            $locations_filter_id = array_flip($locations_filter);
            $locations_filter = $locations->intersectByKeys($locations_filter_id);
            //On mémorise la préférence
            $pref->setOrgLocationFilter( $pref->paramName_RequestUserLocation, $locations_filter);
        }

//        if (isset($Adatas['statLocation'])) {
//            $statLoc = collect();
//            $Location = new Location();
//
//            foreach ($Adatas['statLocation'] as $loc_id) {
//                $RowLoc = $Location::find($loc_id);
//                $statLoc->put($loc_id, $RowLoc->name);
//            }
//            //On stocke en base le changement
//            $pref->setStatUserLocationFilter($statLoc);
//        }
        if ($request->input($pref->paramName_StatUserLocation) !== null) {
            $locations_filter = $request->input($pref->paramName_StatUserLocation);
            $locations_filter_id = array_flip($locations_filter);
            $locations_filter = $locations->intersectByKeys($locations_filter_id);
            //On mémorise la préférence
            $pref->setOrgLocationFilter( $pref->paramName_StatUserLocation, $locations_filter);
        }

        // on mémorie les dates
        if (isset($Adatas['userFilter']) && $Adatas['datefilter']!== null) {
            $Adate = explode('-', $Adatas['datefilter']);
            //dd($Adate);
            $startDate = $Adate[0];
            $endDate = $Adate[1];
            $pref->setStatInTimePref($startDate,$endDate);
        }
        //On récupère les dates mémorisées pour affichage
        $AStatInTimeDates = $pref->getStatInTimePref2();
        $stat_start = $AStatInTimeDates->get('start_date');
        $stat_end = $AStatInTimeDates->get('end_date');

        //Onglet Setting
        if (isset($Adatas['first_name'])){ $user->first_name = $Adatas['first_name'];}
        if (isset($Adatas['last_name'])){ $user->last_name = $Adatas['last_name'];}
        if (isset($Adatas['phone'])){ $user->internal_phone = $Adatas['phone'];}
        if (isset($Adatas['mobile_phone'])){ $user->mobile = $Adatas['mobile_phone'];}
        if (isset($Adatas['gender'])){ $user->gender = $Adatas['gender'];}
        if (isset($Adatas['email'])){ $user->email = $Adatas['email'];}

        //Onglet Professionnel
       /* if (isset($Adatas['office1'])){ $user->office_id = $Adatas['office1'];}
        if (isset($Adatas['office2'])){ $user->office_id_2 = $Adatas['office2'];}
        if (isset($Adatas['dect_phone'])){ $user->dect_phone = $Adatas['dect_phone'];}
        if (isset($Adatas['cellphone_id'])){ $user->cellphone_id = $Adatas['cellphone_id'];}
       */

        //Onglet Personnel
        if (isset($Adatas['address'])){ $user->address = $Adatas['address'];}
        if (isset($Adatas['postal_code'])){ $user->postal_code = $Adatas['postal_code'];}
        if (isset($Adatas['city'])){ $user->city = $Adatas['city'];}
        if (isset($Adatas['country'])){ $user->country = $Adatas['country'];}
        if (isset($Adatas['is_address_visible'])){ //a revoir cette gestion 0/1 de boolean !!
            if ($Adatas['is_address_visible']=='on') {$user->is_address_visible = 1;}}
        else {$user->is_address_visible = 0;}

        $user->save();


        return view('frontend.profile.profile',compact('user', 'pref','itop','itopAccount','stat_start','stat_end'));
    }


    public function verifyOldPassword(Request $request)
    {   \Log::debug($request);

        $user = auth()->user();
        $isValid = Hash::check($request->input('password'), $user->password);
        return response()->json(['valid' => $isValid]);
    }

    public function changePassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
//            'newPassword' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[\W]/',
            'newPassword' => 'required|string|min:6',

        ]);

        // Vérifier si la validation échoue
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // Code 422 pour les erreurs de validation
        }

        // Si la validation réussit
        $user = auth()->user();
        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return response()->json(['success' => true]);
    }

}
