<?php

namespace App\Console\Commands;

use App\Models\UserPreference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateUSerDataId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:updateUSerDataId';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To update the activity of the users who registered in last three months';

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

        $select_ids = UserPreference::join('user_data', function($join){
            $join->on('userPreferences.identity_number', '=', 'user_data.identity_number');
            $join->on('userPreferences.temple_id', '=', 'user_data.temple_id');
        })->whereRaw("userPreferences.user_data_id is null")->select(["userPreferences.id", "user_data.id as user_data_id"])->orderBy('userPreferences.id', 'DESC')
        ->chunk(50, function ($serdata) {
            foreach ($serdata as $users) {
               echo $users->id." | ".$users->user_data_id;
                $update_user_data = UserPreference::where('id', $users->id)->update([
                    "user_data_id"     =>      $users->user_data_id
                ]);
            }
        });
        echo "done";
    }
}
