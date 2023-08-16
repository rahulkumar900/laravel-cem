<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPreference extends Model
{
    use HasFactory;

    protected $table = 'lead_preference';

    protected $fillable = ['lead_id','age_min','age_max','height_min','height_max','caste','marital_status','manglik','food_choice','working','occupation','income_min','income_max','citizenship','description','description','caste_no_bar','mother_tongue','amount_collected','religion','pref_state','pref_country','pref_city','pref_state_id','pref_country_id'];

    // save data to preference table
    public static function savePreferences($lead_id,$age_min,$age_max,$height_min,$height_max,$caste,$religion,$marital_status)
    {
        return LeadPreference::create([
            'lead_id'           =>          $lead_id,
            'age_min'           =>          $age_min,
            'age_max'           =>          $age_max,
            'height_min'        =>          $height_min,
            'height_max'        =>          $height_max,
            'caste'             =>          $caste,
            'religion'          =>          $religion,
            'marital_status'    =>          $marital_status,
        ]);
    }
}
