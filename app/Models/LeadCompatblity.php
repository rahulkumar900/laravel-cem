<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCompatblity extends Model
{
    use HasFactory;

    protected $table = 'leadCompatibilities';

    protected $fillable = ['user_id','for_lead','compatibility','discoverCompatibility','current','active','whatsapp_point','amount_collected','daily_quota','profile_status','si_status','ri_status','reminder','message_is_no','read_message','last_id','extra','pdfs','free_credits_given','free_credit_count','free_id','contacted_count','reject_count','shortlist_count','shown_interest_count','mutual_count','is_recalculate','si_count','discovery_profile_left','first_time','first_comp_day','max','max_new','virtual_receive','virtual_receive_count','free_ids','random_updated','random_count','viewProfile_count','viewed_profiles','negative_contacted_profiles','today_activation_link'];

    public static function leadCompatblityAdd(){

    }
}
