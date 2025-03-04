<?php

namespace App\Http\Controllers\Frontend\Request;

use App\DataTables\ClosedRequestsDataTable;
use App\Http\Controllers\Controller;
use App\Models\User as User;
use App\Repositories\Itop\Request\HtmlContent;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ClosedRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index(ClosedRequestsDataTable $dataTable)
    public function index(ItopWebserviceRepository $itopWS, Request $request)
    {

        $itopWS = new ItopWebserviceRepository();
        $datas = $itopWS->getListClosedRequest();
        $parsedJson= json_decode($datas,false);
        $collection = collect();
        // Vérifie que $parsedJson est un objet et que 'objects' existe
        if (is_object($parsedJson) && property_exists($parsedJson, 'objects') && !is_null($parsedJson->objects)) {
            $objects = $parsedJson->objects;
            foreach ($objects as $object) {
                if (property_exists($object, 'fields')) {
                    $collection->push($object->fields);
                }
            }
        } else {
            // Gère les cas où 'objects' est absent ou $parsedJson est null
            $objects = [];
            //Auquel cas on affiche une page spécifique vide !
        }
        // $collection contient maintenant les objets souhaités
        $totalTickets = $collection->count();
        // Récupérer les filtres depuis la requête
        $filters = [
            'priority' => $request->priority,
            'request_type' => $request->request_type,
            'resolution_code' => $request->get('resolution_code'),
        ];
        // Appliquer chaque filtre de manière dynamique
        foreach ($filters as $key => $value) {
            if ($value && $value !== 'all') {
                $collection = $collection->filter(function ($item) use ($key, $value) {
                    // Vérifier si la valeur est 'undefined' et que le champ est null
                    if ($value === 'undefined' && is_null($item->$key)) {
                        return true;
                    }
                    // Sinon, vérifier si la valeur du champ correspond à la valeur du filtre
                    return isset($item->$key) && $item->$key == $value;
                });
            }
        }
        //Données pour les statistiques
        $requestsByType = $collection->groupBy('request_type')->map->count();
        $requestsByPriority = $collection->groupBy('priority')->map->count();
        $requestsByResolutioncode = $collection->groupBy('resolution_code')->map->count();
        $pie_labels = response()->json($requestsByResolutioncode->keys()); // Les labels (ex: 'new', 'assigned', etc.)
        $pie_data = response()->json($requestsByResolutioncode->values()); // Les valeurs (ex: 14, 10, etc.)

        $dataTable = new ClosedRequestsDataTable($collection);


        $pref = session('preferences');
//        $locations = $pref->locations_list;
////        $locations_filter =json_decode($pref->getLocationFilter(),true);
//        $locations_filter = $pref->getLocationFilter()->toArray();
//        $user_filter_closed = $pref->getUserFilterClosedRequest();

        return $dataTable->render('frontend.request.closedrequest.index',
            /*compact('locations','locations_filter','user_filter_closed')*/
                compact('totalTickets','requestsByResolutioncode','requestsByPriority','requestsByType',
                    'pie_labels','pie_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $tab = null)
    {
        $itopWS = new ItopWebserviceRepository();
        $pref = session('preferences');

        //On recupère les informations du ticket
        $ticket = $itopWS->getInfoTicket($id,$pref->org_id);

        //$Ticket = json_decode(json_encode($ticket), FALSE); //transforme le Array en Objet stdClass
        //on utilise une collection désormais, plus élégant que les tableaux ...
        $Ticket = $ticket->first();
        $this->getAllContacts($pref->org_id);
        $AlogContact = $this->getInfoLogs($Ticket);//On liste les différents intervenants dans les logs
        //dd($AlogContact);
        $Ahistory = $this->getInfoHistory($Ticket->id);
        $Acontact = $this->getContacts($Ticket->id); //On liste les différents contacts associés au ticket
        $Aattach =  $itopWS->getAttachment($id,$pref->org_id);
        //$Abuttons = $this->getButtons($Ticket);

        //on décode le contenu de la description et des logs pour en extraire les images
        $HtmlContent = new HtmlContent();
        $HtmlContent->generateItop2Portal($Ticket); //prend le resultat de la requete OQL en paramètre.
        $Ticket->description = $HtmlContent->getHtmlDescPortal(); // on modifie la valeur de l'objet à la volée ici.
        $LogPortal = $HtmlContent->getHtmlLogPortal();
        krsort($LogPortal);
        $Ticket->public_log->entries = json_decode(json_encode($LogPortal),FALSE);
        return view('frontend.request.closedrequest.request',compact('Ticket', 'tab', 'AlogContact','Ahistory','Acontact','Aattach'));
    }

    // Renvoi un tableau contenant les infos des différents intervenant sur un ticket
    // permet la mise en page du journal public.
    private function getInfoLogs($Ticket){
        //pour la mise en page des logs, on va devoir récupérer tous les intervenants, on parcours alors les logs.
        $Acontact = array();
        $itopWS = new ItopWebserviceRepository();
        foreach($Ticket->public_log->entries as $log) {
            $Acontact[$log->user_id] = array('itop_account_id' => $log->user_id,
                'name' => $log->user_login);
        }
        //On complète ensuite ces données avec les images et des infos iTop,
        foreach ($Acontact as $contact ) {
            //On récupère l'id du contact iTop
            $Aresults = $itopWS->getContactId($contact['itop_account_id']);
            if (isset($Aresults)) {
                foreach ($Aresults as $Aresult) { //on a que 1 seule entrée car id unique
                    $itop_id = $Aresult['fields']['id'];
                    //avec l'id deU iTop, on récupère la photo du profil.
                    $User = User::where('itop_id',$itop_id)->first();
                    if (isset($User)) {$avatar = $User->avatar;}
                    else {$avatar = 'users/default.png';}
                    $Acontact[$contact['itop_account_id']]['itop_id'] = $itop_id;
                    $Acontact[$contact['itop_account_id']]['avatar'] = $avatar;
                    $Acontact[$contact['itop_account_id']]['org_id'] = $Aresult['fields']['org_id'];
                    $Acontact[$contact['itop_account_id']]['org_name'] = $Aresult['fields']['org_name'];
                    $Acontact[$contact['itop_account_id']]['site_id'] = $Aresult['fields']['location_id'];
                    $Acontact[$contact['itop_account_id']]['site_name'] = $Aresult['fields']['location_name'];
                }
            }
            else { //La requete dans iTop fait une jointure entre Person et User, donc si pas ou plus de compte iTop alors on ne ramène rien
                // pour palier à cela, on va mettre des valeurs par défaut.
                $Acontact[$contact['itop_account_id']]['itop_id'] = null;
                $Acontact[$contact['itop_account_id']]['avatar'] = 'users/default.png';;
                $Acontact[$contact['itop_account_id']]['org_id'] = 1; // on met Fives par défaut
                $Acontact[$contact['itop_account_id']]['org_name'] = null;
                $Acontact[$contact['itop_account_id']]['site_id'] = null;
                $Acontact[$contact['itop_account_id']]['site_name'] = null;
            }
        }
        return $Acontact;
    }

    //On construit ici un historique du Ticket, sorte de cycle de vie.
    private function getInfoHistory($request_id){
        $itopWS = new ItopWebserviceRepository();
        $Ahisto = array();
        //On récupère la création
        $Object = 'CMDBChangeOpCreate';
        $Afields = array('id','change', 'date','userinfo','friendlyname','change_friendlyname');
        $where = 'objkey = '.$request_id.' AND objclass="UserRequest"';
        $Histo = $itopWS->getObjects($Object,$Afields,$where);
        //array_push($Ahisto,$Histo->first());
        $Ahisto[$Histo->first()->id] = $Histo->first();
        $Ahisto[$Histo->first()->id]->oldvalue = null;
        $Ahisto[$Histo->first()->id]->newvalue = 'creation';
        $Ahisto[$Histo->first()->id]->icone = '<i class="far fa-edit bg-cyan"></i>';

        //Puis on récupère les différents changement de status
        $Object = 'CMDBChangeOpSetAttributeScalar';
        $Afields = array('id','change', 'date','userinfo','friendlyname','change_friendlyname','oldvalue','newvalue','attcode');
        $where = 'objkey = '.$request_id.' AND objclass="UserRequest" AND (attcode = "status")'; // OR attcode= "updated_by_id")';
        $Histo = $itopWS->getObjects($Object,$Afields,$where);
        if (isset($Histo)) {
            foreach ($Histo as $history) {//array_push($Ahisto,$history);
                $Ahisto[$history->id] = $history;
                //Gestion des changements d'état
                if ($history->attcode == 'status') {
                    if (($history->oldvalue == 'new' && $history->newvalue == 'assigned')) { //Assignation
                        $Ahisto[$history->id]->icone = '<i class="fas fa-user bg-primary"></i>';
                    }
                    elseif (($history->oldvalue == 'assigned' && $history->newvalue == 'qualified') ||
                        ($history->oldvalue == 'new' && $history->newvalue == 'qualified')) { //Qualification
                        $Ahisto[$history->id]->icone = '<i class="fas fa-tag  bg-primary"></i>';
                    }
                    elseif (($history->oldvalue == 'qualified' && $history->newvalue == 'pending')
                        || ($history->oldvalue == 'assigned' && $history->newvalue == 'pending')) { //Suspension
                        $Ahisto[$history->id]->icone = '<i class="fas fa-pause-circle bg-warning"></i>';
                    }
                    elseif (($history->oldvalue == 'pending' && $history->newvalue == 'qualified')
                        || ($history->oldvalue == 'pending' && $history->newvalue == 'assigned')) { //Désuspension
                        $Ahisto[$history->id]->icone = '<i class="far fa-play-circle bg-info"></i>';
                    }
                    elseif ($history->oldvalue == 'qualified' && $history->newvalue == 'resolved') { //Résolution
                        $Ahisto[$history->id]->icone = '<i class="fas fa-thumbs-up bg-success"></i>';
                    }
                    elseif ($history->oldvalue == 'resolved' && $history->newvalue == 'qualified') { //Re-Qualification
                        $Ahisto[$history->id]->icone = '<i class="fas fa-lock-open bg-info"></i>';
                    }
                    elseif ($history->oldvalue == 'resolved' && $history->newvalue == 'closed') { //Clôture
                        $Ahisto[$history->id]->icone = '<i class="fas fa-lock bg-secondary"></i>';
                    }
                    else {$Ahisto[$history->id]->icone = '<i class="fas fa-project-diagram"></i>';}
                }
            }
            krsort($Ahisto); // trie par ordre décroissant
        }
        else {$Histo = null;}
        return $Ahisto;
    }

    // On liste les personnes mis en contact du ticket
    private function getContacts($request_id){
        $itopWS = new ItopWebserviceRepository();
        return $itopWS->getTicketContacts($request_id);
    }

    public function getAllContacts(){
        $itopWS = new ItopWebserviceRepository();
        $pref = session('preferences');
        $org_id = $pref->org_id;
        //On récupère la création
        $Object = 'Person';
        $Afields = array('id','first_name', 'name','location_name');
        $where = 'org_id = '.$org_id.' AND status = "active"';
        //Si on passe un parametre de recherche ...
        if (isset($_GET['search'])) { $where .= ' AND (name LIKE "%'.$_GET['search'].'%" OR first_name LIKE "%'.$_GET['search'].'%")'; }
        $List = $itopWS->getObjects($Object,$Afields,$where);
        $results = array();
        foreach ($List as $person){
            array_push($results,array('id' => $person->id, 'text' => $person->first_name.' '.$person->name));
        }
        $pagination = array('more'=>true);
        $Res = array('results' => $results, 'pagination' => $pagination, 'total_count' => count($results));
        return $Res;

    }

    public function downloadAttachment($id)
    {
        $itopWS = new ItopWebserviceRepository();
        $pref = session('preferences');
        $org_id = $pref->org_id;
        //On récupère la création
        $Object = 'Attachment';
        $Afields = array('id','temp_id','expire','item_class','item_id','item_org_id','contents','friendlyname');
        $where = ' item_org_id = '.$org_id.' AND item_class = "UserRequest" AND id = '.$id;
        $Attachments = $itopWS->getObjects($Object,$Afields,$where);
        if (isset($Attachments)) {
            $Attachment = $Attachments->first();
            $contents = base64_decode($Attachment->contents->data);
            $filename = $Attachment->contents->filename;
            return response()->streamDownload(function () use ($contents) {
                echo $contents;
            }, $filename);
        }
        else {return abort(404);} //Si on trouve rien

    }

    //Pour afficher les imagse intégréees aux descriptions et logs
    public function displayImage($id,$secret) //( $id, $secret)
    {
        $itopWS = new ItopWebserviceRepository();
        $OImage = $itopWS->getImage($id, $secret);
        if (isset($OImage)) {
            return response(base64_decode($OImage->_data))->header('Content-Type', 'text/html;charset=iso-8859-1')
                ->header('Content-Type', $OImage->_mimetype)
                ->header('Content-Disposition:attachment', $OImage->_filename);
        }
    }

}
