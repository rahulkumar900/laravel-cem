<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CheckIn;
use App\Models\Location;
use App\Models\TeamLeader;
use App\Models\User;
use App\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{


    public function index()
    {
        return view('crm.userCheckin');
    }

    public function checkIn(Request $request)
    {

        $current_time_app = date("Y-m-d H:i:s", time() + 18000); // 19800(converting to itc)- 1800 (30 min)
        $checkInApp = CheckIn::where('temple_id', $request->temple_id)->where('updated_at', '>', $current_time_app)->first();

        $user = User::where('temple_id', $request->temple_id)->first();
        $appLogInEnable = 0;
        if ($user) {
            $appLogInEnable = $user->appLogInEnable;
        }

        if ($appLogInEnable == 1 && !$checkInApp) {
            return response()->json(['check_in_status' => 'A', 'message' => 'Please CheckIn from app first'], 200);
        }

        // Data send through POST Method
        $temple_id = $request->temple_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $temples_id = [];
        $teamLeader = User::where('temple_id', $temple_id)->get();

        if ($teamLeader != null) {
            $temples_id = TeamLeader::where('temple_id', $temple_id)->pluck('access_temple_id');
        }

        // Current Timestamp
        $time_stamp = date('h:i a', time() + 19800);

        // Check if any field is sent blank
        if (empty($temple_id) or empty($latitude) or empty($longitude)) {
            return response()->json([
                'check_in_status' => 'N',
                'message' => 'Fill All Fields'
            ], 400);
        }

        // Check if any user is exist for sent temple_id
        $check_user = User::where('temple_id', $temple_id)
            ->first();
        if (!isset($check_user->name))
            return response()->json([
                'check_in_status' => 'N',
                'message' => 'Temple Not Found'
            ]);

        // Finding last checkin for the day
        $check_for_prev_checkin_today = Attendance::where('temple_id', $temple_id)
            ->where('created_at', '>', date('Y-m-d' . ' 00:00:00'))
            ->where('created_at', '<', date('Y-m-d' . ' 23:59:50'))
            ->orderBy('id', 'DESC')->get()
            ->first();

        // Check if this is their first checkin or another checkin of the day
        if (isset($check_for_prev_checkin_today->temple_id)) {
            // Time spent in temple/office
            $time = time() - $check_for_prev_checkin_today->check_in;
            $check_for_location = Location::where('temple_id', $temple_id)
                ->orWhereIn('temple_id', $temples_id)
                ->first();

            // Check if its nearby location or not
            if (isset($check_for_location->temple_id)) {
                // Database update with new checkin entry and set last entry it OUT if its IN
                if ($check_for_prev_checkin_today->status == 'IN') {
                    Attendance::where('id', $check_for_prev_checkin_today->id)->update(['status' => 'OUT', 'time' => $time,]);
                }

                // Create new checkin entry in Database
                Attendance::create(['temple_id' => $temple_id, 'check_in' => time(),]);

                // Response
                return response()->json(['check_in_status' => 'Y', 'time' => $time_stamp], 200);
            } else {
                // Update last checkin status to OUT (Of the day)
                Attendance::where('id', $check_for_prev_checkin_today->id)->update(['status' => 'OUT', 'time' => $time,]);

                // Response
                return response()->json(['check_in_status' => 'N', 'message' => 'Incorrect Location'], 200);
            }
        } else {
            // Near By Latitude and Longitude
            $long_low = $longitude - (.001 * $longitude);
            $long_high = $longitude + (.001 * $longitude);
            $lat_high = $latitude + (.001 * $latitude);
            $lat_low = $latitude - (.001 * $latitude);

            // Location Check if, its nearby location or not
            $check_for_location = Location::where('temple_id', $temple_id)
                ->orWhereIn('temple_id', $temples_id)
                ->whereBetween('latitude', [$lat_low, $lat_high])
                ->whereBetween('longitude', [$long_low, $long_high])
                ->first();

            // Check if its nearby location or not
            if (isset($check_for_location->temple_id)) {
                Attendance::create([
                    'temple_id' => $temple_id,
                    'check_in' => time()
                ]);
                return response()->json([
                    'check_in_status' => 'Y',
                    'time' => $time_stamp
                ], 200);
            } else {
                return response()->json(['check_in_status' => 'N', 'message' => 'Incorrect Location'], 200);
            }
        }
    }


    public function checkInSec(Request $request)
    {
        if (Auth::user()->temple_id != 'admin') {
            // check for any previous check in for the day
            $check_for_prev_checkin_today = Attendance::where('temple_id', Auth::user()->temple_id)
                ->where('created_at', '>', date('Y-m-d' . ' 00:00:00'))
                ->where('created_at', '<', date('Y-m-d' . ' 23:59:50'))
                ->get()->toArray();
            $checkIns = [];

            if ($check_for_prev_checkin_today != null) {
                $checkIn_Time = 'Y'; // var to know to user logged in today
                $i = 0;
                $check_in_time = Representative::where('temple_id', Auth::user()->temple_id)
                    ->first();
                if (isset($check_in_time->temple_id)) {
                    $check_in_time = $check_in_time->check_in_time; // check in time of the user
                } else {
                    $check_in_time = 36000; // else 10 am
                }
                $time_spent = 0;
                foreach ($check_for_prev_checkin_today as $prev_checkin) {
                    // at what time user checked in before
                    $checkIns[$i]['checkIn_Time'] = date("h:i a", $prev_checkin['check_in'] + 19800);
                    // time spent for one session
                    $checkIns[$i]['time_spent'] = $prev_checkin['time'];
                    $time_spent_hr = floor($checkIns[$i]['time_spent'] / (3600));
                    $left_sec = $prev_checkin['time'] % 3600;
                    $time_spent_min = round($left_sec / 60);
                    $checkIns[$i]['time_spent'] = $time_spent_hr . ':' . $time_spent_min;

                    $today_enter_time = strtotime($prev_checkin['created_at']);
                    // check in time for the session
                    $checkIns[$i]['today_enter_time'] = ($today_enter_time + 19800) % (24 * 60 * 60);

                    // check in time of the user is stored as the timestamp, see in to the db for clarity
                    $check_in_time = $check_in_time % (24 * 60 * 60); // gives the seconds if 10am is check in time, 10*60*60 is the value
                    $checkIns[$i]['late_time'] = round(($checkIns[$i]['today_enter_time'] - $check_in_time) / 60);

                    // if session timed out
                    if ($prev_checkin['status'] == 'OUT') {
                        //calculate the checko out time
                        $checkOut_Time = $prev_checkin['check_in'] + $prev_checkin['time'];
                        $checkIns[$i]['checkOut_Time'] = date('h:i a', $checkOut_Time + 19800);
                    }
                    $time_spent += $prev_checkin['time'];
                    $i++;
                }
                // status of the last session
                $checkIns[$i - 1]['status'] = $check_for_prev_checkin_today[sizeof($check_for_prev_checkin_today) - 1]['status'];
                if (Auth::user()->role == 5)
                    $remainingTime = 30600 - $time_spent;
                else
                    $remainingTime = 14400 - $time_spent;
                if ($remainingTime > 0) {
                    $remainingTime_hours = date('h', $remainingTime);
                    $remainingTime_mins = date('i', $remainingTime);
                    $remainingTime = 'You have ' . $remainingTime_hours . ' hours and ' . $remainingTime_mins . ' minutes' . ' to work for more.';
                } else {
                    $remainingTime = 'You have already worked for 4hrs.';
                }
                $time_spent = date('h:i', $time_spent);
            } else {
                $checkIn_Time = 'N';
            }
        } else {
            $checkIn_Time = 'N';
        }


        return response()->json(['checkIns' => $checkIns, 'checkIn_Time' => $checkIn_Time, 'checkOut_Time' => $checkOut_Time, 'time_spent' => $time_spent], 200);
    }

    // mark user checkd-in
    public function markCheckedIn(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d');
        //$date = "2022-04-28";
        $temple_id = Auth::user()->temple_id;
        //dd($temple_id);
        $create_recrd = '';
        $search_record = CheckIn::searchRecord($temple_id, $date);
        $update_attendance = Attendance::where('temple_id', Auth::user()->temple_id)->orderBy('id', 'desc')->take(1)->first();
        $update_attendance->check_out = time();
        $update_attendance->save();
        $create_attendace = Attendance::create([
            'temple_id'         =>      Auth::user()->temple_id,
            'check_in'          =>      time()
        ]);

        if (empty($search_record)) {
            $check_in_array = array(
                "sl"        =>      1,
                "type"      =>      "check-in",
                "time"  =>      date('Y-m-d H:i:s')
            );
            // create new record for today
            $create_recrd = checkIn::createRecord($temple_id, $date, $check_in_array);
        } else {
            $check_in_array = json_decode($search_record->checkIns, true);

            $check_in_array[count($check_in_array)] = array(
                "sl"        => ($search_record->today_checkIn_count + 1),
                "type"      =>      "check-in",
                "check_in"  =>      date('Y-m-d H:i:s')
            );
            $create_recrd = CheckIn::updateRecord($check_in_array, $search_record->id);
        }

        if ($create_recrd) {
            return response()->json(['type' => true, 'message' => 'checkin successfull']);
        }
    }


    // mark user checkout
    public function markCheckedOut()
    {
        Auth::logout();
        return response()->json(["type" => true, "message" => "record added"]);
        // rest code will be written later
        if (Auth::user()->role>0) {
            $save_att = "";
            $update_attendance = Attendance::where('temple_id', Auth::user()->temple_id)->orderBy('id', 'desc')->take(1)->first();
            $update_logout = "";

            if (!empty($update_attendance) && Auth::user()->name != 'admin') {
                $update_attendance->check_out = time();
                $update_logout = DB::table("users_login")->where(["temple_id" => Auth::user()->temple_id, 'login_status' => 1])->latest("id")->first();
                if (!empty($update_logout)) {
                    $update_table = DB::table("users_login")->where('id', $update_logout->id)->update([
                        "logout_time" => date("Y-m-d H:i:s")
                    ]);
                    $save_att = $update_attendance->save();
                    Auth::logout();
                    return response()->json(["type" => true, "message" => "record added"]);
                }
            }else{
                Auth::logout();
                return response()->json(["type" => true, "message" => "record added"]);
            }
// //            dd($auth_logout);
//             if ($auth_logout  || $save_att) {
//                 return response()->json(["type" => true, "message" => "record added"]);
//             }
        }else{
            return response()->json(["type" => true, "message" => "record added"]);
        }
        // login-hans-users?user_id=admin&user_role=9
    }
}
