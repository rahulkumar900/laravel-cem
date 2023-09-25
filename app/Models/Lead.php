<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';


    protected $fillable = ['serial_number', 'assign_by', 'assign_to', 'name', 'mobile', 'enquiry_date', 'followup_call_on', 'comments', 'by_admin_fl', 'is_done', 'appointment_date', 'appointment_created_on', 'appointment_by', 'speed', 'source', 'visited_on', 'online_score', 'offline_score', 'alt_mobile', 'lead_progress', 'call_in', 'call_out', 'web_direct', 'visit', 'app', 'is_subscription_view', 'subscription_seens', 'request_by', 'call_count', 'profile_created', 'rejected_at ', 'reject_count', 'is_call_back', 'call_back_query', 'is_premium_interest', 'lead_value', 'user_data_id', 'age', 'marital_status', 'assigned_at'];

    //relation with users
    /*protected function users()
    {
        return $this->hasOne(User::class, 'temple_id', 'assign_to', );
    }*/

    public function detailsJoin()
    {
        return $this->hasOne(UserData::class, 'id', 'user_data_id');
    }

    public function user_data()
    {
        return $this->belongsTo(UserData::class, 'user_data_id', 'id');
    }

    // relation with free users
    protected function freeUsersJoin()
    {
        return  $this->hasOne(FreeUser::class, Lead::class)->where('is_deleted', 0);
    }

    // search lead details by number
    protected static function searchLeadData($mobile)
    {
        return Lead::join('user_data', 'leads.user_data_id', 'user_data.id')
            ->whereRaw("user_data.user_mobile Like '%$mobile%' OR user_data.mobile_family Like '%$mobile%'
        OR user_data.whatsapp_family Like '%$mobile%' ")->orderBY('id', 'DESC')
            ->first([
                'user_data.name', 'user_data.user_mobile as mobile', 'leads.assign_to', 'leads.enquiry_date',
                'leads.followup_call_on', 'leads.last_followup_date', 'leads.comments', 'leads.is_done', 'user_data.id',
                'leads.id as lead_id', 'leads.assign_by'
            ]);
    }

    // save data into lead table
    protected static function addDataToLead($user_id, $enquiry_date, $followup_call_on, $last_followup_date, $followup_call_at, $comments, $speed, $source, $lead_value, $channel, $user_data_id, $mobile, $lead_name)
    {
        return Lead::updateOrCreate(
            [
                'user_data_id'          =>      $user_data_id,
            ],
            [
                'assign_by'             =>      $user_id,
                'assign_to'             =>      $user_id,
                'enquiry_date'          =>      $enquiry_date,
                'followup_call_on'      =>      $followup_call_on,
                'last_followup_date'    =>      $last_followup_date,
                'followup_call_at'      =>      $followup_call_at,
                'comments'              =>      $comments,
                'speed'                 =>      $speed,
                'source'                =>      $source,
                'lead_value'            =>      $lead_value,
                'channel'               =>      $channel,
                'user_data_id'          =>      $user_data_id,
                'age'                   =>      20,
                'marital_status'        =>      'NA',
                'is_done'               =>      0,
                'assigned_at'           =>      $last_followup_date,
                'name'                  =>      $lead_name,
                'mobile'                =>      $mobile
            ]
        );
    }

    // save lead followup
    protected static function saveFollowups($lead_id, $user_name, $comment, $next_followup_date, $plan_amount, $speed, $temple_id, $temple_name)
    {
        $prev_comment = Lead::where('id', $lead_id)->first();
        //dd(Auth::user()->temple_id);
        /*if (empty($prev_comment)) {
            $prev_comment = Lead::where('id', $lead_id)->first();
        }*/
        if (!empty($prev_comment)) {
            $new_comment = " Lead moved from online to " . $temple_name . " " . date('d-M-Y H:i:s') . ";"
                . ' ' . date('d-M-Y H:i:s') . ' - ' . $comment . " by($user_name);";
            $all_comment = $prev_comment->comments . $new_comment;
            $update_lead = Lead::where('id', $lead_id)->update([
                "comments"             => $all_comment,
                "followup_call_on"     => $next_followup_date,
                "assign_by"            => $temple_id,
                "assign_to"            => $temple_id,
                "last_followup_date"   => date('Y-m-d h:i:s'),
                "amount_collected"     => $plan_amount,
                "speed"                => $speed,
            ]);
            if ($update_lead) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // save Crm lead followup
    protected static function saveCrmFollowups($lead_id, $user_name, $comment, $next_followup_date, $plan_amount, $speed, $temple_id)
    {
        $prev_comment = Lead::where('id', $lead_id)->first();
        // dd($lead_id);
        /*if (empty($prev_comment)) {
            $prev_comment = Lead::where('id', $lead_id)->first();
        }*/
        if (!empty($prev_comment)) {
            $all_comment = $prev_comment->comments . ' ' . date('d-M-Y H:i:s') . ' - ' . $comment . " by($user_name);";
            $update_lead = Lead::where('id', $lead_id)->update([
                "comments"             => $all_comment,
                "followup_call_on"     => $next_followup_date,
                "assign_by"            => $temple_id,
                "assign_to"            => $temple_id,
                "last_followup_date"   => date('Y-m-d h:i:s'),
                "amount_collected"     => $plan_amount,
                "speed"                => $speed,
            ]);
            if ($update_lead) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // check request leads into table
    protected static function checkRequestedLeads($temple_id)
    {
        $all_leads = Lead::join('user_data', 'leads.user_data_id', 'user_data.id')
            ->where('leads.request_by', $temple_id)
            ->where('leads.assign_to', 'online')
            ->where('leads.assign_by', 'online')
            ->where('leads.call_count', '<=', 2)
            ->where('leads.is_done', 0)
            ->limit(5)
            ->get(['leads.id', 'user_data.name', 'user_data.user_mobile as mobile', 'leads.comments', 'leads.user_data_id']);
        // dd($all_leads);
        return $all_leads;
    }

    // check subscription viewed
    protected static function subscriptionView($temple_id)
    {
        return Lead::join('free_users', 'free_users.lead_id', 'leads.id')
            ->where('leads.is_done', 0)
            ->where('leads.request_by', $temple_id)
            ->where('leads.assign_to', 'online')
            ->where('leads.assign_by', 'online')
            ->where('leads.call_count', '<', 2)
            ->where('leads.is_subscription_view', 1)
            ->get();
    }

    // converted leads
    protected static function convertedLeads($temple_id, $week_old_date, $six_months_old)
    {
        return Lead::join('free_users', 'free_users.lead_id', 'leads.id')
            ->where('leads.request_by', $temple_id)
            ->where('leads.is_done', 2)
            ->where('reject_count', '<', 2)
            ->where('leads.call_count', '<', 2)
            ->where('rejected_at', '<', $week_old_date)
            ->where('last_seen', '>', $six_months_old)
            ->get();
    }

    // get website leads
    protected static function getWebsiteLeads($limit, $speed, $income)
    {
        $lead_data = "";

        $lead_data = Lead::join('user_data', 'user_data.id', 'leads.user_data_id')
            ->where('leads.is_done', 0)
            ->where('leads.call_count', '<', 2)
            ->where('leads.assign_to', 'online')
            ->where('leads.assign_by', 'online')
            ->where('leads.is_subscription_view', 1)
            ->where('user_data.maritalStatusCode', '!=', 2);
        if (!empty($speed)) {
            $lead_data = $lead_data->where('speed', $speed);
        }

        if (!empty($income)) {
            $lead_data = $lead_data->where('user_data.monthly_income', '<', $income);
        }
        $lead_data = $lead_data->whereRaw('leads.request_by is null or leads.request_by="online"');
        $lead_data = $lead_data->orderBy('leads.created_at', 'DESC')->limit($limit)
            ->get(['user_data.name', 'user_data.user_mobile as mobile', 'leads.id', 'leads.comments']);
        return $lead_data;
    }

    // get exhaust leads
    protected static function getExhaustLeads($limit, $speed, $income, $credit)
    {
        $lead_data = "";
        $lead_data = Lead::join('user_data', 'user_data.id', 'leads.user_data_id')
            ->leftJoin("userCompatibilities", "userCompatibilities.user_data_id", "leads.user_data_id")
            ->where('leads.is_done', 1)
            ->where('user_data.maritalStatusCode', '!=', 2);

        if (!empty($speed)) {
            $lead_data = $lead_data->where('speed', $speed);
        }

        if ($credit >= 1) {
            $lead_data = $lead_data->where('userCompatibilities.credit_available', '>=', 1);
        }

        if (!empty($income)) {
            $lead_data = $lead_data->where('user_data.monthly_income', '<', $income)->orderBy('user_data.monthly_income', 'desc');
        }

        // OR leads.assign_by = "online"
        $lead_data = $lead_data->whereRaw('(leads.assign_to = "online") AND (leads.request_by is null or leads.request_by="online")');

        $lead_data = $lead_data->orderBy('leads.created_at', 'DESC')->limit($limit)
            ->select(['user_data.name', 'user_data.user_mobile as mobile', 'leads.user_data_id as user_id', 'leads.comments', 'credit_available', 'leads.id as id']);

        $lead_data = $lead_data->get();
        //$lead_data = $lead_data->toSql();
        //dd($lead_data);
        return $lead_data;
    }

    // update request by in lead
    protected static function updateRequestBy($lead_id, $temple_id)
    {
        return Lead::whereRaw("id IN($lead_id)")->update([
            'request_by'        =>      $temple_id
        ]);
    }

    // assign to me
    protected static function assignToMe($temple_id, $lead_id, $temple_name)
    {
        $lead_details = Lead::where('user_data_id', $lead_id)->first();

        $lead_status = 0;

        if ($lead_details->is_done == 2 || $lead_details->is_done == 0) {
            $lead_status = 0;
        } else if ($lead_details->is_done == 1) {
            $lead_status = 1;
        }

        $temple_name_mod = $temple_name;
        $date_curr = date("d-M-Y H:i:s");
        $lead_details->assign_by = $temple_id;
        $lead_details->assign_to = $temple_id;
        $lead_details->is_done = $lead_status;
        $lead_details->assigned_at = Carbon::now();
        $lead_details->comments  = $lead_details->comments . "  $date_curr :- Lead Move from online to $temple_name_mod;";
        $lead_details->save();
        return $lead_details;
    }
    // lead details by id
    protected static function leadDetailsById($user_id)
    {
        return Lead::join('user_data', 'user_data.id', 'leads.user_data_id')->where('user_data.id', $user_id)->first(['user_data.name', 'user_data.user_mobile as mobile', 'leads.assign_to', 'leads.enquiry_date', 'leads.followup_call_on', 'leads.last_followup_date', 'leads.comments', 'leads.is_done', 'user_data.id', 'leads.id as lead_id', 'leads.assign_by', 'user_data.user_mobile']);
    }

    // count message send into lead table
    protected static function messageCount($date_from, $date_to, $temple_id)
    {
        // lead and user data ka join hoga
        return DB::select("SELECT count(leads.id) as count, messege_send_count FROM leads inner join user_data on user_data.id = leads.user_data_id
        where leads.profile_created =0
        and user_data.is_deleted = 0
        and user_data.not_interested = 0
        and leads.is_done=0
        and user_data.is_approved=0
        and user_data.is_approve_ready = 0
        and user_data.annual_income > '250000'
        and user_data.pending_temple_id= $temple_id
        and user_data.maritalStatusCode != '2'
        and (leads.created_at between '$date_from' and '$date_to')
        AND (user_data.photo_url  is null or user_data.photo_url ='')
        group by messege_send_count ORDER BY messege_send_count");
    }

    // get all leads
    protected static function getAllLeads($assign_to)
    {
        return Lead::with('detailsJoin')->where('assign_to', $assign_to)->get();
    }

    protected static function getAllLeadscounts($assign_to)
    {
        return Lead::with('detailsJoin')->where(['assign_to' => $assign_to, 'is_done' => 0])->get(['id'])->count();
    }

    // get limited leads data
    protected static function getLimitedLeads($assign_to, $limit)
    {
        return Lead::where('assign_to', $assign_to)->take($limit)->get(['id']);
    }

    // reject lead
    protected static function rejectLead($lead_id, $comment)
    {
        $prev_comment = Lead::where('id', $lead_id)->first();
        $all_comment = $prev_comment->comments . ' ' . date('d-M-Y') . ' - ' . $comment . ';';
        return Lead::where('id', $lead_id)->update([
            'is_done'       =>      2,
            'comments'      =>      $all_comment
        ]);
    }

    // Delete a Lead
    protected function deleteLead($lead_id,$comment){
        $lead = Lead::where('id',$lead_id)->first();
        $all_comment = $lead->comments . ' ' . date('d-M-Y') . ' - ' . $comment . ';';
        return Lead::where('id',$lead_id)->update([
           // update all necessary filed
        ]);
    }

    // search lead in crm table also
    protected static function checkCrmLeads($mobile)
    {
        return Lead::join('user_data', 'user_data.id', 'leads.user_data_id')->leftJOin('crms', 'crms.user_id', 'user_data.id')->where('user_data.user_mobile', 'Like', "%$mobile%")->orderBY('id', 'DESC')->first(['user_data.name', 'user_data.user_mobile as mobile', 'leads.assign_to', 'leads.enquiry_date', 'leads.followup_call_on', 'leads.last_followup_date', 'leads.comments', 'leads.is_done', 'user_data.id', 'leads.id as lead_id', 'crms.assign_by', 'crms.id as crm_id']);
    }

    // today's folloup query
    protected static function todaysFolloup($temple_id)
    {
        $today = date('Y-m-d');
        return Lead::with('user_data:id,name,user_mobile')->where(['leads.assign_to' => $temple_id, 'is_done' => 0])->whereRaw("DATE(last_followup_date) = '$today'")->get();
    }

    protected static function myPendingLeads($temple_id)
    {
        $today = date('Y-m-d');
        return Lead::with('user_data:id,user_mobile,name')->where(['leads.assign_to' => $temple_id, 'is_done' => 0])->whereRaw("DATE(followup_call_on) < '$today' AND last_followup_date < '$today'")->get();
    }

    protected static function updateAssignTo($lead_ids, $assign_to)
    {
        return Lead::whereIn('id', $lead_ids)->update(['assign_to' => $assign_to]);
    }

    protected static function updateNotPickup($lead_id, $temple_id)
    {
        return Lead::whereRaw("id = $lead_id")->update([
            'request_by'    => 'Not-Pick ' . $temple_id,
            'call_count'   => DB::raw('call_count+1'),
        ]);
    }
}
