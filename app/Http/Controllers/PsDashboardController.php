<?php

namespace App\Http\Controllers;

use App\Models\Caste;
use App\Models\Order;
use App\Models\PremiumMeetings;
use App\Models\UserData;
use App\Models\UserMatches;
use App\Models\UserPreference;
use Carbon\Carbon;
use Database\Seeders\MaritalStatusSeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;
use Illuminate\Database\Eloquent\Model;

class PsDashboardController extends Controller
{

    // return view of client - list (database)
    public function index()
    {
        $income_range = config('constants.income_ranges');
        $caste_list = DB::table('castes')->get();
        $manglik_status = DB::table('manglik_mappings')->get();
        $workins = DB::table('occupation_mappings')->get();
        return view('personalized.client-list', compact('income_range', 'caste_list'));
    }

    // get all my user list
    public function myUserList()
    {
        $all_lsit =  UserData::getAllProfiles(Auth::user()->temple_id)->toArray();

        $dataset = array(
            "echo" => 1,
            "totalrecords" => 214,
            "totaldisplayrecords" => 455,
            "data" => $all_lsit
        );
        return response()->json($dataset);
    }
    public function myUserListpending()
    {
        $all_lsit =  UserData::getAllProfilesPending(Auth::user()->temple_id)->toArray();

        $dataset = array(
            "echo" => 1,
            "totalrecords" => 214,
            "totaldisplayrecords" => 455,
            "data" => $all_lsit
        );
        return response()->json($dataset);
    }

    // get find match view
    public function findMatchView()
    {
        $caste_list = '';
        $caste_pref = array();
        $user_preference = UserPreference::where('user_data_id', $_GET['user_id'])->first()->toArray();
        //dd($user_preference);
        $caste_list = DB::table('castes')->get();
        $manglik_status = DB::table('manglik_mappings')->get();
        $marital_status = DB::table('marital_status_mappings')->where("id", '!=', 2)->get();
        $workins = DB::table('occupation_mappings')->get();
        $income_range = config('constants.income_ranges');

        if (!empty($user_preference) && count(json_decode($user_preference['castePref'])) < 0) {
            $caste_pref = json_decode($user_preference['castePref']);
        }
        // dd($marital_status);

        return view('personalized.find-profile', compact('caste_list', 'manglik_status', 'marital_status', 'user_preference', 'workins', 'income_range', 'caste_pref'));
    }

    // get filtered profiles
    public function getFilteredProfiles(Request $request)
    {
        // removed picking sent profiels from order table and profiles from userMAtches table implemented
        $send_profiles = '';

        //picking all profiles from userMatches table coz its violating unique contraints
        $send_profiles = UserMatches::sentProfileList($request->user_id)->toArray();

        $array_list = array_column($send_profiles, 'userBid');
        $profile_list = "";

        if (!empty($array_list)) {
            $profile_list = implode(",", $array_list);
        } else {
            $profile_list = '';
        }

        $show_disabled = $request->show_disabled;

        $user_gender = UserData::where('id', $request->user_id)->select(["genderCode_user"])->first();
        //dd(implode(",", $request->working));
        $user_data = UserData::getFilteredData($request->disabled_profiles, $request->wish_to, $request->nri, $request->min_age, $request->max_age, $request->min_height, $request->max_height, $request->castes, $request->marital_status, $request->manglik_status, $request->food_choice, $request->working, $request->min_income, $request->max_income, $profile_list, $user_gender->genderCode_user, $show_disabled, $request->religion);
        // dd($user_data);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($user_data),
            "totaldisplayrecords" => count($user_data),
            "data" => $user_data
        );
        return response()->json($dataset);
    }

    // get filtered sample profiles
    public function getSampleFilteredProfiles(Request $request)
    {
        $show_disabled = $request->show_disabled;
        $profile_list = array();
        // $user_gender = $request->user_gender;
        $user_data = UserData::getFilteredData($request->disabled_profiles, $request->wish_to, $request->nri, $request->min_age, $request->max_age, $request->min_height, $request->max_height, $request->castes, $request->marital_status, $request->manglik_status, $request->food_choice, $request->working, $request->min_income, $request->max_income, $profile_list,  $request->user_gender, $show_disabled, $request->religion);
        // return $user_data = UserData::limit(10)->get();
        // dd($user_data);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($user_data),
            "totaldisplayrecords" => count($user_data),
            "data" => $user_data
        );
        return response()->json($dataset);
    }


    // profile sent day
    public function profileSentDay(Request $request)
    {
        $update_record = UserData::userProfileSentDayUpdaet($request->user_id, $request->sent_day);
        if ($update_record) {
            return response()->json(['type' => true, 'message' => 'record updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed']);
        }
    }

    // save send profile list into database
    public function saveSendProfileList(Request $request)
    {

        DB::beginTransaction();
        $profile_list = $request->profile_selected;
        $user_id = $request->user_id_opened;
        UserData::where('id', $user_id)->update(['profile_sent_day' => date('Y-m-d', strtotime('+7 day'))]);
        if (!isset($profile_list)) {
            return  "null";
        }
        $alredy_exist = [];
        $new_profile = [];
        for ($i = 0; $i < count($profile_list); $i++) {
            if (UserMatches::sendProfileList($user_id, $profile_list[$i])) {
                array_push($new_profile, $profile_list[$i]);
            } else {
                array_push($alredy_exist, $profile_list[$i]);
            }
        }
        if (count($profile_list) == (count($alredy_exist) + count($new_profile))) {
            DB::commit();
            return response()->json(['type' => true, 'message' => 'entry created', 'profile_list' => $new_profile, 'already_exit' => implode(',', $alredy_exist)]);
        } else {
            DB::rollBack();
            return response()->json(['type' => false, 'message' => 'entry creation failed', 'profile_list' => '']);
        }
    }

    // display pdf page
    public function displayProfilePdfs()
    {
        return view('profile.pdf-profiles');
    }
    public function displayProfilesPdfs()
    {
        return view('profile.pdf-profiless');
    }
    public function displaySampleProfilesPdfs()
    {
        return view('profile.sample-pdf-profiles');
    }

    //create pdf profile lists
    public function CreateProfilePdfss(Request $request)
    {
        $user_data = '';
        $user_data = UserData::with('userPhotos')->whereIn('id',  explode(',',  $request->user_ids))->get();
        return response()->json($user_data);
    }

    // user sent profile list
    public function sendProfileListUser(Request $request)
    {
        $sentList = '';

        $sent_list = Order::sentProfileList($request->user_id);

        $returned_array = array();

        foreach ($sent_list as $sent_nos) {
            $returned_array[] = array(
                "send_date"         =>      $sent_nos->order_date,
                "sent_profiles"     =>      UserData::getProfilesInGroup($sent_nos->order_list)
            );
        }
        return response()->json($returned_array);
    }

    // save to yes /no from list sent
    public function updateUserAction(Request $request)
    {
        $update_match = UserMatches::updateUserMatches($request->user_id, $request->match_id, $request->action);

        // save into premium meeting with pending status
        // $request->match_id
        if ($request->action == 1) {
            $user_detail = UserData::getProfilesInGroup($request->match_id . ',' . $request->user_id)->toArray();
            $comment[] = array(
                "comment"           =>      $user_detail[0]['name'] . ' has liked ' . $user_detail[0]['name'] . ' profile',
                "meeting_date"      =>      null,
                "status"            =>      3
            );

            $udpdate_meeting = PremiumMeetings::createMeeting($request->user_id, $request->match_id, 2, json_encode($comment), date('Y-m-d'), null);
        }

        if ($update_match) {
            return response()->json(['message' => 'record saved']);
        } else {
            return response()->json(['message' => 'failed to save record']);
        }
    }

    // get total yes pending / contacted list
    public function getContactedUserList(Request $request)
    {
        $user_list = '';
        $user_list = UserMatches::getAllcontactedUsers($request->user_id);
        return response()->json($user_list);
    }

    // update contacted status
    public function updatecontactedStatus(Request $request)
    {
        $comment = array(
            "comment"           =>      $request->comments,
            "meeting_date"      =>      $request->next_update,
            "status"            =>      $request->user_status
        );

        $udpdate_meeting = '';

        $check_record = PremiumMeetings::userMeetingDetails($request->user_id, $request->matched_user_id);

        // if (empty($check_record)) {
        //     // create new record
        //     if ($request->user_status == 3) {
        //         $udpdate_meeting = PremiumMeetings::createMeeting($request->user_id, $request->matched_user_id, $request->user_status, json_encode($comment), $request->next_update, $request->meeting_date);
        //     } else {
        //         $udpdate_meeting = PremiumMeetings::createMeeting($request->user_id, $request->matched_user_id, $request->user_status, json_encode($comment), $request->next_update, null);
        //     }
        //     if ($request->user_status != 2) {
        //         $upadte_match = UserMatches::where(["userAid" => $request->user_id, "userBid" => $request->matched_user_id])->update([
        //             "isLiked"       =>      0,
        //             "isContacted"   =>      1
        //         ]);
        //     }
        // } else {
        // update existing record

        $prev_comment = json_decode($check_record->comments, true);

        if ($request->user_status == 3) {
            if (empty($prev_comment)) {
                $prev_comment[] = $comment;
                $udpdate_meeting = PremiumMeetings::updateMeetings($request->user_id, $request->matched_user_id, $request->user_status, json_encode($prev_comment), $request->next_update, $request->meeting_date);
            } else {
                $prev_comment[count($prev_comment)] = $comment;
                $udpdate_meeting = PremiumMeetings::updateMeetings($request->user_id, $request->matched_user_id, $request->user_status, json_encode($prev_comment), $request->next_update, $request->meeting_date);
            }
        } else {
            $prev_comment[count($prev_comment)] = $comment;
            if (empty($prev_comment)) {
                $prev_comment[] = $comment;
                $udpdate_meeting = PremiumMeetings::updateMeetings($request->user_id, $request->matched_user_id, $request->user_status, json_encode($prev_comment), $request->next_update, null);
            } else {
                $udpdate_meeting = PremiumMeetings::updateMeetings($request->user_id, $request->matched_user_id, $request->user_status, json_encode($prev_comment), $request->next_update, null);
            }
        }
        // if ($request->user_status != 2) {
        //     $upadte_match = UserMatches::where(["userAid" => $request->user_id, "userBid" => $request->matched_user_id])->update([
        //         "isLiked"       =>      0,
        //         "isContacted"   =>      1
        //     ]);
        // }
        // }

        // if ($request->user_status == 0) {
        //     $upadte_match = UserMatches::where(["userAid" => $request->user_id, "userBid" => $request->matched_user_id])->update([
        //         "isLiked"       =>      0,
        //         "isContacted"   =>      0,
        //         "isRejected"    =>      1
        //     ]);
        // }

        if ($udpdate_meeting) {
            return response()->json(['type' => true, 'message' => 'record has been updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to update record']);
        }
    }

    public function getAllPreimumeetingList(Request $request)
    {
        $premium_meetings = '';

        $statuses = $request->status;

        $premium_meetings = PremiumMeetings::getAllPremiumMeetings($request->user_id, $statuses);

        return response()->json($premium_meetings);
    }

    public function getAllPremiumLkedProfile(Request $request)
    {
        $premium_meetings = '';

        $user_id = $request->user_id;
        if ($request->status == 2) {
            $premium_meetings = UserMatches::getAllPremiumLikes($user_id);
        } else if ($request->status == "3,4,5") {
            $premium_meetings = PremiumMeetings::getAllPremiumMeetings($request->user_id, $request->status);
        } else {
            $premium_meetings = UserMatches::getAllPremiumLikes($user_id);
        }
        return response()->json($premium_meetings);
    }

    public function loadAllPremiumMeetingList(Request $request)
    {
        $temple_id = Auth::user()->temple_id;
        $profile_ids = array();
        $premium_meetings_data = PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.user_id')
            ->where(['user_data.temple_id' => $temple_id])
            ->whereRaw('premium_meetings.status in(1,3,4)')
            ->get([
                'user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status',
                'user_data.photo', 'premium_meetings.*', 'user_data.user_mobile as mobile', 'user_data.birth_date'
            ]);
        $profile_ids = array_unique(array_column($premium_meetings_data->toArray(), "user_id"));
        $premium_meetings = UserData::whereIn('id', $profile_ids)->get([
            'user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status',
            'user_data.photo', 'user_data.user_mobile as mobile', 'user_data.birth_date'
        ]);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($premium_meetings),
            "totaldisplayrecords" => count($premium_meetings),
            "data" => $premium_meetings
        );
        return response()->json($dataset);
    }

    public function dayWiseProfileStndList()
    {
        return view('personalized.day-wise-profile-sent');
    }

    // sent profile list user
    public function sentListUserMatch(Request $request)
    {
        $user_list = '';

        $user_list = UserMatches::getAllSentProfileList($request->user_id);

        return response()->json($user_list);
    }

    // sample data
    public function sampleProfile()
    {
        $caste_list = '';
        $caste_list = Caste::getAllCaste();
        $manglik_status = DB::table('manglik_mappings')->get();
        $marital_status = DB::table('marital_status_mappings')->where("id", '!=', 2)->get();
        // dd($marital_status);
        $workins = DB::table('occupation_mappings')->get();
        $religions = DB::table('religion_mapping')->get();
        $income_range = config('constants.income_ranges');
        return view('profile.send-sample-profiles', compact('caste_list', 'manglik_status', 'marital_status', 'workins', 'religions', 'income_range'));
    }

    // overalll yes pending view
    public function overallYesPending()
    {
        return view('personalized.yes-pending');
    }

    // today profile sent list
    public function todaySentList()
    {
        $profile_list = UserData::getSentTodayProfile(Auth::user()->temple_id);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($profile_list),
            "totaldisplayrecords" => count($profile_list),
            "data" => $profile_list
        );
        return response()->json($dataset);
    }

    public function weeklyProfileNotSent()
    {
        return view('personalized.week-wise-profile-not-sent');
    }

    public function weeklyProfileNotSentData(Request $request)
    {
        $date_range = explode("/", $request->date_range);
        $profile_div = $request->url_link;
        $today = date('Y-m-d H:i:s');
        $date_start = $date_range[0];
        $date_end = $date_range[1];
        $temple_id = Auth::user()->temple_id;

        $order_list = DB::table("user_data")->join("userPreferences", "userPreferences.user_data_id", "user_data.id")
            ->whereRaw("userPreferences.validity >= '$today' and user_data.temple_id = '$temple_id' and user_data.is_premium = 1 and DATE(profile_sent_day) BETWEEN '$date_start' AND '$date_end' ")
            ->select(["user_data.name", "user_data.gender", "validity", "birth_date", "user_data.id", 'user_data.profile_sent_day'])->get();

        // foreach ($order_list as $sent_count) {
        //     $check_matches = DB::table("userMatches")->whereRaw("is_sent =1 and userAid = $sent_count->id and DATE(created_at) BETWEEN '$date_start' AND '$date_end'")->count();
        //     if (!empty($check_matches)) {
        //         $sent_count->profile_sent = $check_matches;
        //     } else {
        //         $sent_count->profile_sent = 0;
        //     }
        // }

        if (!empty($order_list)) {
            return response()->json(['type' => true, 'data_table' => $order_list, 'div_name' => $profile_div]);
        } else {
            return response()->json(['type' => false, 'message' => 'no record found']);
        }
    }

    public function transferProfileView()
    {
        return view('personalized.transfer_profile');
    }

    public function transferProfileData(Request $request)
    {
        $useer_details = "";

        $useer_details = UserData::templeWiseProfile($request->temple_id);
        return response()->json($useer_details);
    }

    public function transferProfiles(Request $request)
    {
        $tr_ids = implode(",", $request->profile_id);
        $transfer_query = UserData::whereRaw("id IN ($tr_ids)")->update(['temple_id' => $request->transfer_to]);

        if ($transfer_query) {
            return response()->json(['type' => true, 'message' => 'lead transfered successfully']);
        }
    }

    // overall yes pending
    public function overallYesPendingData()
    {
        $temple_id = Auth::user()->temple_id;
        $premium_meetings = array();
        /*$premium_meetings = PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.user_id')
        ->join('userPreferences', 'userPreferences.user_data_id','user_data.id')
        ->where(['user_data.temple_id' => $temple_id])
        ->whereRaw('premium_meetings.status in(2)')
        ->groupBy('premium_meetings.user_id')
        ->get([
            'user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status',
            'user_data.photo', 'user_data.user_mobile', 'user_data.birth_date', 'userPreferences.validity', 'userPreferences.amount_collected_date'
        ]);*/


        /**
         * first check user validity
         * then find users who have is_liked = 1
         */
        $user_ids = UserData::with('userpreference')->whereHas('userpreference', function ($query) {
            $query->where('validity', '>=', date('Y-m-d H:i:s'));
            $query->select('validity', 'amount_collected_date');
        })->where(['is_premium' => 1, 'temple_id' => $temple_id])->get(['id']);

        if ($user_ids) {
            $user_id_list = array_column($user_ids->toArray(), 'id');
            $premium_meetings = PremiumMeetings::join('user_data', 'premium_meetings.user_id', 'user_data.id')
                ->whereIn('premium_meetings.user_id', $user_id_list)->where('premium_meetings.status', 2)
                ->groupBy('user_id')->get(['user_data.name', 'user_data.user_mobile', 'user_data.id', 'birth_date']);
        }

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($premium_meetings),
            "totaldisplayrecords" => count($premium_meetings),
            "data" => $premium_meetings
        );
        return response()->json($dataset);
    }

    // ovreall pending
    public function overallPendingData()
    {
        $overall_yes_pending = array();

        $overall_yes_pending = UserData::with(['userpreference'])->whereHas('userpreference', function ($query) {
            $query->where('validity', '>=', date('Y-m-d h:i:s'));
        })
            ->where('user_data.temple_id', Auth::user()->temple_id)
            ->whereRaw("( (user_data.is_invisible = 1) OR (user_data.is_premium = 1) ) ")
            ->orderBy('user_data.followup_call_on', 'DESC')->get([
                'user_data.id as user_data_id', 'user_data.name', 'user_data.user_mobile', 'user_data.birth_date'
            ]);
        $returned_data = array();

        foreach ($overall_yes_pending as $overall_data) {
            $user_match_data = DB::select("SELECT count(id) as total FROM hansdb.userMatches where userAid = $overall_data->user_data_id and is_sent = 1 AND isLiked = 0 AND isRejected = 0 AND isContacted = 0 AND isMutualLike = 0;");
            if (!empty($user_match_data) && $user_match_data[0]->total >= 1) {
                $returned_data[] = $overall_data;
            }
        }
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($returned_data),
            "totaldisplayrecords" => count($returned_data),
            "data" => $returned_data
        );
        return response()->json($dataset);
    }

    public function overAllPendingListView()
    {
        return view('personalized.overall-pending-list');
    }

    public function overAllYesPendingListView()
    {
        return view('personalized.overall-yes-pending-list');
    }

    public function overallYesMeeting()
    {
        return view('personalized.yes-meeting');
    }

    public function savePdf()
    {
        return view('profile.pdf-profiles');
    }

    public function overAllTesMeetingList(Request $request)
    {
        $premium_meetings = PremiumMeetings::getAllPremiumMeetings2($request->user_id, $request->status);
        return response()->json($premium_meetings);
    }

    public function historyDetails(Request $request)
    {

        $user_detail_by_id = UserData::where('id', $request->user_id)->select("id", "name", "gender", "user_mobile", "photo", "birth_date")->first();
        $sent_profile_list = UserMatches::getAllSentProfilesList($request->user_id, $request->start_date, $request->end_date);
        $rejected_by_me_profile_list = UserMatches::getAllRejectedProfileList($request->user_id, $request->start_date, $request->end_date);
        $liked_by_me_profile_list = PremiumMeetings::getAllLikedProfileList($request->user_id, $request->start_date, $request->end_date);
        $premium_meetings_list = PremiumMeetings::getAllPremiumMeetings($request->user_id, "3,4,5", $request->start_date, $request->end_date);
        // $liked_premium_meetings_list = PremiumMeetings::getAllLikedPremiumMeetings($request->user_id, $request->start_date, $request->end_date);
        $rejected_premium_meetings_list = PremiumMeetings::getAllRejectedPremiumMeetings($request->user_id, $request->start_date, $request->end_date);
        $dataset = array(
            "echo" => 1,
            "userdetailsbyid" => $user_detail_by_id,
            "sentprofilelist" => $sent_profile_list,
            "likedbymeprofilelist" => $liked_by_me_profile_list,
            "rejectedbymeprofilelist" => $rejected_by_me_profile_list,
            "rejectedpremiummeetingslist" => $rejected_premium_meetings_list,
            "premiummeetingslist" => $premium_meetings_list,
            // "likedpremiummeetingslist" => $liked_premium_meetings_list,
            // "countlikedpremiummeetingslist" => count($liked_premium_meetings_list),
            "countrejectedpremiummeetingslist" => count($rejected_premium_meetings_list),
            "countsentprofilelist" => count($sent_profile_list),
            "countpremiummeetingslist" => count($premium_meetings_list),
            "countrejectedbymelist" => count($rejected_by_me_profile_list),
            "countlikedbymeprofilelist" => count($liked_by_me_profile_list)
        );
        if ($request->generate_pdf) {
            // dd($dataset);
            return view('personalized.profile-details-report', compact('user_detail_by_id', 'sent_profile_list', 'liked_by_me_profile_list', 'rejected_by_me_profile_list', 'rejected_premium_meetings_list', 'premium_meetings_list'));
        } else {
            return response()->json($dataset);
        }
    }

    public function saveBasicProfile(Request $request)
    {

        DB::beginTransaction();
        // gender data
        $gender_string = '';
        if ($request->gender) {
            if ($request->gender == 1) {
                $gender_string = 'Male';
            } else {
                $gender_string = 'Female';
            }
        }

        // religion data
        $religion_string = '';
        $religion_int = '';
        if ($request->religion) {
            $exp_relgn = explode("-", $request->religion);
            $religion_string = $exp_relgn[1];
            $religion_int = $exp_relgn[0];
        }

        // caste data
        $caste_int = '';
        $caste_string = '';
        if ($request->castes) {
            $expl_caste = explode(",", $request->castes);
            $caste_int = $expl_caste[0];
            $caste_string = $expl_caste[1];
        }

        // marital status data
        $marital_int = '';
        $marital_string = '';
        if ($request->marital_status) {
            $expl_mart = explode(",", $request->marital_status);
            $marital_int = $expl_mart[0];
            $marital_string = $expl_mart[1];
        }

        // education data
        $education_int = '';
        $education_string = '';
        if ($request->education_list) {
            $edu_expl = explode(",", $request->education_list);
            $education_int = $edu_expl[0];
            $education_string = $edu_expl[1];
        }

        // qualification data
        $occupation_string = '';
        $occupation_int = '';
        if ($request->occupation_list) {
            $qual_explode = explode(",", $request->occupation_list);
            $occupation_string = $qual_explode[1];
            $occupation_int = $qual_explode[0];
        }

        $mobile_no = '';
        $mobile = explode('+', $request->mobile);
        if (count($mobile) > 1) {
            $mobile_no = $mobile[1];
        } else {
            $mobile_no = $mobile[0];
        }
        $mobile_with_code = intval($request->country_code . $mobile_no);

        $total_comment = "";
        $user_name = Auth::user()->name;
        if ($request->new_lead) {
            $total_comment = date('Y-m-d') . "  Lead moved from online and assigned to $user_name" . ";" . date('Y-m-d H:i:s') . ' ' . $request->followup_comment . " added by " . $user_name . ";";
        } else {
            $total_comment = $request->followup_comment . " added by " . $user_name . ";";
        }

        // save record into user data table
        $alt_1 = "";
        if (!empty($request->alt_mob)) {
            $alt_1 = $request->alt_country_code . $request->alt_mob;
        }

        $temple_id = "qwertuiop@123";
        $weight = $request->weight;
        $save_user_data = UserData::saveBasicProfileRecord($mobile_with_code, $request->full_name, $request->height, $temple_id, $gender_string, $request->gender, $religion_int, $religion_string, $caste_int, $caste_string, $education_int, $education_string, $occupation_int, $occupation_string, $request->birth_date, null, null, $marital_int, $marital_string, $request->yearly_income, null, null, $request->search_working_city, $alt_1, null, $weight);

        //save Lead Preference

        #height & age calculation
        $max_height = '';
        $min_height = '';
        $min_age = '';
        $max_age = '';
        $gender_pref = '';
        if ($request->lead_gender == 'Male') {
            $max_height = $request->user_height;
            $min_height = ($request->user_height - 12);

            $max_age = date('Y') - date('Y', strtotime($request->birth_date));
            $min_age = date('Y') - (date('Y', strtotime($request->birth_date)) + 10);

            $gender_pref = 2;
        } else {
            $max_height = ($request->user_height + 12);
            $min_height = $request->user_height;

            $max_age = date('Y') - date('Y', strtotime($request->birth_date)) + 10;
            $min_age = date('Y') - date('Y');

            $gender_pref = 1;
        }

        $caste_array = array($caste_int);
        $religion_array = array($religion_int);

        $create_preference = UserPreference::createPrefs($min_age, $max_age, $min_height, $max_height, json_encode($caste_array), $marital_int, json_encode($religion_array), $save_user_data->id, $gender_pref);

        if ($save_user_data && $create_preference) {
            DB::commit();
            return response()->json(['type' => true, 'message' => 'profile added successfully', 'id' => $save_user_data->id, 'mobile' => $mobile_with_code, 'data' => $save_user_data]);
        } else {
            DB::rollBack();
            return response()->json(['type' => false, 'message' => 'failed to add']);
        }
    }
}
