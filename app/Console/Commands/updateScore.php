<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\UserPreference as Preference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update score of freshness';

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
         $profiles = Profile::whereNull('goodness_score')->get();
        // $count= 0;
        foreach($profiles as $profile){
            try{
                $preference = Preference::where('user_data_id',$profile->id)->first();
                $created_at = date('Y-m-d', strtotime($profile->created_at));
                $today_date = date('Y-m-d',time());
                $day_diff = abs((strtotime($today_date) - strtotime($created_at))/86400);
                $freshness_score  = 1-(1/600)*($day_diff);
                $profile->freshness_score = $freshness_score;
              $photo_score = $profile->photo_score;
              $salary_score = $profile->salary_score;
              $edu_score = $profile->edu_score;
              $data_score = $profile->data_score;
              if($preference)
                $paid_score = $preference->paid_score;
              else
                $paid_score = 0;
              $goodnessScore = 6*$freshness_score + 5*$photo_score + 4*$salary_score + 7*$edu_score + 2*$paid_score + $data_score;
              $profile->goodness_score = $goodnessScore;
                $profile->save();
                // $count++;
            }
            catch (\Exception $e) {
            }
        }
        $date =date("Y-m-d", strtotime("-1 day"));
        Preference::where('created_at','>',$date)->update([
            'paid_score' => DB::raw('amount_collected/30000')
        ]);
      echo "Score updated Successfully";
    }
}
