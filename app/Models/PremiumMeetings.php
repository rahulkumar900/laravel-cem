<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PremiumMeetings extends Model
{
    use HasFactory;
    /*
        status :
        0 profile send
        1
        2
        3 meeting
        4
    */
    protected $table = "premium_meetings";

    protected $fillable = ["user_id", "matched_id", "status", "like", "rejected", "comments", "rejected_by", "followup_call_on", "meeting_date", "all_meeting", "meeting_count"];

    public function premiumMeetingUser()
    {
        return $this->hasOne(UserData::class, 'id', 'user_id');
    }

    public function premiumMeetingUserMathced()
    {
        return $this->hasOne(UserData::class, 'id', 'matched_id');
    }

    // update user meeting status
    protected static function updateMeetings($user_id, $match_id, $status, $comment, $floowup_call, $meeting_date)
    {
        if ($status == 2) {
            return PremiumMeetings::where(["user_id" => $user_id, "matched_id" => $match_id])->update([
                "comments"          =>          $comment,
                "followup_call_on"  =>          $floowup_call,
                "meeting_date"      =>          $meeting_date,
                "all_meeting"       =>          DB::raw("all_meeting+1"),
                "meeting_count"     =>          DB::raw("meeting_count+1"),
            ]);
        } else {
            return PremiumMeetings::where(["user_id" => $user_id, "matched_id" => $match_id])->update([
                "status"            =>          $status,
                "comments"          =>          $comment,
                "followup_call_on"  =>          $floowup_call,
                "meeting_date"      =>          $meeting_date,
                "all_meeting"       =>          DB::raw("all_meeting+1"),
                "meeting_count"     =>          DB::raw("meeting_count+1"),
            ]);
        }
    }

    // create new meetintg
    protected static function createMeeting($user_id, $match_id, $status, $comment, $floowup_call, $meeting_date)
    {
        return PremiumMeetings::create([
            "user_id"           =>          $user_id,
            "matched_id"        =>          $match_id,
            "status"            =>          $status,
            "comments"          =>          $comment,
            "followup_call_on"  =>          $floowup_call,
            "meeting_date"      =>          $meeting_date,
            "all_meeting"       =>          DB::raw("all_meeting+1"),
            "meeting_count"     =>          DB::raw("meeting_count+1"),
        ]);
    }

    // get user meeting details (user to user)
    protected static function userMeetingDetails($user_id, $match_id)
    {
        return PremiumMeetings::where(['user_id' => $user_id, 'matched_id' => $match_id])->first();
    }

    // get all meetings of a user
    protected static function getAllPremiumMeetings($user_id, $status, $start_date = null, $end_date = null)
    {

        $data =  PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')
            ->where(['premium_meetings.user_id' => $user_id])->whereRaw('status in(' . $status . ')');
        if ($start_date != null &&  $end_date != null) {
            $data = $data->whereBetween('premium_meetings.updated_at', [$start_date, $end_date]);
        }
        return $data->get([
            'user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status',
            'user_data.photo', 'user_data.user_mobile', 'premium_meetings.*'
        ]);
    }
    protected static function getAllPremiumMeetings2($user_id, $status, $start_date = null, $end_date = null)
    {

      
        return PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')
        ->where(['premium_meetings.user_id' => $user_id])->whereRaw('status in(' . $status . ')')->get([
            'user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status',
            'user_data.photo', 'user_data.user_mobile', 'premium_meetings.*'
        ]);
    }

    protected static function getAllPremiumMeetingList($user_id, $status)
    {
        return PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')->where(['user_data.temple_id' => $user_id])->whereRaw('status in(' . $status . ')')->get(['user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status', 'user_data.photo', 'premium_meetings.*', 'user_data.user_mobile as mobile']);
    }

    protected static function getAllRejectedPremiumMeetings($user_id, $start_date, $end_date)
    {
        return PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')
            ->where('premium_meetings.user_id', $user_id)
            ->where('status', 0)
            ->whereBetween('premium_meetings.updated_at', [$start_date, $end_date])
            ->get(['user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status', 'user_data.photo', 'premium_meetings.*', 'user_data.user_mobile as mobile']);
    }

    protected static function getAllLikedPremiumMeetings($user_id, $start_date, $end_date)
    {
        return PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')
            ->where('premium_meetings.user_id', $user_id)
            ->where('status', 3)
            ->whereBetween('premium_meetings.updated_at', [$start_date, $end_date])
            ->get(['user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status', 'user_data.photo', 'premium_meetings.*', 'user_data.user_mobile as mobile']);
    }

    protected static function getAllLikedProfileList($user_id, $start_date, $end_date)
    {
        return PremiumMeetings::join('user_data', 'user_data.id', 'premium_meetings.matched_id')
            ->where('premium_meetings.user_id', $user_id)
            ->where('status', 2)
            ->whereBetween('premium_meetings.updated_at', [$start_date, $end_date])
            ->get(['user_data.name', 'user_data.id as user_id', 'user_data.caste', 'user_data.marital_status', 'user_data.photo', 'premium_meetings.*', 'user_data.user_mobile as mobile']);
    }
}
