<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\activeUser;
use App\Models\User as FreeUsers;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;



class updateDAU extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:DAU';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to update the DAU daily till 30 days and on 30th day delete the first 7 rows and change the day order';

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
     * cron job to udpate the daily active users in table activeusers.
     *
     * @SWG\Post(path="/updateDAU",
     *   summary="to update the DAU daily till 30 days and on 30th day delete the first 7 rows and change the day order",
    * description="to update the DAU daily till 30 days and on 30th day delete the first 7 rows and change the day order table used=>active users,free_users,profiles code logic=>count the number of last seen in todays range, will be the dailyActivities of today. to keep only last 30 days dau only delete the last 7 days records, if record become 30 if records count is not 30, then update the value of day by adding 1 in previous day values and udpate the dau with the count of last seen. ",
        *   produces={"application/json"},
        *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="udpate the daily active users in table activeusers",
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
        $activeUsers = activeUser::orderBy('day','asc')->get();
        $flag = 1;
        if(count($activeUsers) == 0){
            activeUser::create([
                'day' => 1,
                'DAU' => $this->calculateDau()
            ]);
            $flag = 0;
        }
        if(count($activeUsers) == 30){
            //delete code
            DB::table("active_users")
                ->orderBy("day", "asc")
                ->take(7)
                ->delete();
            $users = activeUser::orderBy('day','asc')->get();
            $count = 1;
            foreach($users as $user){
                $user->day =  $count++;
                $user->save();
            }
        }

        if($flag){
            $latest_DAU_day = activeUser::orderBy('day','desc')->first();
            activeUser::create([
                'day' => $latest_DAU_day->day+1,
                'DAU' => $this->calculateDau()
            ]);
        }

    }

    public function calculateDau(){
        $today_date = date('Y-m-d',time()+19800);

        $profiles = Profile::where('last_seen','>',$today_date.' 00:00:00')->where('last_seen','<',$today_date.' 23:59:59')->count();
        return $profiles;
    }
}
