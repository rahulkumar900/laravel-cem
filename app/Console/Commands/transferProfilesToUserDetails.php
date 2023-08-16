<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Profile as Family;
use App\Helpers\MyFuncs;

class transferProfilesToUserDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:abc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    /**
     * Cron to move profiles table details from odl database to new database(rds) .
     *
     * @SWG\Post(path="/cron/moveProfilesTableToUser_DetailsTable",
     *   summary="moving old database details to new database, data fill for the user_Details table ",
     * description=" business logic=>fill user_Details table in new database from profiels,families,preferences table In new database, marked most of the columns with enum to some interger, male=1 female=2, and same has done for the caste,marital_status,food_choice,manglik etc. table used=>profiles(old database),families(old database),user_Details (new database) code logic ->fetch all the profiles which have not been transferred to user_details table, and custom the limit, how many profiles want to transfer. get all the ids corresponding to the enum values, run the insert function on new database ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="login user",
     *     description="JSON Object which login user",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", example="user@mail.com"),
     *         @SWG\Property(property="password", type="string", example="password"),
     *     )
     *   ),
     *   @SWG\Response(response="200", description="Return token or error message")
     * )
     *
     */
    public function handle()
    {

        //rdd connection data that transferred
        $user_details =  DB::connection('rds')->select("select profile_id from user_details");

        $user_details = json_decode(json_encode($user_details), true);
        $family = new Family();
        $family = $family->setConnection('mysql');
        $profiles = $family->join('profiles', 'profiles.id', 'families.id')->orderBy('profiles.created_at', 'desc')->whereNotIn('profiles.id', $user_details)->select('profiles.id', 'profiles.lead_id', 'profiles.temple_id', 'families.mobile', 'families.whats_app_no as whatsapp1', 'profiles.whatsapp as whatsapp2', 'families.email', 'families.email_verified', 'profiles.name', 'profiles.gender', 'families.relation', 'families.religion', 'families.caste', 'profiles.education', 'profiles.degree', 'profiles.college', 'profiles.additional_qualification', 'profiles.occupation', 'profiles.profession', 'profiles.monthly_income', 'profiles.working_city', 'profiles.birth_date', 'profiles.birth_time', 'profiles.birth_place', 'profiles.height', 'profiles.weight', 'profiles.marital_status', 'profiles.manglik', 'profiles.disability', 'profiles.disabled_part', 'profiles.citizenship', 'profiles.about as profile_about', 'profiles.children', 'profiles.carousel', 'profiles.bot_language', 'profiles.is_approved', 'profiles.approved_by', 'profiles.is_automate_approve', 'profiles.fcm_id', 'profiles.fcm_app', 'profiles.last_seen', 'profiles.is_paid', 'profiles.is_premium', 'profiles.is_invisible', 'profiles.upgrade_request', 'upgrade_renew', 'profiles.is_call_back', 'profiles.call_back_query', 'is_premium_interest', 'is_subscription_view', 'families.relation', 'livingWithParents', 'locality', 'families.city', 'unmarried_sons', 'married_sons', 'unmarried_daughters', 'married_daughters', 'family_type', 'house_type', 'religion', 'families.caste', 'gotra', 'families.occupation as father_occupation', 'families.family_income', 'budget', 'families.office_address as father_office_addr', 'families.father_status', 'families.mother_status', 'whats_app_no', 'families.occupation_mother', 'families.about as family_about', 'office_address_mother', 'profiles.lead_id', 'profiles.created_at', 'profiles.audio_profile', 'profiles.unapprove_audio_profile', 'profiles.audioProfile_hls_convert')->limit(200)->orderBy('profiles.created_at', 'desc')->get();


        /*
        ***RELIGION *****
        Hindu = 1
        Jain = 2
        Sikh  = 3
        Muslim = 4
        Buddhist  = 4
        Christian = 6
        Parsi = 7
        Jewish = 8
        Bahai = 9

        ====MARITAL STATUS===
        Never Married   1
        Awaiting Divorce    2
        Divorcee    3
        Widowed 4
        Anulled 5


        ***RELATION***
        Myself  1
        Son 2
        Daughter 3
        Sister  4
        Brother 5
        Other   6
        */

        $count = 0;

        foreach ($profiles as $profile) {
            //  dd($profile->lead_id);
            $mobile = '91' . '' . substr($profile->mobile, -10);

            $whatsapp = null;
            if ($profile->whatsapp1) {
                $whatsapp = '91' . '' . substr($profile->whatsapp1, -10);
            } else if ($profile->whatsapp2) {
                $whatsapp = '91' . '' . substr($profile->whatsapp2, -10);
            }

            $gender = MyFuncs::getGenderId($profile->gender);
            $relation = MyFuncs::getRelationId($profile->relation);
            $religion = MyFuncs::getReligionId($profile->religion);
            $caste = MyFuncs::getCasteId($profile->caste);

            $education = null;
            if ($profile->education) {
                $education = MyFuncs::getEducationId($profile->education);
            } else if ($profile->degree) {
                $education = MyFuncs::getEducationId($profile->degree);
            }

            $occupation = MyFuncs::getOccupationId($profile->occupation);
            $user_income = $profile->monthly_income / 100000;
            $family_income = $profile->family_income / 100000;
            $marital_status = MyFuncs::getMaritalStatusId($profile->marital_status);
            $manglik = MyFuncs::getManglikStatusId($profile->manglik);
            $is_disability = MyFuncs::getDisabilityId($profile->disability);

            $is_livingWithParents = 1;
            if ($profile->livingWithParents == 'No') {
                $is_livingWithParents = 0;
            }
            $house_type = MyFuncs::getHouseTypeId($profile->house_type);
            $family_type = MyFuncs::getFamilyTypeId($profile->family_type);
            $father_status = MyFuncs::getAliveStatus($profile->father_status);
            $mother_status = MyFuncs::getAliveStatus($profile->mother_status);
            $bot_language = 0;
            if ($profile->bot_language && $bot_language == 'Hindi') {
                $bot_language  = 1;
            }

            $locality = str_replace('\'', '', $profile->locality);
            if (!$locality) {
                $locality = str_replace('\'', '', $profile->city);
            }
            $city = str_replace('\'', '', $profile->city);
            $working_city = str_replace('\'', '', $profile->working_city);
            //dd($locality);
            //  dd($profile->caste);
            //  dd($mobile);
            DB::connection('rds')->insert("INSERT INTO user_details(lead_id,profile_id,temple_id,mobile,whatsapp_mobile,email,email_verified,name,gender,relation,religion,caste_id,education,college,additional_qualification,occupation,profession,company,user_income,working_city,birth_date,birth_time,birth_place,height,weight,marital_status,manglik,is_disability,disabled_part,citizenship,about,children,carousel,bot_language,is_verified,verified_by,is_self_verified,fcm_id,fcm_app,last_seen,is_invisible,call_back_query,is_subscription_view,is_delete,deleted_by,is_livingWithParents,locality,city,unmarried_brothers,married_brothers,unmarried_sisters,married_sisters,family_type,house_type,gotra,family_income,father_status,father_occupation,father_office_address,mother_status,mother_occupation,mother_office_address,family_about,created_at,audio_profile,unapprove_audio_profile,audioProfile_hls_convert) VALUES('" . $profile->lead_id . "','" . $profile->id . "','" . $profile->temple_id . "','" . $mobile . "','" . $whatsapp . "','" . $profile->email . "','" . $profile->email_verified . "','" . $profile->name . "','" . $gender . "','" . $relation . "','" . $religion . "','" . $caste . "','" . $education . "','" . $profile->college . "','" . $profile->additional_qualification . "','" . $occupation . "','" . $profile->profession . "','" . $profile->company . "','" . $user_income . "','" . $working_city . "','" . $profile->birth_date . "','" . $profile->birth_time . "','" . $profile->birth_place . "','" . $profile->height . "','" . $profile->weight . "','" . $marital_status . "','" . $manglik . "','" . $is_disability . "','" . $profile->disabled_part . "','" . $profile->citizenship . "','" . $profile->about . "','" . $profile->children . "','" . $profile->carousel . "','" . $profile->bot_language . "','" . $profile->is_approved . "','" . $profile->approved_by . "','" . $profile->automate_approve . "','" . $profile->fcm_id . "','" . $profile->fcm_app . "','" . $profile->last_seen . "','" . $profile->is_invisible . "','null','" . $profile->is_subscription_view . "','" . $profile->is_delete . "','" . $profile->deleted_by . "','" . $is_livingWithParents . "','" . $locality . "','" . $profile->city . "','" . $profile->unmarried_sons . "','" . $profile->married_sons . "','" . $profile->unmarried_daughters . "','" . $profile->married_daughters . "','" . $family_type . "','" . $house_type . "','" . $profile->gotra . "','" . $family_income . "','" . $father_status . "','" . $profile->father_occupation . "','" . $profile->father_office_address . "','" . $mother_status . "','" . $profile->mother_occupation . "','" . $profile->mother_office_address . "','" . $profile->family_about . "','" . $profile->created_at . "','" . $profile->audio_profile . "','" . $profile->unapprove_audio_profile . "','" . $profile->audioProfile_hls_convert . "')");

            echo $count++ . "\n";
        }
        dd("complete");
        //   dd($profile->name);

    }
}
