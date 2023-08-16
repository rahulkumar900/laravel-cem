<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\UserCompatblity as Compatibility;

class DeleteOTAC01 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:one_time_amount_collected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating is_paid where amount_collected > 1100';

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
        // $records = DB::table('preferences as pre')->join('profiles as pro', function($join){
        //      $join->on('pro.identity_number', '=', 'pre.identity_number');
        //      $join->on('pro.temple_id', '=', 'pre.temple_id');
        //   })
        // ->where('pre.amount_collected', '>','1100')
        // ->update(['pro.is_paid'=>'1']);
        $profiles = Compatibility::whereNotNull('profile_status')
                    ->where('user_id','>','220668')
                    ->get();
        foreach($profiles as $profile)
        {
            echo "compatibility calculated \n";
            echo $profile->user_id;
        }
        echo "Compatibility calculated Successfully";
    }
}
