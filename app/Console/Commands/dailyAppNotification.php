<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserMatches;
use Carbon\Carbon;

class dailyAppNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:appNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $url4 = 'https://hansmatrimony.s3.ap-south-1.amazonaws.com/uploads/';
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
        $users = Profile::whereNotNull('fcm_app')
            ->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
            ->select('user_data.user_mobile as mobile', 'userCompatibilities.*', 'user_data.id as id', 'user_data.name as name', 'fcm_app')
            ->get();
        foreach ($users as $user) {
            if (($user->current != 5 || $user->current != 6) && ($user->compatibility != null || $user->compatibility != "[]")) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);

                $name = $user->name;
                $photo = $user->photo_url[0];
                $fcmId = $user->fcm_app;
                if (($photo == "No" || $photo == null) && $user->gender == 'Male') {
                    $photo = "https://hansmatrimony.s3.ap-south-1.amazonaws.com/webImages/female_user.jpeg";
                } else if (($photo == "No" || $photo == null) && $user->gender == 'Female') {
                    $photo = "https://hansmatrimony.s3.ap-south-1.amazonaws.com/webImages/male_user.jpeg";
                } else {
                    $photo = $this->url4 . '' . $photo;
                }
                if ($fcmId != "" && $fcmId != 'undefined' && $fcmId != null && $fcmId != "" && $name != "No") {

                    try {
                        $message_title = 'Hello, ' . $user->name;
                        $message = $name . ' has viewed your profile and seems interested.';

                        app('App\Http\Controllers\NotificationController')->ShortListPushNotification($photo, $fcmId, $message, $message_title);
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
        echo "done";
    }
    public function getProfileStatusName($id, $is_lead)
    {

        if (!$is_lead) {
            $compatibility = UserMatches::where('userAId',$id)->get();
        }
        $object = new \StdClass;
        $object->name = "No";
        $object->photo = null;
        $name = "No";
        if ($compatibility) {
            $profile_status_arr = [];

            if ($compatibility != '[]') {
                $profile_status =  array_reverse($compatibility);
                $flag_s = 0;
                $falg_p = 0;
                $flag_find = 0;
                $object->name = "No";

                foreach ($profile_status as $key) {
                    $profile = Profile::where('id', $key->user_id)->first();
                    $flag_j = $this->isJsProfile($key->user_id);
                    if (!$flag_j && $key->isPostponed == 1) {

                        if ($profile) {
                            $object->name = $profile->name;
                            $object->photo = $profile->photo_url[0];
                            break;
                        }
                    } elseif (!$flag_j && $key->isLiked == 1) {
                        if ($profile) {
                            $object->name = $profile->name;
                            $object->photo = $profile->photo_url[0];
                            break;
                        }
                    }
                }
            }
        }

        return $object;
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
