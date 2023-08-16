<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateCompatblity implements ShouldQueue
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
        DB::select("insert into  hansdb.userCompatibilities( tableId_comp, user_id_comp, compatibility, discoverCompatibility, c_array, current, last_id, active, created_at_compatibility, updated_at_compatibility, credit_available, amount_collected, daily_quota, profile_status, ci_status, ri_status, si_status, reminder, extra, read_message, pdfs, free_credits_given, free_credit_count, free_id, profile_length, contacted_count, reject_count, shortlist_count, shown_interest_count, jeev_count, jeev_compatibility, jeev_profile_status, jeev_timestamp, pending_count, contact_interest_count, mutual_count, ri_count, is_recalculate, si_count, eri_count, esi_count, eci_count, discovery_profile_left, first_time, first_comp_day, max, max_new, virtual_receive, virtualToLike, virtual_send, virtual_send_count, virtual_receive_count, free_ids, random_count, viewProfile_count, random_updated, viewed_profiles, negative_contacted_profiles, today_activation_link ) select id, user_id, compatibility, discoverCompatibility, c_array, current, last_id, active, created_at, updated_at, whatsapp_point, amount_collected, daily_quota, profile_status, ci_status, ri_status, si_status, reminder, extra, read_message, pdfs, free_credits_given, free_credit_count, free_id, profile_length, contacted_count, reject_count, shortlist_count, shown_interest_count, jeev_count, jeev_compatibility, jeev_profile_status, jeev_timestamp, pending_count, contact_interest_count, mutual_count, ri_count, is_recalculate, si_count, eri_count, esi_count, eci_count, discovery_profile_left, first_time, first_comp_day, max, max_new, virtual_receive, virtualToLike, virtual_send, virtual_send_count, virtual_receive_count, free_ids, random_count, viewProfile_count, random_updated, viewed_profiles, negative_contacted_profiles, today_activation_link from hansdb.compatibilities;");
    }
}
