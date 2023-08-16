<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateLeadPreference implements ShouldQueue
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
        DB::select("insert into hansdb.userPreferences ( lead_preferences_id, lead_id, age_min, age_max, height_min_s, height_max_s, caste, marital_status, manglik, food_choice, working, occupation, income_min, income_max, citizenship, description, caste_no_bar, mother_tongue, created_at_leadPref, updated_at_leadPref, amount_collected, religion, pref_city, pref_state, pref_state_id, pref_country, pref_country_id ) select LP.id, LP.lead_id, LP.age_min, LP.age_max, LP.height_min, LP.height_max, LP.caste, LP.marital_status, LP.manglik, LP.food_choice, LP.working, LP.occupation, LP.income_min, LP.income_max, LP.citizenship, LP.description, LP.caste_no_bar, LP.mother_tongue, LP.created_at, LP.updated_at, LP.amount_collected, LP.religion, LP.pref_city, LP.pref_state, LP.pref_state_id, LP.pref_country, LP.pref_country_id from hansdb.lead_preference LP inner join hansdb.user_data UD on LP.lead_id=UD.lead_id where UD.is_lead=1; ");
    }
}
