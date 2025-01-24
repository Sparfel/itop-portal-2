<?php

namespace App\Repositories\Itop;

use App\Repositories\Itop\Request\InlineImage;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ItopWebserviceRepository
{

    /**
     * @var mixed
     */
    private $_username;
    private $_protocol;
    private $_password;
    private $_adress;
    private $_url;
    private $bDebug;

    private $whereFilterLocation; // clause Where permettant le filtre sur les sites
    private $whereFilterUser; // clause where permettant le filtre sur les données lié à l'utilisateur
    private $whereFilterUserClosed; // clause where permettant le filtre sur les données lié à l'utilisateur

    public function __construct($itop_config = null)
    {
        //On récupère l'instance utilisateur pour savoir sur quel iTop on se connecte.
        $user = Auth::user();
        //dd($user);
        if (is_null($user)) {
            if (is_null($itop_config)) {$itop_cfg = 0;}
            else {$itop_cfg = $itop_config;} // permet de spécifier sur quel iTop on est si le webservice est appelé par autre chose qu'un user (cron)
        } else {
            $itop_cfg = $user->itop_cfg;
            $this->_name = $user->last_name; //$session->pref->_user_name;
            $this->_first_name = $user->first_name; //$session->pref->_user_first_name;
            $this->_user_id = $user->itop_id; // $session->pref->_user_id; moi
            $this->_itop_id = $user->itop_id; // $session->pref->_user_id; moi
        }

        $config_itop = config('itop.' . $itop_cfg);
        $this->_username = $config_itop['user'];
        $this->_password = $config_itop['password'];
//        $this->_token = $config_itop['auth_token'];
        $this->_protocol = $config_itop['protocol'];
        $this->_adress = $config_itop['url'];

        $this->_url = $this->_protocol . '://' . $config_itop['url'] . '/webservices/rest.php?version=1.4';
        $this->bDebug = $config_itop['debug'];

        //dd($config_itop);

        $pref = session('preferences');
        if (isset($pref)) {
            $this->_org_id = $pref->org_id; //$session->pref->_org_id; FIVES
            $this->_loc_id = $pref->loc_id; //$session->pref->_loc_id; Lorient
        }


        $this->_OPref = session('preferences');

        //On centralise ici un filtre le filtre sur les sites
//        if (($this->_OPref->locations_filter)) {$this->whereFilterLocation = null;}
//        else {$this->whereFilterLocation = $this->getLocationFilter($this->_OPref->locations_filter);


        if (isset($this->_OPref)) {//Si connexion, on n'a pas de session
            try {
                //Ancienne méthode
                //$this->whereFilterLocation = $this->getLocationFilter($this->_OPref->locations_filter);
                //Nouvelle méthode
                $this->whereFilterLocation = $this->getLocationFilter($this->_OPref->getOrgLocationFilter($this->_OPref->paramName_RequestUserLocation));
            } catch (\Exception $exception) {
                $this->whereFilterLocation = null;
            }
        }

        // clause Where pour filter les tickets ouvert de l'utilisateur
        try {$this->whereFilterUser = $this->getUserFilter($this->_OPref->userFilter);}
        catch (\Exception $exception) {$this->whereFilterUser = null;}
        // clause Where pour filter les tickets fermés de l'utilisateur
        try {$this->whereFilterUserClosed = $this->getUserFilter($this->_OPref->userFilterClosedRequest);}
        catch (\Exception $exception) {$this->whereFilterUserClosed = null;}

    }

    public function getItopUrl()
    {
        return $this->_protocol . '://' . $this->_adress;
    }

    protected function CallWebService($aData)
    {
        $aPostData = array(
            'auth_user' => $this->_username,
            'auth_pwd' => $this->_password,
            'json_data' => json_encode($aData),
        );
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData));
        curl_setopt($curl, CURLOPT_URL, $this->_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if ($this->bDebug) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
        }

        $sResult = curl_exec($curl);
        //Log::debug($sResult);
//        dump($aData);
//        dump(curl_error($curl));
        $aResult = @json_decode($sResult, true /* bAssoc */);
        if ($aResult == null) {
            $aResult = null;
            if ($this->bDebug) {
                echo "Error: the return value from the web service could not be decoded:\n$sResult\n===================\n.";
            }
        }
        return $sResult;


    }

    public function callRestWebService($v_query,$v_fields)
    {
        $result ='Error';
        $query = rawurlencode($v_query);
        $fields = $v_fields;
        $format = 'xml';
        $url = $this->_protocol.'://'.
            $this->_username.':'.$this->_password.'@'.$this->_adress.
            '/webservices/export.php?expression='.
            $query.'&format='.
            $format.'&fields='.
            $fields.'&login_mode=basic';
        try{
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $html = file_get_contents($url,
                false,
                stream_context_create($arrContextOptions));
            $xml = simplexml_load_string($html);
            $result = $xml;
        }
        catch(\Exception $e)
        {
            echo 'La requête n\'a rien ramené.';
            echo "Message: " . $e->getMessage() . "\n";
        }
        return $result;
    }

    function xmlToArray($xml){
        $tab_result = Array();
        $i = 0;
        try {
            if (is_bool($xml)) {return null;}
            else {
                foreach($xml->children() as $row) {
                    foreach($row->children() as $child) {
                        $role = $child->getName();
                        foreach($child as $key => $value) {
                            $tab_result[$i][$role][$key]= (string) $value;
                        }
                    }
                    $i++;
                }
            }
            return $tab_result;
        } catch (\Exception $e) {

        }

    }

    public function jsonToArray($json)
    {
        $parsed_json = json_decode($json, false);
//dump($parsed_json);
        if (isset($parsed_json)) {
            switch ($parsed_json->{'code'}) {
                case 0 :
                    $objects = $parsed_json->{'objects'};
                    $Colldatas = new Collection;
                    if (isset($objects)) {
                        foreach ($objects as $object) {
                            $Colldatas->push($object->fields);
                        }
                        return $Colldatas;
                    } else {
                        return null;
                    }
                    break;
                case 100 :
                    //Log::debug($parsed_json);
                    return null;
                    break;
                default : return null;
                    break;

            }

//            if (isset($parsed_json->{'objects'})) {
//                $objects = $parsed_json->{'objects'};
//                $Colldatas = new Collection;
//                if (isset($objects)) {
//                    foreach ($objects as $object) {
//                        $Colldatas->push($object->fields);
//                    }
//                    return $Colldatas;
//                } else {
//                    return null;
//                }
//            }
//            else {return $parsed_json->{'message'};}
        } else {

            return null;}
    }


    /*
     * Récupération des ticket pour le composant VueJS du Dashboard Home
     */
    public function getListRequest4Component($clause = null, $org_id = null){
        $where = '';
//        $whereFilterLocation = $this->getLocationFilter($this->_OPref->getOrgLocationFilter($this->_OPref->paramName_RequestUserLocation));
        //On autorise selon le cas à visualiser les données d'autre organisations
        if (is_null($org_id)) { $v_org_id = $this->_OPref->org_id;}
        else {$v_org_id = $org_id;}
        if (!(is_null($clause))) {$where .= ' AND '.$clause;}
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'UserRequest',
//            'key' => 'SELECT UserRequest WHERE org_id = "'.$v_org_id.'"'.$this->whereFilterLocation. ' '. $this->whereFilterUser . ' '.$where,
            'key' => 'SELECT UserRequest WHERE org_id = "'.$v_org_id.'"'. $this->whereFilterUser . ' '.$where,
//            'output_fields' => 'id,ref,title,start_date,status,
//                                pending_reason,last_pending_date,
//                                resolution_code, resolution_date,solution',
            'output_fields' => 'id,ref,title,start_date,status,request_type,
                                caller_id_friendlyname,agent_id_friendlyname',

       );
        //dd($aData);
//        Log::debug($aData);
        return $this->jsonToArray($this->CallWebService( $aData));
        //return json_decode($this->CallWebService( $aData));
    }



    /*
        * Requete pour le détail d'un ticket en particulier
        */
    public function getInfoTicket($id, $org_id)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE id = "' . $id . '"' , //AND org_id = "' . $org_id . '"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
            'output_fields' => 'id,ref,title,description,start_date,finalclass,status,priority,org_name,org_id,resolution_code,solution,
									resolution_date,request_type,service_name,service_id,
									servicesubcategory_name,
									caller_id_friendlyname,agent_id_friendlyname,agent_id,last_update,close_date,public_log,pending_reason'

            /* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
        );
        $results = json_decode($this->CallWebService($aData), true);
        //dd($results);
        // Un seul ticket à chaque fois, donc pas besoin de retorner un tableau à plusieurs dimensions.
        if (is_null($results['objects'])) {return null;}
        else {
            if (count($results['objects']) > 0) {
                foreach ($results['objects'] as $result) {
                    $tab_result = $result['fields'];
                }
            } else $tab_result = array();
            //        return $tab_result;
            //        dd($results);
            return $this->jsonToArray($this->CallWebService($aData));
        }
    }

    //List all contact from a Ticket
    public function getTicketContacts($id)
    {
        $Afields = array('id', 'name', 'first_name', 'email', 'org_id', 'org_name', 'location_id', 'location_name', 'phone', 'mobile_phone', 'function');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person AS P
                        JOIN lnkContactToTicket AS LTC
                        ON LTC.contact_id = P.id
                        WHERE LTC.ticket_id = ' . $id,
            'output_fields' => implode(",", $Afields),
            // Attention : iTop standard class is lnkContactToTicket but lnkTicketToContact

        );
        $results = $this->CallWebService($aData);
//        \Log::debug($results);
        return $this->jsonToArray($results);
    }



    /*##########################################################################################################
     * Code issue de la version Beta Fives
     */

    //Fonction qui renvoie un morceau de clause Where afin de filter les sites que l'utilisateur souhaite voir.
    public function getLocationFilter(\Illuminate\Support\Collection $locations_filter){
        $where = '';
        if ($locations_filter instanceof \Illuminate\Support\Collection and count($locations_filter)>0)
        {
            $where .= ' AND (';
            $i = 0;
            foreach ($locations_filter as $id=>$location)
            {
                if (!($location=='All'))
                {
                    //$where .= '(site_name = "'.$location.'")';
                    $where .= '(site_id = "'.$id.'")';
                    if ($i < count($locations_filter) -1 ) { $where .= " OR ";}
                    // Si pas de site défini, on liste tout de même le ticket
                    else  { $where .= " OR (site_id = '')";}
                }
                else {
                    $where .=true;
                    break;
                } // on sort direct car All doit ramener tous les sites
                $i++;
            }
            $where .= ')';

        }
        return $where;
    }

    //Fonction qui renvoie un morceau de clause Where afin de filter les tickets de l'utilisateur.
    public function getUserFilter(String $user_filter)
    {
        $where = '';
        if ($user_filter)
        {// on filtre sur le caller_id
            $where .= ' AND caller_id = "'.$this->_itop_id.'"';
        }
        return $where;
    }

    public function getObjects($Object, $Afields, $where)
    {
        if (!is_null($where)) $where = ' WHERE ' . $where;

        $aData = array(
            'operation' => 'core/get',
            'class' => $Object,
            'key' => 'SELECT ' . $Object . $where,
            'output_fields' => implode(",", $Afields),

        );
//        \Log::debug($aData);
        $results = $this->CallWebService($aData);
//        \Log::debug($results);
        return $this->jsonToArray($results);
    }

    public function getiTopVersion(){
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'ModuleInstallation',
            'key' => 'SELECT ModuleInstallation WHERE name LIKE "itop%" AND parent_id = ""', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
            'output_fields' => 'name,version,installed'
        );
        $results = $this->CallWebService( $aData);
        $Aresults = $this->jsonToArray($results);
        return ($Aresults);
    }


    //Fonction générique permettant des requetes simple.


    //Les requêtes spécifiques selon les besoins des pages

    public function getTickets()
    {
        $where = '';
        $aData = array(
            'operation' => 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE visibility="public" AND org_id = "' . $this->_org_id . '" ' . $where . '
                        AND (status = \'new\' OR status = \'assigned\' OR status = \'qualified\' OR status = \'pending\' OR status = \'resolved\' OR status = \'escalated_tto\')',
            //Attention l'ordre ici est directement visible dans la grille de travail.
//            'output_fields' => 'ref,title,status,priority,start_date,last_update,
//                                caller_id_friendlyname,agent_id_friendlyname,
//                                id,request_type,service_name,serviceelement_name,
//                                resolution_date,resolution_code,last_pending_date,resolution_date,
//                                qualified_date,assignment_date,
//                                origin, parent_request_id_friendlyname,pending_reason,site_name'
            'output_fields' => 'ref,title,status,
                                caller_id_friendlyname,agent_id_friendlyname
                                '
        );


//        $aData = array(
//            'operation' => 'list_operations'
//        );
        $result = $this->CallWebService($aData);

        return $result;
    }

    /*
     * Liste des tickets ouverts
     *
     */
    public function getListOpenedRequest($runQuery = true)
    {
        //$filter est un tableau contenant les filtres pour la clause Wher
        $where = '';
//        if ($this->_OPref->_userFilter == 'true')
//        {// on filtre sur le caller_id
//            $where .= ' AND caller_id = "'.$this->_user_id.'"';
//        }
//        if (is_array($this->_OPref->_AyearFilter) and count($this->_OPref->_AyearFilter)>0)
//        {
//            $where .= ' AND (';
//            $i = 0;
//            foreach ($this->_OPref->_AyearFilter as $year)
//            {
//                if (!($year=='All'))
//                {$where .= '(start_date >= "'.$year.'-01-01" AND start_date <= "'.$year.'-12-31")';
//                    if ($i < count($this->_OPref->_AyearFilter) -1 ) { $where .= " OR ";}
//                }
//                else {
//                    $where .=true;
//                    break;
//                } // on sort direct car All doit ramener toutes les années
//                $i++;
//            }
//            $where .= ')';
//        }
//
//        // No location in request with iTop original version


//        $session = new Zend_Session_Namespace('Zend_Auth');
//        $AsearchCriteria = $session->ASearchCriteria;
//        //Gestion des critères de recherches
//        if (is_array($AsearchCriteria) and count($AsearchCriteria)>0)
//        {
//            $i = 0;
//            foreach ($AsearchCriteria as $key=>$value)
//            {
//                if (strlen($value)>0){ // une valeur existe, on la teste :
//                    if ($i == 0) {
//                        $where .= ' AND (';
//                        $where .= '('. $key .' LIKE "%'.$value.'%") ';
//                    }
//                    else {
//                        $where .= ' OR ('. $key .' LIKE "%'.$value.'%") ';
//                    }
//                    $i++;
//                }
//            }
//            if ($i > 0) { $where .= ')';}
//            //Zend_Debug::dump($where);
//        }
        //Zend_Debug::dump($where);
        $aData = array(
            'operation' => 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE  org_id = "' . $this->_OPref->org_id . '"  '. $this->whereFilterUser . '
							AND (status = \'new\' OR status = \'assigned\' OR status = \'qualified\' OR status = \'pending\' OR status = \'resolved\' OR status = \'escalated_tto\')',
            //Attention l'ordre ici est directement visible dans la grille de travail.
            'output_fields' => 'ref,title,status,priority,start_date,last_update,
									caller_id_friendlyname,agent_id_friendlyname,
									id,request_type,service_name,
									resolution_date,resolution_code,last_pending_date,resolution_date,
									assignment_date,
									origin, parent_request_id_friendlyname,pending_reason'
        );

        //error_log(htmlspecialchars_decode (Zend_Debug::dump($aData,'$aData')),3,"/var/tmp/mes-erreurs.log");
        if ($runQuery) {
            $results = $this->CallWebService($aData);
            $tab_result = $results;
//            $i = 0;
//            if (count($results['objects'])>0)
//                {foreach ($results['objects'] as $result) {
//                    $tab_result[$i] = $result['fields'];
//                    $i++;
//                    }
//                }
//            else $tab_result = array();
            //echo translate('Title');
            //Zend_Debug::dump($tab_result);
        } else {
            $tab_result = $aData['output_fields'];
        }

//        Log::debug($aData);
//        Log::debug($tab_result);

        return $tab_result;

    }

    public function getListClosedRequest($runQuery = true)
    {

        $aData = array(
            'operation' => 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE org_id = "' . $this->_OPref->org_id . '" '. $this->whereFilterUserClosed . '
							AND (status = \'closed\')',
            //Attention l'ordre ici est directement visible dans la grille de travail.
            'output_fields' => 'ref,title,status,priority,start_date,last_update,
									caller_id_friendlyname,agent_id_friendlyname,
									id,request_type,service_name,
									resolution_date,resolution_code,last_pending_date,
									assignment_date,close_date,
									origin, parent_request_id_friendlyname,pending_reason'
        );
        if ($runQuery) {
            $results = $this->CallWebService($aData);
            $tab_result = $results;
        } else {
            $tab_result = $aData['output_fields'];
        }
//        \Log::debug($aData);
//        \Log::debug($tab_result);
        return $tab_result;
    }



    //récupération d'un contact à partir de son id de compte iTop (utile pour les personnes ayant fait des entrées dans les logs des tickets
    public function getContactId($id_itop_account)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person FROM Person JOIN User ON User.contactid = Person.id WHERE User.id = ' . $id_itop_account,
            'output_fields' => 'id,name,first_name,phone,org_id,org_name,'
                                .'location_id, location_name'//site_id, site_name,
                                //'employee_number,department,service'
        );
        $result = json_decode($this->CallWebService($aData), true);
        //return $result;
        if ($result['code'] == '0') //success
        {
            return $result['objects'];
        } else return $result;
    }


    // pour Test
    public function listOperations()
    {
        $aData = array(
            'operation' => 'list_operations'
        );
        return $this->CallWebService($aData);
    }

    //ON récupère les infos PERSON à partir de l'email
    public function getInfoContact($email)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person WHERE email = "' . $email . '" AND status="active"',
            'output_fields' => 'id,name,first_name,phone,org_id,org_name,site_id, site_name,employee_number,department,service'
        );
        //dd($this->CallWebService( $aData));
        $result = $this->CallWebService($aData);
        //Log::debug($aData);
        //Log::debug($result);
        return $this->jsonToArray($result);
    }

    // on récupère les infos à partir de l'ID de iTop stocké dans la table USERS
    public function getInfoContactFull($itop_id)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person WHERE id ="' . $itop_id . '"',
            'output_fields' => 'id,name,first_name,email,phone,org_id,org_name,employee_number,location_id,location_name'

        );
        $result = $this->CallWebService($aData);
        //dump($result);
        if ($result == false) {
            //Fonction utilisé lors que la construction des preférence utilisateur, donc au login
            // si la fonction rend False => échec d'appel du Webservice iTop (indisponible ?)
            // on déconnecte l'utilisateur et on le redirige vers une page d'erreur
            Auth::logout();
            abort(498, 'Failure in iTop\'s webservice call');
        }
        return $this->jsonToArray($result);
    }

    public function getItopUser($contactid)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'User',
            'key' => 'SELECT User WHERE contactid = "' . $contactid . '"',
            'output_fields' => 'id,profile_list,allowed_org_list,login,email'

        );
        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }


    /*
    * Récupération des organisations
    *
    */
    public function getOrganizations()
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Organization',
            'key' => 'SELECT Organization WHERE (type = "both" OR type = "customer") AND status = "active"',
            'output_fields' => 'id,name,parent_id,type'
        );
        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }

    /*
    * Récupération des sites d'une organisation spécifiée
    *
    */
    public function getOrganizationLocations($org_id)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Location',
            'key' => 'SELECT Location WHERE org_id = "' . $org_id . '" AND status = "active"',
            'output_fields' => 'id,name'
        );

        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }

    /*
     * Récupération des sites d'une organisation
     *
     */
    public function getLocations($org_id = null)
    {
        if (is_null($org_id)) {$org_id = $this->_org_id;}
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Location',
            'key' => 'SELECT Location WHERE org_id = "' . $org_id . '" AND status = "active"',
            'output_fields' => 'id,name'
        );
        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }



    /*
     * Récupération des membres des équipes pour la page de contacts.
     *
     */

    public function getMemberTeamSupport($support)
    {
        if ($support == 'manager') {
            $ctname = 'Responsable';
        } else {
            $ctname = 'Agent de support';
        }
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            //'key' => 'SELECT lnkTeamToContact WHERE team_name = "'.$support.'"',
            'key' => 'SELECT p
							FROM Person AS p
							JOIN lnkTeamToContact AS l1
							ON l1.contact_id=p.id
							JOIN Team AS t
							ON l1.team_id=t.id
							JOIN lnkServiceCIToContact AS l2
							ON l2.contact_id = t.id
							JOIN DeliveryModel AS d
							ON l2.serviceci_id=d.id
							JOIN Organization AS o
							ON o.deliverymodel_id=d.id
							JOIN ContactType AS ct
							ON l1.role_id = ct.id
							WHERE o.id = "' . $this->_org_id . '"
							AND ((l2.role_name = "Helpdesk" AND p.name !="SPC") OR t.name LIKE "Projet%" OR t.name LIKE "SUPPORT SPC")
							AND (ct.name LIKE "%' . $ctname . '%")
							AND p.status = "active"',
            'output_fields' => 'id,name,first_name,email, phone, comment,site_name,department, service'
        );
        $results = $this->jsonToArray($this->CallWebService($aData));
        return $results;
    }

    /**
     * Import des Organisations
     */

    public function getAllActiveOrganizations($Atype = null)
    {
        $where = '';
        if ((!(is_null($Atype))) && (is_array($Atype))) {
            $where .= ' AND (';
            $i = 0;
            foreach ($Atype as $type) {
                $where .= ' type = "' . $type . '"';
                $i++;
                if ($i < count($Atype)) {
                    $where .= ' OR ';
                };
            }
            $where .= ')';
        }

        $aData = array(
            'operation' => 'core/get',
            'class' => 'Organization',
            'key' => 'SELECT Organization WHERE status ="active" ' . $where,
            'output_fields' => 'id,name,code,type,parent_id,deliverymodel_id,deliverymodel_id_friendlyname'
        );
        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
        //return $result;
    }

    /*
     * Import des sites
     */
    public function getAllLocations()
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Site',
            'key' => 'SELECT L FROM Site AS L JOIN Organization AS O ON L.org_id = O.id WHERE O.status = "active"',
            'output_fields' => 'id,name,org_id,address,postal_code,city,country,status'

        );
        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }

    /*
     * Import des Contacts (Person) de iTop de nos clients
     *
     */
    public function getCustomerContacts()
    {
        $Afields = array('id', 'name', 'first_name', 'email', 'org_id', 'org_name', 'site_id', 'site_name', 'phone', 'mobile_phone', 'comment');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person AS P JOIN Organization AS O ON P.org_id = O.id
							WHERE P.org_id != "1" AND P.status = "active"
								AND (P.email !="" AND P.email != "fsy.maintenance@fivesgroup.com")
								AND O.type="customer"',
            'output_fields' => implode(",", $Afields),

        );
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    }


    /*
     * Creation des comptes utilisateur sur iTop
     * Objet Person et UserLocal, UserLDAP
     *
     */

    public function CreateItopAccount($contact_id, $login, $first_name, $last_name, $email, $org_id, $class = 'UserLocal')
    {
        $aData = array(
            'operation' => 'core/create',
            'comment' => 'Création via Portail Fives V2 par ' . $this->_first_name . ' ' . $this->_name,
            'class' => $class,
            'output_fields' => 'id, login',
            'fields' => array(
                'contactid' => $contact_id, //'SELECT Organization WHERE name = "SYLEPS"',
                'login' => $login,
                //'first_name'=>$first_name,
                //'last_name'=>$last_name,
                //'email'=>$email,
                'password' => $first_name . '1234',
                'allowed_org_list' => array(
                    array('allowed_org_id' => $org_id)
                ),
                'profile_list' => array(
                    array('profileid' => 'SELECT URP_Profiles WHERE name="Portal user"'),
                ),

            ),
        );
        //echo json_encode($aData);
        //error_log(htmlspecialchars_decode (Zend_Debug::dump($aData)),3,"/var/tmp/mes-erreurs.log");
        $result = $this->CallWebService($aData);
        //error_log(htmlspecialchars_decode (Zend_Debug::dump($result)),3,"/var/tmp/mes-erreurs.log");
        return $result;
    }

    //Creation de l'opbjet Person (fiche Contact dans iTop)
    public function CreateUser($first_name,
                               $last_name,
                               $email,
                               $org_id,
                               $location_id,
                               $phone,
                               $mobile_phone,
                               $comment,
                               $department = null,
                               $service = null)
    {

        $aData = array(
            'operation' => 'core/create',
            'comment' => 'Création via Portail Syleps par ' . $this->_first_name . ' ' . $this->_name,
            'class' => 'Person',
            'output_fields' => 'id, friendlyname,org_name,site_name',
            'fields' => array(
                'org_id' => $org_id, //'SELECT Organization WHERE name = "SYLEPS"',
                'first_name' => $first_name,
                'name' => $last_name,
                'email' => $email,
                'org_id' => $org_id,
                'site_id' => $location_id,//'location_id'=>$location_id,
                'phone' => $phone,
                'mobile_phone' => $mobile_phone,
                'comment' => $comment,
                'department' => $department,
                'service' => $service
            ),
        );
        //echo json_encode($aData);
        //error_log(htmlspecialchars_decode (Zend_Debug::dump($aData)),3,"/var/tmp/mes-erreurs.log");
        $result = $this->CallWebService($aData);
        //error_log(htmlspecialchars_decode (Zend_Debug::dump($result)),3,"/var/tmp/mes-erreurs.log");
        return $result;
    }

    public function DeleteAccount($itop_id)
    {
        $aData = array(
            'operation' => 'core/delete',
            'comment' => 'Delete from Fives Portal V2',
            'class' => 'UserInternal',
            'key' => array('contactid' => $itop_id),
            'simulate' => false
        );
        $result = $this->CallWebService($aData);
        return $result;
    }

    public function DeleteContact($itop_id)
    {
        $aData = array(
            'operation' => 'core/delete',
            'comment' => 'Delete from Fives Portal V2',
            'class' => 'Contact',
            'key' => $itop_id,
            'simulate' => false
        );
        $result = $this->CallWebService($aData);
        return $result;
    }



    /*
     * On crée un nouveau ticket
     */
    public function CreateRequest($title,$description, $type, $location = null)
    {
        if (!(isset($type))) { $type = 'incident';}
        //if (!(isset($location))) { $location = $this->_loc_id;}
        $aData = array(
            'operation'=>'core/create',
            'comment'=>'Création via Portail Fives V2 par '.$this->_first_name.' '.$this->_name,
            'class'=>'UserRequest',
            'output_fields'=>'id, friendlyname',
            'fields'=>array(
                'org_id'=>$this->_org_id, //'SELECT Organization WHERE name = "SYLEPS"',
                'caller_id' => $this->_itop_id,
                'title'=>$title,
                'description'=>$description,
                //'site_id' => $location,
                'origin'=>'portal',
                'priority' => 'medium',
                'request_type'=>$type,

            ),
        );
        /**
         * Service_id => on le détermine automatiquement à partir du contrat de service le service 'Helpdesk'
         * On prend la famille Helpdesk (pour client Fives : family_id = 3) ou Metier Assistance utilisateur (pour Fives SIS : family_id = 9)
         * on filtre aussi sur les requetes demande de service / incident (both)
         * on elimine le service Admys, car par défaut, on prendra Atelys (Fives Syleps)
         */
//        Log::debug($aData);
        //echo json_encode($aData);
        $result =  $this->CallWebService( $aData);
//        Log::debug($result);
        return $this->jsonToArray($result);
    }

    /*
     * Création d'un ticket en mode Expert : plus de champs sont demandés à l'utilisateur
     */
    public function CreateRequestExpert($title,
                                        $description,
                                        $type,
                                        $location,
                                        $service_id,
                                        $module_id,
                                        $impact,
                                        $issue_type,
                                        $componentcreatingissue_id,
                                        $failuremode_id
    )
    {
        if (!(isset($type))) { $type = 'service_request';}
        if (!(isset($location))) { $location = $this->_loc_id;}
        if ($service_id == 0 ) { $service_id = $this->getDefaultService($location);} //Pas de service donné, on va le déterminer


        $aData = array(
            'operation'=>'core/create',
            'comment'=>'Création (expert) via Portail Fives V2 par '.$this->_first_name.' '.$this->_name,
            'class'=>'UserRequest',
            'output_fields'=>'id, friendlyname',
            'fields'=>array(
                'org_id'=>$this->_org_id, //'SELECT Organization WHERE name = "SYLEPS"',
                'site_id'=>$location,
                'caller_id' => $this->_itop_id,
                'title'=>$title,
                'description'=>$description,
                'origin'=>'portal',
                'priority' => 'medium',
                'request_type'=>$type,
                'service_id' => $service_id,
                'module_id' => $module_id,
                'impact' => $impact,
                'issue_type'=> $issue_type,
                'componentcreatingissue_id' => $componentcreatingissue_id,
                'failuremode_id' => $failuremode_id
            ),
        );
        //echo json_encode($aData);
        $result =  $this->CallWebService( $aData);
        Log::debug($result);
        return $this->jsonToArray($result);
    }

    public function getDefaultService($location){
        $Afields = array('id','name','description');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Service',
            'key' => 'SELECT S
                        FROM Service AS S
                        JOIN ServiceContract AS SC
                        ON SC.service_id = S.id
                        WHERE  (servicefamily_id = 3 OR  servicefamily_id = 9 )
                        AND S.request_type = "both"
                        AND S.name NOT LIKE "%ADMYS%"
                        AND SC.end_date > NOW()
                        AND (SC.site_id = "'.$location.'" OR SC.site_id = 0)
                        AND SC.org_id = "'.$this->_org_id.'"',
            'output_fields' => implode(",", $Afields),

        );

        $results = $this->CallWebService($aData);
        //return $results;
        $Aresults = $this->jsonToArray($results);
        //return $Aresults;
        if (!is_null($Aresults)){return $Aresults->first()->id;}
        else {return 0;}

    }

    //Lister les services accessible à un site
    public function getServices($location_id){
        $Afields = array('id','name','description');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Service',
//            'key' => 'SELECT S
//                        FROM Service AS S
//                        JOIN ServiceContract AS SC
//                        ON SC.service_id = S.id
//                        WHERE  (servicefamily_id = 3 OR  servicefamily_id = 9 )
//                        AND S.request_type = "both"
//                        AND S.name NOT LIKE "%ADMYS%"
//                        AND SC.end_date > NOW()
//                        AND (SC.site_id = "'.$location.'" OR SC.site_id = 0)
//                        AND SC.org_id = "'.$this->_org_id.'"',
            'key' => 'SELECT S
                        FROM Service AS S
                        JOIN ServiceContract AS SC
                        ON SC.service_id = S.id
                        WHERE S.request_type = "both"
                        AND SC.end_date > NOW()
                        AND (SC.site_id = "'.$location_id.'" OR SC.site_id = 0)
                        AND SC.org_id = "'.$this->_org_id.'"',
            'output_fields' => implode(",", $Afields),

        );
        $results = $this->CallWebService($aData);
        //dd($results);
//        Log::debug($aData);
//        Log::debug($this->jsonToArray($results));
        return $this->jsonToArray($results);
//        return $results;
    }

    public function getModules($location_id,$service_id){
        $Afields = array('id','name','description');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Module',
            'key' => 'SELECT Module AS m
                        JOIN lnkModuleToServiceContract AS l1
                        ON l1.module_id=m.id
                        JOIN ServiceContract AS sc
                        ON l1.servicecontract_id=sc.id
                    WHERE sc.service_id = "'.$service_id.'"
                        AND (sc.site_id = "'.$location_id.'" OR sc.site_id = 0)
                        AND sc.org_id = "'.$this->_org_id.'"',
            'output_fields' => implode(",", $Afields),

        );
        $results = $this->CallWebService($aData);
        //dd($results);
        //Log::debug($aData);
        //Log::debug($this->jsonToArray($results));
        return $this->jsonToArray($results);
    }


    public function getComponentCreatingIssue($service_id){
        $Afields = array('id','name','description');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'ComponentCreatingIssue',
            'key' => 'SELECT ComponentCreatingIssue AS cc
                        JOIN lnkComponentCreatingIssueToServiceContract AS l1
                        ON l1.	componentcreatingissue_id=cc.id
                        JOIN ServiceContract AS sc
                        ON l1.servicecontract_id=sc.id
                      WHERE sc.service_id = "'.$service_id.'"
                        AND sc.org_id = "'.$this->_org_id.'"',
            'output_fields' => implode(",", $Afields),
        );
        $results = $this->CallWebService($aData);
        //dd($results);
//        Log::debug($aData);
//        Log::debug($this->jsonToArray($results));
        return $this->jsonToArray($results);
    }

    public function getFailureMode($componentcreatingissue_id){
        $Afields = array('id','name','description');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'FailureMode',
            'key' => 'SELECT FailureMode
                        WHERE componentcreatingissue_id = "'.$componentcreatingissue_id.'"',
            'output_fields' => implode(",", $Afields),
        );
        $results = $this->CallWebService($aData);
        //dd($results);
//        Log::debug($aData);
//        Log::debug($this->jsonToArray($results));
        return $this->jsonToArray($results);
    }

    //MAJ de la description pour gérer l'insertion des images par copier/coller dont la taille peut exploser le champ
    public function UpdateRequestDescription($id,$description)
    {
        $aData = array(
            'operation'=>'core/update',
            'comment'=>'Création Requête phase 2',
            'class'=>'UserRequest',
            'key' => $id,
            'output_fields'=>'id, ref,friendlyname',
            'fields'=>array(
                'description' => $description
           ),
        );
        $result = $this->CallWebService( $aData);
        return $result;
    }

    /*
     * On modifie le ticket
     *
     */
    //Ajout d'un log public sans changement d'état
    public function UpdateRequest($id, $user_fullname, $user_itop_account_id, $user_itop_login, $newentry, $ADataExpert)
    {
        $AFields = $this->prepareFieldsUpdateRequest($user_itop_account_id, $user_itop_login,$newentry,$ADataExpert);

        $aData = array(
                'operation' => 'core/update',
                'comment' => 'Modification du ticket via le portail V2 par ' . $user_fullname,
                'class' => 'UserRequest',
                'key' => $id,
                'output_fields' => 'id, ref,friendlyname',
                'fields' => $AFields);
        $results = $this->CallWebService($aData);
        Log::debug($results);
        return $this->jsonToArray($results);
    }

    private function prepareFieldsUpdateRequest($user_itop_account_id, $user_itop_login,$newentry,$ADataExpert){
        $AFields = array();
        $date = Carbon::now();
        if (isset($newentry)) {
            $AFields['public_log'] =  array(
                'add_item' => array('date' => $date->format('Y-m-d H:i:s'),
                    'user_login' => $user_itop_login,
                    'user_id' => $user_itop_account_id, // Attention ! Id of Itop User and NOT Id of Person
                    'message' => $newentry)
            );}
        if ($ADataExpert['mode'] == 'expert') {
            $AFields['service_id'] = $ADataExpert['service'];
            $AFields['module_id'] = $ADataExpert['module'];
            $AFields['impact'] = $ADataExpert['impact'];
            $AFields['issue_type'] = $ADataExpert['issuetype'];
            $AFields['part_creating_issue'] = $ADataExpert['part_creating_issue'];
            $AFields['componentcreatingissue_id'] = $ADataExpert['failurecomponentissue'];
            $AFields['failuremode_id'] = $ADataExpert['failuremode'];
        }
        return $AFields;
    }

    // Résolution d'un ticket
    public function resolveRequest($id,$user_fullname, $user_itop_account_id, $user_itop_login,$newentry,
                                   $ADataExpert)
    {   $AFields = $this->prepareFieldsUpdateRequest($user_itop_account_id, $user_itop_login,$newentry,$ADataExpert);
        $AFields['resolution_code'] = 'solved_by_customer';
        $AFields['solution'] = 'Résolu par le client';
        $AFields['resolution_date'] = Carbon::now()->format('Y-m-d H:i:s');

        $aData = array(
            'operation' => 'core/apply_stimulus',
            'comment' => 'Résolution par ' . $user_fullname,
            'class' => 'UserRequest',
            'key' => $id,
            'stimulus' => 'ev_resolve',
            'output_fields' => 'friendlyname, title, status, contact_list,ref',
            'fields' => $AFields
        );

        $results =  $this->CallWebService( $aData);
        return $this->jsonToArray($results);
    }

    // Fermeture d'un ticket
    public function closeRequest($id,$user_fullname, $user_itop_account_id, $user_itop_login,$newentry,$ADataExpert)
    {
        $AFields = $this->prepareFieldsUpdateRequest($user_itop_account_id, $user_itop_login,$newentry,$ADataExpert);

        $aData = array(
            'operation' => 'core/apply_stimulus',
            'comment' => 'Fermeture par ' . $user_fullname,
            'class' => 'UserRequest',
            'key' => $id,
            'stimulus' => 'ev_close',
            'output_fields' => 'friendlyname, title, status,ref',
            'fields' => $AFields
        );
        $results =  $this->CallWebService( $aData);
        return $this->jsonToArray($results);
    }

    // Fermeture d'un ticket
    public function reopenRequest($id,$user_fullname, $user_itop_account_id, $user_itop_login,$newentry,$ADataExpert)
    {
        //Les champs du mode expert ne sont plus modifiable si le ticket est résolu
        //$AFields = $this->prepareFieldsUpdateRequest($user_itop_account_id, $user_itop_login,$newentry,$ADataExpert);

        $AFields = array();
        $date = Carbon::now();
        if (isset($newentry)) {
            $AFields['public_log'] =  array(
                'add_item' => array('date' => $date->format('Y-m-d H:i:s'),
                    'user_login' => $user_itop_login,
                    'user_id' => $user_itop_account_id, // Attention ! Id of Itop User and NOT Id of Person
                    'message' => $newentry)
            );}

        $aData = array(
            'operation' => 'core/apply_stimulus',
            'comment' => 'Ré-ouverture par ' . $user_fullname,
            'class' => 'UserRequest',
            'key' => $id,
            'stimulus' => 'ev_reopen',
            'output_fields' => 'friendlyname, title, status, contact_list,ref',
            'fields' => $AFields
        );
        //Log::debug($aData);
        $results =  $this->CallWebService( $aData);
        //Log::debug($results);
        return $this->jsonToArray($results);
    }

    //Add Contacts to a Request
    public function AddContactTicket($ticket_id, $contact_id)
    {
        $aData = array(
            'operation' => 'core/create',
            'comment' => 'Ajout de contact par ' . $this->_first_name . ' ' . $this->_name,
//            'class' => 'lnkTicketToContact', //class for iTop Syleps
            'class' => 'lnkContactToTicket', // class for iTop standard
            'output_fields' => 'id',
            'fields' => array(
                'ticket_id' => $ticket_id, //'SELECT Organization WHERE name = "SYLEPS"',
                'contact_id' => $contact_id
            ),
        );
        //echo json_encode($aData);
//        \Log::debug($aData);
        $result = $this->CallWebService($aData);
//        \Log::debug($result);
        return $result;
    }

    // Récupération des pièces jointes d'un ticket
    // Attention ici $id est l'ID et non Ref, 1234 au lieu de R-001234
    public function getAttachment($id, $org_id)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Attachment',
            'key' => 'SELECT Attachment WHERE item_id !="" AND
							item_id = "' . $id . '"
							AND item_class = "UserRequest"
							AND item_org_id = "' . $org_id . '"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
            'output_fields' => 'id,temp_id,expire,item_class,item_id,item_org_id,contents,friendlyname'
            /* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
        );
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    }

    //Ajout d'une pièce jointe à un ticket
    public function AddAttachment($name, $data, $item_class, $item_id, $type, $org_id)
    {
        $aData = array(
            'operation' => 'core/create',
            'comment' => 'Ajout de pièce jointe',
            'class' => 'Attachment',
            'output_fields' => 'id',
            'fields' => array(
                'item_org_id' => $org_id,
                'item_class' => $item_class,
                'item_id' => $item_id,
                'contents' => array('data' => $data,
                    'mimetype' => $type,
                    'filename' => $name),
            ),
        );
        //echo json_encode($aData);
        //Log::debug($aData);
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);

    }

    public function DeleteAttachment($id) //, $type, $org_id)
    {
        $aData = array(
            'operation' => 'core/delete',
            'comment' => 'Delete Attachment ',
            'class' => 'Attachment',
            'key' => $id,
//            'key' => array('id' => $id,
//                'item_org_id' => $org_id,
//                'type' => $type),
            'simulate' => false
        );

        //echo json_encode($aData);
        //Log::debug($aData);
        $results = $this->CallWebService($aData);
        Log::info($results);
        return $this->jsonToArray($results);
    }

    //Fonction pour les images intégrées dans les tickets (description, logs)
    public function getImage($id, $secret)
    {
        $aData = array(
            'operation' => 'core/get',
            'class' => 'InlineImage',
            'key' => 'SELECT InlineImage WHERE item_id = "' . $id . '"AND secret = "' . $secret . '"',
            'output_fields' => 'id,friendlyname,contents,item_class, item_id,item_org_id,expire,temp_id,secret'
            /* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
        );
        $results = $this->jsonToArray($this->CallWebService($aData));
        //dd($this->jsonToArray($results));
        $i = 0;
        if (isset($results)) {
            $result = $results->first();
            $OImage = new InlineImage($result->id,
                $result->temp_id,
                $result->expire,
                $result->item_class,
                $result->item_id,
                $result->item_org_id,
                $result->friendlyname,
                $result->contents->mimetype,
                $result->contents->filename,
                $result->contents->data,
                $result->secret);
        }


//        if (count($results)>0)
//        {foreach ($results as $result) {
//            $OImage = new InlineImage($result['fields']['id'],
//                $result['fields']['temp_id'],
//                $result['fields']['expire'],
//                $result['fields']['item_class'],
//                $result['fields']['item_id'],
//                $result['fields']['item_org_id'],
//                $result['fields']['friendlyname'],
//                $result['fields']['contents']['mimetype'],
//                $result['fields']['contents']['filename'],
//                $result['fields']['contents']['data'],
//                $result['fields']['secret']);
//            $i++;


        else $OImage = null;
        return $OImage;
    }

    public function AddInlineImage($name, $data, $item_class, $item_id, $type, $org_id, $secret)
    {   //We determine here the secret id for iTop

        $aData = array(
            'operation' => 'core/create',
            'comment' => 'Ajout d\'image',
            'class' => 'InlineImage',
            'output_fields' => 'id',
            'fields' => array(
                'item_org_id' => $org_id,
                'item_class' => $item_class,
                'item_id' => $item_id,
                'secret' => $secret,
                'contents' => array('data' => $data,
                    'mimetype' => $type,
                    'filename' => $name),
            ),
        );
        $results = json_decode($this->CallWebService($aData),true);
        if (count($results['objects']) > 0) {
            foreach ($results['objects'] as $result) {
                $id_created = $result['fields']['id'];
            }
        }
        return $id_created;

    }


    //Requete pour les Statisiques

    public function getCountRequest($clause = null, $org_id = null,$start_date = null,$end_date= null){
        //On autorise selon le cas à visualiser les données d'autre organisations
        if (is_null($org_id)) { $v_org_id = $this->_OPref->org_id;}
        else {$v_org_id = $org_id;}
        if (is_null($clause)) { $where = '';}
        else {$where = ' AND '.$clause;}

        //dd($start_date);
        //On reformatte les dates au bon format pour iTop
        if (is_null($start_date)){$whereStartDate = '';}
        else { $Start_date = $start_date->format('Y-m-d');
            $whereStartDate = 'start_date >="'.$Start_date.'"';
        }

        if (is_null($end_date)){$whereEndDate = '';}
        else { $End_date = $end_date->format('Y-m-d');
            $whereEndDate = 'AND start_date <= "'.$End_date.'"';
        }
        //Filtre sur les sites sélectionnés de STAT_USER_LOCATION
//        try {$whereFilterLocation = $this->getLocationFilter($this->_OPref->stat_locations_filter);}
        try {$whereFilterLocation = $this->getLocationFilter($this->_OPref->getStatUserLocationFilter());}
        catch (\Exception $exception) {$whereFilterLocation = null;}

        $aData = array(
            'operation'=> 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE org_id = "'.$v_org_id.'"
                                '. $whereStartDate .'
                                '. $whereEndDate .'
                                '.$where,
            'output_fields' => 'id,ref,resolution_code,service_name,caller_id_friendlyname,status,
                             start_date,description,title, request_type'
        );
//        dd(( $aData));
//        \Log::debug($aData);
//        \Log::debug($this->CallWebService( $aData));
        return $this->jsonToArray($this->CallWebService( $aData));
        //return json_decode($this->CallWebService( $aData));
    }


    public function getListRequest4Component_fives($clause = null, $org_id = null){
        $where = '';
//        $whereFilterLocation = $this->getLocationFilter($this->_OPref->getOrgLocationFilter($this->_OPref->paramName_RequestUserLocation));
        //On autorise selon le cas à visualiser les données d'autre organisations

        if (is_null($org_id)) { $v_org_id = $this->_OPref->org_id;}
        else {$v_org_id = $org_id;}
        if (!(is_null($clause))) {$where .= ' AND '.$clause;}
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE visibility="public" AND org_id = "'.$v_org_id.'"'.$this->whereFilterLocation. ' '. $this->whereFilterUser . ' '.$where,
            'output_fields' => 'id,ref,title,start_date,site_name,status,
                                pending_reason,last_pending_date,
                                resolution_code, resolution_date,solution',
//            'limit' => '12',
//            'page' => '1'
        );
        //dd($aData);
//        Log::debug($aData);
        return $this->jsonToArray($this->CallWebService( $aData));
        //return json_decode($this->CallWebService( $aData));
    }

    /*
     * Requête pour les statistiques (rubrique Statistiques)
     *
     */
    // Pour effectuer un cumul des tickets par mois et par site écoulés sur les 12 derniers mois.
    public function getListLastYearRequest($org_id = null ){
        //On autorise selon le cas à visualiser les données d'autre organisations
        if (is_null($org_id)) { $v_org_id =  $this->_OPref->org_id;}
        else {$v_org_id = $org_id;}

        $lastyear = Carbon::now()->subYears(2);
        $start_date = $lastyear->format('Y-m-d');

        //if (!is_null($status)){$where = ' AND status = "'.$status.'"';}
        //else
        {$where = '';}
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE org_id = "'.$v_org_id.'" AND visibility = "public" AND start_date >="'.$start_date.'"'.$where. ' '.$this->whereFilterLocation,
            //'key' => 'SELECT UserRequest WHERE start_date >="'.$start_date.'"',
            'output_fields' => 'id,site_name,start_date'

        );
        return $this->jsonToArray($this->CallWebService( $aData));
    }

    // Pour effectuer un cumul des tickets sur une plage de dates donnée.
    public function getListPeriodRequest($org_id = null,$start_date, $end_date ){
        //On autorise selon le cas à visualiser les données d'autre organisations
        if (is_null($org_id)) { $v_org_id =  $this->_OPref->org_id;}
        else {$v_org_id = $org_id;}
        //On reformatte les dates au bon format pour iTop
        $Start_date = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
        $End_date = Carbon::createFromFormat('!d/m/Y', $end_date)->format('Y-m-d');
        //Filtre sur les sites sélectionnés de STAT_USER_LOCATION
//        try {$whereFilterLocation = $this->getLocationFilter($this->_OPref->stat_locations_filter);}
        try {$whereFilterLocation = $this->getLocationFilter($stat_locations_filter = $this->_OPref->getOrgLocationFilter($this->_OPref->paramName_StatUserLocation));}
        catch (\Exception $exception) {$whereFilterLocation = null;}


        {$where = '';}
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'UserRequest',
            'key' => 'SELECT UserRequest WHERE org_id = "'.$v_org_id.'" AND visibility = "public"
                        AND start_date >="'.$Start_date.'"
                        AND DATE_SUB(start_date,INTERVAL 1440 MINUTE) <= "'.$End_date.'"
                        '.$where. ' '.$whereFilterLocation,
            //'key' => 'SELECT UserRequest WHERE start_date >="'.$start_date.'"',
            'output_fields' => 'id,site_name,start_date'

        );
//        dump($aData);
        return $this->jsonToArray($this->CallWebService( $aData));
    }

    /* Fin Statistiques */



    //Requete pour les couvertures horaires et Code du serveur vocal

    function getCoverageWindow($org_id = null) {
        if (is_null($org_id)) {$org_id = $this->_org_id;}

//        // on filtre aussi sur les sites mais subtilité : si le contrat n'a pas de site = il s'applique à tous les sites.
//        if (strlen($this->whereFilterLocation)>0 ) {
//            $whereLocation = ' AND (site_id = "" OR ( ""="" '. $this->whereFilterLocation .'))';
//        }        else {$whereLocation = '';}

        $query = "SELECT CW,SC,O FROM ServiceContract AS SC
				JOIN CoverageWindow AS CW
				ON  SC.coverage_id = CW.id
				JOIN Organization AS O
				ON SC.org_id = O.id
				WHERE SC.org_id = '".$org_id."'
				AND (SC.service_name = 'ATELYS' OR SC.service_name LIKE '%garantie%' OR SC.service_name LIKE '%Support Fives Intra SAS%')
				AND SC.status = 'active'". $this->whereFilterLocation;

        $xml = $this->callRestWebService($query,null);

        if (!is_null($xml)){
            $tab_result = $this->xmlToArray($xml);
            //Zend_Debug::dump($tab_result);
            //error_log(htmlspecialchars_decode (Zend_Debug::dump($tab_result,'$tab_result')),3,"/var/tmp/mes-erreurs.log");
            return collect($tab_result);
            //return $xml;
        }
        else {return null;}
    }

    public function getCoverageWindowInterval($coverage_window_id){
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'CoverageWindowInterval',
            'key' => 'SELECT CoverageWindowInterval WHERE coverage_window_id = "'.$coverage_window_id.'"',
            'output_fields' => 'id,coverage_window_name,weekday,start_time,end_time,friendlyname,coverage_window_id_friendlyname'
        );
        //Zend_Debug::dump($aData);
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    }

//$date = Carbon::now();
//('date' => $date->format('Y-m-d H:m:s'),
    function getCommunication() {
        date_default_timezone_set('Europe/Paris');
        $date = Carbon::now();
//        $dateStr = $date->get('YYYY-MM-dd HH:mm:ss');
        $dateStr = $date->format('Y-m-d H:i:s');

        $query = "SELECT C, lnkCO
                    FROM Communication AS C
                    JOIN lnkCommunicationToOrganization AS lnkCO
                    ON lnkCO.communication_id = C.id
                    WHERE lnkCO.org_id = '".$this->_org_id."'
                        AND C.start_date < '".$dateStr."' AND C.end_date > '".$dateStr."'
                        AND C.status = 'ongoing'";

        //Zend_Debug::dump($query);
        $xml = $this->callRestWebService($query,null);
        //dd($query);
        //$tab_result = $xml;
        if (!is_null($xml)){
            $tab_result = $this->xmlToArray($xml);
            //	Zend_Debug::dump($tab_result);
            return collect($tab_result);
        }
        else {return null;}

    }

    /*
     * Liste des Contrats Fournisseurs [Catalogue de services]
     */

    public function getListProviderContract(){
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'ProviderContract',
            'key' => 'SELECT ProviderContract
							WHERE org_id = "'.$this->_org_id.'"
							AND (status = "active" OR status = "dunning")',
            'output_fields' => 'name,description,provider_name,start_date,end_date,contract_manager_id_friendlyname,contract_manager_id,status'
        );
        //dd($aData);
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    }

    /*
     *  Listes des contrats fournisseurs
     * Gestion de la Facturation Fives SAS
     */
    //Liste des contrats
    public function getListContract(){
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'ServiceContract',
            'key' => 'SELECT ServiceContract
							WHERE org_id = "'.$this->_org_id.'" '.$this->whereFilterLocation,
            //AND status = "active"',
            'output_fields' => 'id,name,description,status,start_date,end_date,contract_manager_id_friendlyname,contract_manager_id,
					type,site_id_friendlyname'
        );
        $results = $this->CallWebService($aData);

        return $this->jsonToArray($results);
    }

    // Cas particulier des contrats au carnet de tickets
    public function getTicketBook($ServiceContract_id){
    		$aData = array(
    				'operation'=> 'core/get',
    				'class' => 'TicketBook',
    				'key' => 'SELECT TicketBook
    							WHERE servicecontract_id = "'.$ServiceContract_id.'" AND status = "active"',
    				//AND status = "active"',
    				'output_fields' => 'id,name,description,status,start_date,end_date,purchased_ticket, consumed_ticket,remaining_ticket,
    									low_credit_parameter,low_credit,credit_to_add,
    									coverage_office_hours_id,coverage_extended_id,coverage_24_7_id,
    									weighting_n1_office_hours,weighting_n2_office_hours,weighting_n1_extended,weighting_n2_extended,weighting_n1_24_7,weighting_n2_24_7'
    		);
    		//Zend_Debug::dump($aData);
    		$results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    	}


    	/*
    	 * Requetes pour surveiller l'activité iTop
    	 * et générer éventuellement des notifications
    	 *
    	 */
    function getItopActivity($noChange)
    {
        $query = "SELECT U,ChgfAttr FROM UserRequest AS U
				JOIN CMDBChangeOpSetAttributeScalar AS ChgfAttr
				ON  ChgfAttr.objkey = U.id
				WHERE ChgfAttr.change > '" . $noChange . "'
				AND ChgfAttr.objclass='UserRequest'
				AND ChgfAttr.attcode = 'status'";
        //dump($query);
        $xml = $this->callRestWebService($query, null);
        //dd($xml);
        if (!is_null($xml)) {
            $tab_result = $this->xmlToArray($xml);
            //Zend_Debug::dump($tab_result);
            //error_log(htmlspecialchars_decode (Zend_Debug::dump($tab_result,'$tab_result')),3,"/var/tmp/mes-erreurs.log");
            return collect($tab_result);
            //return $xml;
        } else {
            return null;
        }
    }

    public function getUpdateLogNotification($noNotif){
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'EventNotificationEmail',
            'key' => 'SELECT EventNotificationEmail
    							WHERE to != "" AND id > '.$noNotif.'
    							AND trigger_id_finalclass_recall="TriggerOnLogUpdate"',
            //AND status = "active"',
            'output_fields' => 'id, date, to, cc, object_id, subject'
//            id,message,date,action_id,object_id,to,cc,subject,
//            trigger_id_friendlyname,trigger_id_finalclass_recall,
//            action_id_friendlyname,userinfo'
        );
//        dd($aData);
        $results = $this->CallWebService($aData);
//        dd($results);
        return $this->jsonToArray($results);
    }


    //on liste les Person d'un client particulier (page Client's Team)
    public function getOrganizationContacts($org_id)
    {
        $Afields = array('id', 'name', 'first_name', 'email', 'org_id', 'org_name', 'site_id', 'site_name', 'phone', 'mobile_phone', 'comment');
        $aData = array(
            'operation' => 'core/get',
            'class' => 'Person',
            'key' => 'SELECT Person AS P JOIN Organization AS O ON P.org_id = O.id
							WHERE P.org_id = '.$org_id.' AND P.status = "active"',
            'output_fields' => implode(",", $Afields),

        );
        $results = $this->CallWebService($aData);
        return $this->jsonToArray($results);
    }


    /*
     * Synchronisation avec le Schedule Command (cron)
     */
    //Récupération des sites pour la synchronisation quotidienne
    public function getAllSyncLocations($Atype){
        $where = '';
        if ((!(is_null($Atype))) && (is_array($Atype))) {
            $where .= ' WHERE (';
            //$where .= 'WHERE L.status = "Active" AND (';
            $i = 0;
            foreach ($Atype as $type) {
                $where .= ' O.type = "'.$type.'"';
                $i++;
                if ($i < count($Atype)) {$where .= ' OR ';};
            }
            $where .= ')';
        }
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'Site',
            'key' => 'SELECT L FROM Site AS L JOIN Organization AS O ON L.org_id = O.id '.$where,
            'output_fields' => 'id,name,org_id,address,postal_code,city,country,status,
                                deliverymodel_id,deliverymodel_id_friendlyname');

        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }

    //On récupère le ou les PC d'un utilisateur
    public function getListPc($user_id = null){
        if (is_null($user_id)) {$where = '';}
        else {$where = 'AND user_id = "'.$user_id.'"';}
        $aData = array(
            'operation'=> 'core/get',
            'class' => 'PC',
            'key' => 'SELECT PC
					    WHERE status = "production" '.$where,
            'output_fields' => 'user_id, name'
        );

        $result = $this->CallWebService($aData);
        return $this->jsonToArray($result);
    }

}
