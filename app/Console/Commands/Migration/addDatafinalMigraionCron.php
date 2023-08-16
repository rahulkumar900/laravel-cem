<?php

namespace App\Console\Commands\Migration;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class addDatafinalMigraionCron extends Command
{

    protected $signature = 'migrate:userdatas';

    // php artisan migrate:userdatas
    /*
        steps to save migration data into their respective table
        1. get all active profiles from profiles table while adding data into user data convert their integer values to their respective string convert : gender, marital status, occupation, father occupation, mother occupation, income to float, working city, houst type, relation
        2. get their preference convert : caste pref, city, manglik, marital status income to float
        3. get their compatibilities update : userMatches table later after success run of cron
        4. get all premium meeting and update their respective new user_ids upadte premium meeting profile id to new user_data ids
    */

    public function handle()
    {
        $profile_data = DB::connection('mysql_second')->table('profiles')->where(["profiles.is_deleted" => 0, "record_updated" => 0])->select(['profiles.*', 'profiles.id as profile_id', 'profiles.created_at as profile_created', 'profiles.updated_at as profiles_updated'])->orderBy('profiles.id', 'desc')->chunk(50, function ($profile_datas) {
            foreach ($profile_datas as $profile) {
                DB::beginTransaction();
                $family_details = DB::connection('mysql_second')->table("families")->where('id', $profile->id)->first();

                // mapping arrays
                $gender_mappings = array("Male", "Memale");
                $family_mappings = array("Nuclear", "Joint");
                $house_type_mappings = array("Owned", "Rented", "Leased");
                $manglik_mappings = array("Manglik", "Non-Manglik", "Anshik Manglik", "Don't Know", "Doesn't Matter");
                $marital_status_mappings = array('Never Married', 'Married', 'Awaiting Divorce', 'Divorcee', 'Widowed', 'Anulled', "Doesn't Matter");
                $occupation_mappings = array("Business/Self Employed", "Doctor", "Government Job", "Teacher", "Private Job", "Not Working", "Other");
                $relation_mappings = array('Myself', 'Mother', 'Father', 'Sister', 'Brother', 'Son', 'Daughter', 'Other');
                $religion_mapping = array('Hindu', 'Sikh', 'Jain', 'Muslim', 'Christian', 'Buddhist', 'Bahai', 'Jewish', 'Parsi');

                // don't increase index
                $parrent_occupation_mappings = array('Business/Self Employed', 'Doctor', 'Government Job', 'Not Alive', 'Not Working', 'Other', 'Private Job', 'Retired', 'Teacher');
                $foodchoice_mappings = array("Doesn't Matter", "Vegetarian", "Non-Vegetarian");

                /* profile conversion and updation */

                // food code
                $food_code = 0;
                $food_String = "Doesn't Matter";
                if (!empty($profile->food_choice) && in_array(ucwords($profile->food_choice), $foodchoice_mappings)) {
                    $position_fd = array_search(ucwords($profile->food_choice), $foodchoice_mappings);
                    $food_String = $foodchoice_mappings[$position_fd];
                    $food_code = $position_fd;
                } else {
                    $food_code = 0;
                    $food_String = "Doesn't Matter";
                }

                // gender code
                $gender_code = 1;
                $gender_String = "Male";
                if (!empty($profile->gender) && in_array(ucwords($profile->gender), $gender_mappings)) {
                    $position = array_search(ucwords($profile->gender), $gender_mappings);
                    $gender_String = $gender_mappings[$position];
                    $gender_code = $position + 1;
                } else {
                    $gender_code = 1;
                    $gender_String = "Female";
                }

                // family code
                $family_code = 1;
                $family_string = "Nuclear";
                if (!empty($profile->family_type) && in_array(ucwords($profile->family_type), $family_mappings)) {
                    $position_ft = array_search(ucwords($profile->family_type), $family_mappings);
                    $family_string = $family_mappings[$position_ft];
                    $family_code = $position_ft + 1;
                } else {
                    $family_code = 1;
                    $family_string = "Nuclear";
                }

                // house code
                $house_code = 1;
                $house_string = "Owned";
                if (!empty($family_details->house_type) && in_array(ucwords($family_details->house_type), $house_type_mappings)) {
                    $position_h = array_search(ucwords($family_details->house_type), $house_type_mappings);
                    $house_string = $house_type_mappings[$position_h];
                    $house_code = $position_h + 1;
                } else {
                    $house_code = 1;
                    $house_string = "Owned";
                }

                // manglik code
                $manglik_code = 2;
                $manglik_string = "Non-Manglik";
                if (!empty($profile->manglik) && in_array(ucwords($profile->manglik), $manglik_mappings)) {
                    $position_m = array_search(ucwords($profile->manglik), $manglik_mappings);
                    $manglik_string = $manglik_mappings[$position_m];
                    $manglik_code = $position_m + 1;
                } else {
                    $manglik_code = 2;
                    $manglik_string = "Non-Manglik";
                }

                // marital code
                $marital_code = 1;
                $marital_string = "Never Married";
                if (!empty($profile->marital_status) && in_array(ucwords($profile->marital_status), $marital_status_mappings)) {
                    $position_ma = array_search(ucwords($profile->marital_status), $marital_status_mappings);
                    $marital_string = $marital_status_mappings[$position_ma];
                    $marital_code = $position_ma + 1;
                } else {
                    $marital_code = 1;
                    $marital_string = "Never Married";
                }

                // occupation code
                $occupation_code = 1;
                $occupation_string = "Not-Working";
                if (!empty($profile->occupation) && in_array(ucwords($profile->occupation), $occupation_mappings)) {
                    $position_oc = array_search(ucwords($profile->occupation), $occupation_mappings);
                    $occupation_string = $occupation_mappings[$position_oc];
                    $occupation_code = $position_oc + 1;
                } else {
                    $occupation_code = 1;
                    $occupation_string = "Not-Working";
                }

                // relation code
                $relation_code = 1;
                $relation_string = "Self";
                if (!empty($family_details->relation) && in_array(ucwords($family_details->relation), $relation_mappings)) {
                    $position_re = array_search(ucwords($family_details->relation), $relation_mappings);
                    $relation_string = $relation_mappings[$position_re];
                    $relation_code = $position_re + 1;
                } else {
                    $relation_code = 1;
                    $relation_string = "Self";
                }

                // religion code
                $religion_code = 1;
                $religion_string = "Hindu";
                if (!empty($family_details->religion) && in_array(ucwords($family_details->religion), $religion_mapping)) {
                    $position_rel = array_search(ucwords($family_details->religion), $religion_mapping);
                    $religion_string = $religion_mapping[$position_rel];
                    $religion_code = $position_rel + 1;
                } else {
                    $religion_code = 1;
                    $religion_string = "Hindu";
                }

                // father occupation code
                $parent_occ_code = 0;
                $parent_occ_string = 'Not Working';
                if (!empty($family_details->father_status) && in_array(ucwords($family_details->father_status), $parrent_occupation_mappings)) {
                    $position_rel = array_search(ucwords($family_details->father_status), $parrent_occupation_mappings);
                    $parent_occ_string = $parrent_occupation_mappings[$position_rel];
                    $parent_occ_code = $position_rel;
                } else {
                    $parent_occ_code = 0;
                    $parent_occ_string = 'Not Working';
                }

                // motyher occupation code
                $parent_occ_code_mot = 0;
                $parent_occ_string_mot = 'Not Working';
                if (!empty($family_details->mother_status) && in_array(ucwords($family_details->mother_status), $parrent_occupation_mappings)) {
                    $position_rel_mot = array_search(ucwords($family_details->mother_status), $parrent_occupation_mappings);
                    $parent_occ_string_mot = $parrent_occupation_mappings[$position_rel_mot];
                    $parent_occ_code_mot = $position_rel_mot;
                } else {
                    $parent_occ_code_mot = 0;
                    $parent_occ_string_mot = 'Not Working';
                }

                // caste code
                $caste_code = "";
                $caste_string = "Arora";
                $caste_code_int = 11;
                if (!empty($family_details->caste)) {
                    $caste_code = DB::table('castes')->where('value', $family_details->caste)->first();
                    if (!empty($caste_code)) {
                        $caste_string = $caste_code->value;
                        $caste_code_int = $caste_code->id;
                    } else {
                        $caste_string = "Arora";
                        $caste_code_int = 11;
                    }
                }

                $annual_income = 0;
                if ($profile->monthly_income > 100) {
                    $annual_income = $profile->monthly_income / 100000;
                } else {
                    $annual_income = $profile->monthly_income;
                }

                // education code
                $education_string = "";
                $education_code = "";
                $lit_score = 0;
                $education_data = DB::table('educations')->where('degree_name', $profile->education)->first();
                if (!empty($education_data)) {
                    $education_string = $education_data->degree_name;
                    $education_code = $education_data->id;
                    $lit_score = $education_data->degree_score;
                } else {
                    $education_string = "High School";
                    $education_code = 53;
                    $lit_score = 0;
                }

                // get preference data
                $preference_data = DB::connection('mysql_second')->table('preferences')->where(["identity_number" => $profile->identity_number, "temple_id" => $profile->temple_id])->first();
                $caste_code_arr = array();
                if (!empty($preference_data->caste) && $preference_data->caste != 'All') {
                    $caste_comma_sep = explode(",", $preference_data->caste);
                    // echo $caste_comma_sep;
                    $caste_codes = DB::table('castes')->whereIn("value", $caste_comma_sep)->get()->toArray();
                    $caste_code_arr = array_column($caste_codes, "id");
                } else {
                    $caste_code_arr = [467];
                }

                $birth_date_time = "";

                if (!empty($profile->birth_date) && !empty($profile->birth_time)) {
                    $birth_date_time = $profile->birth_date . ' ' . $profile->birth_time;
                } else if (!empty($profile->birth_date) && empty($profile->birth_time)) {
                    $birth_date_time = $profile->birth_date . ' ' . date('H:i:s');
                } else {
                    $birth_date_time = date('Y-m-d H:i:s');
                }

                $famiily_income_total= "";
                if(!empty($family_details->family_income)){
                    $famiily_income_total = ($family_details->family_income / 100000);
                }else{
                    $famiily_income_total = 1.25;
                }
                /* record insertion starts */

                $mobile = "";
                if(strlen($profile->mobile_profile) > 15){
                    $mobile = substr($profile->mobile_profile, 14);
                }else{
                    $mobile = $profile->mobile_profile;
                }

                $last_followup_date = "";

                if(empty($profile->last_followup_date) || $profile->last_followup_date == '0000-00-00 00:00:00'){
                    $last_followup_date = date('Y-m-d H:i:s');
                }else{
                    $last_followup_date = $profile->last_followup_date;
                }

                $check_existed_id = DB::table("user_data")->where("mobile_profile", $profile->mobile_profile)->first();
                if (empty($check_existed_id)) {
                    // insert into user data
                    $insert_user_data = DB::table('user_data')->insert([
                        "is_lead"                   =>      null,
                        "profile_id"                =>      $profile->profile_id,
                        "free_user_id"              =>      null,
                        "is_client_of"              =>      null,
                        "isApproved"                =>      $profile->isApproved,
                        "is_approve_ready"          =>      0,
                        "temple_id"                 =>      $profile->temple_id,
                        "priority_temple"           =>      $profile->priority_temple,
                        "identity_number"           =>      $profile->identity_number,
                        "lead_id"                   =>      $profile->lead_id,
                        "extra_lead_id"             =>      null,
                        "backup_lead_id"            =>      null,
                        "org_lead_id"               =>      null,
                        "name"                      =>      $profile->name,
                        "gender"                    =>      $gender_String,
                        "mobile_profile"            =>      $profile->mobile_profile,
                        "user_mobile"               =>      $mobile,
                        "new_mobile_freeUser"       =>      $profile->mobile_profile,
                        "new_mobile_leadFamily"     =>      $profile->mobile_profile,
                        "age_freeUser"              =>      null,
                        "sent_profiles_freeUser"    =>      null,
                        "not_delete"                =>      $profile->not_delete,
                        "photo"                     =>      $profile->photo,
                        "photo_score"               =>      $profile->photo_score,
                        "profiles_sent"             =>      $profile->profiles_sent,
                        "whatsapp"                  =>      $profile->whatsapp,
                        "facebook"                  =>      $profile->facebook,
                        "aadhar"                    =>      $profile->aadhar,
                        "birth_place"               =>      $profile->birth_place,
                        "birth_date"                =>      $birth_date_time ?? date('Y-m-d H:i:s'),
                        "birth_time"                =>      $profile->birth_time ?? date('H:i:s'),
                        "weight"                    =>      $profile->weight,
                        "height"                    =>      $profile->height,
                        "height_int"                =>      $profile->height,
                        "education"                 =>      $education_string,
                        "degree"                    =>      $profile->degree,
                        "college"                   =>      $profile->college,
                        "occupation"                =>      $occupation_string,
                        "profession"                =>      $profile->profession,
                        "working_city"              =>      $profile->working_city,
                        "user_working_city"         =>      null,
                        "disability"                =>      $profile->disability,
                        "is_disable"                =>      null,
                        "food_choice"               =>      $food_String,
                        "manglik"                   =>      $manglik_string,
                        "skin_tone"                 =>      $profile->skin_tone,
                        "monthly_income"            =>      $annual_income,
                        "annual_income"             =>      $annual_income,
                        "marital_status"            =>      $marital_string,
                        "citizenship"               =>      $profile->citizenship,
                        "office_address"            =>      $profile->office_address,
                        "about"                     =>      $profile->about,
                        "audio_profile"             =>      $profile->audio_profile,
                        "earned_rewards"            =>      $profile->earned_rewards,
                        "audio_uploaded_at"         =>      $profile->audio_uploaded_at,
                        "audio_uploaded_by"         =>      $profile->audio_uploaded_by,
                        "audio_approved_at"         =>      $profile->audio_approved_at,
                        "unapprove_audio_profile"   =>      $profile->unapprove_audio_profile,
                        "audioProfile_hls_convert"  =>      $profile->audioProfile_hls_convert,
                        "lead_created_at"           =>      null,
                        "created_at_profile"        =>      $profile->profile_created,
                        "updated_at_profile"        =>      $profile->profiles_updated,
                        "children"                  =>      $profile->children,
                        "disabled_part"             =>      $profile->disabled_part,
                        "precedence"                =>      $profile->precedence,
                        "profile_type"              =>      $profile->profile_type,
                        "abroad"                    =>      $profile->abroad,
                        "is_nri"                    =>      $profile->is_invisible,
                        "is_invisible"              =>      $profile->is_invisible,
                        "wishing_to_settle_abroad"  =>      $profile->wishing_to_settle_abroad,
                        "is_renewed"                =>      $profile->is_renewed,
                        "blatitude"                 =>      $profile->blatitude,
                        "bNS"                       =>      $profile->bNS,
                        "blongitude"                =>      $profile->blongitude,
                        "bEW"                       =>      $profile->bEW,
                        "country"                   =>      $profile->country,
                        "countryCode_user"          =>      null,
                        "state"                     =>      $profile->state,
                        "stateCode_user"            =>      null,
                        "comments"                  =>      $profile->comments,
                        "enquiry_date"              =>      $profile->enquiry_date,
                        "followup_call_on"          =>      $profile->followup_call_on,
                        "last_followup_date"        =>      $last_followup_date,
                        "upgrade_renew"             =>      $profile->upgrade_renew,
                        "wantBot"                   =>      $profile->wantBot,
                        "manglik_id"                =>      $profile->manglik_id,
                        "plan_name"                 =>      $profile->plan_name,
                        "carousel"                  =>      $profile->carousel,
                        "photo_url"                 =>      null,
                        "corrupt_carousel"          =>      $profile->corrupt_carousel,
                        "corrupt_photo_url"         =>      null,
                        "caste_id_profile"          =>      $caste_code_int,
                        "company"                   =>      $profile->company,
                        "additional_qualification"  =>      $profile->additional_qualification,
                        "bot_language"              =>      $profile->bot_language,
                        "page_skipped"              =>      $profile->page_skipped,
                        "token"                     =>      $profile->token,
                        "upgrade_request"           =>      $profile->upgrade_request,
                        "upgrade_comment"           =>      $profile->upgrade_comment,
                        "photo_score_updated"       =>      $profile->photo_score_updated,
                        "is_active"                 =>      $profile->is_active,
                        "matchmaker_id"             =>      $profile->matchmaker_id,
                        "is_working"                =>      $profile->is_working,
                        "amount_fix"                =>      $profile->amount_fix,
                        "is_profile_pic"            =>      $profile->is_profile_pic,
                        "bonus"                     =>      $profile->bonus,
                        "is_delete"                 =>      $profile->is_delete,
                        "payment_status"            =>      $profile->payment_status,
                        "matchmaker_note"           =>      $profile->matchmaker_note,
                        "want_horoscope_match"      =>      $profile->want_horoscope_match,
                        "botuser"                   =>      $profile->botuser,
                        "is_approved"               =>      $profile->is_approved,
                        "approved_by"               =>      $profile->approved_by,
                        "not_interested"            =>      $profile->not_interested,
                        "is_automate_approve"       =>      $profile->is_automate_approve,
                        "is_completed"              =>      $profile->is_completed,
                        "fcm_id"                    =>      $profile->fcm_id,
                        "bot_status"                =>      $profile->bot_status,
                        "last_seen"                 =>      $profile->last_seen,
                        "lead_status"               =>      $profile->lead_status,
                        "fcm_app"                   =>      $profile->fcm_app,
                        "is_paid"                   =>      $profile->is_paid,
                        "is_premium"                =>      $profile->is_premium,
                        "education_score"           =>      $profile->education_score,
                        "literacy_score"            =>      $lit_score,
                        "lat_locality"              =>      $profile->lat_locality,
                        "long_locality"             =>      $profile->long_locality,
                        "dual_approved"             =>      $profile->dual_approved,
                        "is_approved_on"            =>      $profile->is_approved_on,
                        "double_approval"           =>      $profile->double_approval,
                        "profile_pdf"               =>      $profile->profile_pdf,
                        "is_premium_interest"       =>      $profile->is_premium_interest,
                        "exhaust_reject"            =>      $profile->exhaust_reject,
                        "temp_upgrade"              =>      $profile->temp_upgrade,
                        "is_sales_approve"          =>      $profile->is_sales_approve,
                        "si_event"                  =>      $profile->si_event,
                        "is_subscription_view"      =>      $profile->is_subscription_view,
                        "freshness_score"           =>      $profile->freshness_score,
                        "salary_score"              =>      $profile->salary_score,
                        "testSalaryScore"           =>      $profile->testSalaryScore,
                        "revise_education"          =>      $profile->revise_education,
                        "edu_score"                 =>      $profile->edu_score,
                        "goodness_score"            =>      $profile->goodness_score,
                        "goodness_score_female"     =>      $profile->goodness_score_female,
                        "data_score"                =>      $profile->data_score,
                        "visibility_score"          =>      $profile->visibility_score,
                        "zvaluePhoto"               =>      $profile->zvaluePhoto,
                        "zSalaryScore"              =>      $profile->zSalaryScore,
                        "zFreshnessScore"           =>      $profile->zFreshnessScore,
                        "zeduScore"                 =>      $profile->zeduScore,
                        "zGoodNessScore"            =>      $profile->zGoodNessScore,
                        "zGoodNessScoreFemale"      =>      $profile->zGoodNessScoreFemale,
                        "testGoodness"              =>      $profile->testGoodness,
                        "zdataScore"                =>      $profile->zdataScore,
                        "activity_score"            =>      $profile->activity_score,
                        "zactivity_score"           =>      $profile->zactivity_score,
                        "zvisibility_score"         =>      $profile->zvisibility_score,
                        "starvation_score"          =>      $profile->starvation_score,
                        "zStarvation"               =>      $profile->zStarvation,
                        "boost_score"               =>      $profile->boost_score,
                        "zBoost_score"              =>      $profile->zBoost_score,
                        "request_by"                =>      $profile->request_by,
                        "crm_created"               =>      $profile->crm_created,
                        "call_count"                =>      $profile->call_count,
                        "profile_exclude"           =>      $profile->profile_exclude,
                        "unapprove_carousel"        =>      $profile->unapprove_carousel,
                        "is_call_back"              =>      $profile->is_call_back,
                        "call_back_query"           =>      $profile->call_back_query,
                        "last_si_date"              =>      $profile->last_si_date,
                        "testProfileGoodNess_score" =>      $profile->testProfileGoodNess_score,
                        "last_reject_date"          =>      $profile->last_reject_date,
                        "exhaust_reject_count"      =>      $profile->exhaust_reject_count,
                        "boost_goodNess_score"      =>      $profile->boost_goodNess_score,
                        "is_deleted"                =>      $profile->is_delete,
                        "deleted_by"                =>      $profile->deleted_by,
                        "relation_value"            =>      $profile->relation_value,
                        "max_income"                =>      $profile->max_income ?? null,
                        "norm_maxIncome"            =>      $profile->norm_maxIncome,
                        "norm_relation_value"       =>      $profile->norm_relation_value,
                        "lead_value"                =>      $profile->lead_value,
                        "norm_income_value"         =>      $profile->norm_income_value,
                        "pending_meetings"          =>      $profile->pending_meetings,
                        "raw_profile_data"          =>      $profile->raw_profile_data,
                        "matchmaker_is_profile"    =>       $profile->matchmaker_is_profile,
                        "profile_comes_from"        =>      $profile->profile_comes_from,
                        "profile_sent_day"          =>      $profile->profile_sent_day,
                        "id_families"               =>      $family_details->id ?? null,
                        "profile_id_family"         =>      $family_details->id ?? null,
                        "temple_id_family"          =>      $family_details->temple_id ?? null,
                        "identity_number_family"    =>      $family_details->identity_number ?? null,
                        "name_family"               =>      $family_details->name ?? null,
                        "relation"                  =>      $relation_string ?? null,
                        "livingWithParents"         =>      $family_details-> livingWithParents ?? null,
                        "is_livingWithParents"      =>      1,
                        "locality"                  =>      $family_details-> locality ?? null,
                        "landline"                  =>      $family_details-> landline ?? null,
                        "family_photo"              =>      $family_details-> family_photo ?? null,
                        "city_family"               =>      $family_details-> city ?? null,
                        "native"                    =>      $family_details-> native ?? null,
                        "mobile_family"             =>      $family_details-> mobile ?? null,
                        "backup_mobile_family"      =>      $family_details-> mobile ?? null,
                        "email_family"              =>      null,
                        "unmarried_brothers"        =>      $family_details-> unmarried_sons ?? null,
                        "married_brothers"          =>      $family_details-> married_sons ?? null,
                        "unmarried_sisters"         =>      $family_details-> unmarried_daughters ?? null,
                        "married_sisters"           =>      $family_details-> married_daughters ?? null,
                        "family_type"               =>      $family_string,
                        "house_type"                =>      $house_string,
                        "religion"                  =>      $religion_string,
                        "caste"                     =>      $caste_string,
                        "gotra"                     =>      $family_details-> gotra ?? null,
                        "occupation_father"         =>      $parent_occ_string ?? null,
                        "occupation_father_code"    =>      $parent_occ_code,
                        "family_income"             => $famiily_income_total,
                        "budget"                    =>      $family_details-> budget ?? null,
                        "father_status"             =>      $parent_occ_string,
                        "father_statusCode"         =>      $parent_occ_code,
                        "mother_status"             =>      $parent_occ_string_mot,
                        "mother_statusCode"         =>      $parent_occ_code_mot,
                        "created_at_family"         =>      $family_details-> created_at ?? null,
                        "updated_at_family"         =>      $family_details-> updated_at ?? null,
                        "whatsapp_family"           =>      $family_details-> whats_app_no ?? null,
                        "marriage_budget_not_applicable"    => $family_details-> marriage_budget_not_applicable ?? null,
                        "email_not_available"       =>      $family_details-> email_not_available ?? null,
                        "mother_tongue"             =>      $family_details-> mother_tongue ?? null,
                        "sub_caste"                 =>      $family_details-> sub_caste ?? null,
                        "country_family"            =>      null,
                        "state_family"              =>      null,
                        "occupation_mother"         =>      $parent_occ_code_mot,
                        "occupation_mother_code"    =>      $parent_occ_code_mot,
                        "address"                   =>      $family_details-> address ?? null,
                        "about_family"              =>      $family_details-> about ?? null,
                        "caste_id_family"           =>      null,
                        "zodiac"                    =>      $family_details-> zodiac ?? null,
                        "father_off_addr"           =>      $family_details-> father_off_addr ?? null,
                        "office_address_mother"     =>      $family_details-> office_address_mother ?? null,
                        "email_verified"            =>      $family_details-> email_verified ?? null,
                        "matchmaker_is_profile"     =>      $profile-> matchmaker_is_profile ?? null,
                        "genderCode_user"           =>      $gender_code,
                        "educationCode_user"        =>      $education_code,
                        "food_choiceCode"           =>      $food_code,
                        "occupationCode_user"       =>      $occupation_code,
                        'manglikCode'               =>      $manglik_code,
                        'maritalStatusCode'         =>      $marital_code,
                        'casteCode_user'            =>      $caste_code_int,
                        'religionCode'              =>      $religion_code,
                        "houseTypeCode"             =>      $house_code,
                        'familyTypeCode'            =>      $family_code,
                        'relationCode'              =>      $relation_code,
                        'profile_percent'           =>      rand(50, 100),
                        'created_at_freeUser'       =>      null,
                        'updated_at_freeUser'       =>      null,
                        'welcome_call_done'         =>      $profile->welcome_call_done,
                        'verification_call_done'    =>      $profile->verification_call_done,
                        'welcome_call_time'         =>      $profile->welcome_call_time,
                        'verifcation_call_time'     =>      $profile->verifcation_call_time,
                        'pending_temple_id'         =>      $profile->temple_id
                    ]);

                    // select user data
                    $user_data_id = DB::table("user_data")->where("mobile_profile", $profile->mobile_profile)->first();

                    // get compatiblity data

                    $compatblity_data = DB::connection('mysql_second')->table('compatibilities')->where("user_id", $profile->id)->first();
                    if (!empty($compatblity_data)) {

                        // insert into user compatblity
                        $save_compat = DB::table("userCompatibilities")->insert([
                            "tableId_lead_comp"                     =>      null,
                            "tableId_comp"                          =>      null,
                            "user_id_comp"                          =>      null,
                            "user_id_leadComp"                      =>      null,
                            "user_data_id"                          =>      $user_data_id->id,
                            "for_lead"                              =>      null,
                            "compatibility"                         =>      $compatblity_data->compatibility,
                            "compatible_userId"                     =>      null,
                            "newlyjoined_userId"                    =>      null,
                            "newlyjoined_pushedDate"                =>      null,
                            "popular_userId"                        =>      null,
                            "popular_pushedDate"                    =>      null,
                            "discoverCompatibility"                 =>      $compatblity_data->discoverCompatibility,
                            "c_array"                               =>      $compatblity_data->c_array,
                            "current"                               =>      $compatblity_data->current,
                            "last_id"                               =>      $compatblity_data->last_id,
                            "active"                                =>      $compatblity_data->active,
                            "created_at_compatibility"              =>      $compatblity_data->created_at,
                            "updated_at_compatibility"              =>      $compatblity_data->updated_at,
                            "credit_available"                      =>      $compatblity_data->whatsapp_point,
                            "amount_collected"                      =>      $compatblity_data->amount_collected,
                            "daily_quota"                           =>      $compatblity_data->daily_quota,
                            "profile_status"                        =>      $compatblity_data->profile_status,
                            "profile_status_json"                   =>      $compatblity_data->profile_status,
                            "ci_status"                             =>      $compatblity_data->ci_status,
                            "ri_status"                             =>      $compatblity_data->ri_status,
                            "si_status"                             =>      $compatblity_data->si_status,
                            "reminder"                              =>      $compatblity_data->reminder,
                            "extra"                                 =>      $compatblity_data->extra,
                            "read_message"                          =>      $compatblity_data->read_message,
                            "message_is_no"                         =>      $compatblity_data->read_message,
                            "pdfs"                                  =>      $compatblity_data->pdfs,
                            "free_credits_given"                    =>      $compatblity_data->free_credits_given,
                            "free_credit_count"                     =>      $compatblity_data->free_credit_count,
                            "free_id"                               =>      $compatblity_data->free_id,
                            "profile_length"                        =>      $compatblity_data->profile_length,
                            "contacted_count"                       =>      $compatblity_data->contacted_count,
                            "reject_count"                          =>      $compatblity_data->reject_count,
                            "shortlist_count"                       =>      $compatblity_data->shortlist_count,
                            "shown_interest_count"                  =>      $compatblity_data->shown_interest_count,
                            "jeev_count"                            =>      $compatblity_data->jeev_count,
                            "jeev_compatibility"                    =>      $compatblity_data->jeev_compatibility,
                            "jeev_profile_status"                   =>      $compatblity_data->jeev_profile_status,
                            "jeev_timestamp"                        =>      $compatblity_data->jeev_timestamp,
                            "pending_count"                         =>      $compatblity_data->contact_interest_count,
                            "contact_interest_count"                =>      $compatblity_data->contact_interest_count,
                            "mutual_count"                          =>      $compatblity_data->mutual_count,
                            "ri_count"                              =>      $compatblity_data->ri_count,
                            "is_recalculate"                        =>      $compatblity_data->is_recalculate,
                            "si_count"                              =>      $compatblity_data->si_count,
                            "eri_count"                             =>      $compatblity_data->eri_count,
                            "esi_count"                             =>      $compatblity_data->esi_count,
                            "eci_count"                             =>      $compatblity_data->eci_count,
                            "discovery_profile_left"                =>      $compatblity_data->discovery_profile_left,
                            "first_time"                            =>      $compatblity_data->first_time,
                            "first_comp_day"                        =>      $compatblity_data->first_comp_day,
                            "max"                                   =>      $compatblity_data->max,
                            "max_new"                               =>      $compatblity_data->max_new,
                            "virtual_receive"                       =>      $compatblity_data->virtual_receive,
                            "virtualToLike"                         =>      $compatblity_data->virtualToLike,
                            "virtual_send"                          =>      $compatblity_data->virtual_send,
                            "virtual_send_count"                    =>      $compatblity_data->virtual_send_count,
                            "virtual_receive_count"                 =>      $compatblity_data->virtual_receive_count,
                            "free_ids"                              =>      $compatblity_data->free_ids,
                            "random_count"                          =>      $compatblity_data->random_count,
                            "viewProfile_count"                     =>      $compatblity_data->viewProfile_count,
                            "random_updated"                        =>      $compatblity_data->random_updated,
                            "viewed_profiles"                       =>      $compatblity_data->viewed_profiles,
                            "negative_contacted_profiles"           =>      $compatblity_data->negative_contacted_profiles,
                            "today_activation_link"                 =>      $compatblity_data->today_activation_link,
                            "created_at"                            =>      $compatblity_data->created_at,
                            "updated_at"                            =>      $compatblity_data->updated_at
                        ]);
                    }


                    // get preference data
                    $preferenec_data = $preference_data;
                    //$preferenec_data = DB::connection('mysql_second')->table('preferences')->where(["identity_number" => $profile->identity_number, "temple_id"=>$profile->temple_id])->first();
                    //dd($preferenec_data->validity);
                    // insert into user preference
                    $min_inc_range = "";
                    $max_inc_range = "";
                    if (!empty($preferenec_data->income_min)) {
                        $min_inc_range = $preferenec_data->income_min / 100000;
                    }

                    if (!empty($preferenec_data->income_max)) {
                        $max_inc_range = $preferenec_data->income_max / 100000;
                    }

                    $save_preference = DB::table("userPreferences")->insert([
                        "lead_preferences_id"        =>  null,
                        "preferences_id"             =>  $preferenec_data->id ?? null,
                        "user_data_id"               =>  $user_data_id->id,
                        "identity_number"            =>  $preferenec_data->identity_number ?? null,
                        "temple_id"                  =>  $preferenec_data->temple_id ?? null,
                        "lead_id"                    =>  null,
                        "age_min"                    =>  $preferenec_data->age_min ?? 25,
                        "age_max"                    =>  $preferenec_data->age_max ?? 40,
                        "height_min_s"               =>  $preferenec_data->height_min ?? 48,
                        "height_max_s"               =>  $preferenec_data->height_max ?? 72,
                        "height_min"                 =>  $preferenec_data->height_min ?? 48,
                        "height_max"                 =>  $preferenec_data->height_max ?? 72,
                        "caste"                      =>  $preferenec_data->caste ?? null,
                        "castePref"                  =>  json_encode($caste_code_arr),
                        "marital_status"             =>  $marital_string,
                        "marital_statusPref"         =>  $marital_code,
                        "manglik"                    =>  $manglik_string,
                        "manglikPref"                =>  $manglik_code,
                        "food_choice"                =>  $food_String,
                        "food_choicePref"            =>  $food_code,
                        "working"                    =>  $occupation_string,
                        "workingPref"                =>  $occupation_code,
                        "occupation"                 =>  $occupation_code,
                        "occupation_id"              =>  $occupation_code,
                        "income_min"                 =>  $min_inc_range  ?? 1.25,
                        "income_max"                 =>  $max_inc_range ?? 5.75,
                        "citizenship"                =>  $preferenec_data->citizenship ?? null,
                        "description"                =>  $preferenec_data->description ?? null,
                        "membership"                 =>  $preferenec_data->membership ?? null,
                        "source"                     =>  $preferenec_data->source ?? null,
                        "caste_no_bar"               =>  $preferenec_data->caste_no_bar ?? null,
                        "no_pref_caste"              =>  $preferenec_data->no_pref_caste ?? null,
                        "mother_tongue"              =>  $preferenec_data->mother_tongue ?? null,
                        "created_at_leadPref"        =>  $preferenec_data->created_at ?? null,
                        "updated_at_leadPref"        =>  $preferenec_data->updated_at ?? null,
                        "amount_collected"           =>  $preferenec_data->amount_collected ?? null,
                        "insentive"                  =>  $preferenec_data->insentive ?? null,
                        "validity"                   =>  $preferenec_data->validity ?? null,
                        "payment_method"             =>  $preferenec_data->payment_method ?? null,
                        "receipt_url"                =>  $preferenec_data->receipt_url ?? null,
                        "status"                     =>  $preferenec_data->status ?? null,
                        "religion"                   =>  $preferenec_data->religion ?? null,
                        "religionPref"               =>  json_encode($religion_code) ?? null,
                        "is_paid"                    =>  $preferenec_data->is_paid ?? null,
                        "amount_collected_date"      =>  $preferenec_data->amount_collected_date ?? null,
                        "paid_score"                 =>  $preferenec_data->paid_score ?? null,
                        "zPaid_score"                =>  $preferenec_data->paid_score ?? null,
                        "roka_charge"                =>  $preferenec_data->roka_charge ?? null,
                        "validity_month"             =>  $preferenec_data->validity_month ?? null,
                        "pref_city"                  =>  $preferenec_data->pref_city ?? null,
                        "cityPref"                   =>  null,
                        "pref_state"                 =>  $preferenec_data->pref_state ?? null,
                        "pref_state_id"              =>  null,
                        "statePref"                  =>  null,
                        "regionPref"                 =>  null,
                        "pref_country"               =>  null,
                        "pref_country_id"            =>  null,
                        "gender_pref"                =>  null,
                        "countryPref"                =>  null,
                        "created_at"                 =>  date("Y-m-d h:i:s"),
                        "updated_at"                 =>  date("Y-m-d h:i:s")
                    ]);

                    // save its respective lead data
                    $check_lead = DB::connection('mysql_second')->table("leads")->where("mobile", $profile->mobile_profile)->first();
                    if (!empty($check_lead)) {
                        // dd($check_lead->id);
                        $lead_array = $check_lead;
                        $lead_array->user_data_id = $user_data_id->id;
                        $lead_array->last_followup_date = $last_followup_date;
                        unset($lead_array->max_income);
                        unset($lead_array->record_updated);
                        $insert_lead_data = json_decode(json_encode($lead_array), true);
                        $save_lead = DB::table("leads")->insert($insert_lead_data);
                    }

                    if ($insert_user_data && $save_preference) {
                        DB::commit();
                        $update_profile = DB::connection('mysql_second')->table("profiles")->where("id", $profile->id)->update(['record_updated' => 1]);
                        echo "\nprofile id " . $profile->id;
                    } else {
                        DB::rollBack();
                    }
                    /* record insertion ends */
                }
            }
        });
    }
}
