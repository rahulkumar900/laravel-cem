<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use App\Models\FreeUser as FreeUsers;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserCompatblity as LeadCompatibility;
use App\Models\UserMatches;
use Carbon\Carbon;

class sendWebPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEvening:webnotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = Profile::whereNotNull('fcm_id')
            ->join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
            ->select('user_data.user_mobile as mobile', 'userCompatibilities.*', 'user_data.id as id', 'user_data.name as name', 'fcm_id')
            ->groupBy('user_data.fcm_id')
            //  ->limit(1)
            ->get();

        foreach ($users as $user) {
            if (($user->current != 5 || $user->current != 6) && ($user->compatibility != null || $user->compatibility != "[]")) {
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);

                $object = $this->getProfileStatusName($user->id, 0);
                $name = $object->name;
                $photo = $object->photo;
                $fcmId = $user->fcm_id;

                if ($fcmId != "" && $fcmId != 'undefined' && $fcmId != null && $fcmId != "" && $name != "No") {

                    try {
                        $message_title = 'Hello, ' . $user->name;
                        $message = $name . ' has viewed your profile and seems interested.';
                        app('App\Http\Controllers\NotificationController')->pushNotification($message_title, $message, $fcmId);
                        $count1++;
                        echo "count1\n";
                        echo $count1;
                        echo "user_id \n";
                        echo $user->id;
                    } catch (\Exception $e) {
                        //    dd($e->getMesage());
                        $count2++;
                        echo "count2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id;
                    }
                }
            }
        }
    }
    public function getProfileStatusName($id, $is_lead)
    {
        $compatibility = UserMatches::where('userAId', $id)->get();

        $object = new \StdClass;
        $object->name = "No";
        $object->photo = null;
        $name = "No";
        if ($compatibility) {

            if ($compatibility != '[]') {
                $object->name = "No";

                foreach ($compatibility as $key) {
                    $profile = Profile::where('id', $key->user_id)->first();
                    $flag_j = $this->isJsProfile($key->user_id);

                    if (!$flag_j && $key->isProfileViewed == 1) {
                        if ($profile) {
                            $object->name = $profile->name;
                            $object->photo = $profile->photo_url[0];

                            $updateData = UserMatches::where(['id',$key->id])->update([
                                "isVirtualLike"     =>      1,
                                "timestamp"         =>      strtotime(date('Y-m-d H:i:s')),
                                "expiryDateTime"    =>      date('Y-m-d H:i:s', strtotime('+1 days'))
                            ]);

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
                            $object->photo = $profile->photo_url[0];
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
