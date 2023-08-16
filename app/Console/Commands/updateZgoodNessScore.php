<?php

namespace App\Console\Commands;

use App\Models\UserData as Profile;
use App\Models\UserPreference as Preference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateZgoodNessScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:zGoodnessScore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To update the freshness, zfreshness, activity,zactivity, scores and zgoodness ';

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
     * cron job to update the freshness, zfreshness, activity,zactivity, scores and zgoodness .
     *
     * @SWG\Post(path="/updateGoodnessScore",
     *   summary="To update the freshness, zfreshness, activity,zactivity, scores and zgoodness ",
     *
     * description="business logic=>updating the ordering score of the tables used when calculating the compating of users table used=>profiles,preferences,families variable used=>activity_score ->how many days before any the user has been active on hans platform freshness score ->how old the user ispaid score ->normalised score, calculated on the basis of amount_collected starvation_score ->its been how many days, user has not gotten like photo_score ->how good the photo is, and it is given out of 10, and given by the hans executive visibility_score ->how long before, any action has been taken on the profile by other users, it can be reject,like,contact. or in other words, how much are we showing the profile to other user zScores=>noramlised score, which has been distributed in between 0 to 1, its calculated by a formula (current_score - average_score)/standard deviation code logic=>update all the normal score by the mentioned logic, each score has different logic of calulation, mentioned with the update. after updating normal score, update the zScore of all with the zScore formula (current score -average of column)/standard_deviation of the column after updating all the zScore calulate the profileGoodness,zGoodNessScore,goodness_score,zGoodNessScoreFemale,goodness_score_female,boost_goodNess_score. boost score if the user added an addon of boostnig profile ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="update update all the goodnessscore, zsocre,normal score",
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
        $today_date = date('Y-m-d', time()) . ' 00:00:00';
        $last_5_day  = date('Y-m-d', strtotime($today_date . "-5 day")) . ' 00:00:00';
        //update activity score where last_seen available
        Profile::whereNotNull('last_seen')
            ->update(array(
                'activity_score' => DB::raw('DATEDIFF(CURDATE(),last_seen)')
            ));

        //update activity score where last_seen not available
        Profile::whereNull('last_seen')
            ->update(array(
                'activity_score' => DB::raw('DATEDIFF(CURDATE(),created_at)')
            ));

        //update freshness score
        Profile::where('id', '>', 0)
            ->update(array(
                'freshness_score' => DB::raw('1-DATEDIFF(CURDATE(),created_at)/600')
            ));
        //salary score
        Profile::where('id', '>', 0)
            ->where('monthly_income', '<', '10000000')
            ->update(array(
                'salary_score' => DB::raw('monthly_income/5000000')
            ));

        //salary score
        Profile::where('id', '>', 0)
            ->where('monthly_income', '>=', '10000000')
            ->update(array(
                'salary_score' => DB::raw('monthly_income/50000000')
            ));
        //update paid score
        //update amount of the users whome amount_collected is null
        Preference::whereNull('amount_collected')
            ->update([
                'amount_collected' => 0
            ]);
        Preference::where('id', '>', 0)
            ->update(array(
                'paid_score' => DB::raw('amount_collected/30000')
            ));

        //update startvation score
        //2. when user is more than 5 days old and last_seen is within 2months && fist like available
        //last_si_date --> the date on which the user gotten the last like
        $last_60_day  = date('Y-m-d', strtotime($today_date . "-60 day")) . ' 00:00:00';
        Profile::where('id', '>', 0)
            ->where('created_at', '<', $last_5_day)
            ->where('last_seen', '>=', $last_60_day)
            ->whereNotNull('last_si_date')
            ->update(array(
                'starvation_score' => DB::raw('DATEDIFF(CURDATE(),last_si_date)/10')
            ));
        //3. when user is more than 5 days old and last_seen is within 2months && fist like not available
        Profile::where('id', '>', 0)
            ->where('created_at', '<', $last_5_day)
            ->where('last_seen', '>=', $last_60_day)
            ->whereNull('last_si_date')
            ->update(array(
                'starvation_score' => 45
            ));

        //4. last_seen > 2 months and user_created is not 5 days old
        Profile::where('id', '>', 0)
            ->where('created_at', '<', $last_5_day)
            ->where('last_seen', '<', $last_60_day)
            ->update(array(
                'starvation_score' => 0
            ));
        //4. last_seen > 2 months and user_created is not 5 days old and last_seen is null
        Profile::where('id', '>', 0)
            ->where('created_at', '<', $last_5_day)
            ->whereNull('last_seen')
            ->update(array(
                'starvation_score' => 0
            ));

        //update z freshness score
        //normalised formula -> (current_score - avg.score)/std.  deviation
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(freshness_score) as st, AVG(freshness_score) as average
            FROM user_data) temp
            set zFreshnessScore= (g.`freshness_score` - average) / st ;");

        //update z visibility score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(visibility_score) as st, AVG(visibility_score) as average
            FROM user_data) temp
            set zvisibility_score = (g.`visibility_score` - average) / st ;");

        //update z activity Score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(activity_score) as st, AVG(activity_score) as average
            FROM user_data) temp
            set zactivity_score= (g.`activity_score` - average) / st ;");

        //update z startvation Score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(starvation_score) as st, AVG(starvation_score) as average
            FROM user_data) temp
            set zStarvation= (g.`starvation_score` - average) / st ;");

        //update z salary score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(salary_score) as st, AVG(salary_score) as average
            FROM user_data) temp
            set zSalaryScore= (g.`salary_score` - average) / st ;");

        //update edu score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(edu_score) as st, AVG(edu_score) as average
            FROM user_data) temp
            set zeduScore= (g.`edu_score` - average) / st ;");

        //udpate z data score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(data_score) as st, AVG(data_score) as average
            FROM user_data) temp
            set zdataScore= (g.`data_score` - average) / st ;");

        //update z photo score
        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(photo_score) as st, AVG(photo_score) as average
            FROM user_data) temp
            set zvaluePhoto= (g.`photo_score` - average) / st ;");

        //update z paid score
        DB::statement("update preferences g
            CROSS JOIN (select STDDEV(paid_score) as st, AVG(paid_score) as average
            FROM preferences) temp
            set zPaidScore= (g.`paid_score` - average) / st ;");

        //update boost_score
        DB::statement("update user_data p
            join preferences pre on pre.temple_id = p.temple_id
            and pre.identity_number = p.identity_number
            set boost_score = pre.amount_collected / 80000
            where pre.amount_collected > 2100 and pre.amount_collected < 8000;");

        DB::statement("update user_data g
            CROSS JOIN (select STDDEV(boost_score) as st, AVG(boost_score) as average
            FROM user_data) temp
            set zBoost_score= (g.`boost_score` - average) / st ;");

        DB::statement("update user_data
            inner join preferences
            on preferences.temple_id = user_data.temple_id
            and
            preferences.identity_number = user_data.identity_number
            set zGoodNessScore = zFreshnessScore*15+zvaluePhoto*15+zSalaryScore*8+zeduScore*3+zdataScore*70+1.3*zPaidScore+3*zStarvation-3*zvisibility_score-3*zactivity_score");

        DB::statement("update user_data
            inner join preferences
            on preferences.temple_id = user_data.temple_id
            and
            preferences.identity_number = user_data.identity_number
            set goodness_score = zFreshnessScore*15+zvaluePhoto*15+zSalaryScore*8+zeduScore*4.5+zdataScore*70+2*zPaidScore");

        //female coeffiecient updates
        DB::statement("update user_data
            inner join preferences
            on preferences.temple_id = user_data.temple_id
            and
            preferences.identity_number = user_data.identity_number
            set zGoodNessScoreFemale = zFreshnessScore*15+zvaluePhoto*5+zSalaryScore*15+zeduScore*6+zdataScore*70+2*zPaidScore+3*zStarvation-3*zvisibility_score-3*zactivity_score");

        DB::statement("update user_data
            inner join preferences
            on preferences.temple_id = user_data.temple_id
            and
            preferences.identity_number = user_data.identity_number
            set goodness_score_female = zFreshnessScore*15+zvaluePhoto*5+zSalaryScore*15+zeduScore*6+zdataScore*70+3*zPaidScore");

        //update boost_goodNess_score
        DB::statement("update user_data set boost_goodNess_score = zStarvation + zvisibility_score + zactivity_score + zBoost_score");
        echo "done";
    }
}
