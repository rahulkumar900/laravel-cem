<?php

namespace App\Console\Commands;

use App\Models\userActivity;
use App\Models\UserData;
use App\Models\UserPreference as Preference;
use Illuminate\Console\Command;
use App\response;


class updateResponse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:response';

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
        $week_old_date = $this->getDate($today_date, -6) . ' 00:00:00';
        $today_date = $today_date . ' 23:59:59';
        //get ProfileValues
        $user_data = userActivity::join('userCompatibilities', 'userCompatibilities.user_data_id', 'userActivities.user_id')
            ->join('user_data', 'user_data.id', 'userActivities.user_id')
            ->where('userActivities.created_at', '>=', $week_old_date)
            ->where('userActivities.created_at', '<=', $today_date)
            ->where('userActivities.is_lead', 0)
            ->select('userActivities.user_id as id', 'compatibilities.profile_status', 'userActivities.created_at as created_at', 'user_data.identity_number', 'user_data.temple_id')
            ->orderBy('userActivities.created_at', 'asc')
            ->get();

        $this->updateResponses($user_data, 0);
        //$this->updateResponses($leads, 1);
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
                $preference = Preference::where('user_data_id', $profile->id)
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
            $od1 = $od2 = $od3 = $od4 = $od5 = $od6 = $od7 = $ow1 = $ow2 = $ow3 = $ow4 = $ow5 = $ow6 = $om1 = $om2 = $om3 = 0;
            $proStatus = $profile->profile_status;
            $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null') ? [] : json_decode($proStatus);
            foreach ($proStatus as $key) {


                if (isset($key->timestamp) && $key->timestamp > $day1[0] && $key->timestamp < $day1[1]) {
                    // dd("OD1");
                    $this->updateScore($profile->id, $key->status, 'D1', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day2[0] && $key->timestamp < $day2[1]) {
                    // dd("OD2");
                    $this->updateScore($profile->id, $key->status, 'D2', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day3[0] && $key->timestamp < $day3[1]) {
                    //       dd("OD3");
                    $this->updateScore($profile->id, $key->status, 'D3', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day4[0] && $key->timestamp < $day4[1]) {
                    //       dd("OD4");
                    $this->updateScore($profile->id, $key->status, 'D4', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day5[0] && $key->timestamp < $day5[1]) {
                    //      dd("OD5");
                    $this->updateScore($profile->id, $key->status, 'D5', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day6[0] && $key->timestamp < $day6[1]) {
                    //       dd("OD6");
                    $this->updateScore($profile->id, $key->status, 'D6', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $day7[0] && $key->timestamp < $day7[1]) {
                    //    dd("OD7");
                    $this->updateScore($profile->id, $key->status, 'D7', $is_lead, $is_paid);
                }

                if (isset($key->timestamp) && $key->timestamp > $week1[0] && $key->timestamp < $week1[1]) {
                    //      dd("OW1");
                    $this->updateScore($profile->id, $key->status, 'W1', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $week2[0] && $key->timestamp < $week2[1]) {
                    //       dd("OW2");
                    $this->updateScore($profile->id, $key->status, 'W2', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $week3[0] && $key->timestamp < $week3[1]) {
                    //       dd("OW3");
                    $this->updateScore($profile->id, $key->status, 'W3', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $week4[0] && $key->timestamp < $week4[1]) {
                    //    dd("ow4");
                    $this->updateScore($profile->id, $key->status, 'W4', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $week5[0] && $key->timestamp < $week5[1]) {
                    //  dd("Ow5");
                    $this->updateScore($profile->id, $key->status, 'W5', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $week6[0] && $key->timestamp < $week6[1]) {
                    // dd("Ow6");
                    $this->updateScore($profile->id, $key->status, 'W6', $is_lead, $is_paid);
                }
                if (isset($key->timestamp) && $key->timestamp > $month1[0] && $key->timestamp < $month1[1]) {
                    //dd("Om1");
                    $this->updateScore($profile->id, $key->status, 'M1', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $month2[0] && $key->timestamp < $month2[1]) {
                    //       dd("OM2");
                    $this->updateScore($profile->id, $key->status, 'M2', $is_lead, $is_paid);
                } else if (isset($key->timestamp) && $key->timestamp > $month3[0] && $key->timestamp < $month3[1]) {
                    //        dd("OM3");
                    $this->updateScore($profile->id, $key->status, 'M3', $is_lead, $is_paid);
                }
            }
        }
    }

    public function updateScore($id, $status, $columnUpdate, $is_lead, $is_paid)
    {
        $score = 0;

        if ($is_lead == 0) {
            $onlineScore = UserData::where('user_id', $id)
                ->where('is_lead', 0)
                ->first();
        } else {
            $onlineScore = UserData::where('user_id', $id)
                ->where('is_lead', 1)
                ->first();
        }

        if ($status == 'S' || $status == 'R') {
            //given
            $columnUpdate = "G" . $columnUpdate;
            if ($status == 'S') {
                $score = 2.5;
            } else if ($status = 'R') {
                $score = 0.5;
            }
        } else if ($status == 'SI' || $status == 'CI') {
            //received
            $columnUpdate = "R" . $columnUpdate;
            if ($status == 'SI') {
                $score = 15;
            } else if ($status == 'CI') {
                $score = 50;
            }
        }

        if ($onlineScore && $score > 0) {
            $currentScore = $onlineScore->$columnUpdate;
            $newScore = $currentScore + $score;
            $onlineScore->$columnUpdate = $newScore;
            $onlineScore->save();
        } else if ($score > 0 && $onlineScore == null) {
            //      dd("197");
            //$is_lead = 1;
            $online = new UserData();
            if ($is_lead == 0) {
                $online->is_lead = 0;
            } else {
                $online->is_lead = 1;
            }
            $online->is_paid = $is_paid;
            $online->user_id = $id;
            $online->$columnUpdate = $score;
            $online->save();
        }
    }
    public function getDate($created_at, $i)
    {
        $profile_created_at  = date('Y-m-d', strtotime($created_at . $i . "day"));
        return $profile_created_at;
    }
}
