<?php

namespace App\Jobs;

use App\Models\CityLists;
use App\Models\UserData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use File;
use Illuminate\Support\Facades\DB;

class InsertCities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_data = DB::table('lead_family')
        ->select('mobile', 'lead_family.lead_id')
        ->orderBy('id', 'DESC')
        ->chunk(50, function ($leads) {
            foreach ($leads as $lead) {
                $search_data = UserData::where('mobile_profile', $lead->mobile)->first();
                if (!empty($search_data)) {
                    DB::table('leads')->where('id', $lead->lead_id)->update([
                        'user_data_id'      =>      $search_data->id
                    ]);
                }
            }
        });
    }
}
