<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    protected $table = 'user_data';


    protected $fillable = ["profile_id", "updated_at", "isApproved", "is_approve_ready", "temple_id", "identity_number", "name", "gender", "mobile_profile", "user_mobile", "not_delete", "photo", "photo_score", "profiles_sent", "whatsapp", "facebook", "aadhar", "birth_place", "birth_date", "birth_time", "height", "height_int", "education", "degree", "college", "occupation", "profession", "working_city", "user_working_city", "disability", "is_disable", "food_choice", "manglik", "skin_tone", "monthly_income", "annual_income", "marital_status", "citizenship", "office_address", "about", "audio_profile", "earned_rewards", "audio_uploaded_at", "audio_uploaded_by", "audio_approved_at", "unapprove_audio_profile", "audioProfile_hls_convert", "lead_created_at", "created_at_profile", "updated_at_profile", "children", "disabled_part", "precedence", "profile_type", "abroad", "is_nri", "is_invisible", "wishing_to_settle_abroad", "is_renewed", "blatitude", "bNS", "blongitude", "bEW", "country", "countryCode_user", "state", "stateCode_user", "comments", "enquiry_date", "followup_call_on", "last_followup_date", "upgrade_renew", "wantBot", "manglik_id", "plan_name", "carousel", "photo_url", "corrupt_carousel", "corrupt_photo_url", "caste_id_profile", "company", "additional_qualification", "bot_language", "page_skipped", "token", "upgrade_request", "upgrade_comment", "photo_score_updated", "is_active", "matchmaker_id", "is_working", "amount_fix", "is_profile_pic", "bonus", "is_delete", "payment_status", "matchmaker_note", "want_horoscope_match", "botuser", "is_approved", "approved_by", "not_interested", "is_automate_approve", "is_completed", "fcm_id", "bot_status", "last_seen", "lead_status", "fcm_app", "is_paid", "is_premium", "education_score", "literacy_score", "lat_locality", "long_locality", "dual_approved", "is_approved_on", "double_approval", "profile_pdf", "is_premium_interest", "exhaust_reject", "temp_upgrade", "is_sales_approve", "si_event", "is_subscription_view", "freshness_score", "salary_score", "testSalaryScore", "revise_education", "edu_score", "goodness_score", "goodness_score_female", "data_score", "visibility_score", "zvaluePhoto", "zSalaryScore", "zFreshnessScore", "zeduScore", "zGoodNessScore", "zGoodNessScoreFemale", "testGoodness", "zdataScore", "activity_score", "zactivity_score", "zvisibility_score", "starvation_score", "zStarvation", "boost_score", "zBoost_score", "request_by", "crm_created", "call_count", "profile_exclude", "unapprove_carousel", "is_call_back", "call_back_query", "last_si_date", "testProfileGoodNess_score", "last_reject_date", "exhaust_reject_count", "boost_goodNess_score", "is_deleted", "deleted_by", "relation_value", "max_income", "norm_maxIncome", "norm_relation_value", "lead_value", "norm_income_value", "pending_meetings", "raw_profile_data", "matchmaker_is_profile_profiles", "profile_comes_from", "profile_sent_day", "id_families", "profile_id_family", "temple_id_family", "identity_number_family", "name_family", "relation", "livingWithParents", "is_livingWithParents", "locality", "landline", "family_photo", "city_family", "native", "mobile_family", "backup_mobile_family", "email_family", "unmarried_brothers", "married_brothers", "unmarried_sisters", "married_sisters", "family_type", "house_type", "religion", "caste", "gotra", "occupation_father", "occupation_father_code", "family_income", "budget", "father_status", "father_statusCode", "mother_status", "mother_statusCode", "created_at_family", "updated_at_family", "whatsapp_family", "marriage_budget_not_applicable", "email_not_available", "mother_tongue", "sub_caste", "country_family", "state_family", "occupation_mother", "occupation_mother_code", "address", "about_family", "caste_id_family", "zodiac", "father_off_addr", "office_address_mother", "email_verified", "matchmaker_is_profile_family", "genderCode_user", "educationCode_user", "food_choiceCode", "occupationCode_user", "manglikCode", "maritalStatusCode", "casteCode_user", "religionCode", "houseTypeCode", "familyTypeCode", "relationCode", "profile_percent", "created_at_freeUser", "updated_at_freeUser", "welcome_call_done", "verification_call_done", "genderCode_user", "gender", "welcome_call_time", "verifcation_call_time", "pending_temple_id", "weight", "whatsapp", "contact_address"];

    protected $hidden = ['aadhar'];

    public function userPhotos()
    {
        return $this->hasMany(userPhoto::class)->whereIn('photo_status', ['active', 'pending'])->orderBy('profile_pic', 'desc')->orderBy('updated_at', 'desc');
    }

    public function userpreference()
    {
        return $this->hasOne(UserPreference::class, 'user_data_id', 'id');
    }

    public function userMatchesSent()
    {
        return UserData::belongsToMany(UserMatches::class, 'userMatches', 'userAid', 'id')->withPivot('userAid')
            //->where(['is_sent'=>1])
        ;
    }

    public function premiummeetings()
    {
        return $this->hasMany(PremiumMeetings::class, 'user_id', 'id');
    }

    public function assignedTemple()
    {
        return $this->hasOne(User::class, 'temple_id', 'temple_id');
    }

    // save record
    protected static function saveRecord($mobile, $user_name, $height, $temple_id, $gender_string, $gender_int, $religion_int, $religion_string, $caste_int, $caste_string, $education_int, $education_string, $occupation_int, $occupation_string, $birth_date, $relation_int, $relation_string, $marital_int, $marital_string, $annual_income, $about, $city_int, $city_string, $alt_mob_1, $alt_mob_2, $weight)
    {
        return UserData::updateOrCreate(
            ["user_mobile"           =>      $mobile],
            [

                "name"                  =>      $user_name,
                "height_int"            =>      $height,
                "is_lead"               =>      1,
                "temple_id"             =>      $temple_id,
                "genderCode_user"       =>      $gender_int,
                "gender"                =>      $gender_string,
                "religionCode"          =>      $religion_int,
                "religion"              =>      $religion_string,
                "casteCode_user"        =>      $caste_int,
                "caste"                 =>      $caste_string,
                "educationCode_user"    =>      $education_int,
                "education"             =>      $education_string,
                "occupationCode_user"   =>      $occupation_int,
                "occupation"            =>      $occupation_string,
                "birth_date"            =>      $birth_date,
                "relationCode"          =>      $relation_int,
                "relation"              =>      $relation_string,
                "maritalStatusCode"     =>      $marital_int,
                "marital_status"        =>      $marital_string,
                "annual_income"         =>      $annual_income,
                "monthly_income"        =>      $annual_income,
                "about"                 =>      $about,
                "user_working_city"     =>      $city_int,
                "working_city"          =>      $city_string,
                "mobile_family"         =>      $alt_mob_1,
                "whatsapp_family"       =>      $alt_mob_2,
                "weight"                =>      $weight
            ]
        );
    }

    // get profiles without photo and un approved and which is not deleted
    protected static function getProfilesforApproval($temple_id)
    {
        //photo_url
        return UserData::join('leads', 'leads.user_data_id', 'user_data.id')->where(['pending_temple_id' => $temple_id, 'is_approved' => 0, 'photo_url' => null, 'is_delete' => 0])->get(['leads.messege_send_count']);
    }

    public static function updateDataById($temple_id)
    {
        return UserData::where(['temple_id', $temple_id])->delete();
    }

    // day wise data two
    protected static function dateRangeWiseData($message_type, $temple_id, $date_from, $date_to)
    {
        //dd($date_from.' | '.$date_to); //"2023-01-25 07:20:40 | 2023-01-31 07:20:40"
        //dd($temple_id); //1602741097278
        $userList = UserData::join('leads', 'leads.user_data_id', 'user_data.id')->leftjoin('user_photos as p', 'p.user_data_id', 'user_data.id')->leftjoin('users as u', 'u.temple_id', 'leads.assign_to')
            ->where([
                'user_data.is_deleted' => 0,
                'user_data.not_interested' => 0,
                // 'user_data.is_approve_ready' => 0,
                // 'user_data.is_approved' => 0,
                'leads.messege_send_count' => $message_type,
                'user_data.pending_temple_id' => $temple_id
            ])
            ->where('user_data.maritalStatusCode', '!=', '2');
        // $userList = $userList->whereRaw('user_data.annual_income > 2.5');
        if ($date_from != "" && $date_to != "") {
            $userList = $userList->whereRaw("user_data.created_at between '$date_from' and '$date_to'");
        }
        $userList = $userList->whereRaw('(user_data.photo_url  is null and p.id is null)');
        $userList = $userList->select('user_data.name', 'user_data.stage', 'u.name as assign_to', 'user_data.photo_url', 'p.id as photo_id', 'leads.name as lead_name', 'leads.mobile', 'leads.created_at', 'leads.lead_value', 'leads.messege_send_count', 'user_data.last_seen', 'user_data.profile_percent', 'user_data.user_mobile as mobile', 'leads.id as lead_id', 'annual_income', 'user_data.id as user_id', 'user_data.religion', 'user_data.caste', 'user_data.gender');
        $userList = $userList->orderBy('user_data.created_at', 'desc')->orderBy('user_data.genderCode_user', 'desc')->take(1000)->get();
        /*
            update leads created at to get data
            raw query
            select `user_data`.`name`, `leads`.`name` as `lead_name`, `leads`.`mobile`, `leads`.`created_at`, `leads`.`lead_value`, `leads`.`messege_send_count`, `user_data`.`last_seen`, `user_data`.`profile_percent`, `user_data`.`user_mobile` as `mobile`, `leads`.`id` as `lead_id`, `annual_income`, `user_data`.`id` as `user_id`, `user_data`.`religion`, `user_data`.`caste`, `user_data`.`gender` from `user_data` inner join `leads` on `leads`.`user_data_id` = `user_data`.`id`
            where (`user_data`.`is_deleted` = 0 and `user_data`.`not_interested` = 0 and `user_data`.`is_approve_ready` = 0 and `user_data`.`is_approved` = 0   
            and `leads`.`messege_send_count` = 0 and `user_data`.`pending_temple_id` = 1602741097278)
            and `user_data`.`maritalStatusCode` != 2 and user_data.annual_income > 2.5
            and user_data.created_at between '2023-01-25 07:20:40' and '2023-01-31 07:20:40' and (user_data.photo_url  is null or user_data.photo_url ="")
            order by `user_data`.`genderCode_user` desc, `user_data`.`annual_income` desc;
        */
        return $userList;
    }

    // day wise data
    protected static function pendingApprovalData($customer_support, $temple_id)
    {
        $userList = UserData::/*join('leads', 'leads.user_data_id', 'user_data.id')->*/
            //join('marital_status_mappings', 'marital_status_mappings.id', 'user_data.maritalStatusCode')
            //->join('gender_mappings', 'gender_mappings.id', 'user_data.genderCode_user')
            //->where('leads.is_done', 0)
            //->where('leads.profile_created', 0)
            //->where('leads.is_deleted', 0)
            //->
            where('user_data.is_deleted', 0)
            //->where('leads.is_not_interested', 0)
            ->where('user_data.not_interested', 0)
            ->where('user_data.is_approve_ready', 0)
            ->where('user_data.is_approved', 0)
            //->whereRaw('leads.name not like "%test%"')
            //->where('leads.name', '!=', 'Hans Lead')
            //->where('user_data.annual_income', '>', 2.5)
            ->whereRaw('user_data.photo_url is not null');

        if ($customer_support == 1) {
            $userList = $userList->where('pending_temple_id', $temple_id);
        }

        $userList = $userList->select(
            'user_data.name',
            'user_data.user_mobile as mobile',
            'user_data.created_at',
            'user_data.marital_status',
            'user_data.last_seen',
            'user_data.profile_percent',
            'user_data.user_mobile as mobile',
            'user_data.genderCode_user as gender',
            'annual_income',
            'user_data.id as user_id'
        );
        $userList = $userList->orderBy('user_data.genderCode_user', 'DESC')->orderBy('user_data.annual_income', 'desc')->take(5000)->get();
        //  dd($userList);
        return $userList;
    }


    // get user details by id with preference
    protected static function getDetailsByIdWPref($user_id)
    {
        $data = UserData::with(['userpreference', 'userPhotos'])->where(['user_data.id' => $user_id])->first();
        $data['religion'] = implode(',', Religion::whereIn('mapping_id', json_decode($data->userpreference->religionPref))->pluck('religion')->toArray());
        return $data;
    }

    // update and approve user data
    protected static function updateUserDataTable($user_id, $user_name, $gender_code, $relation_code, $religion_code, $caste_code, $birth_date, $birth_time, $marital_status, $height, $weight, $manglik_code, $settle_abroad, $yearly_income, $gotra, $family_income, $father_status_code, $mother_status_code, $unmarried_brothers, $unmarried_sisters, $married_brothers, $married_sisters, $house_type_code, $family_type_code, $food_choice, $occupation, $education_code, $temple_id, $working_city, $about_me, $relation_string, $gender_string, $religion_string, $caste_string, $marital_string, $education_string, $occupation_string, $working_city_code, $is_disable, $disability, $citizenship, $birth_place, $no_of_child, $alternate_number1, $alternate_number2, $whatsapp_no, $email, $company_name, $designation, $college_name, $additional_degree, $family_current_city, $native_city, $contact_address, $family_about, $photo_score, $locality, $managedByCodeOrString, $genderCodeOrString, $foodChoiceCodeOrString, $religionCodeOrString, $maritalStatusCodeOrString, $manglikStatusCodeOrString, $educationCodeOrString, $occupationCodeOrString, $houseTypeCodeOrString, $fatherStatusCodeOrStringVal, $motherStatusCodeOrStringVal)
    {

        return UserData::where('id', $user_id)->update([
            "is_approved"           =>          1,
            "is_approve_ready"      =>          1,
            "isApproved"            =>          1,
            "is_approved_on"        =>          date("Y-m-d H:i:s"),
            "approved_by"           =>          $temple_id,
            "name"                  =>          $user_name,
            "genderCode_user"       =>          $gender_code,
            "gender"                =>          $gender_string,
            "relationCode"          =>          $relation_code,
            "religion"              =>          $religion_string,
            "religionCode"          =>          $religion_code,
            "casteCode_user"        =>          $caste_code,
            "caste"                 =>          $caste_string,
            "birth_date"            =>          $birth_date,
            "birth_time"            =>          $birth_time,
            "maritalStatusCode"     =>          $marital_status,
            "height"                =>          $height,
            "height_int"            =>          $height,
            "weight"                =>          $weight,
            "manglikCode"           =>          $manglik_code,
            "wishing_to_settle_abroad"  =>      $settle_abroad,
            "annual_income"        =>           $yearly_income,
            "gotra"                 =>          $gotra,
            "family_income"         =>          $family_income,
            // "father_statusCode"     =>          $father_status_code,
            "occupation_father_code"     =>          $father_status_code,
            // "mother_statusCode"     =>          $mother_status_code,
            "occupation_mother_code"     =>          $mother_status_code,
            "unmarried_brothers"    =>          $unmarried_brothers,
            "unmarried_sisters"     =>          $unmarried_sisters,
            "married_brothers"      =>          $married_brothers,
            "married_sisters"       =>          $married_sisters,
            "houseTypeCode"         =>          $house_type_code,
            "familyTypeCode"        =>          $family_type_code,
            "food_choiceCode"       =>          $food_choice,
            "occupationCode_user"   =>          $occupation,
            "occupation"            =>          $occupation_string,
            "educationCode_user"    =>          $education_code,
            "education"             =>          $education_string,
            "working_city"          =>          $working_city,
            "about"                 =>          $about_me,
            "relation"              =>          $relation_string,
            "marital_status"        =>          $marital_string,
            "is_deleted"            =>          0,
            "not_interested"        =>          0,
            "user_working_city"     =>          $working_city_code,
            "is_disable"            =>          $is_disable,
            "disability"            =>          $disability,
            "citizenship"           =>          $citizenship,
            "birth_place"           =>          $birth_place,
            "no_of_child"           =>          $no_of_child,
            "whatsapp"              =>          $whatsapp_no,
            "company"               =>          $company_name,
            "designation"           =>          $designation,
            "college"               =>          $college_name,
            "additional_qualification" =>       $additional_degree,
            "city_family"           =>          $family_current_city,
            "native"                =>          $native_city,
            "contact_address"       =>          $contact_address,
            "about_family"          =>          $family_about,
            "mobile_family"         =>          $alternate_number1,
            "whatsapp_family"       =>          $alternate_number2,
            "email_family"          =>          $email,
            "locality"              =>          $locality,
            "photo_score"           =>          $photo_score,
            "relation"              =>          $managedByCodeOrString,
            "gender"                =>          $genderCodeOrString,
            "food_choice"           =>          $foodChoiceCodeOrString,
            "religion"              =>          $religionCodeOrString,
            "marital_status"        =>          $maritalStatusCodeOrString,
            "education"             =>          $educationCodeOrString,
            "manglik"               =>          $manglikStatusCodeOrString,
            "occupation"            =>          $occupationCodeOrString,
            "house_type"            =>          $houseTypeCodeOrString,
            "occupation_father"         =>      $fatherStatusCodeOrStringVal,
            "occupation_mother"         =>      $motherStatusCodeOrStringVal
        ]);
    }

    // update user data
    protected static function updateUserData($user_id, $user_name, $gender_code, $relation_code, $religion_code, $caste_code, $birth_date, $birth_time, $marital_status, $height, $weight, $manglik_code, $settle_abroad, $monthly_income, $gotra, $family_income, $father_status_code, $mother_status_code, $unmarried_brothers, $unmarried_sisters, $married_brothers, $married_sisters, $house_type_code, $family_type_code, $food_choice, $occupation, $education_code, $temple_id, $working_city, $about_me)
    {
        return UserData::where('id', $user_id)->update([
            "is_approved"           =>          1,
            "name"                  =>          $user_name,
            "genderCode_user"       =>          $gender_code,
            "relationCode"          =>          $relation_code,
            "religionCode"          =>          $religion_code,
            "casteCode_user"        =>          $caste_code,
            "birth_date"            =>          $birth_date,
            "birth_time"            =>          $birth_time,
            "maritalStatusCode"     =>          $marital_status,
            "height"                =>          $height,
            "height_int"            =>          $height,
            "weight"                =>          $weight,
            "manglikCode"           =>          $manglik_code,
            "wishing_to_settle_abroad"  =>      $settle_abroad,
            "monthly_income"        =>          $monthly_income,
            "gotra"                 =>          $gotra,
            "family_income"         =>          $family_income,
            "father_statusCode"     =>          $father_status_code,
            "mother_statusCode"     =>          $mother_status_code,
            "unmarried_brothers"    =>          $unmarried_brothers,
            "unmarried_sisters"     =>          $unmarried_sisters,
            "married_brothers"      =>          $married_brothers,
            "married_sisters"       =>          $married_sisters,
            "houseTypeCode"         =>          $house_type_code,
            "familyTypeCode"        =>          $family_type_code,
            "food_choiceCode"       =>          $food_choice,
            "occupationCode_user"   =>          $occupation,
            "educationCode_user"    =>          $education_code,
            "working_city"          =>          $working_city,
            "about"                 =>          $about_me,
            "city_family"           =>          $working_city
        ]);
    }

    // update double approve a profile
    protected static function updateDoubleUserData($user_id, $user_name, $gender_code, $relation_code, $religion_code, $caste_code, $birth_date, $birth_time, $marital_status, $height, $weight, $manglik_code, $settle_abroad, $monthly_income, $gotra, $family_income, $father_status_code, $mother_status_code, $unmarried_brothers, $unmarried_sisters, $married_brothers, $married_sisters, $house_type_code, $family_type_code, $food_choice, $occupation, $education_code, $temple_id, $working_city, $about_me, $relation_string, $gender_string, $religion_string, $caste_string, $marital_string, $education_string, $occupation_string)
    {
        return UserData::where('id', $user_id)->update([
            "double_approval"       =>          1,
            "name"                  =>          $user_name,
            "genderCode_user"       =>          $gender_code,
            "gender"                =>          $gender_string,
            "relationCode"          =>          $relation_code,
            "religionCode"          =>          $religion_code,
            "religion"              =>          $religion_string,
            "casteCode_user"        =>          $caste_code,
            "birth_date"            =>          $birth_date,
            "birth_time"            =>          $birth_time,
            "maritalStatusCode"     =>          $marital_status,
            "height"                =>          $height,
            "height_int"            =>          $height,
            "weight"                =>          $weight,
            "manglikCode"           =>          $manglik_code,
            "wishing_to_settle_abroad"  =>      $settle_abroad,
            "monthly_income"        =>          $monthly_income,
            "gotra"                 =>          $gotra,
            "family_income"         =>          $family_income,
            "father_statusCode"     =>          $father_status_code,
            "mother_statusCode"     =>          $mother_status_code,
            "unmarried_brothers"    =>          $unmarried_brothers,
            "unmarried_sisters"     =>          $unmarried_sisters,
            "married_brothers"      =>          $married_brothers,
            "married_sisters"       =>          $married_sisters,
            "houseTypeCode"         =>          $house_type_code,
            "familyTypeCode"        =>          $family_type_code,
            "food_choiceCode"       =>          $food_choice,
            "occupationCode_user"   =>          $occupation,
            "educationCode_user"    =>          $education_code,
            "working_city"          =>          $working_city,
            "about"                 =>          $about_me,
            "caste"                 =>          $caste_string,
            "education"             =>          $education_string,
            "working_city"          =>          $working_city,
            "about"                 =>          $about_me,
            "relation"              =>          $relation_string,
            "marital_status"        =>          $marital_string,
            "occupation"            =>          $occupation_string,
        ]);
    }

    // reject user profile
    protected static function rejectProfile($user_id)
    {
        return UserData::where('id', $user_id)->update([
            "not_interested"        =>      1,
            "is_deleted"            =>      1,
            "is_delete"             =>      1
        ]);
    }

    // update user image to array
    protected static function updateUserImage($user_id, $image_name)
    {
        $prev_data = UserData::where('id', $user_id)->get()->toArray();
        $image_array = array();
        $new_photo_array = json_decode($prev_data[0]['photo_url'], true);
        if (empty($new_photo_array)) {
            $image_array = json_encode(array(
                "0"         =>      $image_name
            ));
        }

        return UserData::where('id', $user_id)->update([
            "photo"         =>      $image_name,
            "photo_url"     =>      $image_array
        ]);
    }

    // rejected users while approval
    protected static function rejectedProfiles()
    {
        $userList = UserData::join('marital_status_mappings', 'marital_status_mappings.id', 'user_data.maritalStatusCode')
            ->join('gender_mappings', 'gender_mappings.id', 'user_data.genderCode_user')
            ->where('user_data.is_deleted', 1)
            ->where('user_data.not_interested', 1);
        $userList = $userList->select('user_data.name', 'user_data.user_mobile as mobile', 'user_data.created_at',  'marital_status_mappings.name as marital_status', 'user_data.last_seen', 'user_data.profile_percent', 'user_data.user_mobile as mobile', 'gender_mappings.name as gender', 'annual_income', 'user_data.id as user_id');
        $userList = $userList->orderBy('user_data.genderCode_user', 'DESC')->orderBy('user_data.annual_income', 'desc')->get();

        return $userList;
    }

    // approved user list with temple id
    protected static function approvedProfiles($temple_id)
    {
        $userList = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')
            //->join('marital_status_mappings', 'marital_status_mappings.id', 'user_data.maritalStatusCode')
            //->where('leads.profile_created', 0)
            ->where('user_data.is_deleted', 0)
            ->where(['user_data.is_approved' => 1,  "not_interested" => 0])
            //->whereRaw('leads.name not like "%test%"')
            //->where('leads.name', '!=', 'Hans Lead')
            ->where('user_data.annual_income', '>', 2.5)
            ->where('user_data.approved_by', $temple_id);
        $userList = $userList->select(
            'user_data.name',
            'user_data.user_mobile as mobile',
            'user_data.created_at',
            'leads.lead_value',
            'leads.messege_send_count',
            'user_data.marital_status as marital_status',
            'user_data.last_seen',
            'user_data.profile_percent',
            'user_data.user_mobile as user_mobile',
            'leads.id as lead_id',
            'user_data.genderCode_user as gender',
            'annual_income',
            'user_data.id as user_id'
        );
        $userList = $userList->orderBy('user_data.genderCode_user', 'DESC')->orderBy('user_data.annual_income', 'desc')->get();
        return $userList;
    }


    // approved user list with temple id
    protected static function doubleApprovalProfiles($temple_id)
    {
        $userList = UserData::join('leads', 'leads.user_data_id', 'user_data.id')
            ->join('marital_status_mappings', 'marital_status_mappings.id', 'user_data.maritalStatusCode')
            ->where('leads.profile_created', 0)
            ->where('user_data.is_deleted', 0)
            ->where(['user_data.is_approved' => 1, 'double_approval' => 0, 'user_data.not_interested' => 0])
            ->whereRaw('leads.name not like "%test%"')
            ->where('leads.name', '!=', 'Hans Lead')
            ->where('user_data.annual_income', '<', 2.5)
            ->where('user_data.approved_by', $temple_id);
        $userList = $userList->select('leads.name', 'leads.mobile', 'leads.created_at', 'leads.lead_value', 'leads.messege_send_count', 'marital_status_mappings.name as marital_status', 'user_data.last_seen', 'user_data.profile_percent', 'user_data.user_mobile as mobile', 'leads.id as lead_id', 'user_data.genderCode_user as gender', 'annual_income', 'user_data.id as user_id');
        $userList = $userList->orderBy('user_data.genderCode_user', 'DESC')->orderBy('user_data.annual_income', 'desc')->take(100)->get();
        return $userList;
    }

    // get un approved photos
    protected static function getUnApprovedPhotos()
    {
        return UserData::whereRaw('unapprove_carousel is not null AND unapprove_carousel != 0')->inRandomOrder()->take(1)->get(['unapprove_carousel', 'id']);
    }

    // get all profiles assigned to me
    protected static function getAllProfiles($temple_id)
    {
        //  dd($temple_id); // 1551419155966
        return UserData::leftJoin('gender_mappings', 'gender_mappings.id', 'user_data.genderCode_user')
            ->leftJoin('occupation_mappings', 'occupation_mappings.id', 'user_data.occupationCode_user')
            ->leftJoin('castes', 'castes.id',  'user_data.casteCode_user')
            ->leftJoin('manglik_mappings', 'manglik_mappings.id',  'user_data.manglikCode')
            ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
            ->where(['user_data.temple_id' => $temple_id, 'is_deleted' => 0, 'user_data.is_premium' => 1])
            ->where('user_data.maritalStatusCode', '!=', 2)
            ->orderBy("user_data.id", "desc")->groupBy('user_data.id')
            ->get(['user_data.name', 'user_data.monthly_income', 'user_data.user_mobile', 'userPreferences.validity', 'userPreferences.validity_month', 'gender_mappings.name as gender', 'occupation_mappings.name as occupation', 'manglik_mappings.name as manglik', 'castes.value as caste', 'user_data.created_at', 'userPreferences.roka_charge', 'userPreferences.amount_collected', 'user_data.id as user_id', 'user_data.birth_date', 'profile_sent_day', 'working_city']);

        // delete duplicate record from preferences
        //delete t1 from userPreferences t1 inner join userPreferences t2 where t1.id < t2.id and t1.user_data_id = t2.user_data_id;
    }
    protected static function getAllProfilesPending($temple_id)
    {
        //  dd($temple_id); // 1551419155966
        return UserData::leftJoin('gender_mappings', 'gender_mappings.id', 'user_data.genderCode_user')
            ->leftJoin('occupation_mappings', 'occupation_mappings.id', 'user_data.occupationCode_user')
            ->leftJoin('castes', 'castes.id',  'user_data.casteCode_user')
            ->leftJoin('manglik_mappings', 'manglik_mappings.id',  'user_data.manglikCode')
            ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
            // ->where(['user_data.temple_id' => $temple_id])
            ->where('user_data.maritalStatusCode', '!=', 2)
            ->where('user_data.created_at', '>', '2023-02-15')
            ->where("user_data.is_approved", "0")
            ->orderBy("user_data.id", "desc")
            ->groupBy('user_data.id')
            ->get(['user_data.name', 'user_data.annual_income as income', 'user_data.user_mobile as mobile', 'user_data.marital_status as marital_status', 'user_data.user_mobile', 'userPreferences.validity', 'userPreferences.validity_month', 'gender_mappings.name as gender', 'occupation_mappings.name as occupation', 'manglik_mappings.name as manglik', 'castes.value as caste', 'user_data.created_at', 'userPreferences.roka_charge', 'userPreferences.amount_collected', 'user_data.id as user_id', 'user_data.birth_date', 'profile_sent_day', 'working_city']);

        // delete duplicate record from preferences
        //delete t1 from userPreferences t1 inner join userPreferences t2 where t1.id < t2.id and t1.user_data_id = t2.user_data_id;
    }

    // get filtered data from database
    protected static function getFilteredData($disabled_profiles,  $wish_to, $nri, $min_age, $max_age, $min_height, $max_height, $castes, $marital_status, $manglik_status, $food_choice, $working_Status, $min_income, $max_income, $send_profiles, $user_gender, $show_disabled, $religion)
    {
        $user_data = '';
        //dd($working_Status);
        if ($disabled_profiles != 2) {
            $user_data = UserData::with('userPhotos')->where(['user_data.is_deleted' => 0])->where('is_disable', '=', "$disabled_profiles");
        } else {
            $user_data = UserData::with('userPhotos')->where(['user_data.is_deleted' => 0]);
        }
        $mydate = getdate();
        if (!empty($min_age) && !empty($max_age)) {
            $year = $mydate["year"] - $min_age;
            $min_age = date_format(date_create("$mydate[mday]-$mydate[mon]-$year"), "Y-m-d H:i:s");
            $year = $mydate["year"] - $max_age;
            $max_age = date_format(date_create("$mydate[mday]-$mydate[mon]-$year"), "Y-m-d H:i:s");
            if ($min_age > $max_age) {
                $user_data = $user_data->whereRaw("birth_date between '$max_age' and '$min_age'");
            } else {
                // return [$min_age, $max_age];
                $user_data = $user_data->whereRaw("birth_date between '$min_age' and '$max_age'");
            }
            // return [$min_age, $max_age];
        }
        // checking wishToGoAbroad
        if ($wish_to != 3) {
            $user_data = $user_data->where('wishing_to_settle_abroad', $wish_to);
        }
        // checking nri
        if ($nri != 3) {
            $user_data = $user_data->where('is_nri', $nri);
        }
        // dd($user_data->toSql());
        // min height and max height
        if (!empty($min_height) && !empty($max_height)) {
            if ($min_height > $max_height) {
                $user_data = $user_data->whereBetween('height_int', [$max_height, $min_height]);
            } else {
                $user_data = $user_data->whereBetween('height_int', [$min_height, $max_height]);
            }
        }

        // check caste array
        if (!empty($castes) && is_array(json_decode($castes)) && json_decode($castes)[0] > 0) {
            $caste_implode = implode(',', json_decode($castes));
            $user_data = $user_data->whereRaw("casteCode_user IN ($caste_implode)");
        } else if (!empty($castes) && !is_array(json_decode($castes))) {
            $user_data = $user_data->whereRaw("casteCode_user in ($castes)");
        } else {
            $user_data = $user_data->whereRaw("casteCode_user BETWEEN 1 AND 1000000000");
        }

        // check for religion
        if (!empty($religion)) {
            $user_data = $user_data->where('religion', $religion);
        }

        // check marital status
        if (!empty($marital_status)) {
            //$imp_marital_status = implode(',', $marital_status);
            $user_data = $user_data->whereRaw("maritalStatusCode in ($marital_status)");
        } else {
            $user_data = $user_data->whereRaw('maritalStatusCode != 2');
        }

        if (!empty($manglik_status) && $manglik_status != 5) {
            $user_data = $user_data->whereRaw("manglikCode in($manglik_status)");
        }

        // check for food choice
        if (!empty($food_choice)) {
            $user_data = $user_data->where('food_choiceCode', $food_choice);
        }

        // working status
        if (!empty($working_Status)) {
            $user_data = $user_data->whereRaw("occupationCode_user in ( $working_Status)");
        }

        // income filter
        if (!empty($min_income) && $min_income > 0 && $max_income > 0 && !empty($max_income)) {
            if ($min_income > $max_income) {
                $user_data = $user_data->whereBetween('annual_income', [$max_income, $min_income]);
            } else {
                $user_data = $user_data->whereBetween('annual_income', [$min_income, $max_income]);
            }
        }

        // remove send user list
        if (!empty($send_profiles)) {
            $user_data = $user_data->whereRaw("user_data.id not in($send_profiles)");
        }

        // hide disabled profiles
        if (!empty($show_disabled) && $show_disabled == 1) {
            $user_data = $user_data->whereRaw("user_data.disability IN ('Yes', 'NO')");
        }

        // gender filter
        $user_data = $user_data->where('genderCode_user', '!=', $user_gender)->where('maritalStatusCode', '!=', 2)->orderBy('user_data.created_at', 'desc')
            ->orderBy('user_data.is_premium', 'desc')->take(4000)
            ->get(['user_data.name', 'user_data.annual_income', 'gender', 'working_city as city', 'occupation', 'working_city', 'manglik', 'caste', 'user_data.created_at', 'user_data.id', 'photo', 'is_premium', 'birth_date', 'height_int', 'weight', 'education', 'family_income', 'user_data.father_status', 'user_data.mother_status', 'food_choice', 'user_data.marital_status', 'wishing_to_settle_abroad as wish_toabroad', 'is_disable']);
        //dd($user_data->toArray());
        return $user_data;
    }

    // update prpfile sent day
    protected static function userProfileSentDayUpdaet($user_id, $sent_day)
    {
        return UserData::where('id', $user_id)->update([
            "profile_sent_day"      =>      $sent_day
        ]);
    }

    // show profiles in group
    protected static function getProfilesInGroup($profile_ids)
    {
        return UserData::whereRaw("id in ($profile_ids)")->orderBy('is_premium', 'desc')->orderBy('created_at', 'desc')->get(['name', 'birth_date', 'education', 'gender', 'manglik', 'occupation', 'religion', 'caste', 'photo_url', 'height', 'weight', 'annual_income', 'marital_status', 'food_choice', 'company', 'designation', 'college', 'additional_qualification', 'citizenship', 'relation', 'about', 'working_city', 'city_family', 'birth_place', 'family_type', 'house_type', 'gotra', 'family_income', 'father_status', 'mother_status', 'unmarried_brothers', 'unmarried_sisters', 'married_brothers', 'about_family', 'married_sisters', 'photo', 'id as user_id']);
    }

    protected static function getTeleContactData($column_name)
    {
        return UserData::where($column_name, 0)->where(['is_delete' => 0, 'not_interested' => 0, 'is_paid' => 1])->whereRaw("name not like '%test%'")->orderBy('updated_at', 'desc')->take(200)->get();
    }

    protected static function markTeleContactDone($column_name, $date_col_name, $user_id)
    {
        return UserData::where('id', $user_id)->update([
            $column_name        =>      1,
            $date_col_name      =>      date('Y-m-d h:i:s')
        ]);
    }

    protected static function getUserDataByMobile($mobile)
    {
        return UserData::with('assignedTemple')->where('user_mobile', 'LIKE', "%$mobile%")->first(['id', 'name', 'user_mobile', 'temple_id', 'followup_call_on', 'last_followup_date', 'enquiry_date', 'is_approved']);
    }

    // get today sentprofile list
    protected static function getSentTodayProfile($temple_id)
    {
        $today = date('Y-m-d');
        // $day = date('D');
        // return ['day' => $today];
        return UserData::join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
            ->where('validity', '>=', "$today")->where(['user_data.temple_id' => $temple_id, 'is_delete' => 0, 'is_premium' => 1, 'profile_sent_day' => "$today"])->get(['user_data.name', 'user_data.user_mobile', 'amount_collected_date', 'validity', 'user_data.id', 'user_data.birth_date', 'user_data.profile_sent_day']);
    }

    protected static function updateSentProfile($user_id, $sent_day)
    {
        return UserData::where('id', $user_id)->update([
            "profile_sent_day"      =>      $sent_day
        ]);
    }

    protected static function saveUpdatedUrl($user_id, $photo_url)
    {
        return UserData::where('id', $user_id)->update([
            'photo_url'     =>      json_encode($photo_url)
        ]);
    }

    protected static function templeWiseProfile($temple_id)
    {
        return UserData::where(["temple_id" => $temple_id, "is_delete" => 0])->get(['id', 'name', 'user_mobile']);
    }

    public static function markMarried($user_id)
    {
        return UserData::where('id', $user_id)->update([
            "marital_status"            =>      'Married',
            "maritalStatusCode"         =>      2,
            "is_delete"                 =>      0,
            "is_deleted"                =>      0,
            "not_interested"            =>      1
        ]);
    }

    public static function markPremium($user_id)
    {
        return UserData::where('id', $user_id)->update([
            "is_premium"                =>   1,
            "is_paid"                =>      1,
            "is_invisible"              =>      1
        ]);
    }

    protected static function saveBasicProfileRecord($mobile, $user_name, $height, $temple_id, $gender_string, $gender_int, $religion_int, $religion_string, $caste_int, $caste_string, $education_int, $education_string, $occupation_int, $occupation_string, $birth_date, $relation_int, $relation_string, $marital_int, $marital_string, $annual_income, $about, $city_int, $city_string, $alt_mob_1, $alt_mob_2, $weight)
    {
        return UserData::create([
            "user_mobile"           =>      $mobile,
            "name"                  =>      $user_name,
            "height_int"            =>      $height,
            "temple_id"             =>      $temple_id,
            "genderCode_user"       =>      $gender_int,
            "gender"                =>      $gender_string,
            "religionCode"          =>      $religion_int,
            "religion"              =>      $religion_string,
            "casteCode_user"        =>      $caste_int,
            "caste"                 =>      $caste_string,
            "educationCode_user"    =>      $occupation_int,
            "education"             =>      $education_string,
            "occupationCode_user"   =>      $occupation_int,
            "occupation"            =>      $occupation_string,
            "birth_date"            =>      $birth_date,
            "maritalStatusCode"     =>      $marital_int,
            "marital_status"        =>      $marital_string,
            "annual_income"         =>      $annual_income,
            "monthly_income"        =>      $annual_income,
            "about"                 =>      $about,
            "user_working_city"     =>      $city_int,
            "working_city"          =>      $city_string,
            "mobile_family"         =>      $alt_mob_1,
            "whatsapp_family"       =>      $alt_mob_2,
            "weight"                =>      $annual_income
        ]);
    }

    public static function managedByCodeOrString($data)
    {
        if ($data == 1) {
            return "Myself";
        } elseif ($data == 2) {
            return "Mother";
        } elseif ($data == 3) {
            return "Father";
        } elseif ($data == 4) {
            return "Sister";
        } elseif ($data == 5) {
            return "Brother";
        } elseif ($data == 6) {
            return "Son";
        } elseif ($data == 7) {
            return "Daughter";
        } elseif ($data == 8) {
            return "Other";
        } elseif ($data == "Myself") {
            return 1;
        } elseif ($data == "Mother") {
            return 2;
        } elseif ($data == "Father") {
            return 3;
        } elseif ($data == "Sister") {
            return 4;
        } elseif ($data == "Brother") {
            return 5;
        } elseif ($data == "Son") {
            return 6;
        } elseif ($data == "Daughter") {
            return 7;
        } elseif ($data == "Other") {
            return 8;
        }
    }

    public static function genderCodeOrString($data)
    {
        if ($data == 1) {
            return "Male";
        } elseif ($data == 2) {
            return "FEMALE";
        } elseif ($data == "Male") {
            return 1;
        } elseif ($data == "Female") {
            return 2;
        }
    }

    public static function foodChoiceCodeOrString($data)
    {
        if ($data == 0) {
            return "Doesn't Matter";
        } elseif ($data == 1) {
            return "Vegetarian";
        } elseif ($data == 2) {
            return "Non-Vegetarian";
        } elseif ($data == 3) {
            return "Eggetarian";
        } elseif ($data == "Doesn't Matter") {
            return 0;
        } elseif ($data == "Vegetarian") {
            return 1;
        } elseif ($data == "Non-Vegetarian") {
            return 2;
        } elseif ($data == "Eggetarian") {
            return 3;
        }
    }

    public static function religionCodeOrString($data)
    {
        if ($data == 1) {
            return "Hindu";
        } elseif ($data == 2) {
            return "Jain";
        } elseif ($data == 3) {
            return "Sikh";
        } elseif ($data == 4) {
            return "Muslim";
        } elseif ($data == 5) {
            return "Christian";
        } elseif ($data == 6) {
            return "Buddhist";
        } elseif ($data == 7) {
            return "Bahai";
        } elseif ($data == 8) {
            return "Jewish";
        } elseif ($data == 9) {
            return "Parsi";
        } elseif ($data == "Hindu") {
            return 1;
        } elseif ($data == "Jain") {
            return 2;
        } elseif ($data == "Sikh") {
            return 3;
        } elseif ($data == "Muslim") {
            return 4;
        } elseif ($data == "Christian") {
            return 5;
        } elseif ($data == "Buddhist") {
            return 6;
        } elseif ($data == "Bahai") {
            return 7;
        } elseif ($data == "Jewish") {
            return 8;
        } elseif ($data == "Parsi") {
            return 9;
        }
    }

    public static function castCodeOrString($temple_id)
    {
        return UserData::where(['temple_id', $temple_id])->delete();
    }

    public static function heightCodeOrString($temple_id)
    {
        return UserData::where(['temple_id', $temple_id])->delete();
    }

    public static function maritalStatusCodeOrString($data)
    {

        if ($data == "1") {
            return "Never Married";
        }
        if ($data == "2") {
            return "Married";
        }
        if ($data == "3") {
            return "Awaiting Divorce";
        }
        if ($data == "4") {
            return "Divorcee";
        } elseif ($data == "5") {
            return "Widowed";
        }
        if ($data == "6") {
            return "Anulled";
        }
        if ($data == "7") {
            return "Doesn't Matter";
        }
        if ($data == "Never Married") {
            return 1;
        }
        if ($data == "Married") {
            return 2;
        }
        if ($data == "Awaiting Divorce") {
            return 3;
        }
        if ($data == "Divorcee") {
            return 4;
        }
        if ($data == "Widowed") {
            return 5;
        }
        if ($data == "Anulled") {
            return 6;
        }
        if ($data == "Doesn't Matter") {
            return 7;
        }
    }

    public static function isDisableCodeOrString($data)
    {
        if ($data == 0) {
            return "no";
        } elseif ($data == 1) {
            return "yes";
        } elseif ($data == "no") {
            return 0;
        } elseif ($data == "yes") {
            return 1;
        }
    }

    public static function citizenshipCodeOrString($data)
    {
        if ($data == 0) {
            return "indian";
        } elseif ($data == 1) {
            return "nri";
        } elseif ($data == "indian") {
            return 0;
        } elseif ($data == "nri") {
            return 1;
        }
    }

    public static function manglikStatusCodeOrString($data)
    {
        if ($data == 1) {
            return "Manglik";
        } elseif ($data == 2) {
            return "Non-Manglik";
        } elseif ($data == 3) {
            return "Anshik Manglik";
        } elseif ($data == 4) {
            return "Don't Know";
        } elseif ($data == 5) {
            return "Doesn't Matter";
        } elseif ($data == "Manglik") {
            return 1;
        } elseif ($data == "Non-Manglik") {
            return 2;
        } elseif ($data == "Anshik Manglik") {
            return 3;
        } elseif ($data == "Don't Know") {
            return 4;
        } elseif ($data == "Doesn't Matter") {
            return 5;
        }
    }

    public static function educationCodeOrString($data)
    {
        if ($data == 38) {
            return "B.A";
        } elseif ($data == 6) {
            return "B.Arch";
        } elseif ($data == 14) {
            return "B.Com";
        } elseif ($data == 8) {
            return "B.Des";
        } elseif ($data == 1) {
            return "B.E/B.Tech";
        } elseif ($data == 42) {
            return "B.Ed";
        } elseif ($data == 12) {
            return "B.IT";
        } elseif ($data == 2) {
            return "B.Pharma";
        } elseif ($data == 39) {
            return "B.Sc.";
        } elseif ($data == 25) {
            return "BAMS";
        } elseif ($data == 21) {
            return "BBA";
        } elseif ($data == 57) {
            return "bbm";
        } elseif ($data == 11) {
            return "BCA";
        } elseif ($data == 11) {
            return "BDS";
        } elseif ($data == 46) {
            return "BFA";
        } elseif ($data == 22) {
            return "BHM";
        } elseif ($data == 26) {
            return "BHMS";
        } elseif ($data == 36) {
            return "BJMC";
        } elseif ($data == 36) {
            return "BL/LLB";
        } elseif ($data == 32) {
            return "BPT";
        } elseif ($data == 15) {
            return "BvSc.";
        } elseif ($data == 15) {
            return "CA";
        } elseif ($data == 19) {
            return "CFA";
        } elseif ($data == 16) {
            return "CS";
        } elseif ($data == 58) {
            return "D Pharma";
        } elseif ($data == 52) {
            return "Diploma";
        } elseif ($data == 34) {
            return "DM";
        } elseif ($data == 53) {
            return "High School";
        } elseif ($data == 17) {
            return "ICWA";
        } elseif ($data == 40) {
            return "M.A.";
        } elseif ($data == 7) {
            return "M.Arch";
        } elseif ($data == 18) {
            return "M.COM";
        } elseif ($data == 56) {
            return "M.D.";
        } elseif ($data == 9) {
            return "M.Des";
        } elseif ($data == 3) {
            return "M.E/M.Tech";
        } elseif ($data == 43) {
            return "M.Ed";
        } elseif ($data == 4) {
            return "M.Pharma";
        } elseif ($data == 51) {
            return "M.Phil";
        } elseif ($data == 28) {
            return "M.S. (Medicine)";
        } elseif ($data == 5) {
            return "M.S. Engineering";
        } elseif ($data == 41) {
            return "M.Sc";
        } elseif ($data == 20) {
            return "MBA/PGDM";
        } elseif ($data == 23) {
            return "MBBS";
        } elseif ($data == 10) {
            return "MCA/PGDCA";
        } elseif ($data == 35) {
            return "MCh";
        } elseif ($data == 31) {
            return "MDS";
        } elseif ($data == 47) {
            return "MFA";
        } elseif ($data == 49) {
            return "MJMC";
        } elseif ($data == 37) {
            return "ML/LLM";
        } elseif ($data == 33) {
            return "MPT";
        } elseif ($data == 44) {
            return "MSW";
        } elseif ($data == 29) {
            return "MVSc.";
        } elseif ($data == 55) {
            return "Other";
        } elseif ($data == 50) {
            return "Ph.D";
        } elseif ($data == 54) {
            return "Trade School";
        } elseif ($data == "B.A") {
            return 38;
        } elseif ($data == "B.Arch") {
            return 6;
        } elseif ($data == "B.Com") {
            return 14;
        } elseif ($data == "B.Des") {
            return 8;
        } elseif ($data == "B.E/B.Tech") {
            return 1;
        } elseif ($data == "B.Ed") {
            return 42;
        } elseif ($data == "B.IT") {
            return 12;
        } elseif ($data == "B.Pharma") {
            return 2;
        } elseif ($data == "B.Sc.") {
            return 39;
        } elseif ($data == "BAMS") {
            return 25;
        } elseif ($data == "BBA") {
            return 21;
        } elseif ($data == "bbm") {
            return 57;
        } elseif ($data == "BCA") {
            return 11;
        } elseif ($data == "BDS") {
            return 27;
        } elseif ($data == "BFA") {
            return 46;
        } elseif ($data == "BHM") {
            return 22;
        } elseif ($data == "BHMS") {
            return 26;
        } elseif ($data == "BJMC") {
            return 48;
        } elseif ($data == "BL/LLB") {
            return 36;
        } elseif ($data == "BPT") {
            return 32;
        } elseif ($data == "BvSc.") {
            return 30;
        } elseif ($data == "CA") {
            return 15;
        } elseif ($data == "CFA") {
            return 19;
        } elseif ($data == "CS") {
            return 16;
        } elseif ($data == "D Pharma") {
            return 58;
        } elseif ($data == "Diploma") {
            return 52;
        } elseif ($data == "DM") {
            return 34;
        } elseif ($data == "High School") {
            return 53;
        } elseif ($data == "ICWA") {
            return 17;
        } elseif ($data == "M.A.") {
            return 40;
        } elseif ($data == "M.Arch") {
            return 7;
        } elseif ($data == "M.COM") {
            return 18;
        } elseif ($data == "M.D.") {
            return 56;
        } elseif ($data == "M.Des") {
            return 9;
        } elseif ($data == "M.E/M.Tech") {
            return 3;
        } elseif ($data == "M.Ed") {
            return 43;
        } elseif ($data == "M.Pharma") {
            return 4;
        } elseif ($data == "M.Phil") {
            return 51;
        } elseif ($data == "M.S. (Medicine)") {
            return 28;
        } elseif ($data == "M.S. Engineering") {
            return 5;
        } elseif ($data == "M.Sc") {
            return 41;
        } elseif ($data == "MBA/PGDM") {
            return 20;
        } elseif ($data == "MBBS") {
            return 23;
        } elseif ($data == "MCA/PGDCA") {
            return 10;
        } elseif ($data == "MCh") {
            return 35;
        } elseif ($data == "MDS") {
            return 31;
        } elseif ($data == "MFA") {
            return 47;
        } elseif ($data == "MJMC") {
            return 49;
        } elseif ($data == "ML/LLM") {
            return 37;
        } elseif ($data == "MPT") {
            return 33;
        } elseif ($data == "MSW") {
            return 44;
        } elseif ($data == "MVSc.") {
            return 29;
        } elseif ($data == "Other") {
            return 55;
        } elseif ($data == "Ph.D") {
            return 50;
        } elseif ($data == "Trade School") {
            return 54;
        }
    }

    public static function occupationCodeOrString($data)
    {
        if ($data == 1) {
            return "Business/Self Employed";
        } elseif ($data == 2) {
            return "Doctor";
        } elseif ($data == 3) {
            return "Government Job";
        } elseif ($data == 4) {
            return "Teacher";
        } elseif ($data == 5) {
            return "Private Job";
        } elseif ($data == "Business/Self Employed") {
            return 1;
        } elseif ($data == "Doctor") {
            return 2;
        } elseif ($data == "Government Job") {
            return 3;
        } elseif ($data == "Teacher") {
            return 4;
        } elseif ($data == "Private Job") {
            return 5;
        }
    }

    public static function wishToGoAbroadCodeOrString($data)
    {
        if ($data == 0) {
            return "no";
        } elseif ($data == 1) {
            return "yes";
        } elseif ($data == "no") {
            return 0;
        } elseif ($data == "yes") {
            return 1;
        }
    }

    public static function yearlyIncomeCodeOrString($temple_id)
    {
        return UserData::where(['temple_id', $temple_id])->delete();
    }

    public static function fatherStatusCodeOrString($data)
    {
        if ($data == 1) {
            return "Not Alive";
        }
        if ($data == 2) {
            return "Business/Self Employed";
        }
        if ($data == 3) {
            return "Doctor";
        }
        if ($data == 4) {
            return "Government Job";
        }
        if ($data == 5) {
            return "Teacher";
        }
        if ($data == 6) {
            return "Private Job";
        }
        if ($data == "Not Alive") {
            return 1;
        }
        if ($data == "Business/Self Employed") {
            return 2;
        }
        if ($data == "Doctor") {
            return 3;
        }
        if ($data == "Government Job") {
            return 4;
        }
        if ($data == "Teacher") {
            return 5;
        }
        if ($data == "Private Job") {
            return 6;
        }
    }

    public static function familyIncomeCodeOrString($data)
    {
        if ($data == 1) {
            return "Hindu";
        } elseif ($data == 2) {
            return "FEMALE";
        } elseif ($data == "Male") {
            return 1;
        } elseif ($data == "Female") {
            return 2;
        }
    }

    public static function motherStatusCodeOrString($data)
    {
        if ($data == 1) {
            return "Not Alive";
        }
        if ($data == 2) {
            return "Business/Self Employed";
        }
        if ($data == 3) {
            return "Doctor";
        }
        if ($data == 4) {
            return "Government Job";
        }
        if ($data == 5) {
            return "Teacher";
        }
        if ($data == 6) {
            return "Private Job";
        }
        if ($data == "Not Alive") {
            return 1;
        }
        if ($data == "Business/Self Employed") {
            return 2;
        }
        if ($data == "Doctor") {
            return 3;
        }
        if ($data == "Government Job") {
            return 4;
        }
        if ($data == "Teacher") {
            return 5;
        }
        if ($data == "Private Job") {
            return 6;
        }
    }

    public static function houseTypeCodeOrString($data)
    {
        if ($data == 1) {
            return "Owned";
        } elseif ($data == 2) {
            return "Rented";
        } elseif ($data == 3) {
            return "Leased";
        } elseif ($data == "Owned") {
            return 1;
        } elseif ($data == "Rented") {
            return 2;
        } elseif ($data == "Leased") {
            return 3;
        }
    }

    public static function familyTypeCodeOrString($data)
    {
        if ($data == 1) {
            return "Nuclear";
        } elseif ($data == 2) {
            return "Joint";
        } elseif ($data == "Nuclear") {
            return 1;
        } elseif ($data == "Joint") {
            return 2;
        }
    }

    public static function incomeCodeOrString($temple_id)
    {
        return UserData::where(['temple_id', $temple_id])->delete();
    }
}
