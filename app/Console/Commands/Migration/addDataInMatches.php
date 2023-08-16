<?php

namespace App\Console\Commands\Migration;

use App\Models\UserData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class addDataInMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:dataInMatches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To migrate the preferences data user_details table';

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
     * Cron to move profile_status columns values of compatibilities table details from old database to new database(rds) .
     *
     * @SWG\Post(path="/cron/moveprofileSttausTomatches",
     *   summary="moving old database details to new database, data fill for the matches table from profile_status of compatibiliies table ",
     *   description="business logic => fill matches table in new database from compatibilities table table used => compatibilities(old database),matches (new database) code logic -> before running any cron, run transfer compatibilities details to user_compatibility table cron, in matches table, will save each profile_status value with single row, and whatever the status will be in profile_status, will marked as binary value (0,1), corresponding with respective column e.g status -> S , this will be marked as 1 coresponding to is_liked column of matches table, timestamp will be saved as liked_time for now  the values of id1,id2 are of profiles table(old database), please replace it with the user_details id, of the corresponding to profile_id, of user_details table run the insert function on new database NOTE: not transferring the profile_status values ",
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
     *
     */
    public function handle()
    {
        // dd("ffff");
        //rds connection data that transferred
        /*$user_details =  DB::select("select id from user_data where is_delete = 0 AND is_deleted = 0 order by id asc");

        $user_details = json_decode(json_encode($user_details), true);
        $user_details = array_column($user_details, 'id');*/

        $comp_profiles = DB::table('compatibilities')->whereRaw("profile_status is not null")
        ->join('profiles', 'profiles.id', 'compatibilities.user_id')
        ->where(["profiles.is_deleted"=>0,"profiles.is_premium"=>1, "reminder" => 0])
        ->select(["compatibilities.profile_status", "compatibilities.user_id", "compatibilities.id", "mobile_profile"])
        ->orderBy('compatibilities.id', 'desc')->chunk(50, function ($profile_datas) {
            foreach ($profile_datas as $profile) {

                echo "\n compat id : " . $compt_id = $profile->id . "\n";
                $user_id = $profile->user_id;
                $decoded_data = json_decode($profile->profile_status, true);
                //dd($decoded_data);
                if (!empty($decoded_data) && count($decoded_data) > 0) {
                    for ($i = 0; $i < count($decoded_data); $i++) {
                        $key = $decoded_data[$i];
                        $isLiked = 0;
                        $isRejected = 0;
                        $isContacted = 0;
                        $likedDateTime = null;
                        $time_Stamp = time();

                        $rejectedDateTime = null;
                        $contactedDateTime = null;
                        $contactedDateTime = null;
                        $contactedDateTime = null;

                        if (!empty($key['status']) && $key['status'] == "R") {
                            $isRejected = 1;

                            $rejectedDateTime = date('Y-m-d H:i:s');

                        } else if (!empty($key['status']) && ($key['status'] == "S" || $key['status'] == "SI")) {
                            $isLiked = 1;

                            $time_Stamp = date('Y-m-d H:i:s');

                        } else if (!empty($key['status']) && $key['status'] == "C") {
                            $isContacted = 1;
                            $contactedDateTime = date('Y-m-d H:i:s');
                        } else {
                            $isLiked = 1;

                            $time_Stamp = date('Y-m-d H:i:s');
                        }

                        $user_id_sec = $key['user_id'];
                        $comp_profiles = DB::table('profiles')->whereRaw("id in ('$user_id', '$user_id_sec')")->get(['mobile_profile']);
                        if (count($comp_profiles) > 1) {
                            $mobile_one = $comp_profiles[0]->mobile_profile;

                            $mobile_two = $comp_profiles[1]->mobile_profile;

                            $user_data_ids = UserData::WhereRaw("mobile_profile ='$mobile_one'")->first();

                            $user_data_sec = UserData::WhereRaw("mobile_profile = '$mobile_two'")->first();

                            if (!empty($user_data_sec) && !empty($user_data_ids)) {
                                $userAid = $user_data_sec->id;
                                $userBid = $user_data_ids->id;
                                //echo "\n usr A" . $userBid . ", user B" . $userAid . ",\n profile_id" . $user_id;
                                $check_exist = DB::select('select id from userMatches where userAid = ' . $userAid . ' and userBid = \'' . $userBid . '\'');
                                if (empty($check_exist)) {
                                    $platformId = 1;
                                    $today = date('Y-m-d H:i:s');
                                    DB::insert("INSERT INTO userMatches(userAid,userBid,platformId,isLiked,isRejected,isContacted,profileSectionId,isFreeProfile,isVirtualLike,timestamp,created_at, updated_at ) VALUES ('" . $userAid . "','" . $userBid . "','" . $platformId . "','" . $isLiked . "','" . $isRejected . "','" . $isContacted . "','" . 0 . "','" . 0 . "','" . 0 . "','" . $time_Stamp . "','$today','$today')");
                                }
                            }
                        }
                    }
                }

                DB::table('compatibilities')->where('id', $compt_id)->delete();
            }
        });
        echo"complete";

    }
}

/*


            //isLike if status == s
            //isrejected if status == R
            //isContacted if status == C
            //rejected time
            //liked time
            //contactedTime

            //profileSectin id
                //todayProfile = 1
                //discovery = 2
                //view = 3
*/
