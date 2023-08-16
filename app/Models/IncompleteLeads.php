<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncompleteLeads extends Model
{
    use HasFactory;

    protected $table = 'incomplete_leads';

    protected $fillable = ['id', 'call_time', 'call_duration', 'recording_link', 'temple_name', 'temple_phoneno', 'assign_to', 'status', 'user_phone', 'area', 'channel', 'parent_id', 'call_time_stamp', 'token', 'on_delete_comment', 'isDelete', 'platform', 'profile_created', 'call_in', 'request_by', 'request_by_at', 'deleteLead', 'call_count'];

    // check facebook request leads
    public static function checkFaceBookLeads($temple_id)
    {
        return IncompleteLeads::where('request_by', $temple_id)->where('isDelete', 0)->whereRaw("channel LIKE '%facebook%'")->limit(5)->orderBy("mobile")->get(['fname as name', 'user_phone as mobile','id']);
    }

    // check channel one requested leads
    public static function channelOneLeads($temple_id)
    {
        return IncompleteLeads::where('request_by', $temple_id)->where('isDelete', 0)->where('channel', 'Channel1')->orderBy('created_at', 'DESC')/*->groupBy('user_phone')*/->get();
    }

    // data channel requested leads
    public static function checkDataChannel($temple_id)
    {
        return IncompleteLeads::where('request_by', $temple_id)->where('isDelete', 0)->where('channel', 'data')->orderBy('created_at', 'DESC')/*->groupBy('user_phone')*/->get();
    }

    // request incomplete facebook leads give five leads public protected private
    protected static function requestFacebookLeads($call_count, $createed_at)
    {
        $incomplete_leads = IncompleteLeads::whereNull('request_by')
            ->whereRaw('channel like "%Facebook%"')
            ->whereNotNull('status')
            ->where('isDelete', 0);

        if ($call_count>0) {
            $incomplete_leads =  $incomplete_leads->where('call_counts', '<=', $call_count);
        } else {
            $incomplete_leads =  $incomplete_leads->where('call_counts', '<', 2);
        }

        if ($createed_at) {
            $incomplete_leads =  $incomplete_leads->where('created_at', '<', date('Y-m-d', strtotime('-' . $createed_at . ' days')) . " 00:00:00");
        }

        $incomplete_leads = $incomplete_leads/*->groupBy('user_phone')*/
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return $incomplete_leads;
    }

    protected static function requestOperatorCalls()
    {
        return IncompleteLeads::where(['status'=>'blank','channel'=>'Channel1', 'request_by'=>null, 'isDelete'=>0])->orderBy("call_time","desc")->take(5)->get();
    }

    // request leads for D category people
    private static function dLevelTempleLeads()
    {
        return IncompleteLeads::whereNotNull('status')
            ->where('isDelete', 1)
            ->whereRaw("(1-isDelete)*(call_count-2)>=0")
            ->where('request_by', null)
            ->whereRaw('channel like "%Facebook%"')
            ->whereNull('request_by_at')
            ->groupBy('user_phone')
            ->orderby('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    // mark isDelete 1 to incomplet lead or delete lead
    public static function deleteIncompleteLead($mobile)
    {
        // dd("2345678974567");
        return IncompleteLeads::where('user_phone', 'Like', "%$mobile%")->update([
            'isDelete' => '1'
        ]);
    }

    // update requested leads status
    public static function updateRequestedLeads($mobile, $temple_id)
    {
        return IncompleteLeads::where('user_phone', 'Like', "%$mobile%")->update([
            'request_by' => $temple_id, 'request_by_at' => date('Y-m-d H:i:s')
        ]);
    }

    // update not pickp
    public static function updateNotPickup($lead_id, $temple_id)
    {
        return IncompleteLeads::whereRaw("id = $lead_id")->update([
                'request_by'    => 'Not-Pick ' . $temple_id,
                'call_counts'   => DB::raw('call_counts+1'),
        ]);
    }

    // update call pick lead created
    public static function updatePickup($mobile, $user_data_id)
    {
        return IncompleteLeads::where('user_phone', 'Like', "%$mobile%")->update([
            'isDelete'          => 1,
            'on_delete_comment' =>  $user_data_id
        ]);
    }
}
