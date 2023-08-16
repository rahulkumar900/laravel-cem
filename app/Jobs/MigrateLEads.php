<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\UserData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MigrateLEads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   // public $lelead_datasad_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->lead_datas = $lead_datas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = Lead::latest('leads.id')
        ->select([
            "free_users.id",
            "free_users.is_approve_ready",
            "free_users.temple_id",
            "lead_family.lead_id",
            "free_users.extra_lead_id",
            "free_users.backup_lead_id",
            "free_users.org_lead_id",
            "lead_family.name",
            "free_users.gender",
            "free_users.mobile",
            "free_users.new_mobile",
            "lead_family.new_mobile",
            "free_users.age",
            "free_users.sent_profiles",
            "free_users.photo",
            "free_users.photo_score",
            "free_users.profiles_sent",
            "free_users.birth_place",
            "free_users.birth_date",
            "free_users.birth_time",
            "free_users.weight",
            "free_users.height",
            "free_users.education",
            "free_users.degree",
            "free_users.college",
            "free_users.occupation",
            "free_users.profession",
            "free_users.working_city",
            "free_users.disability",
            "free_users.food_choice",
            "free_users.manglik",
            "free_users.annual_income",
            "free_users.marital_status",
            "lead_family.office_address",
            "free_users.about",
            "free_users.audio_profile",
            "free_users.earned_rewards",
            "free_users.audio_uploaded_at",
            "free_users.audio_uploaded_by",
            "free_users.audio_approved_at",
            "free_users.unapprove_audio_profile",
            "free_users.audioProfile_hls_convert",
            "free_users.disabled_part",
            "free_users.abroad",
            "free_users.wantBot",
            "free_users.carousel",
            "free_users.company",
            "free_users.additional_qualification",
            "free_users.bot_language",
            "free_users.fcm_id",
            "free_users.bot_status",
            "free_users.last_seen",
            "free_users.fcm_app",
            "free_users.education_score",
            "free_users.lat_locality",
            "free_users.long_locality",
            "free_users.pdf_url",
            "free_users.is_premium_interest",
            "free_users.si_event",
            "free_users.unapprove_carousel",
            "free_users.is_call_back",
            "free_users.call_back_query",
            "free_users.is_deleted",
            "free_users.deleted_by",
            "lead_family.id",
            "lead_family.relation",
            "lead_family.livingWithParents",
            "lead_family.locality",
            "lead_family.city",
            "lead_family.mobile",
            "lead_family.backup_mobile",
            "lead_family.email",
            "lead_family.unmarried_sons",
            "lead_family.married_sons",
            "lead_family.unmarried_daughters",
            "lead_family.married_daughters",
            "lead_family.family_type",
            "lead_family.house_type",
            "free_users.religion",
            "free_users.caste",
            "lead_family.gotra",
            "lead_family.occupation",
            "lead_family.family_income",
            "lead_family.father_status",
            "lead_family.mother_status",
            "lead_family.whats_app_no",
            "lead_family.mother_tongue",
            "lead_family.occupation_mother",
            "lead_family.address",
            "lead_family.about",
            "lead_family.father_off_addr",
            "lead_family.email_verified",
            "free_users.profile_percent",
        ])
            ->join('free_users', 'free_users.lead_id', 'leads.id')
            ->join('lead_family', 'lead_family.lead_id', 'leads.id')
            ->where(['is_done' => 0, 'free_users.is_deleted' => 0])
            ->whereRaw("lead_family.id < 143512")
            ->chunk(500, function ($leads) {
                foreach ($leads as $lead) {
                    // check lead data into user_data then insert
                    $check_user_data = UserData::where('mobile_profile', $lead->mobile)->first();
                    //dd($lead);
                    if (empty($check_user_data)) {
                        // save into
                        $header_array = [
                            "free_user_id",
                            "is_approve_ready",
                            "temple_id",
                            "lead_id",
                            "extra_lead_id",
                            "backup_lead_id",
                            "org_lead_id",
                            "name",
                            "gender",
                            "mobile_profile",
                            "new_mobile_freeUser",
                            "age_freeUser",
                            "sent_profiles_freeUser",
                            "photo",
                            "photo_score",
                            "profiles_sent",
                            "birth_place",
                            "birth_date",
                            "birth_time",
                            "weight",
                            "height",
                            "education",
                            "degree",
                            "college",
                            "occupation",
                            "profession",
                            "working_city",
                            "disability",
                            "food_choice",
                            "manglik",
                            "monthly_income",
                            "marital_status",
                            "office_address",
                            "about",
                            "audio_profile",
                            "earned_rewards",
                            "audio_uploaded_at",
                            "audio_uploaded_by",
                            "audio_approved_at",
                            "unapprove_audio_profile",
                            "audioProfile_hls_convert",
                            "disabled_part",
                            "abroad",
                            "wantBot",
                            "carousel",
                            "company",
                            "additional_qualification",
                            "bot_language",
                            "fcm_id",
                            "bot_status",
                            "last_seen",
                            "fcm_app",
                            "education_score",
                            "lat_locality",
                            "long_locality",
                            "profile_pdf",
                            "is_premium_interest",
                            "si_event",
                            "unapprove_carousel",
                            "is_call_back",
                            "call_back_query",
                            "is_deleted",
                            "deleted_by",
                            "relation",
                            "livingWithParents",
                            "locality",
                            "city_family",
                            "mobile_family",
                            "email_family",
                            "unmarried_brothers",
                            "married_brothers",
                            "unmarried_sisters",
                            "married_sisters",
                            "family_type",
                            "house_type",
                            "religion",
                            "caste",
                            "gotra",
                            "family_income",
                            "father_status",
                            "mother_status",
                            "whatsapp_family",
                            "mother_tongue",
                            "occupation_mother",
                            "address",
                            "father_off_addr",
                            "email_verified",
                            "profile_percent",
                        ];
                        //dd(array_keys($lead->toArray()));
                        //dd($header_array);

                        $saving_data = array_combine($header_array, array_values($lead->toArray()));
                        $create_user_data = UserData::create($saving_data);
                    }
                }
            });

    }
}
