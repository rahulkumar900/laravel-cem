<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use Carbon\Carbon;
use App\Models\UserPreference as Preference;

class updateCompatibleTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:updateCompatibleTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update all the necessary score to update at night';

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
     * cron job to udpate the daily necessary things for the profile.
     *
     * @SWG\Post(path="/updateCOmpatibleTableCron",
     *   summary="Updating whatsapp_point each week, update the whatsapp_point/credit to the premium user by 5 points",
     *   description="update all the necessary score to update at mid night table used=>compatiblities,leads,user_data code logic=>update the daily_quota ->to reset the count to 0, for all the users update the current ->current to 0(this makes pick profile from userCompatibilities and leaduserCompatibilities tables 0 index), for the users who has current values is 6(means user has seen all the user_data in a day), update the offline_score,online_score ->deduce the score by 0.05, update the max to -1 ->by marking max value to -1, it means, now user will call the getTodayProflie or sendMessage api, the function will check the user is coming the first time in a day on the platform then update the max value for a day by calling getCOuntOfRishtey function, and update other required things for a day in function update the random_updated to 0 ->random_updated column we do use when sending viewuser_data, or run cron morning notification, if it is 0, it means we have not assigned any value to this column, and after assigning a random value in between of 1 to 3, we do send user_data in view user_data equal to the number of random_updated value. ",
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
        DB::table('userCompatibilities')->update(['daily_quota' => 0, 'read_message' => 1]);
        DB::table('userCompatibilities')->where('current', 6)->update(['current' => 0]);

        //updating the first_comp_day, max = max_new
        DB::table('userCompatibilities')
            ->update([
                'first_comp_day' => 1,
                'max' => -1,
                'random_updated' => 0
            ]);

        $this->info('Daily Update of Compatible has been done successfully');
    }

    public function calculate($id)
    {
        $my_profile = Profile::find($id);
        if ($my_profile == null)
            return;
        echo 'id: ' . $id . ' gender: ' . $my_profile->gender . ' count:';
        $height = $my_profile->height;
        $last_id = (DB::table('userCompatibilities')->where('user_id', $my_profile->id)->first() == null) ? 0 : DB::table('userCompatibilities')->where('user_id', $my_profile->id)->first()->last_id;
        $birth_date = $my_profile->birth_date;
        if (Preference::where('user_data_id', $my_profile->id)->first() == null)
            return;
        $my_profile->prefered_caste = Preference::where('user_data_id', $my_profile->id)->first()->caste;

        if ($my_profile->gender == 'Male') {
            $gender = 'Female';
            if (Preference::where('user_data_id', $my_profile->id)->first()->caste_no_bar) {
                $query = DB::table('user_data')
                    ->select('user_data.*', 'user_data.caste')
                    ->where('user_data.gender', $gender)
                    ->where('birth_date', '>=', $birth_date)
                    ->where('user_data.id', '>=', $last_id)
                    ->whereBetween('user_data.height', array($height - 9, $height - 1))
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('user_data.monthly_income', '<', $my_profile->monthly_income)
                    ->get();
            } else {
                echo 'not_open_bar ';
                $query = DB::table('user_data')
                    ->select('user_data.*', 'user_data.caste')
                    ->where('birth_date', '>=', $birth_date)
                    ->where('user_data.gender', $gender)
                    ->where('user_data.id', '>=', $last_id)
                    ->whereBetween('user_data.height', array($height - 9, $height - 1))
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('user_data.monthly_income', '<', $my_profile->monthly_income)
                    ->where('user_data.caste', 'LIKE', $my_profile->prefered_caste)
                    ->get();
            }
            echo 'HF-' . $query->count() . '<br>';
            if ($query->count() == 0) {
                $query = DB::table('user_data') // Soft filte
                    ->select('user_data.*', 'user_data.caste')
                    ->where('birth_date', '>=', $birth_date)
                    ->where('user_data.gender', $gender)
                    ->where('user_data.id', '>=', $last_id)
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->get();
                echo 'SF-' . $query->count() . '<br>';
            }
        } else if ($my_profile->gender == 'Female') {
            $gender = 'Male';
            if (Preference::where('user_data_id', $my_profile->id)->first()->caste_no_bar) {
                $query = DB::table('user_data')
                    ->select('user_data.*', 'user_data.caste')
                    ->where('user_data.gender', $gender)
                    ->where('birth_date', '<=', $birth_date)
                    ->where('user_data.id', '>=', $last_id)
                    ->where('user_data.height', '>', $height + 1)
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('user_data.monthly_income', '>', $my_profile->monthly_income)
                    ->get();
            } else {
                echo 'not_open_bar ';
                $query = DB::table('user_data')
                    ->select('user_data.*', 'user_data.caste')
                    ->where('user_data.gender', $gender)
                    ->where('birth_date', '<=', $birth_date)
                    ->where('user_data.id', '>=', $last_id)
                    ->where('user_data.height', '>', $height + 1)
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('user_data.monthly_income', '>', $my_profile->monthly_income)
                    ->where('families.caste', 'LIKE', $my_profile->prefered_caste)
                    ->get();
            }
            echo 'HF-' . $query->count() . '<br>';

            if ($query->count() == 0) // Soft filter
            {
                $query = DB::table('user_data')
                    ->select('user_data.*', 'user_data.caste')
                    ->where('user_data.gender', $gender)
                    ->where('birth_date', '<=', $birth_date)
                    ->where('user_data.id', '>=', $last_id)
                    ->where('user_data.marital_status', $my_profile->marital_status)
                    ->where("user_data.created_at", ">", Carbon::now()->subMonths(6))
                    ->get();
                echo 'SF-' . $query->count() . '<br>';
            }
        }

        //create a json object for blank compatibility
        $data = [];
        foreach ($query as $label) {
            $age_values = $this->age_compatibility($id, $label->id);
            $hei_values = $this->height_compatibility($id, $label->id);
            $bmi_values = $this->bmi_compatibility($id, $label->id);
            $data[] = [
                'user_id' => $label->id,
                'value' => 100 * $age_values + 100 * $hei_values + 50 * $bmi_values,
            ];
        }
        // dd($data);

        $c_array =  collect($data)->sortBy('value')->reverse()->take(100);
        // $newc_array = json_encode($c_array);
        $data = [];
        foreach ($c_array as $val) {
            $data[] = [
                'user_id' => $val['user_id'],
                'value' => $val['value'],
            ];
        }
        $data = json_encode($data);
        $last_id = $query->max('id');

        if (DB::table('userCompatibilities')->where('user_data_id', '=', $id)->exists()) {
            DB::table('userCompatibilities')->where('user_data_id', $id)->update([
                'compatibility' => $data,
                // 'c_array' => $newc_array,
                'last_id' => $last_id,
            ]);
        } else {
            DB::table('userCompatibilities')->insert([
                'user_data_id' => $id,
                'compatibility' => $data,
                // 'c_array' => $newc_array,
                'last_id' => $last_id,
            ]);
        }
    }

    public function age_compatibility($my_id, $your_id)
    {
        $myage = Profile::find($my_id);
        $your_age = Profile::find($your_id);
        $myage1 = strtotime($myage->birth_date);
        $yourage1 = strtotime($your_age->birth_date);
        $age_diff = ($yourage1 - $myage1) / 86400;
        $points = 0;
        if ($age_diff > 730 && $age_diff < 1460) {
            $points = 3;
        } elseif ($age_diff > 365 && $age_diff < 1825) {
            $points = 2;
        } elseif ($age_diff > 0 && $age_diff < 2190) {
            $points = 1;
        } else {
            $points = 0;
        }
        //normalisation
        $points = number_format((float)$points / 3, 2, '.', '');
        return $points;
    }
    public function occupation_compatibility($my_id, $your_id)
    {
        $me = Profile::find($my_id);
        $you = Profile::find($your_id);
        $points = 1;
        if ($me->occupation == 'Not Working') {
            $points = 0;
        }
        $points = number_format((float)$points / 4, 2, '.', '');
        return $points;
    }
    public function height_compatibility($my_id, $your_id)
    {
        $me = Profile::find($my_id);
        $you = Profile::find($your_id);
        $myheight = $me->height;
        $yourheight = $you->height;
        $h_diff = $myheight - $yourheight;
        $points = 0;
        if ($h_diff == 5) {
            $points = 4;
        } elseif ($h_diff == 4 || $h_diff == 6) {
            $points = 3;
        } elseif ($h_diff == 3 || $h_diff == 7) {
            $points = 2;
        } elseif ($h_diff == 2 || $h_diff == 8 || $h_diff == 1) {
            $points = 1;
        } else {
            $points = 0;
        }
        $points = number_format((float)$points / 4, 2, '.', '');
        return $points;
    }

    public function bmi_compatibility($my_id, $your_id)
    {
        $me = Profile::find($my_id);
        $you = Profile::find($your_id);
        $myheight = $me->height;
        $myweight = $me->weight;
        $yourheight = $you->height;
        $yourweight = $you->weight;

        if ($myheight == 0 || $yourheight == 0) {
            $mybmi = 17;
            $yourbmi = 20;
        } else {
            $mybmi = $myweight / (($myheight * 0.0254) * ($myheight * 0.0254));
            $yourbmi = $yourweight / (($yourheight * 0.0254) * ($yourheight * 0.0254));
        }
        $bmi_diff = $mybmi - $yourbmi;
        if ($bmi_diff > 0 && $bmi_diff < 1) {
            $points = 4;
        } elseif ($bmi_diff > 1 && $bmi_diff < 2) {
            $points = 3;
        } elseif ($bmi_diff > 1 && $bmi_diff < 2) {
            $points = 2;
        } elseif ($bmi_diff > 2 && $bmi_diff < 3) {
            $points = 1;
        } else {
            $points = 0;
        }
        $points = number_format((float)$points / 4, 2, '.', '');
        return $points;
    }
}
