<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Attendende;
use App\Models\AuthControlFailedtxn;
use App\Models\CheckIn;
use App\Models\TeamLeader;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user_list = "";
        if (Auth::user()->role == config('constants.roles_ineger.admin') || Auth::user()->role == config('constants.roles_ineger.relationship_mananger')) {
            $user_list = User::getAllUsers()->where('matchmaker_is_user', '=', 'no')->where('mobile', '>', '0')->select('id', 'name', 'mobile', 'role', 'temple_id', 'created_at', 'morning', 'evening', 'dayoff', 'email')->orderBy('name', 'asc')->get();
        } else if (Auth::user()->role == config('constants.roles_ineger.team_leader')) {
            $temple_ids = TeamLeader::where('temple_id', Auth::user()->temple_id)->get(['access_temple_id']);
            $temple_ids = array_column($temple_ids->toArray(), 'access_temple_id');
            $user_list = User::getAllUsers()->where('matchmaker_is_user', '=', 'no')->where('mobile', '>', '0')->whereIn('temple_id', $temple_ids)
                ->select('id', 'name', 'mobile', 'role', 'temple_id', 'created_at', 'morning', 'evening', 'dayoff', 'email')->orderBy('name', 'asc')->get();
        } else {
            $user_list = array();
        }

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($user_list),
            "totaldisplayrecords" => count($user_list),
            "data" => $user_list
        );

        return response()->json($dataset);
    }

    public function loginOtherView()
    {
        if (Auth::user()->role == config('constants.roles_ineger.admin') || Auth::user()->role == config('constants.roles_ineger.team_leader')) {
            return view('admin.loginotheraccount');
        } else {
            return redirect()->back();
        }
    }

    public function userLoginDetails(Request $request)
    {
        return view('admin.view-user-login');
    }

    // login users
    public function loginUsersUsingOTP(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $mobile = substr($request->mobile, -10);
        $check = DB::table('users')->where(["users.mobile" => "91" . $mobile, 'matchmaker_is_user' => 'no'])->select('users.id', 'users.mobile', 'users.temple_id', 'users.morning', 'users.evening')->first();
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $response = '';
        // check user login time if time is between his morning and evening time then proceed login eles
        if (!empty($check) && date('H:i') >= $check->morning && date("H:i") <= $check->evening) {
            if (empty($request->received_otp)) {
                $auth_key = env('MSG_AUTH_KEY');
                $message = urlencode('##OTP## is your HANS MATRIMONY Login OTP.');
                $sender = 'INHANS';
                //dd('https://api.msg91.com/api/sendotp.php?authkey=' . $auth_key . '&mobiles=91' . $mobile . '&message=' . $message . '&sender=' . $sender . '&otp=&DLT_TE_ID=1207162341543384380');
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.msg91.com/api/sendotp.php?authkey=' . $auth_key . '&mobiles=91' . $mobile . '&message=' . $message . '&sender=' . $sender . '&otp=&DLT_TE_ID=1207162341543384380',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $json_decode_data = json_decode($response, true);

                if ($json_decode_data['type'] != "success") {
                    $response = array("status" => false, 'message' => "failed to send otp");
                    return response()->json($response);
                } else {
                    $response1 = array("status" => true, 'message' => 'One time Password is Successfully Send.');
                    return response()->json($response1);
                }
            }
        } else if (!empty($check) && date('H:i') <= $check->morning && date("H:i") >= $check->evening) {
            $mar_loing = DB::table('users_login')->insert([
                "temple_id"     =>      $check->temple_id,
                "login_status"  =>      0,
                'ip_address'    =>      $ip
            ]);
            $response1 = array("status" => false, 'message' => 'timeout you cannot login');
            return response()->json($response1);
        }
    }

    // verify user mobile using OTP
    public function verifyUserMobile(Request $request)
    {
        $mobile = '91' . substr($request->mobile_number, -10);
        $auth_key = env('MSG_AUTH_KEY');
        $received_otp = $request->received_otp;
        $message = urlencode('Your verification code is ##OTP##');
        $sender = 'INHANS';
        $mobile = "91" . substr($mobile, -10);
        $curl = curl_init();

        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://control.msg91.com/api/verifyRequestOTP.php?authkey=' . $auth_key . '&mobile=' . $mobile . '&otp=' . $received_otp . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_json = json_decode($response, true);
        //$decoded_json =array('type'=>'success');
        $data = $decoded_json;
        $i = 0;
        if (!empty($decoded_json)) {
            $check = DB::table('users')->where(["users.mobile" => $mobile, 'matchmaker_is_user' => 'no'])->select('users.id', 'users.mobile', 'users.temple_id', 'users.morning', 'users.evening')->first();

            Auth::loginUsingId($check->id);

            date_default_timezone_set('Asia/Kolkata');
            $date = date('Y-m-d');
            // create attendance
            $create_attendace = Attendance::create([
                'temple_id'         =>      Auth::user()->temple_id,
                'check_in'          =>      time()
            ]);

            $temple_id = Auth::user()->temple_id;
            $create_recrd = '';
            $search_record = CheckIn::searchRecord($temple_id, $date);

            if (empty($search_record)) {
                $check_in_array = array(
                    "sl"        =>      1,
                    "type"      =>      "check-in",
                    "time"  =>      date('Y-m-d H:i:s')
                );
                // create new record for today
                $create_recrd = checkIn::createRecord($temple_id, $date, $check_in_array);
                $mar_loing = DB::table('users_login')->insert([
                    "temple_id"     =>      $temple_id,
                    "login_status"  =>      0,
                    'ip_address'    =>      $ip
                ]);
            } else {
                $check_in_array = json_decode($search_record->checkIns, true);

                $check_in_array[count($check_in_array)] = array(
                    "sl"        => ($search_record->today_checkIn_count + 1),
                    "type"      =>      "check-in",
                    "check_in"  =>      date('Y-m-d H:i:s')
                );
                $create_recrd = CheckIn::updateRecord($check_in_array, $search_record->id);
                $mar_loing = DB::table('users_login')->insert([
                    "temple_id"     =>      $temple_id,
                    "login_status"  =>      1,
                    'ip_address'    =>      $ip
                ]);
            }

            return response()->json($data);
        } else {
            return response()->json(["type" => false, "message" => "something went wrong"]);
        }
    }

    // admin login
    public function adminLogin(Request $request)
    {
        $auth_attempt = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if ($auth_attempt) {
            $user_detail = User::where('email',$request->email)->first();
            Auth::loginUsingId($user_detail->id);
            $type = true;
            $message = 'login success';
        } else {
            $type = false;
            $message = 'login failed. Email or Password is Incorrect';
        }
        return response()->json(['type' => $type, 'message' => $message]);
    }

    // login other account
    public function loginOtherAccount(Request $request)
    {
        if (Auth::loginUsingId($request->user_id)) {
            return response()->json(['type' => true, 'message' => 'login success']);
        } else {
            return response()->json(['type' => false, 'message' => 'login failed try again']);
        }
    }

    public function getAllTemples()
    {
        
        $user_list = Cache::remember('all_temple_users', 60 * 60 * 24, function () {
            return User::whereIn('role', [1, 2, 3, 5, 7])->where('active_status', 1)->orderBy('name', 'asc')->get(['temple_id', 'name', 'id']);
        });
        return response()->json($user_list);
    }

    public function getEmployeesPage()
    {
        return view('hrms.index');
    }





    // used for member login at team member (depreciated)
    public function memberLogin(Request $request)
    {
        $mobile = $request->mobile;

        if (empty($mobile)) {
            return response()->json(['status' => 2, 'message' => 'mobile is required'], 200);
        } else if (!empty($mobile)) {
            // mobile is not empty check user existed or not
            $check_user = User::getUserDetailsByPhone($mobile);
            if (empty($check_user)) {
                return response()->json(['status' => 2, 'message' => 'user is not registered with us'], 200);
            } else if (!empty($check_user)) {
                // uesr existed send or check otp

                if (empty($request->verification_code)) {
                    // send password
                    $otp = rand(100000, 999999);
                    $otp_text = "<#> " . $otp . " is your OTP(One Time Password) for logging into the Matchmaker App.";
                    $mobiles = '+91' . substr($mobile, -10);
                    $username = env('STATICKING_USERNAME');
                    $password = env('STATICKING_PASSWORD');
                    $sender = env('STATICKING_SENDER');
                    $smsurl = "https://staticking.org/index.php/smsapi/httpapi/?uname=MY_USERNAME&password=MY_PASSWORD&sender=HANSAM&receiver=PHONE&route=TA&msgtype=1&sms=MESSAGE";
                    $smsurl = str_replace('MY_USERNAME', $username, $smsurl);
                    $smsurl = str_replace('MY_PASSWORD', $password, $smsurl);
                    $smsurl = str_replace('PHONE', $mobiles, $smsurl);
                    $smsurl = str_replace('MESSAGE', $otp_text, $smsurl);
                    try {
                        $result = file_get_contents($smsurl);
                        return response()->json(['status' => 1, 'message' => $smsurl], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => 2, 'message' => 'Some Error Occurred'], 400);
                    }
                } else {
                    // verify password
                    $otp  = $request->verification_code;
                    $auth_key = env('MSG_AUTH_KEY');
                    $message = urlencode('Your verification code is ##OTP##');
                    $sender = 'INHANS';
                    $mobile = "91" . substr($mobile, -10);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://control.msg91.com/api/verifyRequestOTP.php?authkey=$auth_key&mobile=$mobile&otp=$otp",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "",
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/x-www-form-urlencoded"
                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    $decoded_json = json_decode($response);
                    if ($decoded_json->type == 'success') {
                        // otp verified
                        $token = $this->generateToken();
                        $update_token = User::updateToken($check_user->id, $token);
                        if ($update_token) {
                            return response()->json(['status' => 1, 'token' => $token, 'user' => $check_user, 'message' => 'user verified sucessfully'], 200);
                        }
                    } else {
                        // otp verification failed
                        return response()->json(['status' => 2, 'message' => 'otp verification failed'], 200);
                    }
                }
            }
        }
    }

    // generate tocken
    public function generateToken()
    {
        $len = rand(35, 40);
        $token = '';
        $chars = "abcdefghijklmnopqrstuwxyz0123456789";
        $alphaLength = strlen($chars) - 1;
        for ($i = 1; $i <= $len; $i++) {
            $token .= $chars[rand(0, $alphaLength)];
        }
        return $token;
    }

    // get all rm list
    public function getAllRmList(Request $request)
    {
        $rm_lsit = '';

        $rm_lsit = User::where(["is_matchmaker" => 2])->orderBy('name', 'asc')->get();

        return response()->json($rm_lsit);
    }


    public function saveUserDetails(Request $request)
    {
        $support = 0;
        $approval = 0;
        $matchmaker = 0;
        if ($request->select_role == 1) {
            $support = 1;
            $approval = 0;
            $matchmaker = 0;
        } else if ($request->select_role == 2) {
            $support = 0;
            $approval = 1;
            $matchmaker = 0;
        } else if ($request->select_role == 3) {
            $support = 0;
            $approval = 0;
            $matchmaker = 2;
        }


        if ($request->user_id > 0) {
            $update_record = User::where('id', $request->user_id)->update([
                "morning"       =>      $request->login_start,
                "evening"       =>      $request->login_end,
                "dayoff"        =>      $request->off_day,
                "email"         =>      $request->user_email,
                "name"          =>      $request->user_name,
                "role"          =>      $request->select_role,
                "is_customer_support"   =>  $support,
                "is_approval_cce"   =>      $approval,
                "is_matchmaker"     =>   $matchmaker,
                "mobile"     =>   "91" . substr($request->user_mobile, 0, 10)
            ]);
            if ($update_record) {
                return response()->json(['type' => true, 'message' => 'record udpated']);
            }
        } else {
            // add  new record
            $update_record = User::create([
                "morning"       =>      $request->login_start,
                "evening"       =>      $request->login_end,
                "dayoff"        =>      $request->off_day,
                "email"         =>      $request->user_email,
                "name"          =>      $request->user_name,
                "role"          =>      $request->select_role,
                "is_customer_support"   =>  $support,
                "is_approval_cce"   =>      $approval,
                "is_matchmaker"     =>   $matchmaker,
                "mobile"     =>   "91" . substr($request->user_mobile, -10),
                "temple_id"     =>      round(microtime(true) * 1000)
            ]);
            if ($update_record) {
                return response()->json(['type' => true, 'message' => 'record added']);
            }
        }
    }

    public function getFailedTransaction()
    {
        $temple_logs = "";
        $temple_logs = AuthControlFailedtxn::getControlList();
        return view('admin.manage-failed-transction', compact('temple_logs'));
    }

    public function getTransactionList(Request $request)
    {

        $temple_logs = AuthControlFailedtxn::where('id', $request->txn_id)->update([
            'day'               =>      $request->team_day,
            'team_leader'       =>      $request->team_leaders,
        ]);

        if ($temple_logs) {
            return response()->json(['type' => true, 'message' => 'record updated']);
        }
    }

    public function updateLogoutTime(Request $request)
    {
        $update_record = DB::table('users_login')->where('id', $request->record_id)->update([
            "logout_time"       =>      date('Y-m-d H:i:s')
        ]);

        if ($update_record) {
            return response()->json(['type' => true, 'message' => 'logout successfully']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to logout']);
        }
    }

    public function showUserLogin(Request $request)
    {
        $login_data = UserLogin::getAllLoginDetails($request->login_date);
        return response()->json($login_data);
    }

    public function loginHansUsers(Request $request)
    {
        // $request->user_id $request->user_role

        $user_Details = User::where(['temple_id' => "$request->user_id", 'role' => $request->user_role])->first(['id']);
        if (!empty($user_Details)) {
            if (Auth::loginUsingId($user_Details->id) && $request->user_role == 9) {
                return redirect('user-dashboard');
            } else if (Auth::loginUsingId($user_Details->id) && $request->user_role != 9) {
                return redirect('user-dashboard');
            }
        }
    }
}
