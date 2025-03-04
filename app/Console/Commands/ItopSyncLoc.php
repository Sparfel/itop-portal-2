<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Console\Command;

class ItopSyncLoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ItopLoc:sync {itop_cfg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Location\'s synchronization from iTop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $itop_cfg = $this->argument('itop_cfg');
        $itopWS = new ItopWebserviceRepository($itop_cfg);
        $Atype_org = array('both','customer');
        $lstLoc = $itopWS->getAllSyncLocations($Atype_org);
//        \Log::debug($lstLoc);
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
        return 0;
    }
}
