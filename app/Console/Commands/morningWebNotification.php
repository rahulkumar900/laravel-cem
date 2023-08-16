<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\UserData as Profile;
use App\FreeUsers;
use App\Models\Lead;
use App\Models\UserCompatblity as Compatibility;
use App\LeadCompatibility;
use Carbon\Carbon;

class morningWebNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morning:webPushNotification';

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
        $count2 =0;
        $users = Profile::whereNotNull('fcm_id')
        ->join('families', 'families.id', 'profiles.id')
        ->join('compatibilities','compatibilities.user_id','profiles.id')
      //  ->where('profiles.id',221018)
        ->select('families.mobile as mobile','compatibilities.*','profiles.id as id','profiles.name as name','fcm_id')
        ->groupBy('profiles.fcm_id')
      //  ->limit(1)
        ->get();

        foreach ($users as $user) {
            if(($user->current != 5 || $user->current != 6)&&($user->compatibility != null || $user->compatibility != "[]")){
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                   // $object = $this->getProfileStatusName($user->id,0);
                    $object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id,0);
                    $name = $object->name;
                    $photo = $object->photo;
                    $fcmId = $user->fcm_id;

                if ($fcmId != "" && $fcmId != 'undefined' && $fcmId != null && $fcmId!="" && $name != "No") {

                try {
                    $message_title = 'Hello, '.$user->name;
                    $message = $name. ' has viewed your profile.';
                     // $fcmId= "fzAzSjGm-iLp3BwEd9SwyN:APA91bG_hEBNCDPEZeUZ9ojh-u9ecbXSXgs6TuDhg0fGp6EF-TiapUgv9pDoUMKVlcFFZwn6X177FnMYD4N5UwQud2nSBg-_WLaE9lmP46Jnz9BPiKxztZh8pFgyAKmusEl5W2l3SnqW";
                    app('App\Http\Controllers\AngularDashBoardController')->pushNotification($message_title,$message,$fcmId);
                  //  echo $photo;
                    $count1++;
                    echo "count1\n";
                    echo $count1;
                    echo "user_id \n";
                    echo $user->id;

                }
                    catch (\Exception $e) {
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


//SEND MESSAGES FOR THE FREE USERS



        $free_users = FreeUsers::whereNotNull('fcm_id')
        ->join('leads','leads.id','free_users.lead_id')
        ->join('leadCompatibilities','leadCompatibilities.user_id','leads.id')
        ->where('leads.profile_created',0)
        ->select('leads.mobile as mobile','leadCompatibilities.*','leads.id as id','leads.name as name','fcm_id')
        ->groupBy('fcm_id')
      //  ->limit(1)
        ->get();



                foreach ($free_users as $user) {
            if(($user->current != 5 || $user->current != 6)&&($user->compatibility != null || $user->compatibility != "[]")){
                $mobile = $user->mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
                   // $object = $this->getProfileStatusName($user->id,1);
                    $object = app('App\Http\Controllers\NotificationController')->getProfileStatusName($user->id,1);
                    $name = $object->name;
                    $photo = $object->photo;
                    $fcmId = $user->fcm_id;

               if ($fcmId != "" && $fcmId != 'undefined' && $fcmId != null && $fcmId!="" && $name != "No") {

                try {
                    $message_title = 'Hello, '.$user->name;
                    $message = $name. ' has viewed your profile.';

                    app('App\Http\Controllers\AngularDashBoardController')->pushNotification($message_title,$message,$fcmId);
                    $count1++;
                     echo "count1\n";
                    echo $count1;
                    echo "user_id \n";
                    echo $user->id;

                }
                    catch (\Exception $e) {
                        $count2++;
                         echo "\ncount2\n";
                        echo $count2;
                        echo "user_id \n";
                        echo $user->id."\n";
                    }
                }
            }
        }

    }
    public function getProfileStatusName($id,$is_lead){

        if(!$is_lead){
            $compatibility = Compatibility::where('user_id',$id)->first();
        }
        else{
            $compatibility = LeadCompatibility::where('user_id',$id)->first();
        }
        $object = new \StdClass;
        $object->name = "No";
        $object->photo = null;
        $name = "No";
        if($compatibility){
            $profile_status_arr = [];
            $profile_status = $compatibility->profile_status;
            $profile_status =  ($profile_status == null || $profile_status == '' || $profile_status == 'null')? [] : json_decode($profile_status);
            if($profile_status != '[]'){
                $profile_status =  array_reverse($profile_status);
                $flag_s = 0;
                $falg_p = 0;
                $flag_find = 0;
                $object->name = "No";

                foreach ($profile_status as $key) {
                    $flag_j=$this->isJsProfile($key->user_id);
                    if(!$flag_j && $key->status == 'P'){
                        $profile = Profile::where('id',$key->user_id)->first();
                       // dd("162");
                        if($profile){
                            $object->name = $profile->name;
                            $object->photo = app('App\Http\Controllers\AngularController')->carouselToPhoto(0,$profile->id);
                            break;
                        }
                    }
                    elseif (!$flag_j && $key->status == 'S') {
                        $profile = Profile::where('id',$key->user_id)->first();
                        if($profile){
                            $object->name = $profile->name;
                            $object->photo = app('App\Http\Controllers\AngularController')->carouselToPhoto(0,$profile->id);
                            break;
                        }
                    }
                }
                if($name == 'No'){
                    $comp_datas = json_decode($compatibility->compatibility);
                    if(sizeof($comp_datas)){
                        $profile = Profile::where('id',$comp_datas[0]->user_id)->first();
                        if($profile){
                            $name = $profile->name;
                            $object->photo = app('App\Http\Controllers\AngularController')->carouselToPhoto(0,$profile->id);
                        }
                    }
                }
            }
        }

        return $object;
    }

    public function isJsProfile($id){
        $arr_words = array("Y","Z", "C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","A","B");
        $flag_j =false;
        foreach ($arr_words as $word) {
            if(($pos = strpos($id, $word)) !== false) {
                    $flag_j = true;
                    break;
                    }
                }
            return $flag_j;
        }
}
