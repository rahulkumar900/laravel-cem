<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData  as Profile;
use App\Models\UserData  as FreeUsers;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserMatches;
use Carbon\Carbon;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMessage:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message daily to active chatbot users';

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
    public function handle()
    {
        $d_1 = Carbon::yesterday();
        $d_3 = Carbon::today()->subDays('7');
        $count1 = 0;
        $count2 = 0;
        //sending msg where last_seen is not null and last_seen is last 7 days old excluding today
        $users = Profile::whereNotNull('last_seen')->whereBetween('last_seen', [$d_3, $d_1])
            ->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
            ->select('user_mobile as mobile', 'userCompatibilities.*', 'user_data.id as id')
            ->get();

        foreach ($users as $user) {
            if ($user->current != 5 || $user->current != 6) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                $object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id, 0);
                $name = $object->name;
                // $name = $this->getProfileStatusName($user->id,0);
                if ($mobile && $name != "No") {
                    if (strpos($mobile, ',') !== False)
                        $mobile = substr($mobile, -11, 10);
                    else $mobile = substr($mobile, -10);

                    $msg = "Namastey _/\_
                        Hans Matrimony App par " . $name . " ne Aapki Profile Dekhi Hai.
                        Abhi Rishta Dekhe, Click Here: http://bit.ly/37aqyFI
                        Helpline: 9697989697";

                    $msg = urlencode($msg);
                    $sender = env('STATICKING_SENDER');
                    $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
                    try {
                        $result = file_get_contents($url);
                        $count1++;
                        echo "count1\n";
                        echo $count1;
                        echo "user_id \n";
                        echo $user->id;
                    } catch (\Exception $e) {
                        $count2++;
                        echo "count2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id;
                    }
                }
            }
        }

        //sending msg where last_seen is null so checking created_at is last 7 days old excluding today
        $users = Profile::whereNull('last_seen')->whereBetween('user_data.created_at', [$d_3, $d_1])->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')->select('user_data.user_mobile as mobile', 'compatibilities.*', 'user_data.id as id','user_data.name')->get();

        foreach ($users as $user) {
            if ($user->current != 5 || $user->current != 6) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                //$object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id, 0);
                $name = $user->name;
                // $name = $this->getProfileStatusName($user->id,0);
                if ($mobile && $name != "No") {
                    if (strpos($mobile, ',') !== False)
                        $mobile = substr($mobile, -11, 10);
                    else $mobile = substr($mobile, -10);

                    $msg = "Namastey _/\_
                        Hans Matrimony App par " . $name . " ne Aapki Profile Dekhi Hai.
                        Abhi Rishta Dekhe, Click Here: http://bit.ly/37aqyFI
                        Helpline: 9697989697";

                    $msg = urlencode($msg);
                    $sender = env('STATICKING_SENDER');
                    $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
                    try {
                        $result = file_get_contents($url);
                        $count1++;
                        echo "count1\n";
                        echo $count1;
                        echo "user_id \n";
                        echo $user->id;
                    } catch (\Exception $e) {
                        $count2++;
                        echo "count2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id;
                    }
                }
            }
        }


        //SEND MESSAGES FOR THE FREE USERS of whome last_seen is available



        $free_users = FreeUsers::whereNotNull('last_seen')->whereBetween('last_seen', [$d_3, $d_1])
            ->join('leads', 'leads.id', 'free_users.lead_id')
            ->join('leadCompatibilities', 'leadCompatibilities.user_id', 'leads.id')
            ->where('leads.profile_created', 0)
            ->select('leads.mobile as mobile', 'leadCompatibilities.*', 'leads.id as id')
            ->get();

        //     dd($d_3);
/*
        foreach ($free_users as $user) {
            if ($user->current != 5 || $user->current != 6) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                $object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id, 1);
                $name = $object->name;
                // $name = $this->getProfileStatusName($user->id,1);
                if ($mobile && $name != "No") {
                    if (strpos($mobile, ',') !== False)
                        $mobile = substr($mobile, -11, 10);
                    else $mobile = substr($mobile, -10);

                    $msg = "Namastey _/\_
                        Hans Matrimony App par " . $name . " ne Aapki Profile Dekhi Hai.
                        Abhi Rishta Dekhe, Click Here: http://bit.ly/37aqyFI
                        Helpline: 9697989697";
                    $sender = env('STATICKING_SENDER');
                    $msg = urlencode($msg);
                    $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
                    try {
                        $result = file_get_contents($url);
                        $count1++;
                        echo "count1\n";
                        echo $count1;
                        echo "user_id \n";
                        echo $user->id;
                    } catch (\Exception $e) {
                        $count2++;
                        echo "count2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id;
                    }
                }
            }
        }
        */
        //SEND MESSAGES FOR THE FREE USERS of whome last_seen is not  available but created_at



        $free_users = FreeUsers::whereNull('last_seen')->whereBetween('free_users.created_at', [$d_3, $d_1])
            ->join('leads', 'leads.id', 'free_users.lead_id')
            ->join('leadCompatibilities', 'leadCompatibilities.user_id', 'leads.id')
            ->where('leads.profile_created', 0)
            ->select('leads.mobile as mobile', 'leadCompatibilities.*', 'leads.id as id')
            ->get();

        //     dd($d_3);
/*
        foreach ($free_users as $user) {
            if ($user->current != 5 || $user->current != 6) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                $object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id, 1);
                $name = $object->name;
                //  $name = $this->getProfileStatusName($user->id,1);
                if ($mobile && $name != "No") {
                    if (strpos($mobile, ',') !== False)
                        $mobile = substr($mobile, -11, 10);
                    else $mobile = substr($mobile, -10);

                    $msg = "Namastey _/\_
                        Hans Matrimony App par " . $name . " ne Aapki Profile Dekhi Hai.
                        Abhi Rishta Dekhe, Click Here: http://bit.ly/37aqyFI
                        Helpline: 9697989697";
                    $sender = env('STATICKING_SENDER');
                    $msg = urlencode($msg);
                    $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
                    try {
                        $result = file_get_contents($url);
                        $count1++;
                        echo "count1\n";
                        echo $count1;
                        echo "user_id \n";
                        echo $user->id;
                    } catch (\Exception $e) {
                        $count2++;
                        echo "count2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id;
                    }
                }
            }
        }
        //find echo
        echo "find count1\n";
        echo $count1;
        echo "final count2\n";
        echo $count2;*/
    }

    public function getProfileStatusName($id, $is_lead)
    {

        /*if (!$is_lead) {
            $compatibility = Compatibility::where('user_data_id', $id)->first();
        }*/
        $compatibility = UserMatches::where('userAId', $id)->first();
        $name = "No";
        if ($compatibility) {
            $profile_status_arr = [];
            $profile_status = $compatibility->profile_status;
            $profile_status =  ($profile_status == null || $profile_status == '' || $profile_status == 'null') ? [] : json_decode($profile_status);
            if ($profile_status != '[]') {
                $profile_status =  array_reverse($profile_status);
                $flag_s = 0;
                $falg_p = 0;
                $flag_find = 0;
                $name = "No";

                foreach ($profile_status as $key) {
                    $profile = Profile::where('id', $key->user_id)->first();
                    $flag_j = $this->isJsProfile($key->user_id);
                    if (!$flag_j && $key->isProfileViewed == 1) {

                        if ($profile) {
                            $name = $profile->name;
                            break;
                        }
                    } elseif (!$flag_j && $key->isLiked == 1) {
                        if ($profile) {
                            $name = $profile->name;
                            break;
                        }
                    }
                }
                if ($name == 'No') {
                    $comp_datas = json_decode($compatibility->compatibility);
                    if (sizeof($comp_datas)) {
                        $profile = Profile::where('id', $comp_datas[0]->user_id)->first();
                        if ($profile) {
                            $name = $profile->name;
                        }
                    }
                }
            }
        }

        return $name;
    }

    public function isJsProfile($id)
    {
        $arr_words = array("Y", "Z", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "A", "B");
        $flag_j = false;
        foreach ($arr_words as $word) {
            if (($pos = strpos($id, $word)) !== false) {
                $flag_j = true;
                break;
            }
        }
        return $flag_j;
    }
}
