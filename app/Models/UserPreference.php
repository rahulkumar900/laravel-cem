<?php

namespace App\Models;

use AWS\CRT\HTTP\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = "userPreferences";

    protected $fillable = ["lead_preferences_id", "preferences_id", "user_data_id", "identity_number", "temple_id", "age_min", "age_max", "height_min_s", "height_max_s", "height_min", "height_max", "caste", "castePref", "marital_status", "marital_statusPref", "manglik", "manglikPref", "food_choice", "food_choicePref", "working", "workingPref", "occupation", "sub_occupation", "occupation_id", "income_min", "income_max", "citizenship", "description", "membership", "source", "caste_no_bar", "no_pref_caste", "mother_tongue", "amount_collected", "insentive", "validity", "religion", "religionPref", "is_paid", "paid_score", "zPaid_score", "roka_charge", "validity_month", "pref_city", "cityPref", "pref_state_id", "regionPref", "pref_country", "pref_country_id", "countryPref", "amount_collected_date"];

    protected static function updateUserValidity($user_id, $roka_amount, $extended_montshs)
    {
        $valid_till = date('Y-m-d H:i:s', strtotime("+$extended_montshs months"));
        return UserPreference::where('user_data_id', $user_id)->update([
            "validity_month"        =>      DB::raw("validity_month+$extended_montshs"),
            "roka_charge"           =>      $roka_amount,
            "validity"              =>      $valid_till
        ]);
    }

    protected static function createPrefs($min_age, $max_age, $min_height, $max_height, $caste_array, $marital_int, $religion_array, $save_user_data, $gender_pref)
    {
        return UserPreference::create([
            "age_min"               =>      $min_age,
            "age_max"               =>      $max_age,
            "height_min"            =>      $min_height,
            "height_max"            =>      $max_height,
            "castePref"             =>      $caste_array,
            "marital_statusPref"    =>      $marital_int,
            "religionPref"          =>      $religion_array,
            "user_data_id"          =>      $save_user_data,
            "gender_pref"           =>      $gender_pref,
            "validity"              =>      date('Y-m-d H:i:s'),
            "amount_collected_date" =>      date('Y-m-d H:i:s'),
        ]);
    }

    protected static function getUserPreference($user_data)
    {
        return UserPreference::where('user_data_id', $user_data)->first();
    }

    protected static function savePreferenceData($user_data, $comments)
    {
        return UserPreference::where("user_data_id", $user_data)->update([
            "description"       =>      "testing purpose"
        ]);
    }

    protected static function updateUserPreference($user_data_id, $caste_array, $religion, $age_min, $age_ax, $min_height, $max_height, $income_min, $income_max, $manglik, $marital_status, $occupation, $food_choice, $maritalStatusCodeOrStringPref, $manglikStatusCodeOrStringPref, $foodChoiceCodeOrStringPref, $occupationCodeOrStringPref)
    {
        return UserPreference::updateOrCreate([
            "user_data_id"          =>      $user_data_id
        ], [
            "castePref"             =>      $caste_array,
            "religionPref"          =>      $religion,
            "manglikPref"           =>      $manglik,
            "food_choicePref"       =>      $food_choice,
            "age_min"               =>      $age_min,
            "age_max"               =>      $age_ax,
            "height_min_s"          =>      $min_height,
            "height_max_s"          =>      $max_height,
            "height_min"            =>      $min_height,
            "height_max"            =>      $max_height,
            "marital_statusPref"    =>      $marital_status,
            "food_choicePref"       =>      $food_choice,
            "workingPref"           =>      $occupation,
            "income_min"            =>      $income_min,
            "income_max"            =>      $income_max,
            "marital_status"            =>      $maritalStatusCodeOrStringPref,
            "manglik"            =>      $manglikStatusCodeOrStringPref,
            "food_choice"            =>      $foodChoiceCodeOrStringPref,
            "occupation"            =>      $occupationCodeOrStringPref
        ]);
    }
}
