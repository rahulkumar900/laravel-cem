<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use App\Models\userData as FreeUsers;
use Carbon\Carbon;

class SendMessageAlternatively extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMessage:alternate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message on alternate days';

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
        $d_4 = Carbon::today()->subDays('4');
        $d_7 = Carbon::today()->subDays('7');

        //sending msg where last_seen is not null and last_seen is between 4 to 7 days old
        $users = Profile::whereNotNull('last_seen')->whereBetween('last_seen', [$d_7, $d_4])
            ->get();

        foreach ($users as $user) {
            $mobile = $user->user_mobile ? $user->mobile : ($user->whatsapp ? $user->whatsapp : null);
            if ($mobile) {
                if (strpos($mobile, ',') !== False)
                    $mobile = substr($mobile, -11, 10);
                else $mobile = substr($mobile, -10);
                $msg = "Hans Matrimony
                Acche Rishtey Ko Hai Aapka Intezar
                Aaj Ke Rishtey Abhi Dekhe
                Click Kare: http://bit.ly/2F5OLkp
                Helpline: 9654936205";
                $msg = urlencode($msg);
                $sender = env('STATICKING_SENDER');
                $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
                try {
                    $result = file_get_contents($url);
                } catch (\Exception $e) {
                }
            }
        }
    }
}
