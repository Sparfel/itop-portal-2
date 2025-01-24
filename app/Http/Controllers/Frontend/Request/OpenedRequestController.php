<?php

namespace App\Http\Controllers\Frontend\Request;

use App\DataTables\OpenedRequestsDataTable;
use App\Http\Controllers\Controller;
use App\Mail\UpdateRequest;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Repositories\Itop\Request\HtmlContent;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class OpenedRequestController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(ItopWebserviceRepository $itopWS, Request $request)
    {
        // Récupérer les données via le WebService
        $datas = $itopWS->getListOpenedRequest();
        $parsedJson = json_decode($datas, false);
        $objects = $parsedJson->{'objects'};
        $collection = new Collection();
        foreach ($objects as $object) {
            $collection->push($object->fields);
        }
//dd($collection);
        $totalTickets = $collection->count();


//        // Si Filtre
//        if (isset($request->priority) && $request->priority != 'all'){
//            $priority = $request->priority;
//
//            $filteredCollection = $collection->filter(function ($item) use ($priority) {
//                return isset($item->priority) && $item->priority == $priority;
//            });
//            // Passer les données à la DataTable
//            $dataTable = new OpenedRequestsDataTable($filteredCollection);
//        }
//        else if (isset($request->type)) {
//            $priority = $request->priority;
//            $filteredCollection = $collection->filter(function ($item) use ($priority) {
//                return isset($item->priority) && $item->priority == $priority;
//            });
//            // Passer les données à la DataTable
//            $dataTable = new OpenedRequestsDataTable($filteredCollection);
//        }
//        else { // Pas de filtre
//            // Passer les données à la DataTable
//            $dataTable = new OpenedRequestsDataTable($collection);
//        }

        //on applique les filtres
        // Récupérer les filtres depuis la requête
        $filters = [
            'priority' => $request->priority,
            'request_type' => $request->request_type//,
            //'status' => $request->get('status'),
        ];
        //dd($filters);
        \Log::debug($filters);
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
                \Log::debug($collection);
            }
        }

        // AVANT Eventuels filtres !
        //Récupération de données annexes
        $pref = session('preferences');
        $locations = $pref->locations_list->toArray();
        $locations_filter =$pref->getLocationFilter()->toArray();
        $user_filter = $pref->getUserFilter();

        //Données pour les statistiques
//        $totalTickets = $collection->count();
        $requestsByType = $collection->groupBy('request_type')->map->count();
        //dd($requestsByType);
        $requestsByStatus = $collection->groupBy('status')->map->count();
        \Log::debug($requestsByType);
        //Critical (1), High (2), Medium (3), Low (4)
        $requestsByPriority = $collection->groupBy('priority')->map->count();
//        dd($requestsByPriority);
        $pie_labels = response()->json($requestsByStatus->keys()); // Les labels (ex: 'new', 'assigned', etc.)
        $pie_data = response()->json($requestsByStatus->values()); // Les valeurs (ex: 14, 10, etc.)

        // Passer les données filtrées à la DataTable
        $dataTable = new OpenedRequestsDataTable($collection);

        // Retourner la vue
//        return view('frontend.request.openedrequest.list2', compact('dataTable'));
        return $dataTable->render('frontend.request.openedrequest.list2',
            compact('locations','locations_filter','user_filter',
                'totalTickets','requestsByPriority','requestsByStatus','requestsByType',
                'pie_labels','pie_data'));
    }

    public function index2(OpenedRequestsDataTable $dataTable,$priority = null)
//    public function index()
    {

//        $itopWS = new ItopWebserviceRepository();
//        $collection = $dataTable->query($itopWS);
//        //$collection = $dataTable->getData();
//        //dd($collection);
//        $totalTickets = $collection->count();
//        $requestsByType = $collection->groupBy('request_type')->map->count();
//        //dd($requestsByType);
//        $requestsByStatus = $collection->groupBy('status')->map->count();
//        $pie_labels = response()->json($requestsByStatus->keys()); // Les labels (ex: 'new', 'assigned', etc.)
//        $pie_data = response()->json($requestsByStatus->values()); // Les valeurs (ex: 14, 10, etc.)
//
//        //Critical (1), High (2), Medium (3), Low (4)
//        $requestsByPriority =  $collection->where('priority', 1)->count();
//
//        $requestsByPriority = $collection->groupBy('priority')->map->count();
////        dd($requestsByPriority);

        //Liste complète des sites (array(id=>name))
        $pref = session('preferences');
        $locations = $pref->locations_list->toArray();
        $locations_filter =$pref->getLocationFilter()->toArray();
        $user_filter = $pref->getUserFilter();

        return $dataTable->render('frontend.request.openedrequest.list2',
            compact('locations','locations_filter','user_filter'));
//        ,
//                'totalTickets','requestsByPriority','requestsByStatus','requestsByType',
//                'pie_labels','pie_data'
//            ));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  string $tab //the choosed tab to be display at first
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$tab = null)
    {
        $user0 = Auth()->user();
//        dump ($user0->can('update_opened_request'));
        $user = User::find(1);
        //$user->assignRole('Admin');
//        dump($user->hasRole('Administrator')); // Doit retourner true
//        dump ($user->can('update_opened_request'));
//        dump($user->guard_name);
//        Permission::create([
//            'name' => 'update_opened_request',
//            'guard_name' => 'web'
//        ]);
        $role = Role::findByName('Administrator');
       // $role->givePermissionTo('update_opened_request');
//        dump($role->hasPermissionTo('update_opened_request')); // Doit retourner true si la permission est bien assignée
//        dump($role->hasPermissionTo('list_requests')); // Doit retourner true si la permission est bien assignée
        //on notifie pour test
//        $data['message']="We take a look on request ".$id;
//        Auth::user()->notify(new viewRequest($data));

//        if(Auth::user()->hasPermission('edit_request'))
        {
            //On récupère les informations du ticket choisi
            $itopWS = new ItopWebserviceRepository();
            $pref = session('preferences');
            //On recupère les informations du ticket
            $ticket = $itopWS->getInfoTicket($id,$pref->org_id);
//            $Ticket = json_decode(json_encode($ticket), FALSE); //transforme le Array en Objet stdClass
            if ( is_null($ticket)) { abort(403, 'No result'); }
            else { $Ticket = $ticket->first();}

            //On détermine dans quel mode on se trouve (standard ou expert : plus de champ accessible à la saisie)
            $userMode = $pref-> getPref($pref->paramName_UserMode);

            // On contrôle si l'organisation du ticket est celle du user, sinon on interdit l'accès.
            if ( $Ticket->org_id != $pref->org_id) {
                abort(499, 'Something went wrong');
            }
            //dd($Ticket);
            $this->getAllContacts($pref->org_id);
            $AlogContact = $this->getInfoLogs($Ticket);//On liste les différents intervenants dans les logs
            //dd($AlogContact);
            $Ahistory = $this->getInfoHistory($Ticket->id);
            $Acontact = $this->getContacts($Ticket->id); //On liste les différents contacts associés au ticket
//            \Log::debug($Acontact);
            $Aattach =  $itopWS->getAttachment($id,$pref->org_id);
            $Abuttons = $this->getButtons($Ticket);
            //dump($Abuttons);
            //on décode le contenu de la description et des logs pour en extraire les images
            $HtmlContent = new HtmlContent();
            $HtmlContent->generateItop2Portal($ticket->first()); //prend le resultat de la requete OQL en paramètre.
            $Ticket->description = $HtmlContent->getHtmlDescPortal(); // on modifie la valeur de l'objet à la volée ici.
            $LogPortal = $HtmlContent->getHtmlLogPortal();
            krsort($LogPortal);
            $Ticket->public_log->entries = json_decode(json_encode($LogPortal),FALSE);
            return view('frontend.request.openedrequest.request',compact('Ticket', 'tab', 'AlogContact','Ahistory','Acontact','Aattach','Abuttons','userMode'));
        }
//        else {
//
//            return abort(403);
////            return redirect('/home');
//        }


    }


    public function update(Request $request, $id)
    {
        //Selon le mode, on aura des attributs en plus
        //dd($request);

        $mode = $request->input('mode');
        if ($mode == 'expert') {
            $ADataExpert = array(  'mode' => 'expert',
                'service' => $request->input('service'),
                'module' => $request->input('module'),
                'impact' => $request->input('impact'),
                'issuetype' => $request->input('issuetype'),
                'part_creating_issue' => $request->input('part_creating_issue'),
                'failurecomponentissue' => $request->input('failurecomponentissue'),
                'failuremode' => $request->input('failuremode'));
        }
        else {
            $ADataExpert = array(  'mode' => 'basic',
                'service' => null,
                'module' => null,
                'impact' => null,
                'issuetype' => null,
                'part_creating_issue' => null,
                'failurecomponentissue' => null,
                'failuremode' => null);
        }


        $itopWS = new ItopWebserviceRepository();
        $pref = session('preferences');
        $fullname = Auth::user()->first_name.' '.Auth::user()->last_name;

        switch($request->changeStatus) {
            case 'update' :
                $res = $itopWS->UpdateRequest($id,
                    $fullname,
                    $pref->user_itop_account_id,
                    $pref->user_itop_account_login,
                    $request->newlog,
                    $ADataExpert);
                $flash = array('type' => 'info',
                    'title' => $res->first()->ref,
                    'text' => __('Request is updated.'));
                //dd($request);
                //Emission d'une notification de maj de ticket (vers l'agent).
                $this->notifOnUpate($request);
                break;
            case 'solve' :
                $res = $itopWS->resolveRequest($id,
                    $fullname,
                    $pref->user_itop_account_id,
                    $pref->user_itop_account_login,
                    $request->newlog,
                    $ADataExpert);
                $flash = array('type' => 'success',
                    'title' => $res->first()->ref,
                    'text' => __('Request is solved.'));
                break;
            case 'close' :
                $res = $itopWS->closeRequest($id,
                    $fullname,
                    $pref->user_itop_account_id,
                    $pref->user_itop_account_login,
                    $request->newlog,
                    $ADataExpert);
                $flash = array('type' => 'success',
                    'title' => $res->first()->ref,
                    'text' => __('Request is closed.'));
                break;
            case 'reopen' :
                $res = $itopWS->reopenRequest($id,
                    $fullname,
                    $pref->user_itop_account_id,
                    $pref->user_itop_account_login,
                    $request->newlog,
                    $ADataExpert);

                $flash = array(
                    'type' => 'info',
                    'title' => $res->first()->ref,
                    'text' => __('Request is reopen.'));
                break;
        }

        Session::flash('msg', json_encode($flash) ); //On va mettre un message flash pour indiquer que la MAJ a été effectué
        //TODO : mettre un message flash d'erreur si la maj a échoué
        return redirect('openedrequest/'.$id);
    }

    private function notifOnUpate($request) {
        // Préparation du sujet
        $subject = '['.$request->organization.' - '.$request->location.'] Le ticket '.$request->ref.' a été mis à jour via le portail Fives Services.';

        // Récupération de l'email de l'agent
        $agent_email = $this->getAgent($request->agent_id)?->email;
        $to = $agent_email;

        // Récupération des contacts
        $cc = [];
        $contacts = $this->getContacts($request->id);
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                if (!empty($contact->email)) { // Vérifie que l'email n'est pas null ou vide
                    $cc[] = $contact->email;
                }
            }
        }

        // Préparation des informations du ticket
        $User = Auth::user();
        $link = env('APP_URL') . '/openedrequest/' . $request->id;

        $updRequest = [
            'subject' => $subject,
            'ref' => $request->ref,
            'title' => $request->title,
            'caller' => $User->name,
            'caller_email' => $User->email,
            'organization' => $request->organization,
            'agent' => $to,
            'contacts' => $cc,
            'link' => $link,
            'message' => $request->newlog
        ];

        // Envoi de l'email
        try {
            if (!empty($to) || !empty($cc)) {
                Mail::send(new UpdateRequest($updRequest));
                \Log::info('Email envoyé avec succès.', ['to' => $to, 'cc' => $cc]);
            } else {
                \Log::warning('Notification non envoyée : aucun destinataire trouvé.', [
                    'ticket_id' => $request->id,
                    'agent_id' => $request->agent_id,
                    'subject' => $subject
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'e-mail : '.$e->getMessage());
            // Si nécessaire, informer l'utilisateur que l'email n'a pas pu être envoyé
        }
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
//        \Log::debug($List);
        $results = array();
        if (!is_null($List)) {
            foreach ($List as $person) {
                array_push($results, array('id' => $person->id, 'text' => $person->first_name . ' ' . $person->name));
            }
        }
        $pagination = array('more'=>true);
        $Res = array('results' => $results, 'pagination' => $pagination, 'total_count' => count($results));
        return $Res;

    }

    public function addListContacts(Request $request){
        $data = $request->all();
        //echo $data['list'];
        //Log::debug($data['list']);
        $contactList = $data['list'];
        $ticket_id = $data['request_id'];
        if (isset($contactList) && isset($ticket_id)) {
            $itopWS = new ItopWebserviceRepository();
            foreach ($contactList as $contact) {
                $itopWS->AddContactTicket($ticket_id, $contact);
            }
        }
//         return $data['list']
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
//                dump($Aresults);
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
                        $Ahisto[$history->id]->icone = '<i class="fas fa-lock  bg-gray"></i>';
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

    private function getAgent($id){
        $itopWS = new ItopWebserviceRepository();
        if (is_null($itopWS->getInfoContactFull($id))) {return null;}
        else {return $itopWS->getInfoContactFull($id)->first();}
    }

    private function getButtons($Ticket){
        // See ticket Life Cycle to configure this.
        if (($Ticket->status == 'new') ||
            ($Ticket->status == 'assign') ||
            ($Ticket->status == 'pending')) {
            $options = array ('update'=> true,
                'resolve' => false,
                'close' => false,
                'reopen' => false);
        }
        elseif ($Ticket->status == 'qualified') {
            // Verification if all Madatory are presents
            //Service is set ?
            if (strlen($Ticket->service_name) > 0)
            {$resolvable = true;}
            else {$resolvable = false;}
            $options = array ('update'=> true,
                'resolve' => $resolvable,
                'close' => false,
                'reopen' => false);
            //Gestion de bouton flottant (Valider + Resoudre)
            //$ABtn[] = 'ValidBtn';
            //if ($resolvable) {$ABtn[] = 'ResolveBtn';}
        }
        elseif ($Ticket->status == 'resolved') {
            $options = array ('update'=> false,
                'resolve' => false,
                'close' => true,
                'reopen' => true);
            $ABtn[] = 'CloseBtn';
            $ABtn[] = 'ReopenBtn';
        }
        elseif ($Ticket->status == 'closed') {
            $options = array ('update'=> false,
                'resolve' => false,
                'close' => false,
                'reopen' => false);
        }
        elseif ($Ticket->status == 'escalated_tto') {
            $options = array ('update'=> true,
                'resolve' => false,
                'close' => false,
                'reopen' => false);
        }
        else {
            $options = array ('update'=> true,
                'resolve' => false,
                'close' => false,
                'reopen' => false);
        }

        return json_decode(json_encode($options), FALSE);
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

    public function uploadAttachment(Request $request){
        // Log::debug($request);
        if ($request->file('file')) {
            $fileData =file_get_contents($request->file('file'));
            $fileData = base64_encode($fileData);
            $filePath = $request->file('file');
            $fileName = $filePath->getClientOriginalName();
            $item_class = 'UserRequest';
            $item_id = $request->request_id;
            $type = $request->file('file')->getMimeType();
            $pref = session('preferences');
            $itopWS = new ItopWebserviceRepository();
            $results = $itopWS->AddAttachment($fileName,$fileData,$item_class,$item_id,$type,$pref->org_id);
            if (isset($results)) {
                //Log::debug($results->first()->id);
                return response()->json([ 'success' => $fileName, 'request_id'=>$results->first()->id]);
            }
            else {
                return response()->json([ 'error' => $fileName]);
            }
        }
        return response()->json([ 'error' => 'no file']);

    }

    public function removeAttachment(Request $request){
        //Log::debug($request);
        if ($request->file_id) {
            //$type = $request->file('file')->getMimeType();
            $pref = session('preferences');
            $itopWS = new ItopWebserviceRepository();
            $results = $itopWS->DeleteAttachment($request->file_id,$pref->org_id); //,$type,$pref->org_id);
            //Log::debug($results);
            if (isset($results)) {
                //Log::debug($results->first()->id);
                return response()->json([ 'success' => $request->file_id, 'request_id'=>$results->first()->id]);
            }
            else {
                return response()->json([ 'error' => $request->file_id]);
            }
        }
    }



//    private function getPieDatas($datas){
//        //Récupération des données pour le PieChart (tickets par status)
//        //$res = $datas->first();
////        dd($datas);
//        if (is_null($datas)) {
//            $tab = array('labels' => array('new', 'assigned', 'qualified', 'pending', 'resolved', 'escalated_tto'),
//                'datas' => array(0,0, 0, 0, 0, 0));
//        }
//        else {
//            //Le PieChart étant sur les status, on calcule le total par status
//            $new = $datas->filter(function ($value, $key) {
//                return $value->status == 'new';
//            });
//            $count_new = count($new->all());
//
//            $assigned = $datas->filter(function ($value, $key) {
//                return $value->status == 'assigned';
//            });
//            $count_assigned = count($assigned->all());
//
//            /*$qualified = $datas->filter(function ($value, $key) {
//                return $value->status == 'qualified';
//            });
//            $count_qualified = count($qualified->all());*/
//
//            $pending = $datas->filter(function ($value, $key) {
//                return $value->status == 'pending';
//            });
//            $count_pending = count($pending->all());
//
//            $resolved = $datas->filter(function ($value, $key) {
//                return $value->status == 'resolved';
//            });
//            $count_resolved = count($resolved->all());
//
//            $escalated_tto = $datas->filter(function ($value, $key) {
//                return $value->status == 'escalated_tto';
//            });
//            $count_escalated_tto = count($escalated_tto->all());
//
//            $escalated_ttr = $datas->filter(function ($value, $key) {
//                return $value->status == 'escalated_ttr';
//            });
//            $count_escalated_ttr = count($escalated_ttr->all());
//
//            $waiting_for_approval = $datas->filter(function ($value, $key) {
//                return $value->status == 'waiting_for_approval';
//            });
//            $count_waiting_for_approval = count($waiting_for_approval->all());
//
//            $approved = $datas->filter(function ($value, $key) {
//                return $value->status == 'approved';
//            });
//            $count_approved = count($approved->all());
//
//            $rejected = $datas->filter(function ($value, $key) {
//                return $value->status == 'rejected';
//            });
//            $count_rejected = count($rejected->all());
//            //dd($assigned->all());
//            $tab = array('labels' => array('new', 'assigned', /*'qualified',*/ 'pending', 'resolved', 'escalated_tto','escalated_ttr',
//                                            'waiting_for_approval', 'approved', 'rejected'),
//                'datas' => array($count_new, $count_assigned, /*$count_qualified,*/ $count_pending, $count_resolved, $count_escalated_tto, $count_escalated_ttr,
//                                    $count_waiting_for_approval,$count_approved,$count_rejected));
//
//        }
//        return $tab;
//
//    }

}
