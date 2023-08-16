<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class updateIsPremium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ispremium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update is_premium';

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
    $premium = DB::table('userPreferences as pre')->join('user_data as pro', function ($join) {
      $join->on('pro.identity_number', '=', 'pre.identity_number');
      $join->on('pro.temple_id', '=', 'pre.temple_id');
    })
      ->whereRaw(" ((pro.is_invisible = 1) OR (pre.amount_collected > 8000) OR (pro.plan_name like '%Personalized%') OR (pro.plan_name like '%Bicholiya %') OR (pro.plan_name like '%Matchmaker Plan%') OR (pro.plan_name like '%Upgradation%') OR (pro.plan_name like '%Deluxe%'))")
      ->update([
          'is_premium'      =>      1
      ]);
      echo "updated successfully";
    }
}
