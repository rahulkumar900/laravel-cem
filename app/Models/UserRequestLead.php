<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestLead extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lead_id', 'lead_type', 'lead_status'];

    public static function createRecord($user_id, $lead_id, $lead_type)
    {
        return UserRequestLead::create([
            'user_id'       =>      $user_id,
            'lead_id'       =>      $lead_id,
            'lead_type'     =>      $lead_type
        ]);
    }

    // return user today's requested lead
    public static function requestedLeads($user_id, $start_time, $end_time)
    {
        return UserRequestLead::whereBetween('created_at', [$start_time, $end_time])->where('user_id', $user_id)->get();
    }

    // update lead status
    protected static function updateLeadStatus($lead_id, $lead_type, $lead_status)
    {
        return UserRequestLead::where(['lead_id' => $lead_id, 'lead_type' => $lead_type])->update([
            'lead_status'           =>      $lead_status
        ]);
    }

    // check for requested leads
    protected static function checkRequestedLeads($user_id)
    {
        $date = date('Y-m-d');
        return UserRequestLead::where(['lead_status' => 'requested', 'user_id' => $user_id])->whereRaw("DATE(created_at) = '$date'")->get();
    }

    public static function changeStatus($user_id, $lead_type, $id, $status)
    {
        //dd($user_id.', '.$lead_type . ', ' .$id . ', ' . $status);
        return UserRequestLead::where(['user_id' => $user_id, 'lead_type' => $lead_type, 'lead_id' => $id])->update([
            'lead_status'       =>      $status
        ]);
    }
}
