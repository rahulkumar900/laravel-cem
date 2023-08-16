<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateCompatblityData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        DB::select("update hansdb.userCompatibilities set compatible_userId=compatibility where JSON_VALID(compatibility)=true and  JSON_length(compatibility) >0;");

        DB::select("update hansdb.userCompatibilities set profile_status_json=profile_status where (JSON_VALID(profile_status)=true) and  (JSON_length(profile_status) >0);");

        DB::select("update hansdb.userCompatibilities C, hansdb.user_data U set C.user_data_id=U.id where (C.user_id_comp=U.profile_id or C.user_id_leadComp=U.lead_id and U.is_lead=1);");
        /*update hansdb.userCompatibilities  inner join hansdb.user_data on hansdb.user_data.profile_id = hansdb.userCompatibilities.user_id_comp set hansdb.userCompatibilities.user_data_id = hansdb.user_data.id;
*/
    }
}
