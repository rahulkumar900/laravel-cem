<?php

namespace App\Console\Commands\Migration;


use App\Models\UserData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class photoToPhotoUrl extends Command
{
    protected $signature = 'photo:phototophotourl';

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
        DB::select("update user_data set carousel =null where carousel = '';");
        DB::select("update user_data set carousel =null where carousel = 'null';");


        $user_photos = UserData::whereRaw("(carousel is not null or carousel != '') and photo_url is null")->select(["id", "pending_temple_id", "photo", "photo_url", "carousel"])->orderBy('id', 'DESC')
            ->chunk(50, function ($serdata) {
                foreach ($serdata as $users) {
                    //dd($users->id);
                   // echo "\n new linw $users->id";
                    $photo_data = json_decode($users->carousel, true);
                    //dd(count($photo_data));
                    if (count($photo_data) > 0) {
                        $photo_carouse = array_values($photo_data);
                        var_dump($photo_carouse);

                        $update_user_data = UserData::where('id', $users->id)->update([
                            "photo_url"     =>      json_encode($photo_carouse),
                            "photo"         =>      $photo_carouse[0]
                        ]);
                    }
                }
            });

        echo "sucess";
    }
}
