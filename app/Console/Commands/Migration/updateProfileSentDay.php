<?php

namespace App\Console\Commands\Migration;


use App\Models\UserData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateProfileSentDay extends Command
{
    protected $signature = 'profile:updateProfileSentDay';

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


    public function handle()
    {
        $profile_sent_day = DB::table('profiles')->select(["profiles.profile_sent_day", "profiles.mobile_profile"])->orderBy('id', 'DESC')
            ->chunk(50, function ($profile_data) {
                foreach ($profile_data as $users) {
                    $update_user_data = UserData::where('mobile_profile', $users->mobile_profile)->update([
                        "profile_sent_day"     =>      $users->profile_sent_day
                    ]);
                }
            });


        // $user_photos = UserData::join("profiles", "profiles.mobile_profile", "user_data.mobile_profile")
        // ->whereRaw("user_data.profile_sent_day is not null")->select(["user_data.id", "profiles.profile_sent_day"])->orderBy('id', 'DESC')
        //     ->chunk(50, function ($serdata) {

        //     });
        echo "sucess";
    }
}
