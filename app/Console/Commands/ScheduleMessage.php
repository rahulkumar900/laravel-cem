<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:msg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule messages';

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
        $before_7_days = Carbon::now()->subDays(7);
        $now = Carbon::now();
        $nos = DB::table('whatsapp_messages')
//        ->where('from', '9918419947')
        ->whereBetween('created_at', [$before_7_days, $now])->groupBy('from')->pluck('from');
        foreach($nos as $no) {echo "Number: {$no}\n";
            $msg = "Free 6 Rishtey Har Roz Dekhe\n\nPehle Rishte Dekhe Phir Vishwas Kare\n\nClick Here: http://hansmatrimony.com/chat?mobile={$no} \n\nHelpline: 9697989697";
            $mobile = "91".$no;
            $username = env('INFOBIP_USERNAME');
            $password = env('INFOBIP_PASSWORD');
            $msg = urlencode($msg);
            $url = "https://dqell.api.infobip.com/sms/1/text/query?username=".$username."&password=".$password."&to=".$mobile."&text=".$msg;
            try {
                file_get_contents($url);
            } catch (\Exception $e) {

            }
        }
    }
}
