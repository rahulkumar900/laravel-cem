<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use App\Models\FreeUsers;
use App\Lead;
use App\Models\UserCompatblity as Compatibility;
use App\LeadCompatibility;
use Carbon\Carbon;


class morningNotification extends Command
{
    /**
     * The name and signature of the console command.
     * pupular and newly joined se profiles uthane hain aur unhe hi send karne hain
     * @var string
     */
    protected $signature = 'morning:notification';
    protected $url4 = 'https://hansmatrimony.s3.ap-south-1.amazonaws.com/uploads/';
    protected $fcmId_arr = array();
    protected $fcmApp_arr = array();
    protected $mobile_arr = array();
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Morning Notifcation to all the users who has fcm id';

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
     * Handle a cron to send web/app push notificatoin in the morning.
     *
     * @SWG\Post(path="/cronMorningNotification",
    * summary="cron to send web/app push notificatoin in the morning",
    * * description=" business logic=>cron to send web/app push notificatoin in the morning and send to those who has not seen any user_data in a day yet table used=>userCompatibilities,leaduserCompatibilities,user_data,free_users,families,lead_family variable used=>getRandomProfile() ->this function returns a random profile from compatibility column of userCompatibilities/leaduserCompatibilities table, the return response will be a object, which has name,photo or No Profile pushNotification ->this send a web push notification shortlistPushNotification ->this send app push notification code logic=>fetch all the profile users which has either fcmId or fcmApp. run a loop over the user_data in getTheRandomprofile and send it as both notification web and app do the same for the leads table users ",
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
        $count1 = 0;
        $count2 = 0;
        $users = Profile::join('userCompatibilities', 'userCompatibilities.user_id', 'user_data.id')
            ->where('user_data.marital_status', '!=', 'Married')
            ->where('compatibility', '!=', null)
            ->where('compatibility', '!=', '[]')
            ->where('user_data.is_deleted', 0)
            ->where(function ($q) {
                $q->where('user_data.fcm_id', '!=', null)
                    ->orWhere('user_data.fcm_id', '!=', 'null')
                    ->orWhere('user_data.fcm_id', '!=', 'undefined')
                    ->orWhere('user_data.fcm_app', '!=', null)
                    ->orWhere('user_data.fcm_app', '!=', 'undefined')
                    ->orWhere('user_data.fcm_app', '!=', 'null');
            })
            ->select('user_data.user_mobile as mobile', 'userCompatibilities.*', 'user_data.id as id', 'user_data.name as name', 'fcm_id', 'fcm_app')
            ->get();

        foreach ($users as $user) {
            if (($user->current != 5 || $user->current != 6) && ($user->compatibility != null || $user->compatibility != "[]")) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                $object = app('App\Http\Controllers\NotificationController')->getRandomProfile( $user->user_id);
                //   dd($object);
                if ($object && $object != 'No Profile') {
                    $object = json_decode($object);
                    $name = $object->name;
                    $photo = $object->photo;
                    $fcmId = $user->fcm_id;
                    $fcmApp = $user->fcm_app;


                    //====send push notification=====
                    if ($fcmId != "" && $fcmId != 'undefined' && $fcmId != null && $fcmId != "" && $name != "No" && !in_array($fcmId, $this->fcmId_arr)) {

                        try {
                            $message_title = 'Hello, ' . $user->name;
                            $message = $name . ' has viewed your profile.';
                            app('App\Http\Controllers\AngularDashBoardController')->pushNotification($message_title, $message, $fcmId);
                            array_push($this->fcmId_arr, $fcmId);
                        } catch (\Exception $e) {
                        }
                    }

                    //===sendwebpushnotification
                    if ($fcmApp != "" && $fcmApp != 'undefined' && $fcmApp != null && $name != "No" && !in_array($fcmApp, $this->fcmApp_arr)) {
                        try {
                            $photo = $this->url4 . '' . $photo;
                            $message_title = 'Hello, ' . $user->name;
                            $message = $name . ' has viewed your profile.';
                            app('App\Http\Controllers\AngularDashBoardController')->ShortListPushNotification($photo, $fcmApp, $message, $message_title);
                            array_push($this->fcmApp_arr, $fcmApp);
                        } catch (\Exception $e) {
                        }
                    }

                    //======email content============

                }
                //====== end email content =======
            }
        }
        dd("done");
    }
}
