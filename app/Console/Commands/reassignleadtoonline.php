<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Crm;
use App\Models\UserData as Profile;
use Illuminate\Support\Facades\DB;

class reassignleadtoonline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Reassign:LeadtoOnline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $limit = date('Y-m-d', strtotime("-15 days"));
        $limit = $limit . ' 00:00:00';
        // case 1: assign_to is not null and online
        // case 2:
        //Commented the Free user Join by Sagar. No need to join with Free user Table
        $x = DB::table('leads')
            ->whereRaw("is_done = 0 and followup_call_on < '$limit' and (assign_by != '1584521442351' or assign_to != '1584521442351')")
            ->update(['assign_to' => 'online', 'assign_by' => 'online']);

        $limit_days = date('Y-m-d', strtotime("-10 days"));
        $limit_days = $limit_days . ' 00:00:00';
        $reset_converted = DB::table('leads')
            ->whereRaw("is_done = 1 and followup_call_on < '$limit_days' and (assign_by = '1584521442351' or assign_to = '1584521442351')")
            ->update(['assign_to' => 'online', 'assign_by' => 'online', 'request_by' => 'online']);
        if ($reset_converted) {
            echo "done";
        }
    }
}
