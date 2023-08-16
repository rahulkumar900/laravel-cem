<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdatePreferenceUserData implements ShouldQueue
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
        DB::select("UPDATE hansdb.userPreferences P,hansdb.user_data U SET  P.user_data_id=U.id where (P.temple_id=U.temple_id and P.identity_number=U.identity_number and U.is_lead=0)  or (P.lead_id=U.lead_id and U.is_lead=1) ;");
    }
}

/*
0	34	12:41:53	UPDATE hansdb.leads ,hansdb.user_data  SET  leads.user_data_id=user_data.id
 where (leads.assign_to=user_data.temple_id and leads.identity_number=user_data.identity_number and user_data.is_lead=0)  or (leads.lead_id=user_data.lead_id and user_data.is_lead=1)	Error Code: 1267. Illegal mix of collations (utf8mb4_unicode_ci,IMPLICIT) and (utf8mb4_0900_ai_ci,IMPLICIT) for operation '='	0.109 sec



 UPDATE hansdb.leads ,hansdb.user_data  SET  leads.user_data_id =user_data.id COLLATE utf8mb4_0900_ai_ci
where (leads.assign_to COLLATE utf8mb4_unicode_ci =user_data.temple_id  COLLATE utf8mb4_0900_ai_ci
and leads.identity_number COLLATE utf8mb4_unicode_ci=user_data.identity_number COLLATE utf8mb4_0900_ai_ci
and user_data.is_lead COLLATE utf8mb4_unicode_ci=0)
or (leads.lead_id COLLATE utf8mb4_unicode_ci=user_data.lead_id COLLATE utf8mb4_0900_ai_ci and user_data.is_lead COLLATE utf8mb4_0900_ai_ci=1) ;



 */
