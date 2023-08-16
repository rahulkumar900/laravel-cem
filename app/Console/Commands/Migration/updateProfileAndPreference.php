<?php

namespace App\Console\Commands\Migration;

use App\Models\UserData;
use App\Models\UserPreference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class updateProfileAndPreference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:updateProfileAndPreference';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To migrate the preferences data user_details table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // delete duplicate preferences
       // DB::select("DELETE n1 FROM hansdb.userPreferences n1, hansdb.userPreferences n2 WHERE n1.id < n2.id AND n1.user_data_id = n2.user_data_id;");


        // $updte_fmily= DB::select("update families set family_income = (family_income /1000000) where family_income >1000000");
        $comp_profiles = DB::table('profiles')
            ->join('families', 'families.id', 'profiles.id')
            ->join('preferences', function ($join) {
                $join->on('profiles.temple_id', 'preferences.temple_id');
                $join->on('profiles.identity_number', 'preferences.identity_number');
            })
            ->select(["profiles.id", "profiles.monthly_income", "families.family_income", "profiles.manglik", "profiles.working_city", "preferences.amount_collected_date", "preferences.amount_collected", "preferences.roka_charge", "preferences.validity", "profile_sent_day", "preferences.id as preference_id", "birth_date", "birth_time", "house_type", "birth_place","height","father_status", "mother_status"])
            ->where(["is_deleted" => 0, "profiles.record_updated" => 0])->orderBy('id', 'desc')->chunk(50, function ($profile_datas) {
                // each index me +1 hoga
                $manglik_array = ["Manglik", "Non-Manglik", "Anshik Manglik", "Don't Know", "Doesn't Matter"];
                foreach ($profile_datas as $profile) {

                    $manglik_code = (array_search($profile->manglik, $manglik_array) + 1);
                    //echo "\n processing " . $profile->id;
                    //dd($profile->id);

                    $check_user_data = UserData::where('profile_id', $profile->id)->first();

                    if (!empty($check_user_data)) {
                    $b_date = '';
                    echo "\n processing " . $check_user_data->id.' | '.$profile->birth_date . ' ' . $profile->birth_time;

                    if (empty($profile->birth_date)) {
                        $b_date = date('Y-m-d H:i:s', strtotime('-21 year'));
                    }else{
                        if (!empty($profile->birth_time)) {
                            $b_date = $profile->birth_date . ' ' . date('H:i:s');
                        }else{
                            $b_date = $profile->birth_date . ' ' . date('H:i:s');
                        }
                    }
                    $family_income = 0;
                    if (empty($profile->family_income)) {
                       $family_income = 1.25;
                    }else if(!empty($profile->family_income) && $profile->family_income > 10000){
                        $family_income = $profile->family_income/100000;
                    }else{
                        $family_income = 1.25;
                    }
                        // update profile
                        $update_user_data = UserData::where('id', $check_user_data->id)->update([
                            "family_income"         =>      $family_income,
                            "manglik"               =>      $profile->manglik,
                            "manglikCode"           =>      $manglik_code,
                            "working_city"          =>      $profile->working_city,
                            "monthly_income"        =>      ($profile->monthly_income / 100000),
                            "profile_sent_day"      =>      $profile->profile_sent_day,
                            "birth_date"            =>      $b_date,
                            "house_type"            =>      "Owned",
                            "birth_place"           =>      $profile->birth_place,
                            "height"                =>      $profile->height,
                            "height_int"            =>      $profile->height,
                            "father_status"         =>      $profile->father_status,
                            "mother_status"         =>      $profile->mother_status
                        ]);


                        // update preference uncomment when upadte both
                        /*$user_validity = "";
                        if ($profile->validity != "0000-00-00 00:00:00") {
                            $user_validity = $profile->validity;
                        } else {
                            $user_validity = date('Y-m-d H:i:s');
                        }
                        $upadte_profile_data = UserPreference::where('user_data_id', $check_user_data->id)->update([
                            "amount_collected_date"     =>      $profile->amount_collected_date,
                            "amount_collected"          =>      $profile->amount_collected,
                            "roka_charge"               =>      $profile->roka_charge,
                            "validity"                  =>      $user_validity,
                        ]);

                        $update_profile = DB::table("preferences")->where("id", $profile->preference_id)->update([
                            "record_updated"            =>      1
                        ]);*/


                        $update_profile = DB::table("profiles")->where("id", $profile->id)->update([
                            "record_updated"            =>      1
                        ]);

                    }
                }
            });
        dd("complete");
    }
}
