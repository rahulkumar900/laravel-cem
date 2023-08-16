<?php

namespace App\Console\Commands\Migration;

use App\Models\Compatibility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class migrateCompatibilitiesToUserCompatibilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:compatibilitiesToUserCompatibilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To migrate the preferences data user_data table';

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
     * Cron to move compatibilities table details from old database to new database(rds) .
     *
     * @SWG\Post(path="/cron/moveCompatibilitiesTableTouserCompatibilities",
     *   summary="moving old database details to new database, data fill for the userCompatibilities table ",
     *   description="
        business logic => fill userCompatibilities table in new database from compatibilities table
        In new database, marked most of the columns with enum to some interger, male= 1 female =2, and same has done for the caste,marital_status,food_choice,manglik etc.

        table used => compatibilities(old database),userCompatibilities (new database)

        code logic ->
        before running any cron, run transfer compatibilities details to user_compatibility table cron,
        fetch all the compatibilities which have not been transferred to userCompatibilities  table, this will transfer all the compatibilities which are in user_profiles table, and who has profile_id value

        run the insert function on new database
        NOTE: not transferring the profile_status values
     ",
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
    //  dd("ffff");
      //rds connection data that transferred
        $user_data =  DB::select("select profile_id from user_data where is_lead = 0");

        $user_data = json_decode(json_encode($user_data), true);
        $profiles = new Compatibility();
        $profiles = $profiles->setConnection('mysql');
        $profiles = $profiles->whereIn('compatibilities.user_id',$user_data)->get();

        $count = 0;
        foreach($profiles as $profile){
        //  dd($profile);
        //  $preference = Profile::where('id',$profile->user_id)->first();


          $user_data =  DB::select("select id from user_data where profile_id = ".$profile->user_id);

          //dd($user_data);
          $user_data = json_decode(json_encode($user_data), true);
          $profile1 = $profile;
          (object)$profile2 = DB::select('select * from user_data where profile_id ='.$profile->user_id);
            $profile2 = (object)$profile2[0];

            $userDetailId = $profile2->id;

            $check_pref = DB::select('select id from userCompatibilities where user_data_id = '.$profile2->id);
            if(!$check_pref){
              try{
                 echo $profile1->user_id;
                 echo "\n";
                DB::insert("INSERT INTO userCompatibilities(user_data_id,compatibility,discoverCompatibility,credit_available,daily_quota,free_credits_given,free_credit_count,free_id,contacted_count,reject_count,shortlist_count,ri_count,si_count,ci_count,jeev_count,jeev_compatibility,jeev_timestamp,discovery_profile_left,first_time,max,max_new,virtual_receive,virtualToLike,virtual_send,virtual_send_count,virtual_receive_count,free_ids,random_count,viewProfile_count,random_updated,negative_contacted_profiles,today_activation_link,created_at,updated_at)
                  VALUES('" . $userDetailId. "','" . $profile->compatibility. "','" . $profile->discoverCompatibility. "','" . $profile->whatsapp_point. "','" . $profile->daily_quota. "','" . $profile->free_credits_given. "','" . $profile->free_credit_count. "','" . $profile->free_id. "','" . $profile->contacted_count. "','" . $profile->reject_count. "','" . $profile->shortlist_count. "',
                  '" . $profile->ri_count. "','" . $profile->shown_interest_count. "','" . $profile->contact_interest_count. "','" . $profile->jeev_count. "','" . $profile->jeev_compatibility. "','" . $profile->jeev_timestamp. "','" . $profile->discovery_profile_left. "','" . $profile->first_time. "','" . $profile->max. "','" . $profile->max_new. "','" . $profile->virtual_receive. "','" . $profile->virtualToLike. "','" . $profile->virtual_send. "','" . $profile->virtual_send_count. "','" . $profile->virtual_receive_count. "','" . $profile->free_ids. "','" . $profile->random_count. "','" . $profile->viewProfile_count. "','" . $profile->random_updated. "','" . $profile->negative_contacted_profiles. "','" . $profile->today_activation_link. "','" . $profile->created_at. "','" . $profile->updated_at. "')");
             //   dd(__LINE__);
                }
                catch(\Exception $e){
                  dd($e->getMessage());
                }
            }

        //      dd("fdfd");
            }
            echo $count++."\n";
          /*
          }
          else{
            dd(__LINE__);
          }
          */

      dd("complete");
     //   dd($profile->name);

    }
}


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


/*
            $mobile = '91'.''.substr($profile->mobile, -10);

            $whatsapp = null;
            if($profile->whatsapp1){
                $whatsapp = '91'.''.substr($profile->whatsapp1, -10);
            }
            else if($profile->whatsapp2){
                $whatsapp = '91'.''.substr($profile->whatsapp2, -10);
            }

            $gender = MyFuncs::getGenderId($profile->gender);
            $relation = MyFuncs::getRelationId($profile->relation);
            $religion = MyFuncs::getReligionId($profile->religion);
            $caste = MyFuncs::getCasteId($profile->caste);

            $education = null;
            if($profile->education){
                $education = MyFuncs::getEducationId($profile->education);
            }
            else if($profile->degree){
                $education = MyFuncs::getEducationId($profile->degree);
            }

            $occupation = MyFuncs::getOccupationId($profile->occupation);
            $user_income = $profile->monthly_income/100000;
            $family_income = $profile->family_income/100000;
            $marital_status = MyFuncs::getMaritalStatusId($profile->marital_status);
            $manglik = MyFuncs::getManglikStatusId($profile->manglik);
            $is_disability = MyFuncs::getDisabilityId($profile->disability);

            $is_livingWithParents = 1;
            if($profile->livingWithParents == 'No'){
                $is_livingWithParents = 0;
            }
            $house_type = MyFuncs::getHouseTypeId($profile->house_type);
            $family_type = MyFuncs::getFamilyTypeId($profile->family_type);
            $father_status = MyFuncs::getAliveStatus($profile->father_status);
            $mother_status = MyFuncs::getAliveStatus($profile->mother_status);
            $bot_language = 0;
            if($profile->bot_language && $bot_language == 'Hindi'){
                $bot_language  = 1;
            }

            $locality = str_replace('\'', '', $profile->locality);
            if(!$locality){
                $locality = str_replace('\'', '', $profile->city);
            }
            $city = str_replace('\'', '', $profile->city);
            $working_city = str_replace('\'', '', $profile->working_city);


*/
