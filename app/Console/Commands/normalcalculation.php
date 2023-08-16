<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserCompatblity as Compatibility;
use App\Models\UserData as Profile;
use App\Models\UserPreference as Preference;

class normalcalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normal:calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Storing age, height, bmi compatibilities to calculate mu and sigma';

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

        $profiles = Profile::where('height', 'LIKE', '%.%')->get();
        foreach ($profiles as $profile) {
            $feet = explode('.', $profile->height)[0];
            $inch = explode('"', explode('.', $profile->height)[1])[0];
            echo "Feet : " . $feet . " Inch: " . $inch;
            $profile->height = 12 * $feet + $inch;
            $profile->save();
            echo "Height : " . $profile->height . "\n";
        }
    }

    public function calculate($id)
    {
        $my_profile = Profile::find($id);
        if ($my_profile == null)
            return;
        //echo 'id: '.$id.' gender: '.$my_profile->gender.' count:';
        $height = $my_profile->height;
        $last_id = (DB::table('compatibilities')->where('user_id', $my_profile->id)->first() == null) ? 0 : DB::table('compatibilities')->where('user_id', $my_profile->id)->first()->last_id;
        $birth_date = $my_profile->birth_date;
        $myPreferences = Preference::where('identity_number', $my_profile->identity_number)->where('temple_id', $my_profile->temple_id)->first();
        if ($myPreferences == null)
            return;
        $my_profile->prefered_caste = $myPreferences->caste;
        $my_profile->preferred_manglik = $myPreferences->manglik;
        $my_profile->preferred_min_income = $myPreferences->income_min;
        $amount_collected = $myPreferences->amount_collected;

        if ($my_profile->preferred_manglik == null || $my_profile->preferred_manglik == '') {
            if ($my_profile->manglik == 'Manglik') {
                $mangliks = ['Manglik', 'Anshik Manglik'];
            } elseif ($my_profile->manglik == 'No') {
                $mangliks = ['No', 'Anshik Manglik'];
            } else {
                $mangliks = ['No', 'Anshik Manglik', 'Manglik'];
            }
        } else
            $mangliks[] = $my_profile->preferred_manglik;
        // min. income for male profiles when calculating for female profiles
        if ($my_profile->preferred_min_income == null || $my_profile->preferred_min_income == '') {
            $min_income = $my_profile->monthly_income + 300000;
        } else
            $min_income = $my_profile->preferred_min_income;

        if ($my_profile->gender == 'Male') {
            $gender = 'Female';
            if (Preference::where('identity_number', $my_profile->identity_number)->where('temple_id', $my_profile->temple_id)->first()->caste_no_bar) {
                $query = DB::table('profiles')
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->where('profiles.gender', $gender)
                    ->whereBetween('birth_date', array($birth_date, Carbon::parse($birth_date)->addYears(5)))
                    ->where('profiles.id', '>=', $last_id)
                    ->whereBetween('profiles.height', array($height - 9, $height - 1))
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('profiles.monthly_income', '<', $my_profile->monthly_income)
                    ->whereIN('profiles.manglik', $mangliks)
                    // ->where('families.caste','LIKE',$my_profile->prefered_caste)
                    // ->take(100)
                    ->get();
            } else {
                //echo 'not_open_bar ';
                $query = DB::table('profiles')
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->whereBetween('birth_date', array($birth_date, $birth_date->addYears(5)))
                    ->where('profiles.gender', $gender)
                    ->where('profiles.id', '>=', $last_id)
                    ->whereBetween('profiles.height', array($height - 9, $height - 1))
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('profiles.monthly_income', '<', $my_profile->monthly_income)
                    ->where('families.caste', 'LIKE', $my_profile->prefered_caste)
                    ->whereIN('profiles.manglik', $mangliks)
                    // ->take(100)
                    ->get();
            }
            echo 'HF-' . $query->count() . '<br>';
            if ($query->count() == 0) {
                $query = DB::table('profiles') // Soft filter
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->whereBetween('birth_date', array($birth_date, $birth_date->addYears(5)))
                    ->where('profiles.gender', $gender)
                    ->where('profiles.id', '>=', $last_id)
                    // ->where('profiles.height','>',$height+1)
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    // ->where('profiles.monthly_income','>',$my_profile->monthly_income)
                    // ->where('families.caste','LIKE',$my_profile->prefered_caste)
                    // ->take(100)
                    ->get();
                echo 'SF-' . $query->count() . '<br>';
            }
        } else if ($my_profile->gender == 'Female') {
            $gender = 'Male';
            if (Preference::where('identity_number', $my_profile->identity_number)->where('temple_id', $my_profile->temple_id)->first()->caste_no_bar) {
                $query = DB::table('profiles')
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->where('profiles.gender', $gender)
                    ->whereBetween('birth_date', array($birth_date->subYears(5), $birth_date))
                    ->where('profiles.id', '>=', $last_id)
                    ->where('profiles.height', '>', $height + 1)
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('profiles.monthly_income', '>', $min_income)
                    ->whereIN('profiles.manglik', $mangliks)
                    // ->where('families.caste','LIKE',$my_profile->prefered_caste)
                    // ->take(100)
                    ->get();
            } else {
                //echo 'not_open_bar ';
                $query = DB::table('profiles')
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->where('profiles.gender', $gender)
                    ->whereBetween('birth_date', array($birth_date->subYears(5), $birth_date))
                    ->where('profiles.id', '>=', $last_id)
                    ->where('profiles.height', '>', $height + 1)
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    ->where('profiles.monthly_income', '>', $min_income)
                    ->where('families.caste', 'LIKE', $my_profile->prefered_caste)
                    ->whereIN('profiles.manglik', $mangliks)
                    // ->take(100)
                    ->get();
            }
            echo 'HF-' . $query->count() . '<br>';

            if ($query->count() == 0) // Soft filter
            {
                $query = DB::table('profiles')
                    // ->join('preferences','profiles.id','=','preferences.id')
                    ->join('families', 'profiles.id', '=', 'families.id')
                    ->select('profiles.*', 'families.caste')
                    ->where('profiles.gender', $gender)
                    ->whereBetween('birth_date', array($birth_date->subYears(5), $birth_date))
                    ->where('profiles.id', '>=', $last_id)
                    // ->where('profiles.height','>',$height+1)
                    ->where('profiles.marital_status', $my_profile->marital_status)
                    ->where("profiles.created_at", ">", Carbon::now()->subMonths(6))
                    // ->where('profiles.monthly_income','>',$my_profile->monthly_income)
                    // ->where('families.caste','LIKE',$my_profile->prefered_caste)
                    // ->take(100)
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
            DB::table('values')->insert([
                'age' => $age_values,
                'height' => $hei_values,
                'bmi' => $bmi_values,
            ]);
        }


        // dd($data);

        // $c_array =  collect($data)->sortBy('value')->reverse()->take(100);
        // // $newc_array = json_encode($c_array);
        // $data = [];
        // foreach ($c_array as $val)
        // {
        //   $data[] = [
        //     'user_id' => $val['user_id'],
        //     'value' => $val['value'],
        //   ];
        // }
        // $data =json_encode($data);
        // $last_id = $query->max('id');

        // if(DB::table('compatibilities')->where('user_id','=',$id)->exists()){
        //   DB::table('compatibilities')->where('user_id',$id)->update([
        //     'compatibility' =>$data,
        //     'amount_collected' => $amount_collected,
        //     // 'c_array' => $newc_array,
        //     'last_id'=> $last_id,
        //   ]);
        // }
        // else{
        //   DB::table('compatibilities')->insert([
        //     'user_id' => $id,
        //     'compatibility' =>$data,
        //     'amount_collected' => $amount_collected,
        //     // 'c_array' => $newc_array,
        //     'last_id'=> $last_id,
        //   ]);
        //}
    }

    public function age_compatibility($my_id, $your_id)
    {
        $myage = Profile::find($my_id);
        $your_age = Profile::find($your_id);
        $myage1 = strtotime($myage->birth_date);
        $yourage1 = strtotime($your_age->birth_date);
        $age_diff = ($yourage1 - $myage1) / 86400;
        if ($age_diff < 0)
            $age_diff = ($myage1 - $yourage1) / 86400;
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
        return $points;
    }

    public function height_compatibility($my_id, $your_id)
    {
        $me = Profile::find($my_id);
        $you = Profile::find($your_id);
        $myheight = $me->height;
        $yourheight = $you->height;
        $h_diff = $myheight - $yourheight;
        if ($h_diff < 0)
            $h_diff = $yourheight - $myheight;
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
        if ($me->gender == 'Male')
            $bmi_diff = $mybmi - $yourbmi;
        elseif ($me->gender == 'Female')
            $bmi_diff = $yourbmi - $mybmi;
        if ($bmi_diff > 0 && $bmi_diff < 1) {
            $points = 1;
        } elseif ($bmi_diff > 1 && $bmi_diff < 2) {
            $points = 3;
        } elseif ($bmi_diff > 2 && $bmi_diff < 3) {
            $points = 2;
        } elseif ($bmi_diff > 3 && $bmi_diff < 4) {
            $points = 1;
        } else {
            $points = 0;
        }
        return $points;
    }
}
