<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use App\Models\UserMatches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // index
    public function index(Request $request)
    {
        return view('admin.dashboard');
    }

    public function userDashboard()
    {
        $income_range
        = config('constants.income_ranges');
        $caste_list  = array();
        return view('user-dashboard', compact('income_range', 'caste_list'));
    }

    public function counteUserData()
    {
        $return_data = array();
        $today_date = date('Y-m-d');

        if (Auth::user()->role == config('constants.roles_ineger.telesales')) {
            // lead counting
        } else if (Auth::user()->role == config('constants.roles_ineger.relationship_mananger')) {
            // matchmaker
            $all_profile_ids = UserData::getAllProfiles(Auth::user()->temple_id);
            $profile_ids = array_column($all_profile_ids->toArray(), "user_id");

            $profile_sent_count = UserMatches::whereIn('userAid', $profile_ids)->where('is_sent', 1)->whereRaw(" status IN(3,4,5) and DATE(created_at) = '$today_date'")->get(['id'])->count();
            $profile_sent = array("icon" => "mdi mdi-account-switch", "count" => $profile_sent_count, "text"=>"Profile Sent Today");

            $today_profile_not_sent_count = count(UserData::getSentTodayProfile(Auth::user()->temple_id));

            $premium_meeting_count = UserMatches::whereIn('userAid', $profile_ids)->where('is_sent', 1)->whereRaw("DATE(updated_at) = '$today_date'")->get(['id'])->count();
            $premium_meeting = array("icon" => "mdi mdi-account-tie", "count" => $premium_meeting_count, "text"=>"Premium Meeting Today");
            $premium_profiles = array("icon" => "mdi mdi-account-star-outline", "count" => count($all_profile_ids), "text"=>"Total Premium Profiles");
            $today_profile_not_sent = array("icon" => "mdi mdi-account-remove-outline", "count" => $today_profile_not_sent_count, "text"=>"Profile Not Sent Today");
            $return_data = array($premium_profiles,$profile_sent,$premium_meeting,$today_profile_not_sent);
        }

        return response()->json($return_data);
    }
}
