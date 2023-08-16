<?php

namespace App\Console\Commands;

use App\Models\FreeUser;
use App\Models\UserData as Profile;
use App\Models\userActivity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateUserActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:userActivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To update the activity of the users who registered in last three months';

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

    /**
     * cron job to udpate the userActivities table.
     *
     * @SWG\Post(path="/updateUserActivity",
     *   summary="update the user activities table which is used to track whether the user has performed any activity on hans or not in 7 day, in last 6 weeks or in last 3 months ", * description="business logic ->to track the retention we do use this table, this will mark whether user has performed any activity or not in a day, or in last 7 days or last 6 weeks and last 3 months. table used=>userActivities,profiles,leads,families,compatibilities,leadCompatibilities variable used ->all values will be binary, 0 means no activity performed, 1 means activity performed. is_lead=1 menas consider lead compatibilities is_lead=0 means consider compatibilities table user_id ->id of the user ad1,ad2 ->means after approval,update activities in this when is_approve=1 update_retention_free() ->this will update all the details for the leads table users $i ->variable used for determining the range for days, for date1 we are taking $i+1(refer to the current day or day0), and for date2 -$i+1(refer to the previous day), code logic=>fetch all the profiles which has been created today in profiles table, and not exist in userActivities table and mark there d1,w1,m1 as 1 and if is_approve=1 update ad1,aw1,am1update the details for the profiles $i vara update the userActivity table for days DayUpdateActivity() update the userActivity table for weeks WeekUpdateActivity() update the userActivity table for months MonthUpdateActivity()update all the same details for the leads table users too ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="update the credit to the premium client",
     *     description="JSON Object which login user",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", example="user@mail.com"),
     *         @SWG\Property(property="password", type="string", example="password"),
     *     )
     *   ),
     *   @SWG\Response(response="200", description="Return token or error message")
     * )
     *
     */
    public function handle()
    {
        //to create entry in userActivity table of today's registered users and update the daily activity for day1
        $today_date = date('Y-m-d', time()) . ' 00:00:00';
        $datetime = date("Y-m-d");
        // Convert datetime to Unix timestamp
        $timestamp = strtotime($datetime);
        // Subtract time from datetime
        $time = $timestamp;
        // Date and time after subtraction
        $datetime = date("Y-m-d H:i:s", $time);

        //$today_profile_registrations = Profile::where('created_at', '>=',$datetime)->get();

        $today_profile_registrations = DB::table('user_data AS t1')
            ->select('t1.*')
            ->leftJoin('userActivities AS t2', function ($join) {
                $join->on('t2.user_id', '=', 't1.id');
                $join->where('t2.is_lead', '=', '0');
            })
            ->whereNull('t2.user_id')
            //    ->where('t2.is_lead',0)
            ->where('t1.created_at', '>=', '2020-04-24 00:00:00')
            ->whereNotNull('t1.last_seen')
            ->get();
        //dd($today_profile_registrations);
        foreach ($today_profile_registrations as $profile) {
            $last_seen_date_time = $profile->last_seen;
            $last_seen = date("Y-m-d H:i:s", strtotime($last_seen_date_time) - 19800);
            if ($last_seen >= $datetime) {
                $userActivity = new userActivity();
                $userActivity->user_id = $profile->id;
                $userActivity->is_lead = 0;
                $userActivity->D1 = 1;
                $userActivity->W1 = 1;
                $userActivity->M1 = 1;
                if ($profile->is_sales_approve == 1) {
                    $userActivity->AD1 = 1;
                    $userActivity->AW1 = 1;
                    $userActivity->AM1 = 1;
                }
                $userActivity->save();
            }
        }
        //For days
        $i = -1; //determine the range for the columnn
        while ($i > -7) {
            $this->DayUpdateActivity($i, $i + 1, -$i + 1, $datetime, 0);
            $i--;
        }


        //For weekly
        $j = -6; //a week has 7 days
        $k = 2; // as we are marking  the activity of week 1 in dayUpdateActivity, so will satart with 2nd week, k =2
        while ($j > -41) {
            $this->WeekUpdateActivity($j - 7, $j, $k, $datetime, 0);
            $j = $j - 7;
            $k++;
        }
        //For monthly
        $l = -29; // days in a month
        $a = 2;  // marking for the first month in first 4 week, so will start with 2nd month
        while ($l > -87) {
            $this->MonthUpdateActivity($l - 30, $l, $a, $datetime, 0);
            $l = $l - 30;
            $a++;
        }
        //$this->update_retention_free();
    }

    public function DayUpdateActivity($date1, $date2, $day, $last_seen_time, $is_lead)
    {
        $DRange = [
            date('Y-m-d', strtotime($date1 . " day")) . ' 00:00:00',
            date('Y-m-d', strtotime($date2 . " day")) . ' 00:00:00'
        ]; //creating the range for the last 7 days
        $day2_update = userActivity::whereBetween('created_at', $DRange);
        //get all the user from userActivities, which are created in given range
        if ($is_lead == 0) {
            $day2_update = $day2_update->where('is_lead', 0)->get(); //if this function is called for profiles users
        } else {
            $day2_update = $day2_update->where('is_lead', 1)->get(); //if this function is called for leads users
        }
        $day = "D" . $day; //e.g D1
        $aday = "A" . $day;
        // dd($aday);
        $profileUser = null;
        foreach ($day2_update as $profile) {
            if ($is_lead == 0) {
                $profileUser = Profile::where('id', $profile->user_id)->first();
                if ($profileUser)
                    $last_seen = $profileUser->last_seen;
                else {
                    $last_seen = '2019-01-01 00:00:00';
                }
            } /*else {
                $last_seen = FreeUser::where('lead_id', $profile->user_id)->pluck('last_seen')->first();
            }*/

            //if last seen is in range then update
            if ($last_seen >= $last_seen_time) {
                $profile->$day = 1;
                $profile->W1 = 1;
                $profile->M1 = 1;
                if ($is_lead == 0) {
                    if ($profileUser->is_sales_approve == 1) {
                        $profile->$aday = 1;
                        $profile->AW1 = 1;
                        $profile->AM1 = 1;
                    }
                }
            } else {
                $profile->$day = 0;
                if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                    $profile->$aday = 0;
                }
            }
            $profile->save();
        }
    }

    public function WeekUpdateActivity($date1, $date2, $week, $last_seen_time, $is_lead)
    {
        $DRange = [
            date('Y-m-d', strtotime($date1 . " day")) . ' 00:00:00',
            date('Y-m-d', strtotime($date2 . " day")) . ' 00:00:00'
        ];

        $week = "W" . $week;
        $aweek = "A" . $week;
        $day2_update = userActivity::whereBetween('created_at', $DRange)
            ->where(function ($query) use ($week) {
                return $query->where($week, '!=', '1')
                    ->orWhereNull($week);
            });
        // ->get();
        if ($is_lead == 0) {
            $day2_update = $day2_update->where('is_lead', 0)->get();
        } else {
            $day2_update = $day2_update->where('is_lead', 1)->get();
        }
        $profileUser = null;
        foreach ($day2_update as $profile) {
            if ($is_lead == 0) {
                $profileUser = Profile::where('id', $profile->user_id)->first();
                if ($profileUser)
                    $last_seen = $profileUser->last_seen;
                else
                    $last_seen = '2019-01-01 00:00:00';
            }
            if ($last_seen >= $last_seen_time) {
                $profile->$week = 1;
                if ($is_lead == 0 && $profileUser->is_sales_approve == 1) {
                    $profile->$aweek = 1;
                }
                if ($week != 'W5' || $week != 'W6') {
                    $profile->M1 = 1;
                    if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                        $profile->AM1 = 1;
                    }
                } else {
                    $profile->M2 = 1;
                    if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                        $profile->AM2 = 1;
                    }
                }
            } else {
                $profile->$week = 0;
                if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                    $profile->$aweek = 0;
                }
            }
            $profile->save();
        }
    }

    public function MonthUpdateActivity($date1, $date2, $month, $last_seen_time, $is_lead)
    {
        $DRange = [
            date('Y-m-d', strtotime($date1 . " day")) . ' 00:00:00',
            date('Y-m-d', strtotime($date2 . " day")) . ' 00:00:00'
        ];

        //    print_r($DRange);
        $month = "M" . $month;
        $amonth = "A" . $month;
        $day2_update = userActivity::whereBetween('created_at', $DRange)
            ->where(function ($query) use ($month) {
                return $query->where($month, '!=', '1')
                    ->orWhereNull($month);
            });
        //  ->get();
        if ($is_lead == 0) {
            $day2_update = $day2_update->where('is_lead', 0)->get();
        } else {
            $day2_update = $day2_update->where('is_lead', 1)->get();
        }
        $profileUser = null;
        foreach ($day2_update as $profile) {
            if ($is_lead == 0) {
                $profileUser = Profile::where('id', $profile->user_id)->first();
                if ($profileUser)
                    $last_seen = $profileUser->last_seen;
                else
                    $last_seen = '2019-01-01 00:00:00';
            } else {
                $last_seen = FreeUser::where('lead_id', $profile->user_id)->pluck('last_seen')->first();
            }
            if ($last_seen >= $last_seen_time) {
                $profile->$month = 1;
                if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                    $profile->$amonth = 1;
                }
            } else {
                $profile->$month = 0;
                if ($profileUser && $is_lead == 0 && $profileUser->is_sales_approve == 1) {
                    $profile->$amonth = 0;
                }
            }
            $profile->save();
        }
    }

    // whole function is being depereciated due to free_users is not in use
    /*public function update_retention_free()
    {
        //to create entry in userActivity table of today's registered users and update the daily activity for day1
        $today_date = date('Y-m-d', time()) . ' 00:00:00';
        $datetime = date("Y-m-d");
        // Convert datetime to Unix timestamp
        $timestamp = strtotime($datetime);
        // Subtract time from datetime
        $time = $timestamp;
        // Date and time after subtraction
        $datetime = date("Y-m-d H:i:s", $time);

        //$today_profile_registrations = Profile::where('created_at', '>=',$datetime)->get();
        //     DB::enableQueryLog();

        $today_profile_registrations = DB::table('free_users AS t1')
            ->join('leads AS t3', 't3.id', '=', 't1.lead_id')
            ->select('t1.*', 't3.profile_created')
            ->leftJoin('userActivities AS t2', function ($join) {
                $join->on('t2.user_id', '=', 't1.lead_id');
                $join->where('t2.is_lead', '=', '1');
            })
            ->whereNull('t2.user_id')
            // ->where('t2.is_lead',1)
            ->where('t3.profile_created', 0)
            ->where('t1.created_at', '>=', '2020-05-03 00:00:00')
            ->whereNotNull('t1.last_seen')
            ->get();
        //dd(DB::getQueryLog());
        //    dd($today_profile_registrations);
        foreach ($today_profile_registrations as $profile) {
            // $last_seen_date_time = $profile->last_seen;
            // $last_seen_date = date("Y-m-d", strtotime($last_seen_date_time));
            // $last_seen_timestamp = strtotime($datetime)-19800;
            // $last_seen = date("Y-m-d H:i:s", $last_seen_timestamp);
            $last_seen_date_time = $profile->last_seen;
            $last_seen = date("Y-m-d H:i:s", strtotime($last_seen_date_time) - 19800);
            if ($last_seen >= $datetime) {
                $userActivity = new userActivity();
                $userActivity->user_id = $profile->lead_id;
                $userActivity->is_lead = 1;
                $userActivity->D1 = 1;
                $userActivity->W1 = 1;
                $userActivity->M1 = 1;
                $userActivity->save();
            }
        }
        //For days
        $i = -1;
        while ($i > -7) {
            $this->DayUpdateActivity($i, $i + 1, -$i + 1, $datetime, 1);
            $i--;
        }


        //For weekly
        $j = -6;
        $k = 2;
        while ($j > -41) {
            $this->WeekUpdateActivity($j - 7, $j, $k, $datetime, 1);
            $j = $j - 7;
            $k++;
        }
        //For monthly
        $l = -29;
        $a = 2;
        while ($l > -87) {
            $this->MonthUpdateActivity($l - 30, $l, $a, $datetime, 1);
            $l = $l - 30;
            $a++;
        }
    }*/
}
