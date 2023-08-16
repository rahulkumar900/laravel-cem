<?php

namespace App\Console\Commands;

use App\Models\notReceivedVirtualLike;
use Illuminate\Console\Command;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserData as Profile;
use App\Models\UserPreference as Preference;
use App\Models\User as User;
use App\Models\VLLogs;
use Carbon\Carbon;
use App\Models\SendVLCondition;
use Illuminate\Support\Facades\DB;


class sendAudioVirtualLikes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:audiovirtualLike';
    protected $count = 1;
    protected $allCasteCount = 1;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $noLike = array();
    protected $like = array();
    protected $totalLike  = array();
    protected $likeLead = 0;
    protected $likeProfile = 0;
    protected $totalLikeExpectLead = array();
    protected $totalLikeExpectProfile = array();
    protected $likeGottenLead = array();
    protected $likeGottenProfile = array();
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
     * cron job to send the virtual like to the users who came two hours before and previous day.
     *
     * @SWG\Post(path="/sendVirtualLike",
     *   summary="send the virtual like to the users who came two hours before and previous day",
    * description=" business logic ->send a virtual like to the users who came two hours before and previous day, this will help in retention cron frequency/timing ->in every two hours table used=>profiles,families,preferences,free_users,lead_family,lead_preference,compatibilites,leadCompatibilities variable used=>$query ->this will have all the profiles which are coming in the user preferences. twoMonthOldDate->last 1 year old date, and this will used in $query, to take only a year old last seen profile code logic=>fetch all the users from profiles table who has gotten no like in either last 2 hours or prev day. find all the matched_profiles according to the preferences of the user. Now in the matched_profiles check if the user lies in the preferences of the matched_profile if lies send that profile as virtual like, set the value as SI in profile_status to the user profile, and add the matched_profile id in virtual_receive, increment the virtual_receive_count by 1 filter used(caste,religion,age,height,income,lasName) if user preferences is not in matche_profiles then call the sendMatch() function again and add notUseLastSeen value as 1, which will remove the filter of last seen filter in $query. repeat all the proces again ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="udpate the daily active users in table activeusers",
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
        $mobile_arr = array();
        $marital_status = array(
            "Never Married" => array('Never Married'),
            "Divorced" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Divorcee" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Widowed" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Widow" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Widow/Widower" =>array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Married" => array(),
            "Other" => array(),
            "Awaiting Divorce" => ['Divorced', 'Divorcee', 'Awaiting Divorce','Widow','Widowed'],
            "Anulled" => ['Divorced', 'Divorcee', 'Anulled'],
            "Divorcee & Widowed" => array('Divorced','Widowed','Divorcee','Widow'),
        );

        $manglik = array(
            "Manglik" => ['Manglik', 'Anshik Manglik','manglik'],
            "manglik" => ['Manglik', 'Anshik Manglik','manglik'],
            "No" => ['No', 'Anshik Manglik','Non-Manglik'],
            "Non-Manglik" => ['No', 'Anshik Manglik','Non-Manglik'],
            "Non-manglik" => ['No', 'Anshik Manglik','Non-Manglik'],
            "Anshik Manglik" => ['No', 'Anshik Manglik', 'Manglik','Non-Manglik',null,'manglik'],
            "Anshik manglik" => ['No', 'Anshik Manglik', 'Manglik','Non-Manglik',null,'manglik'],
        );

        $food_choice = array(
            "Vegetarian" => ['Vegetarian', 'Vegeterian'],
            "Vegeterian" => ['Vegetarian', 'Vegeterian'],
            "Non-Vegeterian" => ['Non-V egeterian', 'Non-Vegetarian','Vegetarian', 'Vegeterian'],
            "Non-Vegetarian" => ['Non-Vegeterian', 'Non-Vegetarian','Vegetarian', 'Vegeterian','Doesn\'t matter'],
            "Doesn't Matter" => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
            "undefined" => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
            "Doesn't matter" => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
            "Not Working" => ['Not Working'],
            'Doesn\'t matter' => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
            'null' => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
            '' => ['Vegetarian', 'Vegeterian','Non-Vegeterian', 'Non-Vegetarian','Doesn\'t matter'],
        );

        $lastFiveDayDate = date('Y-m-d', strtotime("-5 days"));
        $lastFiveDayDate = $lastFiveDayDate.' 00:00:00';
        $twoMonthOldDate = date('Y-m-d', strtotime("-365 days"));
        $twoMonthOldDate = $twoMonthOldDate.' 00:00:00';
        $prevDay = date('Y-m-d', strtotime("-1 days"));
        $lastThreeDay = date('Y-m-d', strtotime("-2 days"));
        $dataAccounts = User::where('data_account',1)->pluck('temple_id')->toArray();

        $condition_status = SendVLCondition::first();

        //manual work flow
        if($condition_status->status == 1)
        {
            $profiles = Compatibility::where('shown_interest_count','>=',0)->get()->pluck('user_id');

            $profiles = Profile::whereIn('profiles.id',$profiles)
                  ->where('profiles.last_seen','>', Carbon::now()->subDays(15))
                    ->join('families','families.id','profiles.id')
                    ->join('preferences', function($join) {
                        $join->on('preferences.identity_number', '=', 'profiles.identity_number');
                        $join->on('preferences.temple_id', '=', 'profiles.temple_id');
                    })
                    ->where('preferences.amount_collected',0)
                    ->where('profiles.id','!=','302478')
                    ->select('profiles.id','profiles.gender','preferences.age_min','preferences.age_max','preferences.height_min','preferences.height_max','preferences.income_min','preferences.income_max','preferences.caste','profiles.marital_status','preferences.food_choice','preferences.religion','profiles.manglik','profiles.birth_date','profiles.monthly_income','profiles.height','profiles.name','families.mobile as mobile','profiles.education_score')->get();
        }
        else    //usual workflow repeated at 2 hours interval
        {
            $profiles = Compatibility::
                where('shown_interest_count',0)
                ->where('virtual_receive_count',0)
                ->get()
                ->pluck('user_id');


            $profiles = Profile::whereIn('profiles.id',$profiles)
                    ->where('profiles.lead_created_at',$prevDay)
                //  ->where('profiles.last_seen','>','2021-03-15 00:00:00')
                    ->join('families','families.id','profiles.id')
                    ->join('preferences', function($join) {
                        $join->on('preferences.identity_number', '=', 'profiles.identity_number');
                        $join->on('preferences.temple_id', '=', 'profiles.temple_id');
                    })
                    ->where('preferences.amount_collected',0)
                    ->where('profiles.id','!=','302478')
                    ->select('profiles.id','profiles.gender','preferences.age_min','preferences.age_max','preferences.height_min','preferences.height_max','preferences.income_min','preferences.income_max','preferences.caste','profiles.marital_status','preferences.food_choice','preferences.religion','profiles.manglik','profiles.birth_date','profiles.monthly_income','profiles.height','profiles.name','families.mobile as mobile','profiles.education_score')->get();
            //     dd(sizeof($profiles));
        }


        $total_profile_count = sizeof($profiles);
        foreach($profiles as $profile){
            if(!in_array($profile->mobile,$mobile_arr)){
                array_push($mobile_arr, $profile->mobile);
                $this->sendMatch($profile,0,$marital_status,$manglik,$food_choice,$dataAccounts,$twoMonthOldDate);
            }
        }
        $total_like_profile = $this->count; //using this for final count else no use in logic

        $prevDay = date('Y-m-d H:i:s', strtotime("-1 days"));
        $lastTwoHoursPrev = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($prevDay)));
        $lastTwoHoursToday = date('Y-m-d H:i:s', strtotime('-2 hour'));
        $lastFourHoursToday = date('Y-m-d H:i:s', strtotime('-4 hour'));
        echo "prevDay \t".$prevDay;
        echo "lastTwoHoursPrev".$lastTwoHoursPrev;
        echo "last4Hours\t".$lastTwoHoursToday."\n";
    //    dd("ffff");


        $total_profile_count = 0;
        $total_profile = $total_profile_count;
        $total_profile_count = $total_profile_count + sizeof($profiles);

        $countVirtual = 0; //using this for final count else no use in logic
        echo "\n";
        $virtual_count1 = 0; //using this for final count else no use in logic
        $virtual_count2 = 0; //using this for final count else no use in logic
        $total_leads = 0; //using this for final count else no use in logic
     //   dd(sizeof($profiles));

        //shown_interest_count -> this is the count how many users has liked this profile
        foreach($profiles as $profile){
            if(!in_array($profile->mobile,$mobile_arr)){
                array_push($mobile_arr, $profile->mobile);
                if($profile->created_at > $lastFourHoursToday && $profile->shown_interest_count == 0 || ($profile->created_at < $prevDay && $profile->created_at > $lastTwoHoursPrev && ($profile->shown_interest_count == 0 || $profile->shown_interest_count == 1))){
                     $countVirtual++;
                 //   echo $profile->id."\n";
                     $total_leads++;
                    $this->sendMatch($profile,1,$marital_status,$manglik,$food_choice,$dataAccounts,$twoMonthOldDate);
                }
            }
        }
        //using this for final count else no use in logic
        $temp1ExpecLead = array_unique($this->totalLikeExpectLead);
        $temp2ExpecProfile = array_unique($this->totalLikeExpectProfile);
        $temp3LikeGotLead = array_unique($this->likeGottenLead);
        $temp4LikeGotProfile = array_unique($this->likeGottenProfile);

        $noLikeReceivedLead = array_diff($temp1ExpecLead, $temp3LikeGotLead);
        $noLikeReceivedProfile = array_diff($temp2ExpecProfile, $temp4LikeGotProfile);

        foreach($noLikeReceivedProfile as $noProfileId){
            $temp_check = notReceivedVirtualLike::where('is_lead',0)->where('user_id',$noProfileId)->first();
            if(!$temp_check){
                notReceivedVirtualLike::create([
                'is_lead' => 0,
                'user_id' => $noProfileId,
                'last_attempted' => date("Y-m-d H:i:s", time()+19800)
                ]);
            }
            else{
                $temp_check->last_attempted = date("Y-m-d H:i:s", time()+19800);
                $temp_check->save();
            }
        }

        foreach($noLikeReceivedLead as $noLeadId){
            $temp_check = notReceivedVirtualLike::where('is_lead',1)->where('user_id',$noLeadId)->first();
            if(!$temp_check){
                notReceivedVirtualLike::create([
                'is_lead' => 1,
                'user_id' => $noLeadId,
                'last_attempted' => date("Y-m-d H:i:s", time()+19800)
                ]);
            }
            else{
                $temp_check->last_attempted = date("Y-m-d H:i:s", time()+19800);
                $temp_check->save();
            }
        }


        echo "\n first if \t".$virtual_count1;
        echo "\n second if \t".$virtual_count2;

        echo "\nall caste count \t".$this->allCasteCount;
        echo "\n";
        echo "\ntotal like expected leads\t".$total_leads;
        echo "\n tota; leads gotten like \t".($this->count - $total_like_profile);
        echo "\ntotal like expected profiles\t".$total_profile;
        echo "\n total profile like \t".$total_like_profile;
        echo "\n total profiles to be expected for like \t".$total_profile_count."\n";
        $abc = array_diff($this->totalLike,$this->like);
        echo "like array\n";
        print_r($this->like);
        echo "\nno like\n";
        print_r($abc);

        echo "\ntotal like lead\t".$this->likeLead;
        echo "\ntotal like Profile\t".$this->likeProfile;


        echo "\n\n";
        echo "leads who received no like\t";
        print_r($noLikeReceivedLead);

        echo "\n\n";
        echo "profiles who received no like\t";
        print_r($noLikeReceivedProfile);

        $execution_type = "";
        if($condition_status->status == 1)
            $execution_type = "Manual";
        else
            $execution_type = "Automatic";

        $update_status = SendVLCondition::find(1);
        $update_status->status = 0;
        $update_status->save();

        VLLogs::create([
            'count' => $this->count,
            'execution_type' => $execution_type,
            'created_at' => Carbon::now()->addSeconds(19800),
            'updated_at' => Carbon::now()->addSeconds(19800),
        ]);
        dd($this->count);


    }

    public function markVirtualSend($user_id,$findProfileId,$is_lead){
        $compatibilitySI = Compatibility::where('user_id',$findProfileId)->first();
        echo "markVirtualSend\n";
        echo $findProfileId;
        if($compatibilitySI){
            $proStatus = $compatibilitySI->virtual_send;

            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null')? [] : json_decode($proStatus);
            if(sizeof($proStatus)>=1){
                $elem = array();
                $elem['user_id'] = $user_id;
                $elem['is_lead'] = $is_lead;
                $elem['timestamp'] = time()+19800;
                array_splice($proStatus,sizeof($proStatus)-1,0,[$elem]);
                $proStatus = json_encode($proStatus);

                Compatibility::where('user_id', $findProfileId)->update(['virtual_send' => $proStatus,
                    'virtual_send_count' => DB::raw('virtual_send_count+1')
                    ]);

            }
            //this happens when profile status of shortlisted user is null
            else{
                $elem = array();
                $elem['user_id'] = $user_id;
                $elem['is_lead'] = $is_lead;
                $elem['timestamp'] = time()+19800;
                array_push($proStatus,$elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);

                Compatibility::where('user_id', $findProfileId)->update(['virtual_send' => $proStatus,
                        'virtual_send_count' => DB::raw('virtual_send_count+1')
                    ]);

            }
        }
    }

    public function markVirtualReceive($user_id,$findProfileId,$is_lead){
        if($is_lead == 0){
            $compatibilitySI = Compatibility::where('user_id',$user_id)->first();
        }

        echo "markVirtualReceive\n";
        echo $user_id;
        if($compatibilitySI){
            $proStatus = $compatibilitySI->virtual_receive;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null')? [] : json_decode($proStatus);
            if(sizeof($proStatus)>=1){
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['timestamp'] = time()+19800;
                array_splice($proStatus,sizeof($proStatus)-1,0,[$elem]);
                $proStatus = json_encode($proStatus);
                if($is_lead == 0){
                    Compatibility::where('user_id', $user_id)->update(['virtual_receive' => $proStatus,
                        'virtual_receive_count' => DB::raw('virtual_receive_count+1')
                        ]);
                }
            }
            //this happens when profile status of shortlisted user is null
            else{
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['timestamp'] = time()+19800;
                array_push($proStatus,$elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);
                if($is_lead == 0){
                    Compatibility::where('user_id', $user_id)->update(['virtual_receive' => $proStatus,
                                        'virtual_receive_count' => DB::raw('virtual_receive_count+1')
                                    ]);
                }
            }
        }
        if($compatibilitySI){
            $proStatus = $compatibilitySI->profile_status;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null')? [] : json_decode($proStatus);
            if(sizeof($proStatus)>=1){
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['status'] = 'SI';
                $elem['timestamp'] = time()+19800;
                array_splice($proStatus,sizeof($proStatus)-1,0,[$elem]);
                $proStatus = json_encode($proStatus);
                if($is_lead == 0){
                Compatibility::where('user_id', $user_id)->update(['profile_status' => $proStatus,
                    'shown_interest_count' => DB::raw('shown_interest_count+1')
                    ]);
                }
            }
            //this happens when profile status of shortlisted user is null
            else{
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['status'] = 'SI';
                $elem['timestamp'] = time()+19800;
                array_push($proStatus,$elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);
                if($is_lead == 0){
                    Compatibility::where('user_id', $user_id)->update(['profile_status' => $proStatus,
                            'shown_interest_count' => DB::raw('shown_interest_count+1')
                        ]);
                }
            }
            $siStatus = $compatibilitySI->si_status;
            $siStatus = ($siStatus == null || $siStatus == '' || $proStatus == 'null')? [] : json_decode($siStatus);
            $this->markSiStatus($siStatus,$user_id,$findProfileId,$is_lead);
        }
    }

    public function markSiStatus($proStatus,$user_id,$findProfileId,$is_lead){
        if(sizeof($proStatus)>=1){
            $elem = array();
            $elem['user_id'] = $findProfileId;
            $elem['timestamp'] = time()+19800;
            array_splice($proStatus,sizeof($proStatus)-1,0,[$elem]);
            $proStatus = json_encode($proStatus);
            if($is_lead == 0){
                Compatibility::where('user_id', $user_id)->update(['si_status' => $proStatus
                    ]);
            }

        }
        //this happens when profile status of shortlisted user is null
        else{
            $elem = array();
            $elem['user_id'] = $findProfileId;
            $elem['timestamp'] = time()+19800;
            array_push($proStatus,$elem);
            $proStatus = json_encode($proStatus);
            // print_r($proStatus);
            if($is_lead == 0){
                Compatibility::where('user_id', $user_id)->update(['si_status' => $proStatus
                    ]);
            }
        }
    }
    public function getImplodedCaste($caste){
        $findProfilecaste_list = array();
        if (strpos($caste,'Brahmin-All') !== False) {
            $findProfilecaste_list = DB::table('caste_mappings')->where('caste', 'like','%Brahmin%')->pluck('caste')->toArray();
            array_push($findProfilecaste_list, "Bhardwaj");
            foreach($findProfilecaste_list as $key => $value)
                if(empty($value))
                    unset($findProfilecaste_list[$key]);
            $preference_castes = explode(',', $caste);
            foreach($preference_castes as $preference_caste){
                array_push($findProfilecaste_list, $preference_caste);
                if($preference_caste == 'All'){
                    $findProfilecaste_list = array();
                    array_push($findProfilecaste_list, 'All');
                    break;
                }
            }
            $findProfilecaste_list = array_unique($findProfilecaste_list);
            return $findProfilecaste_list;
        }
        else{
            $findProfilecaste_list = explode(',', $caste);
            foreach($findProfilecaste_list as $key => $value)
                if(empty($value))
                    unset($findProfilecaste_list[$key]);

            // Display the array elements
            $a=[];
            foreach($findProfilecaste_list as $key => $value) {
              array_push($a, $value);
              if($value == 'All'){
                    $a = array();
                    array_push($a, 'All');
                    break;
                }
            }
            $findProfilecaste_list = $a;
            return $findProfilecaste_list;
        }
    }

    public function sendMatch($profile,$is_lead,$marital_status,$manglik,$food_choice,$dataAccounts,$twoMonthOldDate,$notUseLastSeen = 0){
        if($is_lead == 0){
            $compatibility = Compatibility::where('user_id', $profile->id)->first();
        }
        $sent_profiles_id = [];
        //getting profile_status id
        if ($compatibility){
            $sent_profiles = json_decode($compatibility->profile_status);
            if($sent_profiles!=null && sizeof($sent_profiles) > 0) {
                $sent_profiles_id = array_column($sent_profiles, 'user_id');
            }
        }

        //extracting discover profiels id to not include in compatibility
        $discoverProfiles_id =  [];
        if($compatibility && $compatibility->discoverCompatibility){
            $discover = json_decode($compatibility->discoverCompatibility);
            if($discover!=null && sizeof($discover) > 0) {
                $discoverProfiles_id = array_column($discover, 'user_id');
            }
        }

        $caste_list = array();
        if ($profile->caste != null && $profile->caste != 'null' && strpos($profile->caste,'Brahmin-All') !== False) {
            $caste_list = DB::table('caste_mappings')->where('caste', 'like','%Brahmin%')->pluck('caste')->toArray();
            array_push($caste_list, "Bhardwaj");
            foreach($caste_list as $key => $value)
                if(empty($value))
                    unset($caste_list[$key]);
            $preference_castes = explode(',', $profile->caste);
            foreach($preference_castes as $preference_caste){
                echo $preference_caste."\t";
                if($preference_caste == 'All'){
                    $caste_list = array();
                    array_push($caste_list, 'All');
                    break;
                }
            }
            $caste_list = array_unique($caste_list);
        }
        // dd($caste_list);

        if($profile->gender == 'Male'){
            $query = DB::table('profiles')
                ->join('families', 'families.id', '=', 'profiles.id')
                ->join('preferences', function($join) {
                    $join->on('preferences.identity_number', '=', 'profiles.identity_number');
                    $join->on('preferences.temple_id', '=', 'profiles.temple_id');
                })
                ->join('compatibilities','compatibilities.user_id','profiles.id')
                ->select('profiles.id as id','preferences.caste','preferences.age_min','preferences.age_max','preferences.height_max','preferences.height_min','preferences.income_min','preferences.income_max','preferences.food_choice','preferences.manglik','preferences.marital_status','preferences.religion','profiles.birth_date','profiles.height','profiles.monthly_income','profiles.food_choice','profiles.marital_status','profiles.manglik','families.caste','profiles.name','profiles.identity_number','profiles.temple_id')

                 ->whereNotIn('profiles.temple_id',$dataAccounts)
                ->where('profiles.marital_status','!=','Married')
                 ->where('profiles.gender', '!=', $profile->gender)
                 ->where('profiles.is_approved', 1)
                 ->where('profiles.is_deleted',0);
                if($notUseLastSeen == 0){
                    $query->where('shortlist_count','>','1')->where('profiles.last_seen','>',$twoMonthOldDate)->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike','ASC');
                }
                else{
                    $query->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike','ASC');
                }
            }
        else {
            $query = DB::table('profiles')
                ->join('families', 'families.id', '=', 'profiles.id')
                ->join('preferences', function($join) {
                    $join->on('preferences.identity_number', '=', 'profiles.identity_number');
                    $join->on('preferences.temple_id', '=', 'profiles.temple_id');
                })
                ->join('compatibilities','compatibilities.user_id','profiles.id')
                ->select('profiles.id as id','preferences.caste','preferences.age_min','preferences.age_max','preferences.height_max','preferences.height_min','preferences.income_min','preferences.income_max','preferences.food_choice','preferences.manglik','preferences.marital_status','preferences.religion','profiles.birth_date','profiles.height','profiles.monthly_income','profiles.food_choice','profiles.marital_status','profiles.manglik','families.caste','profiles.name','profiles.identity_number','profiles.temple_id')
                ->whereNotIn('profiles.temple_id',$dataAccounts)
                ->where('profiles.marital_status','!=','Married')
                ->where('profiles.gender', '!=', $profile->gender)
                ->where('profiles.is_approved', 1)
                ->where('profiles.is_deleted',0);
            //    ->whereNotIn('profiles.id', $sent_profiles_id)
            //    ->whereNotIn('profiles.id', $discoverProfiles_id)
        //        ->where('shortlist_count','>','1')
            //    ->where('virtual_send_count','<=',10)

             //   ->where('profiles.education_score',1)

        //        ->where('profiles.last_seen','>',$twoMonthOldDate)
        //        ->orderBy('virtual_send_count', 'ASC')
        //        ->orderBy('virtualToLike','ASC');

                if($notUseLastSeen == 0){
                    $query->where('shortlist_count','>','1')->where('profiles.last_seen','>',$twoMonthOldDate)->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike','ASC');
                }
                else{
                    $query->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike','ASC');
                }
            }

            //caste filter
            //caste filter
            //dd($caste_list);
            if ($caste_list != [] && !($caste_list[0] == '' || $caste_list[0] == "Doesn't Matter" || $caste_list[0] == 'All')) {
                $query = $query->whereIN('families.caste', $caste_list);
            }
            if($caste_list == 'All'){
                echo "\n all caste of profiles \t".$profile->id;
            }

            //matching the preference of profile(boy) with findingProfile(girl)
        //age
        try{
            if ($profile->age_min && $profile->age_max) {
                $min_age = Carbon::today()->subYears($profile->age_min)->format('Y-m-d');
                $max_age = Carbon::today()->subYears($profile->age_max+1)->endOfDay()->format('Y-m-d');
            }
            $query->whereBetween('profiles.birth_date', [$max_age, $min_age]);
            echo "\n age \t ".$query->count();
        }
        catch(\Exception $e){}

        //height
        try{
            if ($profile->height_min && $profile->height_max) {
                $min_height = $profile->height_min;
                $max_height = $profile->height_max;
            }
            $query = $query->whereBetween('profiles.height', [$min_height, $max_height]);

            echo "\n height \t ".$query->count();
        }
        catch(\Exception $e){}

        //income
        //income range
        try{
            if($profile->income_min != null && $profile->income_max != null) {
                if($is_lead == 0){
                    $min_income = $profile->income_min;
                    $max_income = $profile->income_max;
                }
                else{
                    $min_income = $profile->income_min*100000;
                    $max_income = $profile->income_max*100000;
                }
                echo "\nmin+income \t".$min_income;
                echo "\nmax+income \t".$max_income;
                $query = $query->whereBetween('profiles.monthly_income', [$min_income, $max_income]);
            }

            echo "\n income \t ".$query->count();
        }
        catch(\Exception $e){}

    /*
        //food choices filter
        if($profile->food_choice != null && $profile->food_choice != '' && $profile->food_choice == "Vegetarian")
        {
            $query = $query->whereIn('profiles.food_choice', $food_choice[$profile->food_choice]);
        }
    */


      //  echo "\n food_choice \t ".$query->count();
        //marital_status


        if ($profile->marital_status != 'Never Married') {
            if(isset($marital_status[$profile->marital_status]))
                $query = $query->whereIn('profiles.marital_status', $marital_status[$profile->marital_status]);
            else
            {
                echo "\n marital status array not found - assuming never married";
                $query = $query->where('profiles.marital_status','=','Never Married');
            }
        }
        else{
            $query = $query->where('profiles.marital_status','=','Never Married');
        }
        //dd($query->count());
/*
        echo "\n marital_status \t ".$query->count();
        //manglik
        if ($profile->manglik != null && $profile->manglik != "Doesn't Matter") {
            $query = $query->whereIn('profiles.manglik', $manglik[$profile->manglik]);
        }
*/
//        echo "\n manglik \t ".$query->count();
       //matching the preference of findingProfile(girl) with profile(boy)
        $query = $query->get();
      //  dd(sizeof($query));
        $findProfilecaste_list = array();
        $userPreferenceCast = array();
        $finalProfiles = array();

        if($profile->caste != null && $profile->caste != 'null'){
            $userPreferenceCast = $this->getImplodedCaste($profile->caste);
        }
        $caste_flag = 0;
        $totalLike = array_push($this->totalLike, $profile->id);
        if($is_lead == 1){
            $totalLikeExpectLead = array_push($this->totalLikeExpectLead, $profile->id);
        }
        else{
            $totalLikeExpectProfile = array_push($this->totalLikeExpectProfile, $profile->id);
        }
        $findProfileCount = 0;
        // if($notUseLastSeen == 1){
        //     dd($profile->id);
        // }
     //   dd(sizeof($query));
        foreach($query as $findProfile){
           // if($findProfile->caste == 'All'){
           //     dd($findProfile->caste);
            //}
            $preferenceCaste = $this->getPreferenceCaste($findProfile->identity_number,$findProfile->temple_id);
            // dd($preferenceCaste);
            $findProfile->caste = $preferenceCaste;
            $flag = 0;//to check the condition remaining true till last filter
            //USER DETAILS
            //birth_date
            //height
            //monthly income
            //caste
            if($is_lead == 0){
                $profile = Profile::join('families','families.id','profiles.id')->where('profiles.id',$profile->id)->select('profiles.*','families.caste','families.religion','families.family_income')->first();
            }


            try{
                if($findProfile->age_min && $findProfile->age_max && $profile->birth_date){
                    $user_age = app('App\Http\Controllers\WebhookController')->calculateAge($profile->birth_date);

                    if($user_age >= $findProfile->age_min && $user_age <= $findProfile->age_max){
                        $flag =1;
                    }
                    else{
                        if($is_lead ==1)
                            echo "\nlead age find";
                        $flag =0;
                     //   continue;
                    }
                }
                else{
                  //  dd("33333");
                    echo "\n".$findProfile->id;
                    echo "\nfsfsfs";
                    $flag =0;
                }
            }
            catch(\Exception $e){
               // dd($e->getMessage());
                //echo "erro\n";
               // dd($profile->id);
            }

            //height
            if($flag =1 && $findProfile->height_min && $findProfile->height_max && $profile->height){
                $user_height = $profile->height;
                if($user_height >= $findProfile->height_min && $user_height <= $findProfile->height_max){
                    $flag =1;
                    echo "\n522\t userHeight \t".$user_height;
                    echo "\n522\t height_min \t".$findProfile->height_min;
                    echo "\n522\t height_max \t".$findProfile->height_max;

                }
                else{
                    $flag =0;
                  //  continue;
                }
            }
            else{
                $flag =0;
             //   continue;
            }



            //income
            try{
                $check_flag =0;
                if($flag ==1 && $is_lead == 0){
                    $user_income = $profile->monthly_income*100000;
                    $family_income = $profile->family_income;
                 //   dd($family_income);
                    echo "\nuser min\t".$user_income;
                    echo "\n family incomee".$family_income;
                      echo "\nincome min\t".$findProfile->income_min;
                        echo "\nincome max\t".$findProfile->income_max."\n";
                    if(($user_income >= $findProfile->income_min && $user_income <= $findProfile->income_max) || ($family_income >= $findProfile->income_min && $family_income <= $findProfile->income_max)){
                        $flag =1;
                        echo "\n inside monthly income if ";
                    }
                    else{
                        echo "\n\n inside monthly income else";
                        $flag =0;
                      //  continue;
                    }
                }

                else if($flag ==1 && $is_lead == 1){
                     $user_income = $profile->annual_income*100000;
                     $family_income = $profile->family_income;
                    echo "\nuser income\t".$profile->annual_income*100000;
                    echo "\n family income".$profile->family_income;
                      echo "\nincome min\t".$findProfile->income_min;
                        echo "\nincome max\t".$findProfile->income_max;
                    if(($user_income >= $findProfile->income_min && $user_income <= $findProfile->income_max)|| ($family_income >= $findProfile->income_min && $family_income <= $findProfile->income_max)){
                        $flag =1;
                        echo "\n\n inside annual_income income if\n";
                    }
                    else{
                        echo "\n\n inside annual income else";
                        $flag =0;
                        $check_flag = 1;
                     //   continue;
                    }
                }
                else{
                    if($check_flag == 1){
                       // dd("check mate");
                    }
                    echo "\n user_id of user\t".$profile->id;
                    echo "\n inside else of monthly & annual income if";
                    if($is_lead == 1){

                        if($flag == 1){
                            echo "\n inside flag which has value 1";
                        }
                        if($findProfile->income_max != null){
                            echo "\n inside preference income max which has not null(pure null) value";
                        }
                        if($flag ==1 && $is_lead == 1 && ($profile->annual_income <= '0' || ($profile->annual_income != 'null' && $profile->annual_income != '' && $profile->annual_income != 'NaN'))){
                            echo "\npure null has proble, in of new if of income";
                        }


                        echo "\n khud ki income \t".$profile->annual_income;
                        echo "\n flag\t".$flag;
                        echo "\n opp. preference ki minimum income \t".$findProfile->income_min;
                        echo "\n opp. preference ki maximum income \t".$findProfile->income_max;
                    }
                    $flag =0;
                 //   continue;
                }
            }
            catch(\Exception $e){
               // dd($e->getMessage());
               // dd($profile->annual_income);
             $flag = 1;
            }

            /*
            $user_marital_status = array();
            $findProfileMarital_status = array();
            //maritalStatus
            if($flag == 1 && $findProfile->marital_status){
                $user_marital_status = $marital_status[$profile->marital_status];
                $findProfileMarital_status = $marital_status[$findProfile->marital_status];
                if(array_intersect($user_marital_status, $findProfileMarital_status)){
                    $flag= 1;
                    echo "\n inside marital status if\n";
                }
                else{
                    echo "\n inside marital status else\n";
                    $flag =0;
                 //   continue;
                }
            }
            else{
                echo "\nflag\t".$flag;
                echo "\n findProfileId\t".$findProfile->id;
                echo "\n else of marital_status";
                echo "\n khud ka marital_status\t";
                print_r($user_marital_status);
                echo "\n preference_caste ka marital_status\t";
                print_r($findProfileMarital_status);
                $flag =0;
               // continue;
            }
            */


/*
            //foodchoice
            if($profile->food_choice == null || $profile->food_choice == 'null' && $profile->food_choice == ''){
                $profile->food_choice = 'Doesn\'t Matter';
            }
            try{
                if($flag ==1 && $findProfile->food_choice && $findProfile->food_choice != 'null' && $findProfile->food_choice != ''){
                    $user_food_choice = $food_choice[$profile->food_choice];
                    $findProfileFoodChoice = $food_choice[$findProfile->food_choice];
                    if(array_intersect($user_food_choice, $findProfileFoodChoice)){
                        $flag= 1;
                    echo "\n inside food choice if\n";

                    }
                    else{
                    echo "\n inside food choice else\n";
                        $flag =0;
                    //    continue;
                    }
                }
                else{
                    echo "\n else food choice ka";
                    echo "\n user ka food choice\t".$profile->food_choice;
                    echo "\n preference ka food choice \t";
                    print_r($findProfileFoodChoice);
                    $flag =0;
                  //  continue;
                }
            }
            catch(\Exception $e){ $flag =0; continue;}
*/

            /*
            //manglik
            if($flag ==1 && $findProfile->manglik && $findProfile->manglik != 'Doesn\'t Matter' && $profile->manglik != ''){
                $user_manglik = $manglik[$profile->manglik];
                $findProfileManglik = $manglik[$findProfile->manglik];
                if(array_intersect($user_manglik, $findProfileManglik)){
                    $flag= 1;
                    echo "\n inside manglik if\n";
                    echo "242\n";
                }
                else{
                    echo "\n inside manglik else\n";
                    $flag = 0;
                }
            }
            else{
                    echo "\n flag \t".$flag;
                    echo "\n findprofile manglik \t".$findProfile->manglik;
                    echo "\n profile manglik \t".$profile->manglik;
                    echo "\n inside end else of manglik else\n";
            }
            */


            //regligion
            if($flag == 1 && $findProfile->religion && $profile->religion){
                $religion_list = explode(',', $findProfile->religion);
                if(in_array($profile->religion, $religion_list)){
                    $flag = 1;
                    echo "\inside religion if\n";
                }
                else{

                    echo "\n religion \t ".$profile->religion;
                    echo "\n findReligion \t". $profile->religion;
                    echo "\inside religion else\n";
                    $flag = 0;
                //    continue;
                }
            }

            //caste
            $findProfilecaste_list = array();
            if($flag ==1 && $findProfile->caste != null && $findProfile->caste != 'null' && $profile->caste && $findProfile->caste != 'All'){
                $findProfilecaste_list = $this->getImplodedCaste($findProfile->caste);
            }
            else{
                $userPrefCaste = null;
                if($is_lead == 0){
                    $prof = Profile::where('id',$profile->id)->first();
                    $prefCaste = Preference::where('identity_number',$prof->identity_number)->where('temple_id',$prof->temple_id)->first();
                    $userPrefCaste = $prefCaste->caste;
                }

                if($flag == 1 && ($findProfile->caste == 'All' || $findProfile->caste == null || $findProfile->caste == 'null') && ($profile->caste == 'All' || $userPrefCaste == 'All')){
                    $flag = 1;
                }
                else{
                    echo "\n implode caste ka else h jha flag 0 krte because hame flag==1 ho caste null ho to preference caste \t".$findProfile->caste;
                    echo "\n user ki id\t".$profile->id;
                    echo "\n find profile ki id \t".$findProfile->id;
                    echo "\n or profile caste h \t".$profile->caste;
                    echo "\n else of the implode caste";
                    $flag =0;
                }
            }


            /*
                $caste_list != [] && !($caste_list[0] == '' || $caste_list[0] != "Doesn't Matter" || $caste_list[0] != 'All')
            */
            if($flag == 1 && $profile->caste && $findProfilecaste_list != [] && $findProfilecaste_list != '' && $findProfilecaste_list[0] != "Doesn't Matter" && $findProfilecaste_list[0] != 'All'){
                if(in_array($profile->caste, $findProfilecaste_list)){
                    $flag = 1;
                    echo "\n inside caste if";
                }
                else{
                    if($profile->caste == 'All' && $caste_flag == 0){
                        echo "\nall caste count \t".$this->allCasteCount++;
                        echo "\n user id of the caste".$profile->id;
                        $caste_flag = 1;
                        $flag = 0;

                    }
                    else{
                        echo "\n inside caste else";
                        echo "\n findProfile caste ki id \t".$findProfile->id;
                        echo ("\n findProfilecaste \t ");
                        print_r($findProfilecaste_list);
                        echo "\nfindProfile preference caste \t".$findProfile->caste;
                        echo "\n profileIDInCaste\t".$findProfile->id;
                    echo "\n profileCaste \t". $profile->caste;
                    echo "\n 597";
                        echo "260";
                        $flag = 0;
                    }

                //    continue;
                }
            }
            else{

                echo "\n imploded caste, else of the all
                \t";
                print_r($findProfilecaste_list);
                echo "\n";
                echo "proifleCaste".$profile->caste;
            }
            //last name filter
            $profileLast = explode(" ", $profile->name);
            $profileLastName = $profileLast[sizeof($profileLast)-1];

            $preferenceLast = explode(" ", $findProfile->name);
            $preferenceLastName = $preferenceLast[sizeof($preferenceLast)-1];

            if($profileLast == $preferenceLast){
                $flag =0;
                echo "\n inside last name if";
             //   continue;
            }

            if($flag == 1){
                if($notUseLastSeen == 1){
                 //   dd($profile->id);
                }
                array_push($this->like, $profile->id);
                if($is_lead == 1){
                    $this->likeLead++;
                }
                else{
                    $this->likeProfile++;
                }

                if($is_lead == 1){
                    $likeGottenLead = array_push($this->likeGottenLead, $profile->id);
                }
                else{
                    $likeGottenProfile = array_push($this->likeGottenProfile, $profile->id);
                }

                $this->markVirtualSend($profile->id,$findProfile->id,$is_lead);
                $this->markVirtualReceive($profile->id,$findProfile->id,$is_lead);
                app('App\Http\Controllers\WebhookController')->sendAllLikeNotification($findProfile->id,$profile->id,0,$is_lead,1);
                echo "\nfindID\t". $findProfile->id;
                echo "\n profileID\t".$profile->id;
                echo "\n counttttttttttttttttttttttttttttt \t".$this->count++;
                break;
            }
            else{
                //dd($profile->id);
           //     array_push($this->abc, $profile->id);
            }
            $findProfileCount++;
            if($findProfileCount == sizeof($query) && $notUseLastSeen == 0){
                if($is_lead == 0){
                    $profile = Profile::where('profiles.id',$profile->id)
                        ->join('families','families.id','profiles.id')
                        ->join('preferences', function($join) {
                            $join->on('preferences.identity_number', '=', 'profiles.identity_number');
                            $join->on('preferences.temple_id', '=', 'profiles.temple_id');
                        })
                    //    ->where('preferences.amount_collected',0)
                        ->select('profiles.id','profiles.gender','preferences.age_min','preferences.age_max','preferences.height_min','preferences.height_max','preferences.income_min','preferences.income_max','preferences.caste','profiles.marital_status','preferences.food_choice','preferences.religion','profiles.manglik','profiles.birth_date','profiles.monthly_income','profiles.height','profiles.name','families.mobile as mobile','profiles.education_score')->first();
                }
                if($is_lead == 1){
                    $profile = DB::table('leads')->where('leads.id',$profile->id)
                        ->join('leadCompatibilities','leadCompatibilities.user_id','leads.id')
                        ->join('lead_preference','lead_preference.lead_id','leads.id')
                        ->join('free_users','free_users.lead_id','leads.id')->select('leads.id','free_users.gender','lead_preference.age_min','lead_preference.age_max','lead_preference.height_min','lead_preference.height_max','lead_preference.income_min','lead_preference.income_max','lead_preference.caste','lead_preference.marital_status','lead_preference.food_choice','lead_preference.religion','free_users.manglik','free_users.birth_date','free_users.annual_income','free_users.height','leads.name','leads.mobile as mobile','free_users.education_score','leads.created_at as created_at','leadCompatibilities.shown_interest_count')
                        ->first();
                }
                $this->sendMatch($profile,$is_lead,$marital_status,$manglik,$food_choice,$dataAccounts,$twoMonthOldDate,1);
                break;
            }
        }
    }
    public function getPreferenceCaste($identity_number,$temple_id){
        $preference = Preference::where('identity_number',$identity_number)->where('temple_id',$temple_id)->first();
        $caste = 'All';
        if($preference && $preference->caste){
            $caste = $preference->caste;
        }
        return $caste;
    }
}
