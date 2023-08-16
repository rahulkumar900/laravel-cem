<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionModel extends Model
{
    use HasFactory;
    protected $table = 'acquisition_channels';

    protected $fillable = ['lead_id','incomplete_lead_id','profile_id','source','acquisition_channel','on_old','on_call_on_operator','on_visit','on_assisted_telesales','on_number_drop','on_app','on_web','engage_app','engage_web','engage_teleSales','engage_temple_visit','engage_temple_visit','conv_temple_visit','conv_home_visit','conv_cash_collection','conv_online_assist','conv_online_automated','conv_online_auto_razor','conv_online_auto_paytm','conv_office_visit','conv_app','conv_web','shortlink','campaign_name','mop','account','mobile','c','pid','g_cl_id','ad_id','current_status','fb_lead_id'];

    //save data to acquisition table
    public static function saveDataToAcquisition()
    {
        return '';
    }
}
