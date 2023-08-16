<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdatePreference implements ShouldQueue
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
        DB::select("insert into hansdb.userPreferences( preferences_id, identity_number, temple_id, age_min, age_max, height_min_s, height_max_s, caste, marital_status, manglik, food_choice, working, occupation, sub_occupation, income_min, income_max, citizenship, description, membership, source, caste_no_bar, no_pref_caste, mother_tongue, created_at_leadPref, updated_at_leadPref, amount_collected, insentive, validity, payment_method, receipt_url, status, religion, is_paid, amount_collected_date, paid_score, zPaid_score, roka_charge, validity_month, pref_city, pref_state, pref_state_id, pref_country, pref_country_id ) select id, identity_number, temple_id, age_min, age_max, height_min, height_max, caste, marital_status, manglik, food_choice, working, occupation, sub_occupation, income_min, income_max, citizenship, description, membership, source, caste_no_bar, no_pref_caste, mother_tongue, created_at, updated_at, amount_collected, insentive, validity, payment_method, receipt_url, status, religion, is_paid, amount_collected_date, paid_score, zPaidScore, roka_charge, validity_month, pref_city, pref_state, pref_state_id, pref_country, pref_country_id from hansdb.preferences;");
    }
}
