<?php

namespace App\Console\Commands\Migration;

use Illuminate\Console\Command;
use DB;
use App\Profile;
use App\Family;
use App\Lead;
use App\Preference;
use App\Helpers\MyFuncs;
class migratePreferencesToUserPreferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:preferencesToUserPreferences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To migrate the preferences data user_details table';

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
     * @SWG\Post(path="/cron/movePreferencesTableToUserPreferencesTable",
     *   summary="moving old database details to new database, data fill for the user_preferences table ",
     *   description="
        business logic => fill user_Details table in new database from profiels,families,preferences table
        In new database, marked most of the columns with enum to some interger, male= 1 female =2, and same has done for the caste,marital_status,food_choice,manglik etc.

        table used => profiles(old database),families(old database),user_Details (new database)

        code logic -> fetch all the profiles which have not been transferred to user_details table, and custom the limit, how many profiles,preferencs rows want to transfer.
        get all the ids corresponding to the enum values,
        run the insert function on new database
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
        $user_details =  DB::connection('rds')->select("select profile_id from user_details where is_lead = 0");

        $user_details = json_decode(json_encode($user_details), true);
        $profiles = new Profile();
        $profiles = $profiles->setConnection('mysql');
        $profiles = $profiles->whereIn('profiles.id',$user_details)->get();

        $count = 0;
        foreach($profiles as $profile){
          $preference = Preference::where('temple_id',$profile->temple_id)->where('identity_number',$profile->identity_number)->first();

          $castes = [];
          $pref_caste = $preference->caste;
          $pref_caste = explode(',', $pref_caste);
          if($pref_caste){
            foreach($pref_caste as $caste){
              array_push($castes, MyFuncs::getCasteId($caste));
            }
            array_unique($castes);
          }
          $pref_caste = implode(',', $castes);

          $religions =  [];
          $pref_religion = $preference->religion;
          $pref_religion = explode(',', $pref_religion);
          if($pref_religion){
            array_push($religions, MyFuncs::getRelationId($pref_religion));
            array_unique($religions); 
          }
          $pref_religion = implode(',', $religions);

          $marital_status = MyFuncs::getMaritalStatusId($preference->marital_status);
          $manglik = MyFuncs::getManglikStatusId($preference->manglik);
          $food_choice = MyFuncs::getFoodChoiceId($preference->food_choice);
         // $occuption = MyFuncs::getOccupationId($preference->occuption);
          $income_min = $preference->income_min/100000;
          $income_max = $preference->income_max/100000;
          $citizenship = 0;
          if($preference->citizenship == 'Settle Abroad in Future'){
            $citizenship = 1; 
          }

          $no_castes = [];
          $no_pref_caste = $preference->no_pref_caste;
          if($no_pref_caste){
          $no_pref_caste = explode(',', $no_pref_caste);
            if($no_pref_caste != '[]'){
              foreach($no_pref_caste as $caste){
                array_push($no_castes, MyFuncs::getCasteId($caste));
                array_unique($no_castes);
              }
            }
            $no_castes = implode(',', $no_castes);
          }
          else{
            $no_castes = null;
          }
          $no_pref_caste = $no_castes;
          $user_details =  DB::connection('rds')->select("select id from user_details where profile_id = ".$profile->id);

          $user_details = json_decode(json_encode($user_details), true);
          $profile1 = $profile;
          (object)$profile = DB::connection('rds')->select('select * from user_details where profile_id ='.$profile->id);

            $profile = (object)$profile[0];
            $userDetailId = $profile->id;
         //   dd("ff");
            $working = 0;
            if($preference->working == 'working'){
              $working = 1;
            }
            // $is_requestCallBack = $profile->is_requestCallBack;
            // if($lead->is_call_back1){
            //   $is_requestCallBack = 1;
            // }

            // $profile = Profile::where('lead_id',$lead->id)->first();
            // $call_back_query = $profile->call_back_query;
            // if($lead->call_back_query1){
            //   $call_back_query = $lead->call_back_query;
            // }
            // $is_subscription_view = $profile->is_subscription_view;
            // if($lead->is_subscription_view1){
            //   $is_subscription_view = $lead->is_subscription_view;
            // }
            // $is_renewed = $profile->is_renewed;
            // $is_upgradeRequest = 0;
            // if($profile->upgrade_request == 'on'){
            //   $is_upgradeRequest = 1;
            // }
            // else if($lead->premium_lead){
            //   $is_upgradeRequest = 1;
            // }

            // $is_showPremiumInterest = $profile->is_premium_interest;

            // if($lead->is_premium_interest){
            //   $is_showPremiumInterest = $lead->is_premium_interest;
            // }
            // $lead->comments = str_replace('\'', '', $lead->comments);
            $occupation = null;
            $citizenship = 0;
            if($citizenship == 'Indian'){
              $citizenship = 1;
            }
            else if($citizenship == 'NRI'){
              $citizenship = 2;
            }
            $about = str_replace('\'', '', $preference->description);
       //     dd($userDetailId);
       //     dd($pref_caste);
           
            
            $check_pref = DB::connection('rds')->select('select id from user_preferences where user_detail_id = '.$profile->id);
            if(!$check_pref){
              try{
                 echo $profile1->id;
                 echo "\n";
                DB::connection('rds')->insert("INSERT INTO user_preferences(temple_id,user_detail_id,age_min,age_max,height_min,height_max,caste,marital_status,manglik,food_choice,working,occupation,income_min,income_max,citizenship,preference_about,created_at,source,amount_collected,validity,payment_method,receipt_url,not_include_caste,pref_city,amount_collected_date,roka_charge,validity_month,religion_id,pref_state,pref_country,pref_country_id,pref_state_id,literacy_score,paid_score,zPaid_score,photo_score,zPhoto_score,degree_score,zDegree_score,freshness_score,zFreshness_score,dataAccount_score,zDataAccount_score,salary_score,zSalary_score,activity_score,zActivity_score,visibility_score,zVisibility_score,starvation_score,zStarvation_score,boost_score,zBoost_score,goodness_score,zGoodNess_score,goodness_score_female,zGoodness_score_female)
                  VALUES('" . $preference->temple_id. "','" . $userDetailId. "','" . $preference->age_min. "','" . $preference->age_max. "','" . $preference->height_min. "','" . $preference->height_max. "','" . $pref_caste. "','" . $marital_status. "','" . $manglik. "','" . $food_choice. "','" . $working. "',
                  '" . $occupation. "','" . $income_min. "','" . $income_max. "','" . $citizenship. "','" . $about. "','" . $preference->created_at. "','" . $preference->source. "','" . $preference->amount_collected. "','" . $preference->validity. "',
                  '" . $preference->payment_method. "','" . $preference->receipt_url. "','" . $no_pref_caste. "','" . $preference->pref_city. "','" . $preference->amount_collected_date. "','" . $preference->roka_charge. "','" . $preference->validity_month. "','" . $pref_religion. "','" . $preference->pref_state. "','" . $preference->pref_country. "','" . $preference->pref_country_id. "','" . $preference->pref_state_id. "','" . $preference->education_score. "','" . $preference->paid_score. "','" . $preference->zPaidScore. "','" . $profile1->photo_score. "','" . $profile1->zvaluePhoto. "','" . $profile1->edu_score. "','" . $profile1->zeduScore. "','" . $profile1->freshness_score. "','" . $profile1->zFreshnessScore. "','" . $profile1->data_score. "','" . $profile1->zdataScore. "','" . $profile1->salary_score. "','" . $profile1->zSalaryScore. "','" . $profile1->activity_score. "','" . $profile1->zactivity_score. "','" . $profile1->visibility_score. "','" . $profile1->zvisibility_score. "','" . $profile1->starvation_score. "','" . $profile1->zStarvation. "','" . $profile1->boost_score. "','" . $profile1->zBoost_score. "','" . $profile1->goodness_score. "','" . $profile1->zGoodNessScore. "','" . $profile1->goodness_score_female. "','" . $profile1->zGoodNessScoreFemale. "')"); 
                }
                catch(\Exception $e){
                  dd($e->getLine());
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