<?php

namespace App\Console\Commands;

use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Console\Command;
use App\Models\Organization;


class ItopSyncOrg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ItopOrg:sync {itop_cfg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Organization\'s synchronization from iTop';

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
    {   $itop_cfg = $this->argument('itop_cfg');
        $itopWS = new ItopWebserviceRepository($itop_cfg);
        $lstOrg = $itopWS->getOrganizations();
        foreach ($lstOrg as $org) {
            $Org = Organization::updateOrCreate(
                ['id' => $org->id],
                ['name' => $org->name,
                    'parent_id' => $org->parent_id ,
                    'type' => 'customer']
            );
        }

        return 0;
    }
}
