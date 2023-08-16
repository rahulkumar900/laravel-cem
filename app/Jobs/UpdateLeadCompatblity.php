<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateLeadCompatblity implements ShouldQueue
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
        DB::select("insert into hansdb.userCompatibilities ( tableId_lead_comp, user_id_leadComp, for_lead, compatibility, discoverCompatibility, current, active, created_at_compatibility, updated_at_compatibility, credit_available, amount_collected, daily_quota, profile_status, ri_status, si_status, reminder, extra, read_message, message_is_no, pdfs, free_credits_given, free_credit_count, free_id, last_id, contacted_count, reject_count, shortlist_count, shown_interest_count, mutual_count, is_recalculate, si_count, discovery_profile_left, first_time, first_comp_day, max, max_new, virtual_receive, virtual_receive_count, free_ids, random_count, viewProfile_count, random_updated, viewed_profiles, negative_contacted_profiles, today_activation_link ) select LC.id, LC.user_id, LC.for_lead, LC.compatibility, LC.discoverCompatibility, LC.current, LC.active, LC.created_at, LC.updated_at, LC.whatsapp_point, LC.amount_collected, LC.daily_quota, LC.profile_status, LC.ri_status, LC.si_status, LC.reminder, LC.extra, LC.read_message, LC.message_is_no, LC.pdfs, LC.free_credits_given, LC.free_credit_count, LC.free_id, LC.last_id, LC.contacted_count, LC.reject_count, LC.shortlist_count, LC.shown_interest_count, LC.mutual_count, LC.is_recalculate, LC.si_count, LC.discovery_profile_left, LC.first_time, LC.first_comp_day, LC.max, LC.max_new, LC.virtual_receive, LC.virtual_receive_count, LC.free_ids, LC.random_count, LC.viewProfile_count, LC.random_updated, LC.viewed_profiles, LC.negative_contacted_profiles, LC.today_activation_link from hansdb.leadCompatibilities LC inner join hansdb.user_data UD  on LC.user_id=UD.free_user_id;");
    }
}
