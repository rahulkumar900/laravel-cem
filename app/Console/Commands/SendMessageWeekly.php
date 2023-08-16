<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use Carbon\Carbon;

class SendMessageWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMessage:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message weekly on sundays';

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
        $d_16 = Carbon::today()->subDays('16');
        $d_30 = Carbon::today()->subDays('30');


//sending msg where last_seen is not null and last_seen is between 16 to 30 days old
        $users = Profile::whereNotNull('last_seen')->whereBetween('last_seen', [$d_30, $d_16])
        ->get();

        foreach ($users as $user) {
            $mobile = $user->user_mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
            if ($mobile) {
                if (strpos($mobile, ',') !== False)
                    $mobile = substr($mobile, -11, 10);
                else $mobile = substr($mobile, -10);
                $msg = "Hans Matrimony
                Accha Rishta Hath Se Na Jaye
                Aaj Ke Rishtey Abhi Dekhe
                Click Kare: http://bit.ly/37cSpoT
                Helpline: 9654936205";
                $msg = urlencode($msg);
                $sender = env('STATICKING_SENDER');
                $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=".$sender."&receiver=".$mobile."&route=TA&msgtype=1&sms=".$msg;
                try {
                    $result = file_get_contents($url);
                } catch (\Exception $e) {}
            }
        }

//sending msg where last_seen is null and created_at is between 16 to 30 days old
        $users = Profile::whereNull('last_seen')->whereBetween('profiles.created_at', [$d_30, $d_16])
        ->get();

        foreach ($users as $user) {
            $mobile = $user->user_mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
            if ($mobile) {
                if (strpos($mobile, ',') !== False)
                    $mobile = substr($mobile, -11, 10);
                else $mobile = substr($mobile, -10);
                $msg = "Hans Matrimony
                Accha Rishta Hath Se Na Jaye
                Aaj Ke Rishtey Abhi Dekhe
                Click Kare: http://bit.ly/37cSpoT
                Helpline: 9654936205";
                $msg = urlencode($msg);
                $sender = env('STATICKING_SENDER');
                $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=".$sender."&receiver=".$mobile."&route=TA&msgtype=1&sms=".$msg;
                try {
                    $result = file_get_contents($url);
                } catch (\Exception $e) {}
            }
        }

    }
}
