<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFamily extends Model
{
    use HasFactory;

    protected $table = 'lead_family';

    protected $fillable = ['lead_id','relation','livingWithParents','locality','mobile','new_mobile','backup_mobile','email','unmarried_sons','married_sons','unmarried_daughters','married_daughters','family_type','house_type','religion','caste','gotra','occupation','family_income','office_address','father_status','whats_app_no','mother_status','mother_tongue','occupation_mother','address','about','city','father_off_addr','email_verified'];


    // save  details into family table
    public static function saveFamilyRecord($lead_id,$relation,$locality,$mobile,$email,$religion,$caste,$occupation,$family_income,$about,$city){
        return LeadFamily::create([
            'lead_id'               =>      $lead_id,
            'relation'              =>      $relation,
            'locality'              =>      $locality,
            'mobile'                =>      $mobile,
            'new_mobile'            =>      $mobile,
            'backup_mobile'         =>      $mobile,
            'email'                 =>      $email,
            'religion'              =>      $religion,
            'caste'                 =>      $caste,
            'occupation'            =>      $occupation,
            'family_income'         =>      $family_income,
            'whats_app_no'          =>      $mobile,
            'about'                 =>      $about,
            'city'                  =>      $city,
        ]);
    }

}
