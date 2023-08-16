<?php

namespace App\Console\Commands;

use App\Models\UserCompatblity as Compatibility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class resetDailyQuota extends command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:dailyQuota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Daily Quota and Max to the users whose limit has been exceeded';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $send_notification  = "";
        $last_thre_days = date("Y-m-d", strtotime("-3 day"));
        // dd($last_thre_days);
        // user_data quota
        $total_users = DB::select("SELECT user_data.fcm_id,user_data.name, user_data.fcm_app, user_data.photo, user_data.last_seen, user_data.id FROM user_data
        INNER JOIN userCompatibilities ON userCompatibilities.user_data_id = user_data.id
        WHERE DATE(user_data.last_seen) >= '" . $last_thre_days . "'
        ORDER BY user_data.last_seen DESC");

        if (!empty($total_users)) {
            $i=1;
            foreach ($total_users as $profile_users) {
                $update_profile = Compatibility::where('user_data_id', $profile_users->id)->update([
                    'max'           =>          '-1',
                    'daily_quota'   =>          '0'
                ]);
                //if ($update_profile) {
                    $photo = $profile_users->photo_url[0];
                    $fcmId = $profile_users->fcm_id;
                    $fcmApp = $profile_users->fcm_app;
                    $message = 'Hi ' . $profile_users->name . ', your daily picks have been refreshed ';
                    $message_title = 'Hi ' . $profile_users->name . ', your daily picks have been refreshed 🎉. Tap to see new user_data ✨';

                    if(!empty($fcmId)){
                        $send_notification = app('App\Http\Controllers\NotificationController')->appPushNotification($message_title,$message,$fcmId);
                        echo "Send FCM Id Profile Notification ".$i." \n";
                    }else if(!empty($fcmApp)){
                        $send_notification = app('App\Http\Controllers\NotificationController')->shortListPushNotification($photo,$fcmApp,$message,$message_title);
                        echo "Send FCM App Profile Notification ".$i." \n";
                    }
                $i++;
            }
        } else {
            echo "No record found\n";
        }
    }
}


//
