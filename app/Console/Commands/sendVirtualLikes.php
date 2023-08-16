<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCompatblity as Compatibility;
use App\Models\Profile;
use App\Models\UserPreference as Preference;
use App\Models\User;
use App\Models\VLLogs;
use Carbon\Carbon;
use App\Models\SendVLCondition;
use App\Models\notReceivedVirtualLike;
use Illuminate\Support\Facades\DB;

class sendVirtualLikes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:virtualLike';
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
     *   description="
     business logic -> send a virtual like to the users who came two hours before and previous day, this will help in retention
     cron frequency/timing -> in every two hours

    table used => user_data,families,userPreferences,free_users,lead_family,lead_preference,compatibilites,leaduserCompatibilities
    variable used => $query -> this will have all the user_data which are coming  in the user userPreferences.
            twoMonthOldDate-> last 1 year old date, and this will used in $query, to take only a year old last seen profile


    code logic =>
         fetch all the users from user_data table who has gotten no like in either last 2 hours or prev day.
         find all the matched_user_data according to the userPreferences of the user.
         Now in the matched_user_data
            check if the user lies in the userPreferences of the matched_profile
                if lies send that profile as virtual like, set the value as SI in profile_status to the user profile, and add the matched_profile id in virtual_receive, increment the virtual_receive_count by 1
                filter used(caste,religion,age,height,income,lasName)
            if user userPreferences is not in matche_user_data then
                call the sendMatch() function again and add notUseLastSeen value as 1, which will remove the filter of last seen filter in $query.

                repeat all the proces again


      ",
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
            "Widow/Widower" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Married" => array(),
            "Other" => array(),
            "Awaiting Divorce" => ['Divorced', 'Divorcee', 'Awaiting Divorce', 'Widow', 'Widowed'],
            "Anulled" => ['Divorced', 'Divorcee', 'Anulled'],
            "Divorcee & Widowed" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
        );

        $manglik = array(
            "Manglik" => ['Manglik', 'Anshik Manglik', 'manglik'],
            "manglik" => ['Manglik', 'Anshik Manglik', 'manglik'],
            "No" => ['No', 'Anshik Manglik', 'Non-Manglik'],
            "Non-Manglik" => ['No', 'Anshik Manglik', 'Non-Manglik'],
            "Non-manglik" => ['No', 'Anshik Manglik', 'Non-Manglik'],
            "Anshik Manglik" => ['No', 'Anshik Manglik', 'Manglik', 'Non-Manglik', null, 'manglik'],
            "Anshik manglik" => ['No', 'Anshik Manglik', 'Manglik', 'Non-Manglik', null, 'manglik'],
        );

        $food_choice = array(
            "Vegetarian" => ['Vegetarian', 'Vegeterian'],
            "Vegeterian" => ['Vegetarian', 'Vegeterian'],
            "Non-Vegeterian" => ['Non-V egeterian', 'Non-Vegetarian', 'Vegetarian', 'Vegeterian'],
            "Non-Vegetarian" => ['Non-Vegeterian', 'Non-Vegetarian', 'Vegetarian', 'Vegeterian', 'Doesn\'t matter'],
            "Doesn't Matter" => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
            "undefined" => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
            "Doesn't matter" => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
            "Not Working" => ['Not Working'],
            'Doesn\'t matter' => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
            'null' => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
            '' => ['Vegetarian', 'Vegeterian', 'Non-Vegeterian', 'Non-Vegetarian', 'Doesn\'t matter'],
        );
        $lastFiveDayDate = date('Y-m-d', strtotime("-5 days"));
        $lastFiveDayDate = $lastFiveDayDate . ' 00:00:00';
        $twoMonthOldDate = date('Y-m-d', strtotime("-365 days"));
        $twoMonthOldDate = $twoMonthOldDate . ' 00:00:00';
        $prevDay = date('Y-m-d', strtotime("-1 days"));
        $lastThreeDay = date('Y-m-d', strtotime("-2 days"));
        $dataAccounts = User::where('data_account', 1)->pluck('temple_id')->toArray();

        $condition_status = SendVLCondition::first();

        //manual work flow
        if ($condition_status->status == 1) {
            $user_data = Compatibility::where('shown_interest_count', '>=', 0)->get()->pluck('user_data_id');

            $user_data = Profile::whereIn('user_data.id', $user_data)
                ->where('user_data.last_seen', '>', Carbon::now()->subDays(15))
                ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
                ->where('userPreferences.amount_collected', 0)
                ->select('user_data.id', 'user_data.gender', 'userPreferences.age_min', 'userPreferences.age_max', 'userPreferences.height_min', 'userPreferences.height_max', 'userPreferences.income_min', 'userPreferences.income_max', 'userPreferences.caste', 'user_data.marital_status', 'userPreferences.food_choice', 'userPreferences.religion', 'user_data.manglik', 'user_data.birth_date', 'user_data.monthly_income', 'user_data.height', 'user_data.name', 'user_mobile as mobile', 'user_data.education_score', 'user_data.lead_value')->orderBy('user_data.created_at', 'desc')->get();
        } else    //usual workflow repeated at 2 hours interval
        {
            $user_data = Compatibility::where('shown_interest_count', '<', 2)
                ->where('virtual_receive_count', '<', 2)
                ->get()
                ->pluck('user_id');
            $user_data = Profile::whereIn('user_data.id', $user_data)
                ->where('user_data.lead_created_at', $prevDay)
                ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
                ->where('userPreferences.amount_collected', 0)
                ->select('user_data.id', 'user_data.gender', 'userPreferences.age_min', 'userPreferences.age_max', 'userPreferences.height_min', 'userPreferences.height_max', 'userPreferences.income_min', 'userPreferences.income_max', 'userPreferences.caste', 'user_data.marital_status', 'userPreferences.food_choice', 'userPreferences.religion', 'user_data.manglik', 'user_data.birth_date', 'user_data.monthly_income', 'user_data.height', 'user_data.name', 'user_mobile as mobile', 'user_data.education_score', 'user_data.lead_value')->orderBy('user_data.created_at', 'desc')->get();
        }

        echo "\ncount of user_data " . sizeof($user_data) . "\n";
        $total_profile_count = sizeof($user_data);
        foreach ($user_data as $profile) {
            if (!in_array($profile->mobile, $mobile_arr)) {
                array_push($mobile_arr, $profile->mobile);
                $this->sendMatch($profile, 0, $marital_status, $manglik, $food_choice, $dataAccounts, $twoMonthOldDate, $condition_status->send_message);
            }
        }

        $total_like_profile = $this->count; //using this for final count else no use in logic
        $prevDay = date('Y-m-d H:i:s', strtotime("-1 days"));
        $lastTwoHoursPrev = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($prevDay)));
        $lastTwoHoursToday = date('Y-m-d H:i:s', strtotime('-2 hour'));
        $lastFourHoursToday = date('Y-m-d H:i:s', strtotime('-4 hour'));

        //manual work flow
        $update_lead_compatblity = DB::table('leaduserCompatibilities')->where('compatibility', '{"user_data_id":null}')->update([
            'compatibility'  =>      ''
        ]);


        $total_profile_count = 0;
        $total_profile = $total_profile_count;
        $total_profile_count = $total_profile_count + sizeof($user_data);

        $countVirtual = 0; //using this for final count else no use in logic
        echo "\n" . $total_profile_count . "Total Lead count \n";
        $virtual_count1 = 0; //using this for final count else no use in logic
        $virtual_count2 = 0; //using this for final count else no use in logic
        $total_leads = 0; //using this for final count else no use in logic

        foreach ($user_data as $profile) {
            if (!in_array($profile->mobile, $mobile_arr)) {
                array_push($mobile_arr, $profile->mobile);
                $countVirtual++;
                $total_leads++;
                $this->sendMatch($profile, 1, $marital_status, $manglik, $food_choice, $dataAccounts, $twoMonthOldDate, $condition_status->send_message);
            }
        }
        //using this for final count else no use in logic
        $temp1ExpecLead = array_unique($this->totalLikeExpectLead);
        $temp2ExpecProfile = array_unique($this->totalLikeExpectProfile);
        $temp3LikeGotLead = array_unique($this->likeGottenLead);
        $temp4LikeGotProfile = array_unique($this->likeGottenProfile);

        $noLikeReceivedLead = array_diff($temp1ExpecLead, $temp3LikeGotLead);
        $noLikeReceivedProfile = array_diff($temp2ExpecProfile, $temp4LikeGotProfile);

        foreach ($noLikeReceivedProfile as $noProfileId) {
            $temp_check = notReceivedVirtualLike::where('is_lead', 0)->where('user_id', $noProfileId)->first();
            if (!$temp_check) {
                notReceivedVirtualLike::create([
                    'is_lead' => 0,
                    'user_id' => $noProfileId,
                    'last_attempted' => date("Y-m-d H:i:s", time() + 19800)
                ]);
            } else {
                $temp_check->last_attempted = date("Y-m-d H:i:s", time() + 19800);
                $temp_check->save();
            }
        }

        foreach ($noLikeReceivedLead as $noLeadId) {
            $temp_check = notReceivedVirtualLike::where('is_lead', 1)->where('user_id', $noLeadId)->first();
            if (!$temp_check) {
                notReceivedVirtualLike::create([
                    'is_lead' => 1,
                    'user_id' => $noLeadId,
                    'last_attempted' => date("Y-m-d H:i:s", time() + 19800)
                ]);
            } else {
                $temp_check->last_attempted = date("Y-m-d H:i:s", time() + 19800);
                $temp_check->save();
            }
        }


        echo "\n first if \t" . $virtual_count1;
        echo "\n second if \t" . $virtual_count2;

        echo "\nall caste count \t" . $this->allCasteCount;
        echo "\n";
        echo "\ntotal like expected leads\t" . $total_leads;
        echo "\n tota; leads gotten like \t" . ($this->count - $total_like_profile);
        echo "\ntotal like expected user_data\t" . $total_profile;
        echo "\n total profile like \t" . $total_like_profile;
        echo "\n total user_data to be expected for like \t" . $total_profile_count . "\n";
        $abc = array_diff($this->totalLike, $this->like);
        echo "like array\n";
        //print_r($this->like);
        echo "\nno like\n";
        // print_r($abc);

        echo "\ntotal like lead\t" . $this->likeLead;
        echo "\ntotal like Profile\t" . $this->likeProfile;


        echo "\n\n";
        echo "leads who received no like\t";
        print_r($noLikeReceivedLead);

        echo "\n\n";
        echo "user_data who received no like\t";
        print_r($noLikeReceivedProfile);

        $execution_type = "";
        if ($condition_status->status == 1)
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
        //dd($this->count);
    }

    public function markVirtualSend($user_id, $findProfileId, $is_lead)
    {
        $compatibilitySI = Compatibility::where('user_data_id', $findProfileId)->first();
        echo "markVirtualSend\n";
        echo "\n" . $findProfileId . "\n";
        if ($compatibilitySI) {
            $proStatus = $compatibilitySI->virtual_send;
            if ($proStatus == 0 || $proStatus == null) {
                $proStatus = [];
            } else {
                $proStatus = json_decode($proStatus);
            }
            //$proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null') ? [] : json_decode($proStatus);
            if (count($proStatus) >= 1) {
                $elem = array();
                $elem['user_id'] = $user_id;
                $elem['is_lead'] = $is_lead;
                $elem['timestamp'] = time() + 19800;
                array_splice($proStatus, sizeof($proStatus) - 1, 0, [$elem]);
                $proStatus = json_encode($proStatus);

                Compatibility::where('user_data_id', $findProfileId)->update([
                    'virtual_send' => $proStatus,
                    'virtual_send_count' => DB::raw('virtual_send_count+1')
                ]);
            }
            //this happens when profile status of shortlisted user is null
            else {
                $elem = array();
                $elem['user_id'] = $user_id;
                $elem['is_lead'] = $is_lead;
                $elem['timestamp'] = time() + 19800;
                array_push($proStatus, $elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);

                Compatibility::where('user_data_id', $findProfileId)->update([
                    'virtual_send' => $proStatus,
                    'virtual_send_count' => DB::raw('virtual_send_count+1')
                ]);
                $proStatus = array(
                    'user_id'   =>  $user_id,
                    'is_lead'   =>  $is_lead,
                    'timestamp' =>  time() + 19800
                );
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);

                Compatibility::where('user_data_id', $findProfileId)->update([
                    'virtual_send' => $proStatus,
                    'virtual_send_count' => DB::raw('virtual_send_count+1')
                ]);
            }
        }
    }

    public function markVirtualReceive($user_id, $findProfileId, $is_lead)
    {
        $compatibilitySI = Compatibility::where('user_data_id', $user_id)->first();

        echo "markVirtualReceive\n";
        echo $user_id;
        if ($compatibilitySI) {
            $proStatus = $compatibilitySI->virtual_receive;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null') ? [] : json_decode($proStatus);
            if (sizeof($proStatus) >= 1) {
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['timestamp'] = time() + 19800;
                array_splice($proStatus, sizeof($proStatus) - 1, 0, [$elem]);
                $proStatus = json_encode($proStatus);
                if ($is_lead == 0) {
                    Compatibility::where('user_data_id', $user_id)->update([
                        'virtual_receive' => $proStatus,
                        'virtual_receive_count' => DB::raw('virtual_receive_count+1')
                    ]);
                }
            }
            //this happens when profile status of shortlisted user is null
            else {
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['timestamp'] = time() + 19800;
                array_push($proStatus, $elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);
                if ($is_lead == 0) {
                    Compatibility::where('user_data_id', $user_id)->update([
                        'virtual_receive' => $proStatus,
                        'virtual_receive_count' => DB::raw('virtual_receive_count+1')
                    ]);
                }
            }
        }
        if ($compatibilitySI) {
            $proStatus = $compatibilitySI->profile_status;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null') ? [] : json_decode($proStatus);
            if (sizeof($proStatus) >= 1) {
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['status'] = 'SI';
                $elem['timestamp'] = time() + 19800;
                array_splice($proStatus, sizeof($proStatus) - 1, 0, [$elem]);
                $proStatus = json_encode($proStatus);
                if ($is_lead == 0) {
                    Compatibility::where('user_data_id', $user_id)->update([
                        'profile_status' => $proStatus,
                        'shown_interest_count' => DB::raw('shown_interest_count+1')
                    ]);
                }
            }
            //this happens when profile status of shortlisted user is null
            else {
                $elem = array();
                $elem['user_id'] = $findProfileId;
                $elem['status'] = 'SI';
                $elem['timestamp'] = time() + 19800;
                array_push($proStatus, $elem);
                $proStatus = json_encode($proStatus);
                // print_r($proStatus);
                if ($is_lead == 0) {
                    Compatibility::where('user_data_id', $user_id)->update([
                        'profile_status' => $proStatus,
                        'shown_interest_count' => DB::raw('shown_interest_count+1')
                    ]);
                }
            }
            $siStatus = $compatibilitySI->si_status;
            $siStatus = ($siStatus == null || $siStatus == '' || $proStatus == 'null') ? [] : json_decode($siStatus);
            $this->markSiStatus($siStatus, $user_id, $findProfileId, $is_lead);
        }
    }

    public function markSiStatus($proStatus, $user_id, $findProfileId, $is_lead)
    {
        if (sizeof($proStatus) >= 1) {
            $elem = array();
            $elem['user_id'] = $findProfileId;
            $elem['timestamp'] = time() + 19800;
            array_splice($proStatus, sizeof($proStatus) - 1, 0, [$elem]);
            $proStatus = json_encode($proStatus);
            if ($is_lead == 0) {
                Compatibility::where('user_data_id', $user_id)->update([
                    'si_status' => $proStatus
                ]);
            }
        }
        //this happens when profile status of shortlisted user is null
        else {
            $elem = array();
            $elem['user_id'] = $findProfileId;
            $elem['timestamp'] = time() + 19800;
            array_push($proStatus, $elem);
            $proStatus = json_encode($proStatus);
            // print_r($proStatus);
            if ($is_lead == 0) {
                Compatibility::where('user_data_id', $user_id)->update([
                    'si_status' => $proStatus
                ]);
            }
        }
    }
    public function getImplodedCaste($caste)
    {
        $findProfilecaste_list = array();
        if (strpos($caste, 'Brahmin-All') !== False) {
            $findProfilecaste_list = DB::table('caste_mappings')->where('caste', 'like', '%Brahmin%')->pluck('caste')->toArray();
            array_push($findProfilecaste_list, "Bhardwaj");
            foreach ($findProfilecaste_list as $key => $value)
                if (empty($value))
                    unset($findProfilecaste_list[$key]);
            $preference_castes = explode(',', $caste);
            foreach ($preference_castes as $preference_caste) {
                array_push($findProfilecaste_list, $preference_caste);
                if ($preference_caste == 'All') {
                    $findProfilecaste_list = array();
                    array_push($findProfilecaste_list, 'All');
                    break;
                }
            }
            $findProfilecaste_list = array_unique($findProfilecaste_list);
            return $findProfilecaste_list;
        } else {

            $findProfilecaste_list = explode(',', $caste);
            foreach ($findProfilecaste_list as $key => $value)
                if (empty($value))
                    unset($findProfilecaste_list[$key]);

            // Display the array elements
            $a = [];
            foreach ($findProfilecaste_list as $key => $value) {
                array_push($a, $value);
                if ($value == 'All') {
                    $a = array();
                    array_push($a, 'All');
                    break;
                }
            }
            $findProfilecaste_list = $a;
            return $findProfilecaste_list;
        }
    }

    public function sendMatch($profile, $is_lead, $marital_status, $manglik, $food_choice, $dataAccounts, $twoMonthOldDate, $message_status, $notUseLastSeen = 0)
    {
        echo "\n" . $profile->id . "\n";
        $compatibility = Compatibility::where('user_data_id', $profile->id)->first();

        $sent_user_data_id = [];
        //getting profile_status id
        if (!empty($compatibility)) {
            // checking is_lead and decode data according to their tables added by meraj
            if ($is_lead == 0) {
                $sent_user_data = json_decode($compatibility->profile_status);
            } else {
                $sent_user_data = json_decode($compatibility->compatibility);
            }
            //dd($sent_user_data);  || sizeof($sent_user_data) > 0
            if (!empty($sent_user_data)) {
                $sent_user_data_id = array_unique(array_column($sent_user_data, 'user_id'));
            } else {
                echo "empty profile id" . $profile->id;
            }

            #virtual send user_data donot comment its for removing laready sent user_data
            $virtual_rec_user_data = array();
            if (!empty($compatibility->virtual_receive)) {
                $virtual_receive_json = json_decode($compatibility->virtual_receive, true); // array_column($sent_user_data, 'user_id');
                $virtual_rec_user_data = array_unique(array_column($virtual_receive_json, 'user_id'));
            }
        }

        //extracting discover profiels id to not include in compatibility
        $discoveruser_data_id =  [];
        if (!empty($compatibility) && !empty($compatibility->discoverCompatibility)) {
            $discover = json_decode($compatibility->discoverCompatibility);
            if ($discover != null && count($discover) > 0) {
                $discoveruser_data_id = array_column($discover, 'user_id');
            }
        }
        if (!empty($compatibility) && !empty($sent_user_data_id)) {
            echo "\n" . $profile->caste . "\n";
            $caste_list = array();
            echo "\ncaste handling $profile->id \n";
            if ($profile->caste != 'All') {
                if ($profile->caste != null && $profile->caste != 'null' && strpos($profile->caste, 'Brahmin-All') !== False) {
                    $caste_list = DB::table('caste_mappings')->where('caste', 'like', '%Brahmin%')->pluck('caste')->toArray();
                    array_push($caste_list, "Bhardwaj");
                    foreach ($caste_list as $key => $value)
                        if (empty($value))
                            unset($caste_list[$key]);
                    $preference_castes = explode(',', $profile->caste);

                    foreach ($preference_castes as $preference_caste) {
                        // echo $preference_caste . "\t";
                        if ($preference_caste == 'All') {
                            $caste_list = array();
                            //array_push($caste_list, 'All');
                            $caste_list[] = 'All';
                            break;
                        }
                    }
                    $caste_list = array_unique($caste_list);
                } else {
                    $cast_list_array = explode(',', $profile->caste);

                    $caste_list_db = DB::table('caste_mappings')->where('caste', 'like', '%' . $cast_list_array[0] . '%')->select('caste', 'mapping_id')->get();

                    if (!empty($caste_list_db) && json_decode($caste_list_db) != []) {
                        $caste_list2 =  DB::table('caste_mappings')->where('mapping_id', $caste_list_db[0]->mapping_id)->select('caste')->get();
                        // profile cast list array
                        foreach ($caste_list2 as $caste_list_val) {
                            $caste_list[] = $caste_list_val->caste;
                        }
                    } else {
                        if ($profile->caste != null && $profile->caste != 'null' && strpos($profile->caste, 'Brahmin-All') !== False) {
                            $caste_list = DB::table('caste_mappings')->where('caste', 'like', '%Brahmin%')->pluck('caste')->toArray();
                            array_push($caste_list, "Bhardwaj");
                            foreach ($caste_list as $key => $value)
                                if (empty($value))
                                    unset($caste_list[$key]);
                            $preference_castes = explode(',', $profile->caste);

                            foreach ($preference_castes as $preference_caste) {
                                // echo $preference_caste . "\t";
                                if ($preference_caste == 'All') {
                                    $caste_list = array();
                                    //array_push($caste_list, 'All');
                                    $caste_list[] = 'All';
                                    break;
                                }
                            }
                            $caste_list = array_unique($caste_list);
                        } else {
                            $cast_list_array = explode(',', $profile->caste);

                            $caste_list_db = DB::table('caste_mappings')->where('caste', 'like', '%' . $cast_list_array[0] . '%')->select('caste', 'mapping_id')->get();

                            if (!empty($caste_list_db) && json_decode($caste_list_db) != []) {
                                $caste_list2 =  DB::table('caste_mappings')->where('mapping_id', $caste_list_db[0]->mapping_id)->select('caste')->get();
                                // profile cast list array
                                foreach ($caste_list2 as $caste_list_val) {
                                    $caste_list[] = $caste_list_val->caste;
                                }
                            } else {
                                $caste_list[] = 'All';
                            }
                        }
                    }
                }
            } else {
                $caste_list[] = 'All';
            }

            // lead value for checking and sending message
            if (!empty($profile->lead_value)) {
                $lead_value  = $profile->lead_value;
            } else {
                $lead_value  = 0;
            }
            if ($profile->gender == 'Male') {
                $query = DB::table('user_data')
                    ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
                    ->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
                    ->select('user_data.id as id', 'userPreferences.caste', 'userPreferences.age_min', 'userPreferences.age_max', 'userPreferences.height_max', 'userPreferences.height_min', 'userPreferences.income_min', 'userPreferences.income_max', 'userPreferences.food_choice', 'userPreferences.manglik', 'userPreferences.marital_status', 'userPreferences.religion', 'user_data.birth_date', 'user_data.height', 'user_data.monthly_income', 'user_data.food_choice', 'user_data.marital_status', 'user_data.manglik', 'families.caste', 'user_data.name', 'user_data.identity_number', 'user_data.temple_id', 'user_data.lead_id')
                    ->whereNotIn('user_data.temple_id', $dataAccounts)
                    ->where('user_data.marital_status', '!=', 'Married')
                    ->where('user_data.gender', '!=', $profile->gender)
                    ->where('user_data.is_approved', 1)
                    ->where('user_data.is_deleted', 0)
                    ->whereNotIn('user_data.id', $virtual_rec_user_data);
                if ($notUseLastSeen == 0) {
                    $query->where('shortlist_count', '>', '1')->where('user_data.last_seen', '>', $twoMonthOldDate)->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike', 'ASC')->orderBy('user_data.created_at', 'desc')
                        ->orderBy('user_data.photo_score', 'DESC');
                } else {
                    $query->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike', 'ASC');
                }
            } else {
                $query = DB::table('user_data')
                    ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
                    ->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
                    ->select('user_data.id as id', 'userPreferences.caste', 'userPreferences.age_min', 'userPreferences.age_max', 'userPreferences.height_max', 'userPreferences.height_min', 'userPreferences.income_min', 'userPreferences.income_max', 'userPreferences.food_choice', 'userPreferences.manglik', 'userPreferences.marital_status', 'userPreferences.religion', 'user_data.birth_date', 'user_data.height', 'user_data.monthly_income', 'user_data.food_choice', 'user_data.marital_status', 'user_data.manglik', 'families.caste', 'user_data.name', 'user_data.identity_number', 'user_data.temple_id', 'user_data.lead_id', 'user_data.name')
                    ->whereNotIn('user_data.temple_id', $dataAccounts)
                    ->where('user_data.marital_status', '!=', 'Married')
                    ->where('user_data.gender', '!=', $profile->gender)
                    ->where('user_data.is_approved', 1)
                    ->where('user_data.is_deleted', 0)
                    ->whereNotIn('user_data.id', $virtual_rec_user_data);

                if ($notUseLastSeen == 0) {
                    $query->where('user_data.last_seen', '>', $twoMonthOldDate)->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike', 'ASC')->orderBy('user_data.created_at', 'desc')
                        ->orderBy('user_data.photo_score', 'DESC');
                } else {
                    $query->orderBy('virtual_send_count', 'ASC')->orderBy('virtualToLike', 'ASC');
                }
            }

            echo "\ngetting profile using caste array " . $profile->id . " \n";

            if (!empty($caste_list[0]) && ($caste_list[0] !== "All")) {
                echo $caste_list[0];
                if (($caste_list[0] == "Doesn't Matter") || ($caste_list[0] !== "All")) {
                    //dd($caste_list);
                    echo "\n inside caste query\n";
                    $query = $query->whereIN('families.caste', $caste_list);
                }
            }
            echo "\noutside caste handling\n";
            try {
                if ($profile->age_min && $profile->age_max) {
                    $min_age = Carbon::today()->subYears($profile->age_min)->format('Y-m-d');
                    $max_age = Carbon::today()->subYears($profile->age_max + 1)->endOfDay()->format('Y-m-d');
                }
                $query->whereBetween('user_data.birth_date', [$max_age, $min_age]);
                //  echo "\n age \t " . $query->count();
            } catch (\Exception $e) {
            }

            //height
            try {
                if ($profile->height_min && $profile->height_max) {
                    $min_height = $profile->height_min;
                    $max_height = $profile->height_max;
                }
                $query = $query->whereBetween('user_data.height', [$min_height, $max_height]);

                // echo "\n height \t " . $query->count();
            } catch (\Exception $e) {
            }

            //income range
            try {
                if ($profile->income_min != null && $profile->income_max != null) {
                    if ($is_lead == 0) {
                        $min_income = $profile->income_min;
                        $max_income = $profile->income_max;
                    } else {
                        $min_income = $profile->income_min * 100000;
                        $max_income = $profile->income_max * 100000;
                    }
                    $query = $query->whereBetween('user_data.monthly_income', [$min_income, $max_income]);
                }

                //echo "\n income \t " . $query->count();
            } catch (\Exception $e) {
            }

            if ($profile->marital_status != 'Never Married') {
                if (isset($marital_status[$profile->marital_status]))
                    $query = $query->whereIn('user_data.marital_status', $marital_status[$profile->marital_status]);
                else {
                    echo "\n marital status array not found - assuming never married";
                    $query = $query->where('user_data.marital_status', '=', 'Never Married');
                }
            } else {
                $query = $query->where('user_data.marital_status', '=', 'Never Married');
            }

            //matching the preference of findingProfile(girl) with profile(boy)
            $profileLast = explode(" ", $profile->name);

            if (count($profileLast) > 1) {
                $query = $query->/*whereNotIn("user_data.id" ,$sent_user_data_id)->*/where("user_data.name", "NOT LIKE", $profileLast[1]);
            }

            echo "\n outside name \n";
            $query = $query->get();
            //echo "count of compatblity".sizeof($query)." line 812 \n";
            $findProfilecaste_list = array();
            $userPreferenceCast = array();
            $finaluser_data = array();

            //dd($query[0]);
            if ($profile->caste != null && $profile->caste != 'null') {
                $userPreferenceCast = $this->getImplodedCaste($profile->caste);
            }
            $caste_flag = 0;
            $totalLike = array_push($this->totalLike, $profile->id);
            if ($is_lead == 1) {
                $totalLikeExpectLead = array_push($this->totalLikeExpectLead, $profile->id);
            } else {
                $totalLikeExpectProfile = array_push($this->totalLikeExpectProfile, $profile->id);
            }

            $findProfileCount = 0;

            echo "\n inside query[0] \n";
            if (!empty($query[0])) {
                //foreach ($query[0] as $findProfile) {
                echo "\n inside query 0\n";
                $findProfile = $query[0];
                $flag = 1; //to check the condition remaining true till last filter

                //last name filter
                $profileLastName = $profileLast[sizeof($profileLast) - 1];
                $preferenceLast = explode(" ", $findProfile->name);
                $preferenceLastName = $preferenceLast[sizeof($preferenceLast) - 1];
                $flag == 1;

                if ($flag == 1) {
                    echo "\ninside send\n";
                    if ($notUseLastSeen == 1) {
                        //   dd($profile->id);
                    }

                    array_push($this->like, $profile->id);
                    if ($is_lead == 1) {
                        $this->likeLead++;
                    } else {
                        $this->likeProfile++;
                    }

                    if ($is_lead == 1) {
                        $likeGottenLead = array_push($this->likeGottenLead, $profile->id);
                    } else {
                        $likeGottenProfile = array_push($this->likeGottenProfile, $profile->id);
                    }
                    $send_virtual = '';
                    $receive_virtual = '';
                    $send_message_to_mobile = '';
                    $send_virtual = $this->markVirtualSend($profile->id, $findProfile->id, $is_lead);
                    $receive_virtual = $this->markVirtualReceive($profile->id, $findProfile->id, $is_lead);
                    if ($lead_value >= 20 && $message_status == 1) {
                        $send_message_to_mobile = app('App\Http\Controllers\WebhookController')->sendAllLikeNotification($findProfile->id, $profile->id, 0, $is_lead);
                    } else {
                        app('App\Http\Controllers\WebhookController')->sendAllLikeNotificationWithoutMessage($findProfile->id, $profile->id, 0, $is_lead);
                    }
                    $send_virtual = '';
                    $receive_virtual = '';
                    $send_message_to_mobile = '';
                    /******** shows count and profile id of send data *********/
                    echo "\nfindID\t" . $findProfile->id;
                    echo "\n profileID\t" . $profile->id;
                    echo "\n counttttttttttttttttttttttttttttt \t" . $this->count++;
                    // break;
                } else {
                    //dd($profile->id);
                         array_push($this->abc, $profile->id);
                    // break;
                }
            }
        }
    }
    public function getPreferenceCaste($identity_number, $temple_id)
    {
        $preference = Preference::where('identity_number', $identity_number)->where('temple_id', $temple_id)->first();
        $caste = 'All';
        if ($preference && $preference->caste) {
            $caste = $preference->caste;
        }
        return $caste;
    }
}
