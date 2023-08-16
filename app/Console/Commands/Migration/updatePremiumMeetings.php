<?php

namespace App\Console\Commands\Migration;

use App\Models\UserData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class updatePremiumMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:updatePremiumMeeting';

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
     */
    public function handle()
    {
        // dd("ffff");
        //rds connection data that transferred
        /*$user_details =  DB::select("select id from user_data where is_delete = 0 AND is_deleted = 0 order by id asc");

        $user_details = json_decode(json_encode($user_details), true);
        $user_details = array_column($user_details, 'id');*/

        // update premiium meetings user_id
        $comp_profiles = DB::table('premium_meetings')->whereRaw("meeting_count is null")->orderBy('id', 'desc')->chunk(
            50,
            function ($premium_data) {
                foreach ($premium_data as $premium_users) {
                   // dd($premium_users);
                   echo "\n id ". $premium_users->id;
                    $update_premium =  DB::table('premium_meetings')->where('id', $premium_users->id)->update([
                        "meeting_count" =>      1
                    ]);
                    $get_user_id = UserData::whereRaw("profile_id in('$premium_users->user_id', '$premium_users->matched_id')")->get();
                    if (count($get_user_id) > 1 && !empty($get_user_id[0]->id) && !empty($get_user_id[1]->id)) {
                        $update_premium =  DB::table('premium_meetings')->where('id', $premium_users->id)->update([
                            "user_id"       =>      $get_user_id[0]->id,
                            "matched_id"    =>      $get_user_id[1]->id,
                            "meeting_count" =>      1
                        ]);
                    }
                }
            }
        );

        dd("complete");
        //   dd($profile->name);

    }
}
