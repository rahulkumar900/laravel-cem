<?php

namespace App\Http\Controllers;

use App\Jobs\InsertCities;
use App\Jobs\MigrateLEads;
use App\Jobs\MigrationJobProcess;
use App\Jobs\UpdateCompatblity;
use App\Jobs\UpdateCompatblityData;
use App\Jobs\UpdateLeadCompatblity;
use App\Jobs\UpdateLeadPreference;
use App\Jobs\UpdateName;
use App\Jobs\UpdatePreference;
use App\Jobs\UpdatePreferenceUserData;
use App\Jobs\UpdreUSerDataId;
use App\Models\Caste;
use App\Models\CityLists;
use App\Models\Country;
use App\Models\Lead;
use App\Models\UserData;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;

class MigrationController extends Controller
{

    public function getProfileDataAndSave()
    {
        //DB::beginTransaction();
        // fetch data from old database
        /*$profiles_datas = DB::connection('mysql_old')
        ->table('profiles')
        ->join('families', 'families.id', 'profiles.id')
        ->where(["is_delete" => 0])
        ->whereRaw("year(profiles.created_at)>=2021")
        ->orderBy('profiles.id', 'desc')
        ->count();*/
        foreach (DB::connection('mysql_old')
            ->table('profiles')
            ->join('families', 'families.id', 'profiles.id')
            ->where(["is_delete" => 0])
            ->whereRaw("year(profiles.created_at)>=2021")
            ->orderBy('profiles.id', 'desc')
            ->cursor() as $profiles_datas) {
            //$batch = Bus::batch()->dispatch();

            //$batch->add(new MigrationJobProcess(($profiles_datas)));
            //MigrationJobProcess::dispatch($profiles_datas);
        }
        //dd(ceil($profiles_datas/1000));

        /*foreach ($profiles_datas as $key => $profiles_data) {
           // dd($profiles_data);
            $insert_user_data = UserData::create([
                "profile_id"            =>      $profiles_data->id,
                "isApproved"            =>      1,
                "is_approve_ready"      =>      1,
                "temple_id"             =>      $profiles_data->temple_id,
                "identity_number"       =>      $profiles_data->identity_number,
                "name"                  =>      $profiles_data->name,
                "gender"                =>      $profiles_data->gender,
                "mobile_profile"        =>      $profiles_data->mobile_profile,
                "user_mobile"           =>      $profiles_data->mobile_profile,
                "not_delete"            =>      1,
                "photo"                 =>      $profiles_data->photo,
                "photo_score"           =>      $profiles_data->photo_score,
                "profiles_sent"         =>      $profiles_data->profiles_sent,
                "whatsapp"              =>      $profiles_data->whatsapp,
                "facebook"              =>      $profiles_data->facebook,
                "aadhar"                =>      $profiles_data->aadhar,
                "birth_place"           =>      $profiles_data->birth_place,
                "birth_date"            =>      $profiles_data->birth_date,
                "birth_time"            =>      $profiles_data->birth_time,
                "height"                =>      $profiles_data->height,
                "height_int"            =>      $profiles_data->height,
                "education"             =>      $profiles_data->education,
                "degree"                =>      $profiles_data->degree,
                "college"               =>      $profiles_data->college,
                "occupation"            =>      $profiles_data->occupation,
                "profession"            =>      $profiles_data->profession,
                "working_city"          =>      $profiles_data->working_city,
                "disability"            =>      $profiles_data->disability,
                "food_choice"           =>      $profiles_data->food_choice,
                "manglik"               =>      $profiles_data->manglik,
                "skin_tone"             =>      $profiles_data->skin_tone,
                "monthly_income"        =>      $profiles_data->monthly_income,
                "annual_income"         =>      $profiles_data->monthly_income,
                "marital_status"        =>      $profiles_data->marital_status,
                "citizenship"           =>      $profiles_data->citizenship,
                "office_address"        =>      $profiles_data->office_address,
                "about"                 =>      $profiles_data->about,
                "audio_profile"         =>      $profiles_data->audio_profile,
                "earned_rewards"        =>      $profiles_data->earned_rewards,
                "audio_uploaded_at"     =>      $profiles_data->audio_uploaded_at,
                "audio_uploaded_by"     =>      $profiles_data->audio_uploaded_by,
                "audio_approved_at"     =>      $profiles_data->audio_approved_at,
                "unapprove_audio_profile"   =>      $profiles_data->unapprove_audio_profile,
                "audioProfile_hls_convert"  =>      $profiles_data->audioProfile_hls_convert,
                "children"              =>      $profiles_data->children,
                "disabled_part"         =>      $profiles_data->disabled_part,
                "precedence"            =>      $profiles_data->precedence,
                "profile_type"          =>      $profiles_data->profile_type,
                "abroad"                =>      $profiles_data->abroad,
                "is_nri"                =>      $profiles_data->wishing_to_settle_abroad,
                "is_invisible"          =>      $profiles_data->is_invisible,
                "wishing_to_settle_abroad"  =>      $profiles_data->wishing_to_settle_abroad,
                "is_renewed"            =>      $profiles_data->is_renewed,
                "blatitude"             =>      $profiles_data->blatitude,
                "bNS"                   =>      $profiles_data->bNS,
                "blongitude"            =>      $profiles_data->blongitude,
                "bEW"                   =>      $profiles_data->bEW,
                "country"               =>      $profiles_data->country,
                "state"                 =>      $profiles_data->state,
                "comments"              =>      $profiles_data->comments,
                "enquiry_date"          =>      $profiles_data->enquiry_date,
                "followup_call_on"      =>      $profiles_data->followup_call_on,
                "last_followup_date"    =>      $profiles_data->last_followup_date,
                "upgrade_renew"         =>      $profiles_data->upgrade_renew,
                "wantBot"               =>      $profiles_data->wantBot,
                "manglik_id"            =>      $profiles_data->manglik_id,
                "plan_name"             =>      $profiles_data->plan_name,
                "carousel"              =>      $profiles_data->carousel,
                "corrupt_carousel"      =>      $profiles_data->corrupt_carousel,
                "company"               =>      $profiles_data->company,
                "additional_qualification"  =>      $profiles_data->additional_qualification,
                "bot_language"          =>      $profiles_data->bot_language,
                "page_skipped"          =>      $profiles_data->page_skipped,
                "token"                 =>      $profiles_data->token,
                "upgrade_request"       =>      $profiles_data->upgrade_request,
                "upgrade_comment"       =>      $profiles_data->upgrade_comment,
                "photo_score_updated"   =>      $profiles_data->photo_score_updated,
                "is_active"             =>      $profiles_data->is_active,
                "matchmaker_id"         =>      $profiles_data->matchmaker_id,
                "is_working"            =>      $profiles_data->is_working,
                "amount_fix"            =>      $profiles_data->amount_fix,
                "is_profile_pic"        =>      $profiles_data->is_profile_pic,
                "bonus"                 =>      $profiles_data->bonus,
                "is_delete"             =>      $profiles_data->is_delete,
                "payment_status"        =>      $profiles_data->payment_status,
                "matchmaker_note"       =>      $profiles_data->matchmaker_note,
                "want_horoscope_match"  =>      $profiles_data->want_horoscope_match,
                "botuser"               =>      $profiles_data->botuser,
                "is_approved"           =>      $profiles_data->is_approved,
                "approved_by"           =>      $profiles_data->approved_by,
                "not_interested"        =>      $profiles_data->not_interested,
                "is_automate_approve"   =>      $profiles_data->is_automate_approve,
                "is_completed"          =>      $profiles_data->is_completed,
                "fcm_id"                =>      $profiles_data->fcm_id,
                "bot_status"            =>      $profiles_data->bot_status,
                "last_seen"             =>      $profiles_data->last_seen,
                "lead_status"           =>      $profiles_data->lead_status,
                "fcm_app"               =>      $profiles_data->fcm_app,
                "is_paid"               =>      $profiles_data->is_paid,
                "is_premium"            =>      $profiles_data->is_premium,
                "education_score"       =>      $profiles_data->education_score,
                "literacy_score"        =>      $profiles_data->edu_score,
                "lat_locality"          =>      $profiles_data->lat_locality,
                "long_locality"         =>      $profiles_data->long_locality,
                "dual_approved"         =>      $profiles_data->dual_approved,
                "is_approved_on"        =>      $profiles_data->is_approved_on,
                "double_approval"       =>      $profiles_data->double_approval,
                "profile_pdf"           =>      $profiles_data->profile_pdf,
                "is_premium_interest"   =>      $profiles_data->is_premium_interest,
                "exhaust_reject"        =>      $profiles_data->exhaust_reject,
                "temp_upgrade"          =>      $profiles_data->temp_upgrade,
                "is_sales_approve"      =>      $profiles_data->is_sales_approve,
                "si_event"              =>      $profiles_data->si_event,
                "is_subscription_view"  =>      $profiles_data->is_subscription_view,
                "freshness_score"       =>      $profiles_data->freshness_score,
                "salary_score"          =>      $profiles_data->salary_score,
                "testSalaryScore"       =>      $profiles_data->testSalaryScore,
                "revise_education"      =>      $profiles_data->revise_education,
                "revise_education"      =>      $profiles_data->revise_education,
                "goodness_score"        =>      $profiles_data->goodness_score,
                "goodness_score_female" =>      $profiles_data->goodness_score_female,
                "data_score"            =>      $profiles_data->data_score,
                "visibility_score"      =>      $profiles_data->visibility_score,
                "zvaluePhoto"           =>      $profiles_data->zvaluePhoto,
                "zSalaryScore"          =>      $profiles_data->zSalaryScore,
                "zFreshnessScore"       =>      $profiles_data->zFreshnessScore,
                "zeduScore"             =>      $profiles_data->zeduScore,
                "zGoodNessScore"        =>      $profiles_data->zGoodNessScore,
                "zGoodNessScoreFemale"  =>      $profiles_data->zGoodNessScoreFemale,
                "testGoodness"          =>      $profiles_data->testGoodness,
                "zdataScore"            =>      $profiles_data->zdataScore,
                "activity_score"        =>      $profiles_data->activity_score,
                "zactivity_score"       =>      $profiles_data->zactivity_score,
                "zvisibility_score"     =>      $profiles_data->zvisibility_score,
                "starvation_score"      =>      $profiles_data->starvation_score,
                "zStarvation"           =>      $profiles_data->zStarvation,
                "boost_score"           =>      $profiles_data->boost_score,
                "zBoost_score"          =>      $profiles_data->zBoost_score,
                "request_by"            =>      $profiles_data->request_by,
                "crm_created"           =>      $profiles_data->crm_created,
                "call_count"            =>      $profiles_data->call_count,
                "profile_exclude"       =>      $profiles_data->profile_exclude,
                "unapprove_carousel"    =>      $profiles_data->unapprove_carousel,
                "is_call_back"          =>      $profiles_data->is_call_back,
                "call_back_query"       =>      $profiles_data->call_back_query,
                "last_si_date"          =>      $profiles_data->last_si_date,
                "testProfileGoodNess_score" =>      $profiles_data->testProfileGoodNess_score,
                "last_reject_date"      =>      $profiles_data->last_reject_date,
                "exhaust_reject_count"  =>      $profiles_data->exhaust_reject_count,
                "boost_goodNess_score"  =>      $profiles_data->boost_goodNess_score,
                "is_deleted"            =>      $profiles_data->is_deleted,
                "deleted_by"            =>      $profiles_data->deleted_by,
                "relation_value"        =>      $profiles_data->relation_value,
                "max_income"            =>      $profiles_data->max_income,

                "norm_maxIncome"        =>      $profiles_data->norm_maxIncome,
                "norm_relation_value"   =>      $profiles_data->norm_relation_value,
                "lead_value"            =>      $profiles_data->lead_value,
                "norm_income_value"     =>      $profiles_data->norm_income_value,
                "pending_meetings"      =>      $profiles_data->pending_meetings,
                "raw_profile_data"      =>      $profiles_data->raw_profile_data,
                "matchmaker_is_profile"    =>      $profiles_data->matchmaker_is_profile,
                "profile_comes_from"    =>      $profiles_data->profile_comes_from,
                "profile_sent_day"      =>      $profiles_data->profile_sent_day,



                "relation"             =>      $profiles_data->relation,
                "livingWithParents"    =>      $profiles_data->livingWithParents,
                "locality"             =>      $profiles_data->locality,
                "landline"             =>      $profiles_data->landline,
                "family_photo"         =>      $profiles_data->family_photo,
                "city_family"          =>      $profiles_data->city,
                "native"               =>      $profiles_data->native,
                "mobile_family"        =>      $profiles_data->mobile,
                "backup_mobile_family" =>      $profiles_data->backup_mobile,
                "email_family"         =>      $profiles_data->email,
                "unmarried_brothers"   =>      $profiles_data->unmarried_sons,
                "married_brothers"     =>      $profiles_data->married_sons,
                "unmarried_sisters"    =>      $profiles_data->unmarried_daughters,
                "married_sisters"      =>      $profiles_data->married_daughters,
                "family_type"          =>      $profiles_data->family_type,
                "house_type"           =>      $profiles_data->house_type,
                "religion"             =>      $profiles_data->religion,
                "caste"                =>      $profiles_data->caste,
                "gotra"                =>      $profiles_data->gotra,
                "family_income"        =>      $profiles_data->family_income,
                "budget"               =>      $profiles_data->budget,
                "father_status"        =>      $profiles_data->father_status,
                "mother_status"        =>      $profiles_data->mother_status,
                "whatsapp_family"      =>      $profiles_data->whats_app_no,
                "marriage_budget_not_applicable"   =>      $profiles_data->marriage_budget_not_applicable,
                "email_not_available"   =>     $profiles_data->email_not_available, "mother_tongue"         =>      $profiles_data->mother_tongue,
                "sub_caste"            =>      $profiles_data->sub_caste,
                "occupation_mother"    =>      $profiles_data->occupation_mother,
                "address"              =>      $profiles_data->office_address_mother,
                "zodiac"               =>      $profiles_data->zodiac,
                "father_off_addr"      =>      $profiles_data->office_address_mother,
                "office_address_mother"    =>      $profiles_data->office_address_mother,
                "email_verified"       =>      $profiles_data->email_verified,
                "welcome_call_done"    =>      $profiles_data->welcome_call_done,
                "verification_call_done"   =>      $profiles_data->verification_call_done,
                "welcome_call_time"    =>      $profiles_data->welcome_call_time,
                "verifcation_call_time" =>      $profiles_data->verifcation_call_time,
            ]);
           // dd($profiles_data->identity_number.' | '. $profiles_data->temple_id);
            // save into preference
            $preference_data = DB::connection('mysql_old')
                ->table('preferences')->where(['identity_number' => $profiles_data->identity_number, 'temple_id' => $profiles_data->temple_id])->first();
            $pref_array = (array) $preference_data;

            $preference = UserPreference::create($pref_array);

            // save compatblity
            $compat_data = DB::connection('mysql_old')
                ->table('compatibilities')->where('user_id', $profiles_data->id)->first();
            $comp_array = (array) $compat_data;
            $save_compat = UserCompatblity::create($comp_array);

            if($insert_user_data && $preference && $save_compat){
                DB::commit();
            }else{
                DB::rollBack();
            }
        }*/
        MigrationJobProcess::dispatch();
        //  return "send";
        //return $batch;
    }

    // migrate leads
    public function migrateLeads()
    {
        foreach (DB::connection('mysql_old')
            ->table('leads')
            ->join('free_users', 'free_users.lead_id', 'leads.id')
            ->join('lead_family', 'lead_family.lead_id', 'leads.id')
            ->where(["leads.is_deleted" => 0])
            ->whereRaw("year(leads.created_at)>=2021")
            ->orderBy('leads.id', 'desc')
            ->cursor() as $profiles_datas) {
            $batch = Bus::batch()->dispatch();

            $batch->add(new MigrateLEads(($profiles_datas)));

            //MigrateLEads::dispatch($profiles_datas);
        }
        return $batch;
    }

    // data conversion
    public function dataConversionUpdate()
    {
    }

    // fill data one by one
    public function checkAndUpdateLeads()
    {
        MigrateLEads::dispatch();
    }

    public function prepareCaste()
    {

        // create family type
        DB::table('family_type_mappings')->insert([
            [
                "id"            => 1,
                "mapping_id"    => 1,
                "name"          => "Nuclear",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 2,
                "mapping_id"    => 2,
                "name"          => "Joint",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);


        // create family type
        DB::table('religion_mapping')->insert([
            [
                "id" => 1,
                "religion" => "Hindu",
                "mapping_id" => 1,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 2,
                "religion" => "Sikh", "
                mapping_id" => 1,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 3,
                "religion" => "Jain", "
                mapping_id" => 1,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 4,
                "religion" => "Muslim",
                "mapping_id" => 2,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 5,
                "religion" => "Christian",
                "mapping_id" => 3,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 6,
                "religion" => "Buddhist",
                "mapping_id" => 4,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 7,
                "religion" => "Bahai",
                "mapping_id" => 5,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 8,
                "religion" => "Jewish",
                "mapping_id" => 6,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id" => 9,
                "religion" => "Parsi",
                "mapping_id" => 7,
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // house type maoping
        DB::table('house_type_mappings')->insert([
            [
                "id"  => 1,
                "mapping_id"  => 1,
                "name"    => "Owned",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"  => 2,
                "mapping_id"  => 2,
                "name"    => "Rented",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"  => 3,
                "mapping_id"  => 3,
                "name"    => "Leased",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // parent occupation
       DB::table('parrent_occupation_mappings')->insert([
            [
                "id"           => 1,
                "mapping_id"   => 0,
                "name"         => "Not Alive",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 2,
                "mapping_id"   => 1,
                "name"         => "Business/Self Employed",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 3,
                "mapping_id"   => 2,
                "name"         => "Doctor",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 4,
                "mapping_id"   => 3,
                "name"         => "Government Job",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 5,
                "mapping_id"   => 4,
                "name"         => "Teacher",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 6,
                "mapping_id"   => 5,
                "name"         => "Private Job",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 7,
                "mapping_id"   => 6,
                "name"         => "Not Working",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 8,
                "mapping_id"   => 7,
                "name"         => "Other",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"           => 9,
                "mapping_id"   => 8,
                "name"         => "Retired",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // mrital status mapping
        DB::table('marital_status_mappings')->insert([
            [
                "id"                => 1,
                "marital_status_id" => 1,
                "name"              => "Never Married",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 2,
                "marital_status_id" => 2,
                "name"              => "Married",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 3,
                "marital_status_id" => 3,
                "name"              => "Awaiting Divorce",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 4,
                "marital_status_id" => 4,
                "name"              => "Divorcee",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 5,
                "marital_status_id" => 5,
                "name"              => "Widowed",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 6,
                "marital_status_id" => 6,
                "name"              => "Anulled",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"                => 7,
                "marital_status_id" => 7,
                "name"              => "Doesn't Matter",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // manglik mapping
        DB::table('manglik_mappings')->insert([
            [
                "id"        => 1,
                "mapping_id" => 1,
                "name"      => "Manglik",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 2,
                "mapping_id" => 2,
                "name"      => "Non-Manglik",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 3,
                "mapping_id" => 3,
                "name"      => "Anshik Manglik",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 4,
                "mapping_id" => 4,
                "name"      => "Don't Know",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 5,
                "mapping_id" => 5,
                "name"      => "Doesn't Matter",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // food choice mapping
        DB::table('foodchoice_mappings')->insert([
            [
                "id"          => 1,
                "mapping_id"  => 0,
                "name"        => "Doesn't Matter",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"          => 2,
                "mapping_id"  => 1,
                "name"        => "Vegetarian",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"          => 3,
                "mapping_id"  => 2,
                "name"        => "Non-Vegetarian",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // relation mapping
        DB::table('relation_mappings')->insert([
            [
                "id"            => 1,
                "mapping_id"    => 1,
                "name"          => "Myself",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 2,
                "mapping_id"    => 2,
                "name"          => "Mother",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 3,
                "mapping_id"    => 3,
                "name"          => "Father",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 4,
                "mapping_id"    => 4,
                "name"          => "Sister",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 5,
                "mapping_id"    => 5,
                "name"          => "Brother",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 6,
                "mapping_id"    => 6,
                "name"          => "Son",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 7,
                "mapping_id"    => 7,
                "name"          => "Daughter",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"            => 8,
                "mapping_id"    => 8,
                "name"          => "Other",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // gender mapping
        DB::table('gender_mappings')->insert([
            [
                "id"        => 1,
                "mapping_id" => 1,
                "name"      => "Male",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 2,
                "mapping_id" => 2,
                "name"      => "Female",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        // occupation mapping
        DB::table('occupation_mappings')->insert([
            [
                "id"        => 1,
                "mapping_id" => 1,
                "name"      => "Business/Self Employed",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 2,
                "mapping_id" => 2,
                "name"      => "Doctor",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 3,
                "mapping_id" => 3,
                "name"      => "Government Job",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 4,
                "mapping_id" => 4,
                "name"      => "Teacher",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 5,
                "mapping_id" => 5,
                "name"      => "Private Job",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 6,
                "mapping_id" => 6,
                "name"      => "Not Working",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ],
            [
                "id"        => 7,
                "mapping_id" => 7,
                "name"      => "Other",
                "created_at"    =>      date('Y-m-d H:i:s'),
                "updated_at"    =>      date('Y-m-d H:i:s')
            ]
        ]);

        return response()->json(['type'=>true,'message'=>'successd']);
    }

    public function dispacthCities()
    {

        $cities = resource_path() . "/master_jsons/cities.json";
        $city_aray = json_decode(File::get($cities), true);
        foreach (array_chunk($city_aray, 500) as $city) {

            $create_city = CityLists::insert($city);
        }

        // used to pupulate user_data_id in leads
       // InsertCities::dispatch();
    }

    // update name in user_data
    public function updateName()
    {
        UpdateName::dispatch();
    }

    // update compatblities
    public function updateCompatblities()
    {
        UpdateCompatblity::dispatch();
        UpdateLeadCompatblity::dispatch();
        UpdateCompatblityData::dispatch();
    }

    // update preference
    public function updatePreference()
    {
        UpdatePreference::dispatch();
        UpdateLeadPreference::dispatch();
        UpdatePreferenceUserData::dispatch();
    }

    // update lead id
    public function updateUserDataId()
    {
        $user_data = DB::table('lead_family')
        ->select('mobile', 'lead_family.lead_id')
        ->orderBy('id', 'DESC')
        ->chunk(100, function ($leads) {
            UpdreUSerDataId::dispatch($leads);
        });
    }
}
