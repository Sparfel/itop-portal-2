<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Itop\ItopWebserviceRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function test()
    {
        return view('test');
    }

    public function dashboard()
    {
        $itopWS = new ItopWebserviceRepository();
        $iTop = $itopWS->getiTopVersion()->first();
        $datas = $itopWS->getListRequest4Component("status !='closed'");
//
        if (is_null($datas)) {
            $arrayData = collect();
            $requestsByType = collect();
            $bar_tab = ['labels' => [], 'datas' => []];
            } else {
            // Convertit les objets stdClass en tableaux associatifs
            $arrayData = $datas->map(function ($item) {
                return (array)$item;
            });
            $requestsByType = $arrayData->groupBy('request_type')->map->count();
            //Données pour la graphe à barres.
            $bar_tab = $this->getBarDatas($datas);
        }

//        dd($bar_tab);
        $bar_labels = response()->json($bar_tab['labels']);
        $bar_datas = response()->json($bar_tab['datas']);

        return view('frontend.home.dashboard',compact('iTop',//'tableHeaders', 'tableDatas','tableHeaders1', 'tableDatas1',
                                                'bar_labels','bar_datas',
                                                            'requestsByType'));
    }

    function getCommunications() {
        $itopWS = new ItopWebserviceRepository();
        $Communications = $itopWS->getCommunication();
        //dd($Communications);

        return response()->json($Communications);
    }


    public function getListRequests(){
        $itopWS = new ItopWebserviceRepository();
        //On liste les tickets que l'on veut
        $datas = $itopWS->getListRequest4Component("status !='closed'");
        return response()->json($datas);

    }

    private function getBarDatas($datas)
    {
//        dd($datas);
        if (is_null($datas)) {

            $BarDatas = array(
                array('status' => 'New', 'datas' => 0),
                array('status' => 'Assigned', 'datas' => 0),
                array('status' => 'Qualified', 'datas' => 0),
                array('status' => 'Pending', 'datas' => 0),
                array('status' => 'Resolved', 'datas' => 0),
                array('status' => 'Escalated TTO', 'datas' => 0),
                array('status' => 'Closed', 'datas' => 0)
            );
            $last12MonthsArray = $this->getLatestMonth(12);

        } else {
            //Premiere chose, on filtre les tickets des 12 derniers mois
            $last12MonthsTickets = $datas->filter(function ($value, $key) {
//                $date = DateTime::createFromFormat('d/m/Y H:i:s', $value->start_date);
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $value->start_date);
                $carbon_date = Carbon::parse($date);
                $date_min = Carbon::now()->subYears(1);
                return $carbon_date >= $date_min;
            });
            //        dd($last12MonthsTickets);
            //On ajoute la notion de start_month pour les cumuls à venir.
            foreach ($last12MonthsTickets as $data) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $data->start_date);
                //$date = $date->format('d/m/Y H:i:s');
                //  echo $date;
                $data->start_month = $date->format('m/Y');
                //dd($data);
                // dd($date);
            }
            //dd($this->getLatestMonth(12))
            //Tableau des 12 mois précédents.
            $last12MonthsArray = $this->getLatestMonth(12);

            //On va faire autant de liste que de status
            $newTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'new';
            });

            $assignedTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'assigned';
            });
//            $qualifiedTickets = $last12MonthsTickets->filter(function ($value, $key) {
//                return $value->status == 'qualified';
//            });
            $pendingTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'pending';
            });
            $escalatedTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'esacalated_tto';
            });
            $resolvedTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'resolved';
            });
            $closedTickets = $last12MonthsTickets->filter(function ($value, $key) {
                return $value->status == 'closed';
            });

            //On va ensuite tous les lire pour faire les cumuls par mois
            $countNew = array();
            $countAssigned = array();
            $countQualified = array();
            $countPending = array();
            $countEscalated = array();
            $countResolved = array();
            $countClosed = array();

            foreach ($last12MonthsArray as $month) {
                $newTicketsMonth = $newTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
//                dd(count($newTicketsMonth->all()));
                array_push($countNew, count($newTicketsMonth->all()));

                $assignedTicketsMonth = $assignedTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
                array_push($countAssigned, count($assignedTicketsMonth->all()));

//                $qualifiedTicketsMonth = $qualifiedTickets->filter(function ($value, $key) use ($month) {
//                    return $value->start_month == $month['month'] . '/' . $month['year'];
//                });
//                array_push($countQualified, count($qualifiedTicketsMonth->all()));

                $pendingTicketsMonth = $pendingTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
                array_push($countPending, count($pendingTicketsMonth->all()));

                $resolvedTicketsMonth = $resolvedTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
                array_push($countResolved, count($resolvedTicketsMonth->all()));

                $escalatedTicketsMonth = $escalatedTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
                array_push($countEscalated, count($escalatedTicketsMonth->all()));

                $closedTicketsMonth = $closedTickets->filter(function ($value, $key) use ($month) {
                    return $value->start_month == $month['month'] . '/' . $month['year'];
                });
                array_push($countClosed, count($closedTicketsMonth->all()));
            }
            //dd($countNew);
            //On constitue maintenant le tableau final
            $BarDatas = array(
                array('status' => 'New', 'datas' => $countNew),
                array('status' => 'Assigned', 'datas' => $countAssigned),
                array('status' => 'Qualified', 'datas' => $countQualified),
                array('status' => 'Pending', 'datas' => $countPending),
                array('status' => 'Resolved', 'datas' => $countResolved),
                array('status' => 'Escalated TTO', 'datas' => $countEscalated),
                array('status' => 'Closed', 'datas' => $countClosed)
            );
        }
        //On liste les 12 derniers mois en lettre
        $last12FullMonths  =array();
        foreach ($last12MonthsArray as $month ) {
            array_push($last12FullMonths, trans($month ['full_month']));
        }
//        \Log::debug($last12FullMonths);
//        \Log::debug($BarDatas);
        return (array ('labels' => $last12FullMonths,
            'datas' => $BarDatas));

    }

    //Fonction qui génère un tableau des 12 derniers mois écoulés.
    private function getLatestMonth($dernierMois){
        $arParMois = array();
//        $date_courant = date("Y-m-d"); //bug si 29/03,quand on soustrait 1 mois sur une année non bissextile = problème
        $month = date("m");
        $year = date("Y");
        $date_courant = date($year."-".$month."-01"); // on se place au premier du mois ...
        for($i = 0; $i < $dernierMois; $i++){
            if($i === 0){
                $arParMois[$i] = array(
                    'month' => date("m"),
                    'full_month' => date("F"),
                    'year' => $year
                );
            }else{
                //- 1 mois à la date du jour
                $mois = date("m", strtotime("-1 month", strtotime($date_courant)));
                $mois_complet = date("F", strtotime("-1 month", strtotime($date_courant)));
                if($mois == '12')
                {
                    $year = date("Y", strtotime("-1 year", strtotime($date_courant)));
                }
                $arParMois[$i] = array(
                    'month' => $mois,
                    'full_month' => $mois_complet,
                    'year' => $year
                );
                $date_courant = date($year."-".$mois."-01"); // on se place au premier du mois ...
            }
        }

        return array_reverse($arParMois);
    }

}
