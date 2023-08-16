<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Lead;
use Carbon\Carbon;

class attendanceAndPendnigPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_points:Attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating user points ';

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
    public function handle() {
        $users = DB::table('checkIns')
                ->where('checkIn_date',date('Y-m-d'))
                ->where('today_checkIn_count','>=',16)
                ->get();

        foreach ($users as $key => $value) {
            $att = floor($value->today_checkIn_count/16);
            if($att>=1){
                DB::table('points')->insert([
                    'point'=>$att,
                    'temple_id'=>$value->temple_id,
                    'reason'=>'1'
                ]);
                echo $value->temple_id."\n";
            }
        }
    }
}
