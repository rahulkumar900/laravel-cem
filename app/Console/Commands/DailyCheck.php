<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\UserPreference as Preference;
use App\Models\Content;

class DailyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command for messaging the expired user_data. ';

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
        //

        $user_data = DB::table('user_data')
            ->join('userPreferences', 'userPreferences.user_data_id','user_data.id')
            ->join('users', 'users.temple_id', 'user_data.temple_id')
            ->where('user_data.marital_status', '!=', "'Married'")
            ->select('user_data.*', 'userPreferences.amount_collected', 'users.name as temple_name' )
            ->orderBy('followup_call_on', 'asc')->get();
        foreach ($user_data as $profile) {
            $profile->timeExpired = 'No';
            $profile->limitExpired = 'No';
            $profile->dateExpiresAt = '';
            $dueto = '';
            $profile->overAllStatus = 'Active';
            $pre = Preference::where('user_data_id', $profile->id)->first();
            if ($profile->plan_name == null) {
                if ($pre->amount_collected <= 4000) {
                    $expires_at = date_create($profile->created_at);
                    date_add($expires_at, date_interval_create_from_date_string('180 days'));
                    $expires_at = $expires_at->format('Y-m-d H:i:s');
                    $profile->dateExpiresAt = $expires_at;
                    if (time() > strtotime($expires_at)) {
                        $profile->timeExpired = 'Yes';
                        $dueto = 'expired';
                    }
                    if ($profile->user_data_sent > 45) {
                        $profile->limitExpired = 'Yes';
                        $dueto = 'expired';
                    }
                    if ($profile->timeExpired == 'Yes' && $profile->limitExpired == 'Yes') {
                        $profile->overAllStatus = 'Expired';
                        $profile->upgrade_renew = 1;
                        $dueto = 'expired';
                    }
                } elseif ($pre->amount_collected <= 8000) {
                    $expires_at = date_create($profile->created_at);
                    date_add($expires_at, date_interval_create_from_date_string('365 days'));
                    $expires_at = $expires_at->format('Y-m-d H:i:s');
                    $profile->dateExpiresAt = $expires_at;
                    if (time() > strtotime($expires_at)) {
                        $profile->timeExpired = 'Yes';
                        $dueto = 'expired';
                    }
                    if (($profile->user_data_sent / 2) > 45) {
                        $profile->limitExpired = 'Yes';
                        $dueto = 'expired';
                    }
                    if ($profile->timeExpired == 'Yes' && $profile->limitExpired == 'Yes') {
                        $profile->overAllStatus = 'Expired';
                        $profile->upgrade_renew = 1;
                        $dueto = 'expired';
                    }
                } elseif ($pre->amount_collected > 8000) {
                    $expires_at = date_create($profile->created_at);
                    date_add($expires_at, date_interval_create_from_date_string('365 days'));
                    $expires_at = $expires_at->format('Y-m-d H:i:s');
                    $profile->dateExpiresAt = $expires_at;
                    if (time() > strtotime($expires_at)) {
                        $profile->timeExpired = 'Yes';
                        $profile->limitExpired = 'Yes';
                    }
                    if ($profile->timeExpired == 'Yes') {
                        $profile->overAllStatus = 'Expired';
                        $profile->upgrade_renew = 1;
                        $dueto = 'expired';
                    }
                }
            } else {
                $validity = Content::where('type', $profile->plan_name)->value('validity');
                $expires_at = date_create($profile->created_at);
                date_add($expires_at, date_interval_create_from_date_string('' . $validity . 'months'));
                $expires_at = $expires_at->format('Y-m-d H:i:s');
                $profile->dateExpiresAt = $expires_at;

                if (time() > strtotime($expires_at)) {
                    $profile->timeExpired = 'Yes';
                    $dueto = 'expired';
                }
                if ($profile->user_data_sent / 2 > 13 * $validity) {
                    $profile->limitExpired = 'Yes';
                    $dueto = 'expired';
                }
                if ($profile->timeExpired == 'Yes' && $profile->limitExpired == 'Yes') {
                    $profile->overAllStatus = 'Expired';
                    $profile->upgrade_renew = 1;
                    $dueto = 'expired';
                }
            }
            $template = Content::where('type', $dueto)->value('text');
            $template = str_replace('TEMP_NAME', $profile->temple_name, $template);
            $template = str_replace('PLAN_NAME', $profile->plan_name, $template);
            $template = str_replace('ID', $profile->id, $template);
            $template = str_replace('NAME', $profile->name, $template);
            $template = str_replace('EXPIRY_DATE', $expires_at, $template);
            $sender = 'INHANS';
            $auth_key = env('MSG_AUTH_KEY');
            $myURL = 'http://api.msg91.com/api/sendhttp.php?';
            $options = array(
                'sender' => $sender,
                'route' => 4,
                'mobiles' => $profile->mobile,
                'authkey' => $auth_key,
                'country' => 91,
                'message' => $template,
            );
            $myURL .= http_build_query($options, '', '&');
            try {
                file_get_contents($myURL);
            } catch (\Exception $e) {
            }
        }
    }
}
