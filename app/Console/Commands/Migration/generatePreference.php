<?php

namespace App\Console\Commands\Migration;


use App\Models\UserData;
use App\Models\UserPreference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class generatePreference extends Command
{
    protected $signature = 'preference:generatepreference';

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
        $user_photos = UserData::leftJoin("userPreferences", "userPreferences.user_data_id", "user_data.id")->whereRaw("userPreferences.user_data_id is null")
        ->select(["user_data.id", "genderCode_user"])
        ->orderBy('user_data.id', 'DESC')
            ->chunk(50, function ($serdata) {
                foreach ($serdata as $users) {

                    $gender_pref = 1;
                    if ($users->genderCode_user == 1) {
                        $gender_pref = 2;
                    } else {
                        $gender_pref = 1;
                    }
                    //echo "\n user id : $users->id";
                    //dd(json_encode(array("467")));
                    $create_preference = UserPreference::updateOrCreate(
                        [
                            "user_data_id"      =>      $users->id
                        ],
                        [
                            "age_min"               =>      25,
                            "age_max"               =>      50,
                            "height_min"            =>      48,
                            "height_max"            =>      75,
                            "castePref"             =>      json_encode(array("11")),
                            "marital_statusPref"    =>      1,
                            "religionPref"          =>      json_encode(array("1")),
                            "user_data_id"          =>      $users->id,
                            "gender_pref"           =>      $gender_pref,
                            "height_min_s"          =>      48,
                            "height_max_s"          =>      75,
                            "validity"              =>      date('Y-m-d H:i:s'),
                            "amount_collected_date" =>      0,
                            "amount_collected_date" =>      date('Y-m-d H:i:s')
                        ]
                    );

                echo "\n user created : $create_preference->id";
                }
            });

        echo "sucess";
    }
}

