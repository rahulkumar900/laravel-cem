<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRM extends Model
{
    use HasFactory;
    protected $table = 'crms';

    protected $fillable = ["user_id", "name", "mobile", "followup_call_on", "last_followup_date", "comment", "is_done", "created_at", "updated_at", "leads_of", "assign_by", "call_count","is_selfUpgrade", "is_exhaust"];

    public static function serchCrmData($mobile)
    {
        return CRM::where('crms.mobile', 'Like', "%$mobile%")->first();
    }

    // assign lead to self
    public static function assignLead($lead_id, $user_id)
    {
        return CRM::where('id',$lead_id)->update([
            'assign_by'         =>      $user_id
        ]);
    }

    // add crm lead
    public static function FunctionName($name, $mobile, $followupcall, $last_followup, $comment, $assign_by,$user_id)
    {
        return CRM::create([
            "name"              =>      $name,
            "mobile"            =>      $mobile,
            "user_id"           =>      $user_id,
            "followup_call_on"  =>      $followupcall,
            "last_followup_date"=>      $last_followup,
            "comment"           =>      $comment,
            "is_done"           =>      0,
            "created_at"        =>      date('Y-m-d H:i:s'),
            "updated_at"        =>      date('Y-m-d H:i:s'),
            "assign_by"         =>      $assign_by,
            "call_count"        =>      0
        ]);
    }

    // requested CRM leads
    public static function checkRequestedCRMLeads($temple_id)
    {
        return CRM::join('user_data','user_data.id', 'crms.user_id')->where('crms.request_by',$temple_id)->get(['user_data.name','crms.id', 'user_data.user_mobile'])->get();
    }

    // update rquested CRM leads
    public static function updateCRMLeads($temple_id, $crm_ids)
    {
        return CRM::whereRaw("id IN($crm_ids)")->update([
            'request_by'            =>      $temple_id,
            'last_assigned_date'    =>      date('Y-m-d')
        ]);
    }

    // get new crm leads
    public static function getNewCRmLeads()
    {
        return CRM::join('user_data', 'user_data.id', 'crms.user_id')->where(['crms.request_by'=>'online', 'request_by'=>''])->get(['user_data.name', 'crms.id', 'user_data.user_mobile'])->get();
    }


}
