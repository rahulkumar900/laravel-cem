<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeUser extends Model
{
    use HasFactory;

    protected $table = 'free_users';

    protected $fillable = ['last_seen','is_deleted','is_approve_ready','mobile','new_mobile','temple_id','age','photo','photo_score','carousel','religion','caste','education','height','weight','manglik','marital_status','occupation','gender','annual_income','birth_date','birth_time','locality','degree','college','additional_qualification','profession','company','working_city','food_choice','about','audio_profile','earned_rewards','audio_uploaded_at','audio_approved_at','unapprove_audio_profile','audioProfile_hls_convert','lead_id','extra_lead_id','backup_lead_id','org_lead_id','profiles_sent','pdf_url','wantBot','bot_language','fcm_id','bot_status','last_seen','fcm_app','birth_place','relation','lat_locality','long_locality','education_score','is_premium_interest','profile_percent','disability','disabled_part','abroad','si_event','unapprove_carousel','is_call_back','call_back_query','is_deleted','deleted_by'];

    // relation with leads
    public function leadsJoin(){
        $this->hasOne(Lead::class,'lead_id','id');
    }

    // add data to free users
    public static function addDataToFreeUsers($mobile,$temple_id,$religion,$caste,$education,$height,$weight,$marital_status,$occupation,$gender,$income,$birth_date,$locality,$degree,$profession,$about,$lead_id,$relation)
    {
        return FreeUser::create([
            'mobile'            =>      $mobile,
            'new_mobile'        =>      $mobile,
            'temple_id'         =>      $temple_id,
            'religion'          =>      $religion,
            'caste'             =>      $caste,
            'education'         =>      $education,
            'height'            =>      $height,
            'weight'            =>      $weight,
            'marital_status'    =>      $marital_status,
            'occupation'        =>      $occupation,
            'gender'            =>      $gender,
            'annual_income'     =>      $income,
            'birth_date'        =>      $birth_date,
            'locality'          =>      $locality,
            'degree'            =>      $degree,
            'profession'        =>      $profession,
            'about'             =>      $about,
            'lead_id'           =>      $lead_id,
            'extra_lead_id'     =>      $lead_id,
            'backup_lead_id'    =>      $lead_id,
            'org_lead_id'       =>      $lead_id,
            'relation'          =>      $relation,
        ]);
    }
}
