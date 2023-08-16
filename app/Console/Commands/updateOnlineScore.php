<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profile;
use App\Models\OnlineScore;



class updateOnlineScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:onlineScore';

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

        //online score of profiles

        $profiles = Profile::join('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
            ->join('userActivities', 'userActivities.user_id', 'user_data.id')
            ->where('is_lead', 0)
            ->where('userActivities.created_at', '>', '2020-06-30 00:00:00')
            ->select('user_data.id', 'userCompatibilities.profile_status', 'profiles.created_at as created_at')
            ->get();

        $count = 0;
        foreach ($profiles as $profile) {

            $created_at = date('Y-m-d', strtotime($profile->created_at));
            $created_at = $created_at . " 00:00:00";
            //dd($created_at);
            // $day1=[
            //     strtotime($created_at." 0 day"),
            //     strtotime($created_at." 1 day")
            //     ];

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
                    $this->updateScore($profile->id, $key->status, 'OD1');
                } else if (isset($key->timestamp) && $key->timestamp > $day2[0] && $key->timestamp < $day2[1]) {
                    // dd("OD2");
                    $this->updateScore($profile->id, $key->status, 'OD2');
                } else if (isset($key->timestamp) && $key->timestamp > $day3[0] && $key->timestamp < $day3[1]) {
                    //       dd("OD3");
                    $this->updateScore($profile->id, $key->status, 'OD3');
                } else if (isset($key->timestamp) && $key->timestamp > $day4[0] && $key->timestamp < $day4[1]) {
                    //       dd("OD4");
                    $this->updateScore($profile->id, $key->status, 'OD4');
                } else if (isset($key->timestamp) && $key->timestamp > $day5[0] && $key->timestamp < $day5[1]) {
                    //      dd("OD5");
                    $this->updateScore($profile->id, $key->status, 'OD5');
                } else if (isset($key->timestamp) && $key->timestamp > $day6[0] && $key->timestamp < $day6[1]) {
                    //       dd("OD6");
                    $this->updateScore($profile->id, $key->status, 'OD6');
                } else if (isset($key->timestamp) && $key->timestamp > $day7[0] && $key->timestamp < $day7[1]) {
                    //    dd("OD7");
                    $this->updateScore($profile->id, $key->status, 'OD7');
                }

                if (isset($key->timestamp) && $key->timestamp > $week1[0] && $key->timestamp < $week1[1]) {
                    //      dd("OW1");
                    $this->updateScore($profile->id, $key->status, 'OW1');
                } else if (isset($key->timestamp) && $key->timestamp > $week2[0] && $key->timestamp < $week2[1]) {
                    //       dd("OW2");
                    $this->updateScore($profile->id, $key->status, 'OW2');
                } else if (isset($key->timestamp) && $key->timestamp > $week3[0] && $key->timestamp < $week3[1]) {
                    //       dd("OW3");
                    $this->updateScore($profile->id, $key->status, 'OW3');
                } else if (isset($key->timestamp) && $key->timestamp > $week4[0] && $key->timestamp < $week4[1]) {
                    //    dd("ow4");
                    $this->updateScore($profile->id, $key->status, 'OW4');
                } else if (isset($key->timestamp) && $key->timestamp > $week5[0] && $key->timestamp < $week5[1]) {
                    //  dd("Ow5");
                    $this->updateScore($profile->id, $key->status, 'OW5');
                } else if (isset($key->timestamp) && $key->timestamp > $week6[0] && $key->timestamp < $week6[1]) {
                    // dd("Ow6");
                    $this->updateScore($profile->id, $key->status, 'OW6');
                }
                if (isset($key->timestamp) && $key->timestamp > $month1[0] && $key->timestamp < $month1[1]) {
                    //dd("Om1");
                    $this->updateScore($profile->id, $key->status, 'OM1');
                } else if (isset($key->timestamp) && $key->timestamp > $month2[0] && $key->timestamp < $month2[1]) {
                    //       dd("OM2");
                    $this->updateScore($profile->id, $key->status, 'OM2');
                } else if (isset($key->timestamp) && $key->timestamp > $month3[0] && $key->timestamp < $month3[1]) {
                    //        dd("OM3");
                    $this->updateScore($profile->id, $key->status, 'OM3');
                }
            }
        }
    }

    public function getRange($date1, $date2, $created_at)
    {
        $day1 = [
            strtotime($created_at . $date1 . "day"),
            strtotime($created_at . $date2 . "day")
        ];
        return $day1;
    }

    public function updateScore($id, $status, $columnUpdate)
    {

        $score = 0;
        $is_lead = 0;
        if ($status == 'S')
            $score = 2.5;
        else if ($status == 'CI')
            $score = '50';
        else if ($status == 'R')
            $score = 0.5;
        else if ($status == 'C')
            $score = 0.8;
        else if ($status == 'SI')
            $score = 15;

        if ($is_lead == 0) {
            $onlineScore = OnlineScore::where('user_id', $id)
                ->where('is_lead', 0)
                ->first();
        } else {
            $onlineScore = OnlineScore::where('user_id', $id)
                ->where('is_lead', 1)
                ->first();
        }
        if ($onlineScore) {
            $currentScore = $onlineScore->$columnUpdate;
            $newScore = $currentScore + $score;
            $onlineScore->$columnUpdate = $newScore;
            $onlineScore->save();
        } else {
            //      dd("197");
            //$is_lead = 1;
            $online = new OnlineScore;
            if ($is_lead == 0)
                $online->is_lead = 0;
            else
                $online->is_lead = 1;
            $online->user_id = $id;
            $online->$columnUpdate = $score;
            $online->save();
        }
    }
}
