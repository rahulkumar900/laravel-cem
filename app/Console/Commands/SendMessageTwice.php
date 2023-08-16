<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use App\Models\UserData as FreeUsers;
use Carbon\Carbon;

class SendMessageTwice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMessage:twice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message twice a week, on Tue and Sat';

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
        $d_8 = Carbon::today()->subDays('8');
        $d_15 = Carbon::today()->subDays('15');

//sending msg where last_seen is not null and last_seen is between 8 to 15 days old
        $users = Profile::whereNotNull('last_seen')->whereBetween('last_seen', [$d_15, $d_8])
        ->get();

        foreach ($users as $user) {
            $mobile = $user->user_mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
            if ($mobile) {
                if (strpos($mobile, ',') !== False)
                    $mobile = substr($mobile, -11, 10);
                else $mobile = substr($mobile, -10);
                $msg = "Hans Matrimony
                Namastey
                Aaj Ke Rishtey Abhi Dekhe
                Yaha Click Kare: http://bit.ly/2StYnh3
                Hans Helpline: 9654936205";
                $sender = env('STATICKING_SENDER');
                $msg = urlencode($msg);
                $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=".$sender."&receiver=".$mobile."&route=TA&msgtype=1&sms=".$msg;
                try {
                    $result = file_get_contents($url);
                } catch (\Exception $e) {}
            }
        }

    }
}
