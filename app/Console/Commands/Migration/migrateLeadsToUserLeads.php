<?php

namespace App\Console\Commands\Migration;

use App\Models\Lead;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class migrateLeadsToUserLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:leadsToUserLeads';

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
     * @SWG\Post(path="/cron/moveLeadsTableToUserLeadsTable",
     *   summary="moving old database details to new database, data fill for the user_leads table ",
     *   description="
        business logic => fill user_data table in new database from profiels,families,preferences table
        In new database, marked most of the columns with enum to some interger, male= 1 female =2, and same has done for the caste,marital_status,food_choice,manglik etc.

        table used => leads(old database),free_users(old database),user_leads (new database)

        code logic ->
        before running any cron, run transfer profile details to user_data table cron,
        fetch all the leads,free_users which have not been transferred to user_leads  table, this will transfer all the leads which are in user_profiles table, and who has lead_id value
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
        //rds connection data that transferred
        $user_data =  DB::select("select lead_id from user_data where lead_id != 0");
        //$user_data =  DB::select("select lead_id from user_data where lead_id != 0");

        $user_data = json_decode(json_encode($user_data), true);
       // dd($user_data);
        $leads = new Lead();
        $leads = $leads->setConnection('mysql');
        $leads = $leads->join('free_users','free_users.lead_id','leads.id')->orderBy('leads.created_at','desc')->whereIn('leads.id',$user_data)->select('leads.id as id','leads.assign_by','leads.assign_to','leads.enquiry_date','leads.followup_call_on','leads.comments','leads.is_done','leads.created_at','leads.assigned_at','leads.speed','leads.source','leads.acq_type','leads.acq_category','leads.visited_on','leads.offline_score','leads.online_score','leads.total_score','leads.profile_created','leads.appointment_date','leads.appointment_created_on','leads.premium_lead as upgradeRequest1','leads.appointment_by','leads.lead_progress','leads.approve_followup_call_on','leads.approve_comments','leads.call_in','leads.call_out','leads.web_direct','leads.visit','leads.app','leads.is_subscription_view as subscription_view','leads.subscription_seens','leads.request_by','leads.call_count','leads.rejected_at','leads.reject_count','leads.is_call_back','leads.relation_value','leads.norm_relation_value','leads.max_income','leads.norm_maxIncome','leads.norm_income_value','leads.lead_value','leads.approved_by','leads.is_approved_on','leads.is_not_interested','free_users.is_call_back as is_call_back1','free_users.call_back_query as call_back_query1')->orderBy('leads.created_at','desc')->get();
  $count = 0;
// dd("fdfdfd");
        foreach($leads as $lead){
          $user_data =  DB::select("select lead_id from user_data where lead_id = ".$lead->id);
          $user_data = json_decode(json_encode($user_data), true);
          //if($user_data)
        //  if(!$user_data){
     //       dd(__LINE__);
            (object)$profile = DB::select('select * from user_data where lead_id ='.$lead->id);
            $profile = (object)$profile[0];
            $userDetailId = $profile->id;
            $is_requestCallBack = $profile->is_requestCallBack;
            if($lead->is_call_back1){
              $is_requestCallBack = 1;
            }

            $profile = Profile::where('lead_id',$lead->id)->first();
            $call_back_query = $profile->call_back_query;
            if($lead->call_back_query1){
              $call_back_query = $lead->call_back_query;
            }
            $is_subscription_view = $profile->is_subscription_view;
            if($lead->is_subscription_view1){
              $is_subscription_view = $lead->is_subscription_view;
            }
            $is_renewed = $profile->is_renewed;
            $is_upgradeRequest = 0;
            if($profile->upgrade_request == 'on'){
              $is_upgradeRequest = 1;
            }
            else if($lead->premium_lead){
              $is_upgradeRequest = 1;
            }

            $is_showPremiumInterest = $profile->is_premium_interest;

            if($lead->is_premium_interest){
              $is_showPremiumInterest = $lead->is_premium_interest;
            }
            $lead->comments = str_replace('\'', '', $lead->comments);

            try{
              DB::insert("INSERT INTO user_leads(user_detail_id,assign_by,assign_to,enquiry_date,followup_call_on,comments,is_done,created_at,assigned_at,interest_level,source,acq_type,acq_category,visited_on,offline_score,online_score,total_score,profile_created,appointment_date,appointment_created_on,premium_lead,appointment_by,lead_progress,approve_followup_call_on,approve_comments,call_in,call_out,web_direct,visit,app,is_subscription_view,subscription_seens,request_by,call_count,rejected_at,reject_count,is_call_back,relation_value,norm_relation_value,is_renewed,is_requestCallback,call_back_query,is_showPremiumInterest,max_income,norm_maxIncome,norm_income_value,lead_value) VALUES('" . $userDetailId. "','" . $lead->assign_by. "','" . $lead->assign_to. "','" . $lead->enquiry_date. "','" . $lead->followup_call_on. "','" . $lead->comments. "','" . $lead->is_done. "','" . $lead->created_at. "','" . $lead->assigned_at. "','" . $lead->speed. "','" . $lead->source. "','" . $lead->acq_type. "','" . $lead->acq_category. "','" . $lead->visited_on. "','" . $lead->offline_score. "','" . $lead->online_score. "','" . $lead->total_score. "','" . $lead->profile_created. "','" . $lead->appointement_date. "','" . $lead->appointment_created_on. "','" . $lead->premium_lead. "','" . $lead->appointment_by. "','" . $lead->lead_progress. "','" . $lead->approve_followup_call_on. "','" . $lead->approve_comments. "','" . $lead->call_in. "','" . $lead->call_out. "','" . $lead->web_direct. "','" . $lead->visit. "','" . $lead->app. "','" . $is_subscription_view. "','" . $lead->subscription_seens. "','" . $lead->request_by. "','" . $lead->call_count. "','" . $lead->rejected_at. "','" . $lead->reject_count. "','" . $lead->is_call_back. "','" . $lead->relation_value. "','" . $lead->norm_relation_value. "','" . $is_renewed. "','" . $is_requestCallBack. "','" . $call_back_query. "','" . $is_showPremiumInterest. "','" . $lead->max_income. "','" . $lead->norm_maxIncome. "','" . $lead->norm_income_value. "','" . $lead->lead_value. "')");
              }
              catch(\Exception $e){

              }
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
