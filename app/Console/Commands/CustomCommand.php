<?php

namespace App\Console\Commands;

use App\Models\CasteMapping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\UserData as Profile;
use App\Models\UserCompatblity as Compatibility;
use Carbon\Carbon;
use App\Models\UserPreference as Preference;
use App\Models\Content as Content;
use App\Models\Order;


class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cronjob to calculate compatibility';

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

        $this->mend();
    }

    public function mend()
    {
        $messages = DB::table('whatsapp_messages')
            ->where('message', 'like', '%"type":"profile"%')
            ->where('created_at', '>', date("Y-m-d", strtotime("2020-01-12")))
            ->where('contact', 1)
            ->get();

        if ($messages) {
            foreach ($messages as $message) {
                $data_sent = json_decode($message->message);
                $sent_id = $data_sent->apiwha_autoreply->id;
                $profile = Profile::whereRaw('user_data.user_mobile like "%' . $message->from . '%" or whatsapp_family like "%' . $message->from . '%" or whatsapp like "%' . $message->from . '%"')
                    ->whereRaw("(user_data.temple_id != 'st1' AND user_data.temple_id != 'st3')")
                    ->orderBy('user_data.id', 'desc')->first();
                if (!$profile) {
                    $profile = Profile::whereRaw('user_data.user_mobile like "%' . $message->from . '%" or whatsapp_family like "%' . $message->from . '%" or whatsapp like "%' . $message->from . '%"')
                        ->whereRaw("(user_data.temple_id != 'st1' AND user_data.temple_id != 'st3')")
                        ->orderBy('user_data.id', 'desc')->first();
                    $this->sd();
                    $this->findMatch($profile->identity_number, $profile->temple_id);
                }

            }
        }

    }

    public function sd()
    {
        $profiles = Profile::where('id', '!=', 220679)->latest()->take(8000)->get();
        foreach ($profiles as $profile) {
            echo "User Id: {$profile->id}\n";
            $com = Compatibility::where('user_id', $profile->id)->first();
            if ($profile->plan_name && $com) {
                if (Content::where('type', $profile->plan_name)->first()) {
                    $credits = 10 * Content::where('type', $profile->plan_name)->first()->validity;
                    echo "Credits: {$credits}\nProfiles Sent: {$profile->profiles_sent}\n";
                    $credits = floor($credits - $profile->profiles_sent / 2);
                    $credits = $credits < 0 ? 0 : $credits;
                    echo "Remaining credits: {$credits}\n\n";
                    $com->whatsapp_point = $credits;
                    $com->save();
                }
            }
        }
    }


    public function sendMessage()
    {
        $profiles = Profile::whereNotIn('temple_id', ['1520359943437', '1551267379367', '1551419155966', '1562063641937', '1531987244092', '1528452763091', '1528019572280', 'st1', 'st3'])
            ->where('id', '>', '181472')
            ->where('maritalStatusCode', '!=', 'Married')
            ->where('created_at', '>', Carbon::now()->subMonths(12))
            ->get();
        foreach ($profiles as $profile) {
            echo "profile: " . $profile->id . "\n";

            if ($profiles->user_mobile) {
                $mobile = substr($profiles->user_mobile, -10);
                $username = env('INFOBIP_USERNAME');
                $password = env('INFOBIP_PASSWORD');
                $message = "Hans Matrimony\n\nAb khud hi Rishtey dekhe Online\n\nLink par click kare\n\nhttps://hansmatrimony.com/chat?mobile=" . $mobile . "\n\nHelpline: 8318034352";
                $message = urlencode($message);
                $url = "https://dqell.api.infobip.com/sms/1/text/query?username=" . $username . "&password=" . $password . "&to=91" . $mobile . "&text=" . $message;
                try {
                    file_get_contents($url);
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function findMatch($identity_number, $temple_id)
    {
        $profile = Profile::where('identity_number', $identity_number)->where('temple_id', $temple_id)->first();
       // $family = Family::where('identity_number', $identity_number)->where('temple_id', $temple_id)->first();
        $preference = Preference::where('identity_number', $profile->identity_number)->where('temple_id', $profile->temple_id)->first();

        $marital_status = array(
            "Never Married" => array('Never Married'),
            "Divorced" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Divorcee" => array('Divorced', 'Widowed', 'Divorcee', 'Widow'),
            "Widowed" => array('Divorced', 'Widowed'),
            "Widow" => array('Divorced', 'Widowed'),
            "Widow/Widower" => array('Divorced', 'Widowed'),
            "Married" => array(),
            "Other" => array(),
            "Awaiting Divorce" => ['Divorced', 'Divorcee', 'Awaiting Divorce'],
            "Anulled" => ['Divorced', 'Divorcee', 'Anulled'],
        );

        $manglik = array(
            "Manglik" => ['Manglik', 'Anshik Manglik'],
            "No" => ['No', 'Anshik Manglik'],
            "Non-manglik" => ['No', 'Anshik Manglik'],
            "Anshik Manglik" => ['No', 'Anshik Manglik', 'Manglik'],
            "Anshik manglik" => ['No', 'Anshik Manglik', 'Manglik'],
        );

        if ($preference->marital_status == null)
            $preference->marital_status = $profile->marital_status;

        if ($preference->manglik == null)
            $preference->manglik = $profile->manglik;

        $caste_list = array();
        if ($preference->caste != null && $preference->caste != 'null') {
            $caste_list = explode(',', $preference->caste);
        } else {
            if ($profile)
                if ($profile->caste != null && $profile->caste != '') {
                    $mapping_id = DB::table('caste_mappings')->where('caste', $profile->caste)->first();
                    if ($mapping_id != null) {
                        $mapping_id = $mapping_id->mapping_id;
                        $castes = CasteMapping::selectRaw("mapping_id, GROUP_CONCAT(caste) as castes")->groupBy('mapping_id')->get();
                        $castes = json_decode($castes)[$mapping_id - 1];
                        $caste_list = explode(',', $castes->castes);
                    } else $caste_list[0] = 'All';
                }
        }


        $sent_profiles_id = [];
        $compatibility = Compatibility::where('user_data_id', $profile->id)->first();
        if ($compatibility) {
            $sent_profiles = json_decode($compatibility->profile_status);
            if (sizeof($sent_profiles) > 0) {
                $sent_profiles_id = array_column($sent_profiles, 'user_id');
            }
        }
        $old_orders = Order::where('id_number', $profile->id)->get(['order_list']);
        foreach ($old_orders as $old_order) {
            $ss = $old_order->order_list;
            $ss_arrs = explode(',', $ss);
            foreach ($ss_arrs as $ss_arr) {
                $sent_profiles_id[] = $ss_arr;
            }
        }

        $query = DB::table('user_data')
            ->join('userPreferences', 'userPreferences.user_data_id', 'user_data.id')
            ->join('users', 'users.temple_id', '=', 'user_data.temple_id')
            ->select(
                'userPreferences.caste as preferred_caste',
                'user_data.*'
            )
            ->where('user_data.gender', '!=', $profile->gender)
            ->whereNotIn('user_data.id', $sent_profiles_id)
            ->where('user_data.is_approved', 1)
            ->where('photo', '!=', null)
            ->orderBy('users.data_account', 'ASC')
            ->orderBy('user_data.photo_score', 'DESC')
            ->orderBy('user_data.created_at', 'DESC');

        if ($profile->gender == 'Female') {
            //age range
            if ($preference->age_min && $preference->age_max) {
                $min_age = Carbon::today()->subYears($preference->age_min)->format('Y-m-d');
                $max_age = Carbon::today()->subYears($preference->age_max + 1)->endOfDay()->format('Y-m-d');
            } else {
                $min_age = $profile->birth_date;
                $max_age = Carbon::parse($profile->birth_date)->subYears(5)->format('Y-m-d');
            }
            $query = $query->whereBetween('user_data.birth_date', [$max_age, $min_age]);

            //height range
            if ($preference->height_min && $preference->height_max) {
                $min_height = $preference->height_min;
                $max_height = $preference->height_max;
            } else {
                if ($profile->height) {
                    $min_height = $profile->height + 1;
                    $max_height = $profile->height + 9;
                } else {
                    $min_height = 60;
                    $max_height = 84;
                }
            }
            $query = $query->whereBetween('user_data.height', [$min_height, $max_height]);

            //income range
            if ($preference->income_min != null && $preference->income_max != null) {
                $min_income = $preference->income_min * 100000;
                $max_income = $preference->income_max * 100000;
                $query = $query->whereBetween('user_data.monthly_income', [$min_income, $max_income]);
            } else {
                if ($preference->income_min)
                    $min_income = $preference->income_min * 100000;
                else {
                    if ($profile->occupation != 'Not Working') {
                        $min_income = $profile->monthly_income ? $profile->monthly_income * 100000 : 0;
                        $max_income = 0;
                    } else {
                        $min_income = 0;
                        $max_income = 0;
                    }
                }
                $query = $query->where('user_data.monthly_income', '>', $min_income);
            }

            //occupation filter
            if ($preference->occupation != "Doesn't Matter" && $preference->occupation != null) {
                $query = $query->where('user_data.occupation', $preference->occupation);
            }
        } elseif ($profile->gender == 'Male') {
            //age range
            if ($preference->age_min && $preference->age_max) {
                $min_age = Carbon::today()->subYears($preference->age_min)->format('Y-m-d');
                $max_age = Carbon::today()->subYears($preference->age_max + 1)->endOfDay()->format('Y-m-d');
            } else {
                $max_age = $profile->birth_date;
                $min_age = Carbon::parse($profile->birth_date)->addYears(5)->format('Y-m-d');
            }
            $query = $query->whereBetween('user_data.birth_date', [$max_age, $min_age]);

            //height range
            if ($preference->height_min && $preference->height_max) {
                $min_height = $preference->height_min;
                $max_height = $preference->height_max;
            } else {
                if ($profile->height) {
                    $min_height = $profile->height - 9;
                    $max_height = $profile->height - 1;
                } else {
                    $min_height = 60;
                    $max_height = 72;
                }
            }
            $query = $query->whereBetween('user_data.height', [$min_height, $max_height]);

            //income range
            if ($preference->income_min != null && $preference->income_max != null) {
                $min_income = $preference->income_min * 100000;
                $max_income = $preference->income_max * 100000;
                $query = $query->whereBetween('user_data.monthly_income', [$min_income, $max_income]);
            } else {
                if ($preference->income_max)
                    $max_income = $preference->income_max * 100000;
                else {
                    $max_income = $profile->monthly_income ? $profile->monthly_income * 100000 : 0;
                    $min_income = 0;
                }
                if ($profile->occupation != 'Not Working')
                    $query = $query->where('user_data.monthly_income', '>', 0);
                if ($max_income != 0)
                    $query = $query->where('user_data.monthly_income', '<=', $max_income);
            }

            if ($preference->working == 'Working')
                $query = $query->whereRaw("(user_data.occupation != 'Not Working' and user_data.occupation is not null)");
        }

        //caste filter
        if (count($caste_list))
            if (!($caste_list[0] == '' || $caste_list[0] == "Doesn't Matter" || $caste_list[0] == 'All')) {
                $query = $query->whereIN('user_data.caste', $caste_list);
            }

        //manglik status filter
        if ($preference->manglik != null && $preference->manglik != "Doesn't Matter") {
            $query = $query->whereIn('user_data.manglik', $manglik[$preference->manglik]);
        }

        //marital status filter
        if ($preference->marital_status != null && $preference->marital_status != 'null' && $preference->marital_status != "Doesn't Matter") {
            $query = $query->whereIn('user_data.marital_status', $marital_status[$preference->marital_status]);
        } else {
            $query = $query->where('user_data.marital_status', '!=', 'Married');
        }
        //    $query = $query->distinct()->limit(20)->get();
        return $query->count();
    }
}
