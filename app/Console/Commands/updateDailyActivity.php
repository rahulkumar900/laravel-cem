<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserCompatblity as  LeadCompatibility;
use App\Models\dailyActivity;
use Illuminate\Support\Facades\DB;



class updateDailyActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:dailyActivity';

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

    /**
     * cron job to update the dailyActivity table, it keeps the track of dailyActivities performed by a user in last 30 days .
     *
     * @SWG\Post(path="/udpateDailyActivities",
     *   summary="update the dailyActivity table, it keeps the track of dailyActivities performed by a user in last 30 days",
     * description=" business logic=>update the dailyActivity table, it keeps the track of dailyActivities performed by a user in last 30 days table used=>leadCompatibilites,userCompatibilities,profiles,leads variable used=>today_activation_link ->how a user arrived to the hans platform today TransferPreviousDaysColumn() ->it shifts all the column values in table to right by 1 place updateBlankTodayColumn()->mark today column as blank, it will be default values, if user will have taken action, then values will be updated for that action updateTodayColumn() ->this udpates the values in today column, for the actions by comparing the timestamp for today range in profile_status column code logic=>1. to managing the activities, for the last 30 day activity, we are passing the first column values to a right place so that will have the users last 30day activity, 2. now will fetch all the records which are two months old date. 3. foreach record will update the value in day1 column, by comparing the timestamp with today range and will see which action user has taken and update the count accordingly [call function updatTodayColumn()] ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name=" update the dailyActivity table, it keeps the track of dailyActivities performed by a user in last 30 days",
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

        $yesterday = date('Y-m-d');
        $monthOldDate = date('Y-m-d', strtotime("-30 days"));
        $twoMonthOldDate = date('Y-m-d', strtotime("-60 days"));
        //approved profiles available in daily activity
        $this->TransferPreviousDaysColumn();
        $profiles1 = dailyActivity::where('analytic_date', '>', $twoMonthOldDate)->get();
        foreach ($profiles1 as $profile) {
            if ($profile->is_active) {
                $this->updateTodayColumn($profile->user_id, $profile->is_lead, $yesterday, $profile);
            } else {
                $this->updateBlankTodayColumn($profile);
            }
        }
        DB::table('userCompatibilities')
            ->update([
                'today_activation_link' => null
            ]);
    }

    public function TransferPreviousDaysColumn()
    {
        for ($i = 30; $i > 1; $i--) {
            dailyActivity::where('id', '>=', 1)->update([
                'day' . $i => DB::raw('day' . ($i - 1))
            ]);
        }
    }
}
