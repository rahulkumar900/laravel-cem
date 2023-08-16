<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;

class updateCheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Checkin Status each 5 minutes';

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
        $attendances = Attendance::where('created_at', '>=', date("Y-m-d" . " 00:00:00"))
        ->where('status', 'IN')->get();
        foreach ($attendances as $attendance) {
            $thirty_minutes_after = strtotime($attendance->updated_at) + 1800 + 19800;
            $thirty_minutes_after = date("Y-m-d H:i:s", $thirty_minutes_after);
            $now = date("Y-m-d H:i:s", time()+19800);
            if($now > $thirty_minutes_after) {
                echo "Checking Out ".$attendance->temple_id."----";
                Attendance::where('id', $attendance->id)->update(['status' => "OUT", 'time' => time() - $attendance->check_in]);
            }
        }
    }
}
