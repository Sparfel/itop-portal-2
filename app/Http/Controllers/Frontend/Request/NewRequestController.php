<?php

namespace App\Http\Controllers\Frontend\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\AjaxController;
use App\Repositories\Itop\ItopWebserviceRepository;
use App\Repositories\Itop\Request\HtmlContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewRequestController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $pref = session('preferences');
        $Locations = $pref->locations_list;
        $user_loc_id = $pref->loc_id;
        //On détermine ici quel service est concerné.
        //
        $test = new AjaxController();
        //$test->getServices(526);
//dump($Locations);
        //On détermine dans quel mode on se trouve (standard ou expert : plus de champ accessible à la saisie)
        $userMode = $pref-> getPref($pref->paramName_UserMode);
        return view('frontend.request.newrequest.create',compact('Locations','user_loc_id','userMode'));
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
        $itopWS = new ItopWebserviceRepository();
        $pref = session('preferences');
        Log::debug($request);

        //On vérifie le mode de création : basic ou expert
        if ($request->input('mode') == 'expert') {
            //dd($request);
            $result = $itopWS->CreateRequestExpert($request->title,
                $request->description,
                $request->type,
                $request->location,
                $request->service,
                $request->module,
                $request->impact,
                $request->issuetype,
                $request->failurecomponentissue,
                $request->failuremode);
        }
        else {
           // dd($request);
            $result = $itopWS->CreateRequest($request->title,
                                            $request->description, //$description, Si copier / coller d'image, la description peut dépasser la taille max autorisée.//$request->description,
                                            $request->type,
                                            $request->location);
        }

        $res = $result->first();
        $ticketItop_id = $res->id;
        $ticketItop_ref = $res->friendlyname;
        Log::debug('Création du ticket '.$ticketItop_ref);
        //On met à jour le ticket avec la description au format Html avec l'image stockée
        $HtmlRequest = new HtmlContent($ticketItop_id);
        $itopWS->UpdateRequestDescription($ticketItop_id,$HtmlRequest->generatePortal2Itop($request->description));
        //Log::debug($request->file);
        $Afiles = $request->file;
        if (is_array($Afiles)){
            foreach ($Afiles as $file) {
                $fileData = file_get_contents($file);
                $fileData = base64_encode($fileData);
                $filePath = $file;
                $type = $file->getMimeType();
                $fileName = $filePath->getClientOriginalName();
                $item_class = 'UserRequest';
                //Log::debug(base64_encode(file_get_contents($file)));
                $results = $itopWS->AddAttachment($fileName,$fileData,$item_class,$ticketItop_id,$type,$pref->org_id);
            }
            return $ticketItop_ref;
        }
        else {
            return view('frontend.request.newrequest.done', compact('ticketItop_ref'));
        }

        Log::debug('Création du ticket '.$ticketItop_ref);
        //$result = $itopWS->CreateRequest($request->title, $request->description,$request->type,$request->location);
        //on cree le ticket avec les fichiers
        //et on retourne la ref du ticket créé
        //Du coup on affice une page indiquant que le ticket R-xxxxx vient d'etre créé.
        //$res = $result->first();
        //return $ticketItop_ref;

    }

    public function done(Request $request)
    {$ticketItop_ref = $request->ref;
        return view('frontend.request.newrequest.done', compact('ticketItop_ref'));
    }
}
