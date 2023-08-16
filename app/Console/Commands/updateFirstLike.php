<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\firstLike;
use App\Models\UserPreference as Preference;
use App\Models\userActivity;

class updateFirstLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:firstLike';
    protected $flagNo = 0;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        //online score of user_data
        $today_date = date('Y-m-d', time());
        $today_date = $this->getDate($today_date, -7) . ' 00:00:00';
        $week_old_date = $this->getDate($today_date, -37) . ' 00:00:00';
        $today_date = $today_date . ' 23:59:59';

        firstLike::truncate();
        $user_data = userActivity::join('userCompatibilities', 'compatibilities.user_data_id', 'userActivities.user_id')
            ->join('user_data', 'user_data.id', 'userActivities.user_id')
            ->where('userActivities.created_at', '>=', $week_old_date)
            ->where('userActivities.is_lead', 0)
            ->select('userActivities.user_id as id', 'compatibilities.profile_status', 'userActivities.created_at as created_at', 'user_data.identity_number', 'user_data.temple_id', 'user_data.gender')
            ->orderBy('userActivities.created_at', 'asc')
            //    ->limit(2)
            ->get();
    }
    public function getRange($date1, $date2, $created_at)
    {
        $day1 = [
            strtotime($created_at . $date1 . "day"),
            strtotime($created_at . $date2 . "day")
        ];
        return $day1;
    }

    public function updateResponses($user_data, $is_lead)
    {
        $count = 0;

        foreach ($user_data as $profile) {
            $profile_created_at = date('Y-m-d', strtotime($profile->created_at));
            $created_at = $profile_created_at . " 00:00:00";

            if ($is_lead == 0 && $profile->temple_id) {
                $preference = Preference::where('identity_number', $profile->identity_number)
                    ->where('temple_id', $profile->temple_id)
                    ->where('amount_collected', '>=', '2000')
                    ->first();
                if ($preference) {
                    $is_paid = 1;
                } else {
                    $is_paid = 0;
                }
            } else {
                $is_paid = 0;
            }
            $gender = "M";
            if ($profile->gender == 'Female')
                $gender = 'F';

            $day1 = $this->getRange(0, 1, $created_at);
            $day2 = $this->getRange(1, 2, $created_at);
            $day3 = $this->getRange(2, 3, $created_at);
            $day4 = $this->getRange(3, 4, $created_at);
            $day5 = $this->getRange(4, 5, $created_at);
            $day6 = $this->getRange(5, 6, $created_at);
            $day7 = $this->getRange(6, 7, $created_at);
            $week1 = $this->getRange(0, 7, $created_at);
            $week2 = $this->getRange(7, 14, $created_at);
            $week3 = $this->getRange(14, 21, $created_at);
            $week4 = $this->getRange(21, 28, $created_at);
            $week5 = $this->getRange(28, 35, $created_at);
            $week6 = $this->getRange(35, 42, $created_at);
            $month1 = $this->getRange(0, 30, $created_at);
            $month2 = $this->getRange(30, 60, $created_at);
            $month3  = $this->getRange(60, 90, $created_at);
            echo "id \t" . $profile->id;
            echo "\n";
            echo "count \t" . $count++;
            echo "\n";
            $proStatus = $profile->profile_status;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null') ? [] : json_decode($proStatus);
            $id = $profile->id;
            $this->flagNo = 0;
            foreach ($proStatus as $key) {
                if (isset($key->timestamp) && $key->timestamp > $day1[0] && $key->timestamp < $day1[1] && $key->status == 'SI') {
                    // dd("OD1");
                    $this->updateLike($is_lead, $id, 'day1', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day2[0] && $key->timestamp < $day2[1] && $key->status == 'SI') {
                    // dd("OD2");
                    $this->updateLike($is_lead, $id, 'day2', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day3[0] && $key->timestamp < $day3[1] && $key->status == 'SI') {
                    //       dd("OD3");
                    $this->updateLike($is_lead, $id, 'day3', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day4[0] && $key->timestamp < $day4[1] && $key->status == 'SI') {
                    //       dd("OD4");
                    $this->updateLike($is_lead, $id, 'day4', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day5[0] && $key->timestamp < $day5[1] && $key->status == 'SI') {
                    //      dd("OD5");
                    $this->updateLike($is_lead, $id, 'day5', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day6[0] && $key->timestamp < $day6[1] && $key->status == 'SI') {
                    //       dd("OD6");
                    $this->updateLike($is_lead, $id, 'day6', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $day7[0] && $key->timestamp < $day7[1] && $key->status == 'SI') {
                    //    dd("OD7");
                    $this->updateLike($is_lead, $id, 'day7', $profile_created_at, $is_paid, $gender);
                    break;
                } else if (isset($key->timestamp) && $key->timestamp > $month1[0] && $key->timestamp < $month1[1] && $key->status == 'SI') {
                    //    dd("OD7");
                    $this->updateLike($is_lead, $id, 'dayPlus', $profile_created_at, $is_paid, $gender);
                    break;
                }
            }
            if ($this->flagNo == 0) {
                $this->updateLike($is_lead, $id, 'NoDay', $profile_created_at, $is_paid, $gender);
            }
        }
    }


    public function updateLike($is_lead, $id, $column_name, $created_at, $is_paid, $gender)
    {
        // dd($column_name);
        $firstLike  = new firstLike();
        $firstLike->is_lead = $is_lead;
        $firstLike->user_id = $id;
        $firstLike->$column_name = 1;
        $firstLike->gender = $gender;
        $firstLike->is_paid = $is_paid;
        $firstLike->analytic_date = date('Y-m-d', strtotime($created_at));
        if ($column_name == 'day1') {
            $firstLike->day2 = 2;
            $firstLike->day3 = 2;
            $firstLike->day4 = 2;
            $firstLike->day5 = 2;
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        if ($column_name == 'day2') {
            $firstLike->day3 = 2;
            $firstLike->day4 = 2;
            $firstLike->day5 = 2;
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        if ($column_name == 'day3') {
            $firstLike->day4 = 2;
            $firstLike->day5 = 2;
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        if ($column_name == 'day4') {
            $firstLike->day5 = 2;
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        if ($column_name == 'day5') {
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        if ($column_name == 'day6') {
            $firstLike->day6 = 2;
            $firstLike->day7 = 2;
        }
        $firstLike->save();
        $this->flagNo = 1;
    }

    public function getDate($created_at, $i)
    {
        $profile_created_at  = date('Y-m-d', strtotime($created_at . $i . "day"));
        return $profile_created_at;
    }
}
