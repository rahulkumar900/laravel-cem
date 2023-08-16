<?php

namespace App\Http\Controllers;

use App\Models\Caste;
use App\Models\CityLists;
use App\Models\Country;
use App\Models\Religion;
use App\Models\RokaLogs;
use App\Models\State;
use App\Models\UserData;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Auth;
class UserPreferenceController extends Controller
{
    // update user validity
    public function updateUserValidity(Request $request)
    {
        $previous_data = UserPreference::getUserPreference($request->user_data_id);

        $update_preference = UserPreference::updateUserValidity($request->user_data_id, $request->roka_amount, $request->validity_month);

        if ($request->profile_sent_day && !empty($request->profile_sent_day)) {
            $updateSent_proile = UserData::updateSentProfile($request->user_data_id, $request->profile_sent_day);
        }

        if($update_preference){
            $message = "";
            // changes detected into validity this
            if($previous_data->validity_month != $request->validity_month){
                $message = $message."validity extended previous $previous_data->validity_month and extnended is $request->validity_month";
            }

            if($previous_data->roka_charge != $request->roka_amount){
                $message = $message." roka amount changed from $previous_data->roka_charge to $request->roka_amount";
            }

            if(!empty($message)){
                $message .= " changed into database on " . date('d-M-Y H:i:s') . " ";
                $save_roka_logs = RokaLogs::saveLogs(Auth::user()->id, $request->user_data_id, $message);
            }
            return response()->json(['type'=>true,'message'=>'record updated successfully']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to update record. Try Again']);
        }
    }

    public function saveUserNotes(Request $request)
    {
        $save_notes = "";

        $prev_notes = UserPreference::getUserPreference($request->user_id);

        $prev_comments = $prev_notes->description;

        $new_comment = $prev_comments.$request->user_comments.";";

        $save_comments = UserPreference::savePreferenceData($request->user_id, $new_comment);

        if($save_comments){
            return response()->json(['type'=>true,'message'=>'record addedd success']);
        }else{
            return response()->json(['type' => false, 'message' => 'record addedd success']);
        }
    }


    // get user's all preference in string
    public function getUserPreferenceString(Request $request)
    {
        $user_preferences = UserPreference::getUserPreference($request->user_id);

        // castePref
        $caste_array = json_decode($user_preferences->castePref, true);
        $fetched_caste = Caste::whereIn('id', $caste_array)->get();
        $caste_data = array_column($fetched_caste->toArray(), "value");
        $caste_string = implode(", ", $caste_data);

        // marital_statusPref
        $marital_Status = "";
        switch ($user_preferences->marital_statusPref) {
            case '1':
                $marital_Status = "Never Married";
                break;
            case '2':
                $marital_Status = "Married";
                break;
            case '3':
                $marital_Status = "Awaiting Divorce";
                break;
            case '4':
                $marital_Status = "Divorcee";
                break;
            case '5':
                $marital_Status = "Widowed";
                break;
            case '6':
                $marital_Status = "Anulled";
                break;
            case '7':
                $marital_Status = "Does't Matter";
                break;
            default:
                $marital_Status = "Does't Matter";
                break;
        }

        // manglikPref
        $manglik_status = "";
        switch ($user_preferences->manglikPref) {
            case '1':
                $manglik_status = "Manglik";
                break;
            case '2':
                $manglik_status = "Non-Manglik";
                break;
            case '3':
                $manglik_status = "Aanshik Manglik";
                break;
            case '4':
                $manglik_status = "Don't Know";
                break;
            case '5':
                $manglik_status = "Doesn't Matter";
                break;
            default:
                $marital_Status = "Does't Matter";
                break;
        }
        // food_choicePref
        $food_chioce = "";
        switch ($user_preferences->manglikPref) {
            case '1':
                $food_chioce = "Vegetarian";
                break;
            case '2':
                $food_chioce = "Non-Vegetarian";
                break;
            case '0':
                $food_chioce = "Does't Matter";
                break;
            default:
                $marital_Status = "Does't Matter";
                break;
        }

        // caste_no_bar
        $caste_bar= "";
        if($user_preferences->caste_no_bar==1){
            $caste_bar = "Yes";
        }else{
            $caste_bar = "No";
        }

        // cityPref

        $city_array = json_decode($user_preferences->cityPref, true);
        $fetched_cities = CityLists::whereIn('id', $city_array)->get();
        $city_data = array_column($fetched_cities->toArray(), "name");
        $city_string = implode(", ", $city_data);

        // statePref
        $state_array = json_decode($user_preferences->statePref, true);
        $fetched_states = State::whereIn('id', $state_array)->get();
        $state_data = array_column($fetched_states->toArray(), "name");
        $state_string = implode(", ", $state_data);

        // religionPref
        $religion_array = json_decode($user_preferences->religionPref, true);
        $fetched_religion = Religion::whereIn('id', $religion_array)->get();
        $religion_data = array_column($fetched_religion->toArray(), "religion");
        $religion_string = implode(", ", $religion_data);

        //  countryPref
        $ccountry_array = json_decode($user_preferences->countryPref, true);
        $fetched_cities = Country::whereIn('id', $ccountry_array)->get();
        $ccountry_data = array_column($fetched_cities->toArray(), "name");
        $ccountry_string = implode(", ", $ccountry_data);


        $prefered_array = array(
            "pref_min_height"       =>      (intdiv($user_preferences->height_min_s , 12))."Ft ".($user_preferences->height_min_s%12)."In",
            "pref_max_height"       => (intdiv($user_preferences->height_max_s, 12)) . "Ft " . ($user_preferences->height_max_s % 12) . "In",
            "caste_string"          =>      $caste_string,
            "marital_status"        =>      $marital_Status,
            "income_min"            =>      ($user_preferences->income_min/100000)."LPA",
            "income_max"            =>      ($user_preferences->income_max/100000)."LPA",
            "citizenship"           =>      $user_preferences->citizenship,
            "caste_bar"             =>      $caste_bar,
            "city_pref"             =>      $city_string,
            "state_pref"            =>      $state_string,
            "country_pref"          =>      $ccountry_string,
            "religion_prefs"        =>      $religion_string
        );

        return response()->json(['type'=>true, 'prefered_data'=>$prefered_array]);
    }
}
