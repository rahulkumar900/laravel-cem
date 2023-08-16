<?php

namespace App\Jobs;

use App\Models\UserData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdreUSerDataId implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $leads;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($leads)
    {
        $this->leads = $leads;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->leads as $lead) {
            $search_data = UserData::where('mobile_profile', $lead->mobile)->first();
            if (!empty($search_data)) {
                DB::table('leads')->where('id', $lead->lead_id)->update([
                    'user_data_id'      =>      $search_data->id
                ]);
            }
        }
    }
}
