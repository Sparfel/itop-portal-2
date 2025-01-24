<?php

namespace App\Repositories;

use App\Models\User as User;
use App\Models\UserPreference;
use App\Models\UserPreference as UserPref;
use App\Repositories\Itop\ItopWebserviceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class PreferenceRepository
{
    /*
    * Cette classe permet de charger en session les préférences utilisateurs, utilisées un peu partout ensuite.
    */
    /**
     * @var mixed
     */

    public $paramName_UserFilter = 'USER_FILTER_OPENED_REQUEST';
    public $paramName_LocationFilter = 'Location_Filter';
    public $paramName_UserFilterClosedRequest = 'USER_FILTER_CLOSED_REQUEST';
    public $paramName_UserLanguage = 'USER_LANGUAGE';
    public $paramName_Location = 'LOCATION';
    public $paramName_UserLocation = 'REQUEST_USER_LOCATION';
    public $paramName_RequestUserLocation = 'REQUEST_USER_LOCATION';
    public $paramName_StatUserLocation = 'STAT_USER_LOCATION';
    public $paramName_StatInTime = 'STAT_IN_TIME';
    public $paramName_UserMode = 'USER_MODE';
    public $paramName_UserOrganization = 'USER_ORGANIZATION';
    public $paramName_UserYear = 'USER_YEAR';

    // Donnée lié au compte Portail iTop
    public $user_itop_account_id; // Id du compte utilisateur iTop (utile pour signer les public log [principe imposé par iTop])
    public $user_itop_account_profile;
    public $user_itop_account_allowedOrgId;
    public $user_itop_login_account;

    public $user_id; // Id du user Interne au portail
    public $itop_id; //redondant avec $_user_id mais permet de modifier un minimum de code $MOD 31.10.2017, ELOZ
    public $user_name;
    public $user_first_name;
    public $org_id;
    public $org_name;
    public $loc_id;
    public $loc_name;
    public $email;
    public $language;
    public $userFilter; //Filtre sur les données du user
    public $AyearFilter; // Filtre sur les années
    public $AyearList ; // Liste des années
    public $locations_list; // Liste des sites
    public $locations_filter; // Filtre sur les sites pour les ticket
    public $Clocations_filter; // Collect contenant les Filtres sur les sites pour les ticket

    // Statistique dans le temps
    public $AStatInTime = array();
    public $stat_locations_filter; // Filtre sur les sites pour les statistique dans le temps

    public $user_mode; // standard or expert

    public $AhomeServicesPosition;
    public $gridWidth; // largeur de la grille des listes

    public $is_production; // le user est-il lié sur iTop de production
    public $is_staff; // l'utilisateur est il membre de Fives ?

    private $user_itop_id;


    public function __construct(User $user)
    {
        $this->user_id = $user->id; // ID du user
        $this->user_itop_id = $user->itop_id; // identifiant iTop du user

        $itopWS = new ItopWebserviceRepository();
        $datas = $itopWS->getInfoContactFull($user->itop_id);

        if (is_null($datas) or $datas == false) {
            Auth::logout();
            abort(499, 'Something went wrong');}

        $data = $datas->first(); // on prend la première entrée du tableau, et la seule car unique

        $this->org_id = $data->org_id; // organisation du user
        $this->org_name = $data->org_name; // nom de l'organisation du user
        $this->loc_id = $data->location_id; // site par défaut du user
        $this->loc_name = $data->location_name; // nom du site par defaut du user


        // liste des sites de l'organisation
//        //$this->locations_list = collect($itopWS->getLocations($data->org_id)); // liste des sites de l'organisation
//        $WSlocations = $itopWS->getLocations($this->org_id);
//        $this->locations_list = $WSlocations->mapWithKeys(function ($item) {
//            return [$item->id => $item->name];
//        });
        $this->locations_list = $this->getLocationList();
        //Une fois qu'on a la liste, on intialise la structure
        $this->initOrgLocationFilter();


        $this->locations_filter = $this->getLocationFilter(); // liste des sites filtrés par le user

        $this->userFilter = $this->getUserFilter(); // on filtre sur les tickets du user
        $this->userFilterClosedRequest = $this->getUserFilter(); // on filtre sur les tickets clos du user
        $this->language = $this->getUserLanguage();
        //dd($this->language);
        session(['locale' => $this->language]);
        //on récupère l'Id du compte utilisateur iTop (utile pour signer les public log [principe imposé par iTop])
        $iTopAccount = $itopWS->getItopUser($user->itop_id);
        if (isset($iTopAccount)) {
            $this->user_itop_account_id = $iTopAccount->first()->id;
            $this->user_itop_account_profile = $iTopAccount->first()->profile_list;
            $this->user_itop_account_allowedOrgId = $iTopAccount->first()->allowed_org_list;
            $this->user_itop_account_login = $iTopAccount->first()->login;
        }

        //On récupérer une plage de date pour les Stat dans le temps
        $this->AStatInTime = $this->getStatInTimePref();
        $this->stat_locations_filter = $this->getStatUserLocationFilter();

        $userMode = $this->getPref($this->paramName_UserMode);
        if (is_null($userMode)) {$this->user_mode = 'standard';}
        else {$this->user_mode = $userMode;}

        //On stocke les filtres sur les sites en une seule structure
//        $this->Clocations_filter = collect([$this->org_id => collect([$this->paramName_Location => $this->locations_list,
//                                                            $this->paramName_UserLocation => $this->locations_filter,
//                                                            $this->paramName_StatUserLocation => $this->stat_locations_filter])
//                                            ]);
//        $userPref = new UserPref();
//        $userPref->savePref($this->user_id, $this->paramName_LocationFilter,json_encode($this->Clocations_filter));


//        $this->stat_locations_filter = $this->getLocationFilter();
        session(['preferences'=> $this]);

    }


    //On récupère des données stockée pour affichage des stat
    public function getStatInTimePref()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id, $this->paramName_StatInTime);
        if (is_null($result)) {
            $start = Carbon::now()->addYear(-1)->format('d/m/Y');
            $end = Carbon::now()->format('d/m/Y');
            return collect(array('start_date' => $start, 'end_date' => $end));
        } else {
            return collect(json_decode($result, true));
        }
    }

    // Retourne un Collect
    public function getStatInTimePref2()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id, $this->paramName_StatInTime);
        $filtred = false; // boolean pour savoir si des dates sont renseigné ou non, car on renvoie tjs une date
        if (is_null($result)) { // Si pas de config, on renvoie l'année passée
            $start = Carbon::now()->addYear(-1)->format('d/m/Y');
            $end = Carbon::now()->format('d/m/Y');
            $result = collect(array('start_date' => $start,
                                    'Dstart' => Carbon::now()->addYear(-1),
                                    'end_date' => $end,
                                    'Dend' => Carbon::now(),
                                    'filtred' => $filtred)
                            );
        } else { // Si config, on la vérifie et on retourne la collection (en String et en Date Carbon)
            $ColDate = collect(json_decode($result));
            $start = $ColDate->get('start_date');
            $end = $ColDate->get('end_date');
            $filtred1 = $filtred2 = true;
            if ($start == null) {
                $start = Carbon::now()->addYears(-1)->format('d/m/Y');
                $filtred1 = false;}
            if ($end == null) {
                $end = Carbon::now()->format('d/m/Y');
                $filtred2 = false;}
            $filtred = $filtred1||$filtred2;
            //On effectue un contrôle de cohérence, au cas ou ...
            // On prend alors les 12 derniers mois
            if (Carbon::createFromFormat('d/m/Y',$start) >= Carbon::createFromFormat('d/m/Y',$end)) {
                $start = Carbon::now()->addYears(-1)->format('d/m/Y');
                $end = Carbon::now()->format('d/m/Y');
                //Si les dates sont incohérentes, on considère que pas de filtre
                $filtred = false;
            }
            $result =  collect(array('start_date' => $start,
                    'Dstart' => Carbon::createFromFormat('d/m/Y',$start),
                    'end_date' => $end,
                    'Dend' => Carbon::createFromFormat('d/m/Y',$end),
                    'filtred' => $filtred)
            );
        }
        return $result;
    }

    public function setStatInTimePref($start_date, $end_date)
    {
        $startDate = trim($start_date);
        $endDate = trim($end_date);
        // si date du jour - 1 an, on met null pour laisser la date courir sans quoi on fige la date de debut au jour J de l'année derniere
        // 01-01-2010 est une date par défaut si l'utilisateur fait un Erase des dates => on prend alors les 12 dernier mois en mettant le parametre a NULL
        if ($startDate == null || $startDate == '01-01-2010' || $startDate == Carbon::now()->addYears(-1)->format('d/m/Y')) {$startDate = null;}
        // si date du jour, on met null pour laisser la date courir sans quoi on fige la date de fin au jour J
        if ($endDate == null || $endDate == '01-01-2010' || $endDate == Carbon::now()->format('d/m/Y')) {$endDate = null;}

        $this->AStatInTime = array('start_date' => $startDate, 'end_date' => $endDate);
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id, $this->paramName_StatInTime,json_encode($this->AStatInTime));
    }



    public function getStatUserLocationFilter()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id, $this->paramName_StatUserLocation);
        if (is_null($result)) {
            $res =  $this->locations_list;
        } else {
            $res =  collect(json_decode($result, true));
        }
//        dd($res);
        return $res;
    }

    //Donnée stockée en base - Filtre Site
    //Si pas de filtre en préférence, on renvoie la liste de tous les sites
    public function getLocationFilter()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id, $this->paramName_UserLocation);
        if (is_null($result)) {
            $res =  $this->locations_list;
        } else {
            $res =  collect(json_decode($result, true));
        }
        return $res;
    }

    public function setUserLocationFilter($value)
    {
            $this->locations_filter = $value;
            $userPref = new UserPreference();
            $userPref->savePref($this->user_id, $this->paramName_UserLocation,$value);
    }

    public function setStatUserLocationFilter($value)
    {
        $this->stat_locations_filter = $value;
        //dd($value);
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id, $this->paramName_StatUserLocation,$value);

    }


    /*
     * Gestion des préférences liées aux sites
     *
     */

    //génération de la liste des sites
    public function getLocationList(){
        // liste des sites de l'organisation
        //$this->locations_list = collect($itopWS->getLocations($data->org_id)); // liste des sites de l'organisation
        $itopWS = new ItopWebserviceRepository();
        $WSlocations = $itopWS->getLocations($this->org_id);
        $locations_list = $WSlocations->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
            });
        return $locations_list;
    }

    //Initialisation de la structure que l'on stocke dans la session
    public function initOrgLocationFilter(){
        //On stocke les filtres sur les sites en une seule structure

        //on charge ce qu'il y a en base
        $userPref = new UserPreference();
        $this->Clocations_filter =  collect(json_decode($userPref->getPref($this->user_id, $this->paramName_LocationFilter),true));
//        \Log::debug($this->Clocations_filter);
        if (!($this->Clocations_filter->has($this->org_id))) {
//            \Log::debug('pas de pref pour le client '.$this->org_id);
//            $this->Clocations_filter= collect([$this->org_id => collect([$this->paramName_Location => $this->locations_list,
//                $this->paramName_RequestUserLocation => $this->getOrgLocationFilter($this->paramName_RequestUserLocation),
//                $this->paramName_StatUserLocation => $this->getOrgLocationFilter($this->paramName_StatUserLocation)])
//            ]);
            // On l'a crée
            $this->locations_list = $this->getLocationList();

            $this->Clocations_filter->put($this->org_id , collect([$this->paramName_Location => $this->locations_list,
                $this->paramName_RequestUserLocation => $this->getOrgLocationFilter($this->paramName_RequestUserLocation),
                $this->paramName_StatUserLocation => $this->getOrgLocationFilter($this->paramName_StatUserLocation)])
            );
//            \Log::debug($this->Clocations_filter);
        }
    }

    /*
     * La clé initiale étant l'organisation (permet de stocker les infos pour les multi organisations
     * Structure ['1' => ['request_user_location' => ['121' => 'Lorient', '122' => 'Ploemeur']
     *                          'stat_user_location' => ['121' => 'Lorient', '123' => 'Bordeaux']]
     *          '253' => [ ....]
     * On stocke le changement en session et dans la BDD
     */
    public function setOrgLocationFilter( $name, $list_locations){
       // $this->Clocations_filter->$this->org_id->put($name,$list_locations);
//        \Log::debug($this->Clocations_filter);
        $COrgLoc = collect($this->Clocations_filter->get($this->org_id));
        $COrgLoc->put($name,$list_locations);
        $this->Clocations_filter->put($this->org_id,$COrgLoc );
//        \Log::debug($this->Clocations_filter);
        //On mémorise le changement en base
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id, $this->paramName_LocationFilter,json_encode($this->Clocations_filter));
    }

    /*
     * Retourne une collection
     * Si pas de parametre donné, on renvoie la structure (pour l'organisation en cours)
     */
    public function getOrgLocationFilter( $name = null ){
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id, $this->paramName_LocationFilter);
        if (is_null($name)){ // si pas de parametre demandé, on renvoie la structure complete.
            if (is_null($result)) { // rien n'est stocké en BDD, aucune préférence
                //on renvoie alors la liste de tous les sites.
                \Log::debug('Result NULL, res = '.$this->locations_list);
                $res = $this->locations_list;
            } else {
                $COrgLoc = collect(json_decode($result, true));
                $res = collect($COrgLoc->get($this->org_id));
                \Log::debug('Sinon, res = '.$res);
            }
        }
        else {
            if (is_null($result)) { // rien n'est stocké en BDD, aucune préférence
                //on renvoie alors la liste de tous les sites.
                $res = $this->locations_list;
            } else { // L'entrée existe, mais la préférence demandée existe-t-elle ?
                $COrgLoc = collect(json_decode($result, true));
//                \Log::debug('Org '.$this->org_id);
                $CLoc = collect($COrgLoc->get($this->org_id));
//                \Log::debug('00'.$CLoc);
                if ($CLoc->get($name) !== null) {
                    $res = collect($CLoc->get($name));
//                    \Log::debug('1'.$res);
                } else {
                    $res = $this->locations_list;
//                    \Log::debug('2'.$res);
                }

            }
        }
        return $res;
    }




    //Filtre sur les données du user
    //Donnée stockée en base - Filtre Visu
    public function getUserFilter()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id,$this->paramName_UserFilter);
        if (is_null($result)) {return false;} // par defaut, on affiche tout.
        else {return $result;}
    }

    public function setUserFilter($value)
    {
        $this->userFilter = $value;
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id, $this->paramName_UserFilter,$value);
    }

    public function getUserFilterClosedRequest()
    {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id,$this->paramName_UserFilterClosedRequest);
        if (is_null($result)) {return false;} // par defaut, on affiche tout.
        else {return $result;}
    }

    public function setUserFilterClosedRequest($value)
    {
        $this->userFilterClosedRequest = $value;
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id, $this->paramName_UserFilterClosedRequest,$value);
    }

    //Gestion du changement d'organisation, our les utilisateur multi-organisation
    public function setUserOrganization($org_id,$org_name){
        $this->org_id = $org_id;
        $this->org_name = $org_name;
        $Adata = array('org_id' => $org_id, 'org_name'=>$org_name);
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id,$this->paramName_UserOrganization,serialize($Adata));
    }

    //Donnée stockée en base - Langue
    public function getUserLanguage() {
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id,$this->paramName_UserLanguage);
        if (is_null($result)) {$result = 'fr';}
        return $result;
    }

    public function setUserLanguage($value){
        $this->language = $value;
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id,$this->paramName_UserLanguage,$value);
    }

    public function setPref($pref_name,$value){
        $this->$pref_name = $pref_name;
        $userPref = new UserPreference();
        $userPref->savePref($this->user_id,$this->$pref_name,$value);
    }

    public function getPref($pref_name){
        $userPref = new UserPreference();
        $result = $userPref->getPref($this->user_id,$pref_name);

        return $result;
    }

}
