<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    //
    public function index()
    {

        $channels = Channel::first()->toarray();
        $channel_name1 = $channel_name2 = $channel_token1 = $channel_token2 = $channel_handler1 = $channel_handler2 = null;

        if (count($channels)) {
            $channel_name1 = $channels['name'];
            $channel_token1 = $channels['token'];
            $channel_handler1 = $channels['blank_lead_handler'];
            $channel_name2 = $channels['name'];
            $channel_token2 = $channels['token'];
            $channel_handler2 = $channels['blank_lead_handler'];
        }

        $facebook_lead = Channel::Where('name', 'LIKE', '%Facebook Leads%')->first();
        $facebook_hiring = Channel::where('name', 'LIKE', '%Facebook Hiring%')->get()->toArray();
        $sender_whatsapp_no = User::where('temple_id', 'admin')->first()->mobile;
        $ad_accounts = AdAccount::where('is_active', 1)->get();
        $countries = DB::table('countries')->get();
        $facebook_lead_additional = '';
        return view('admin.addfbtoken', compact('channel_name1', 'channel_token1', 'channel_handler1', 'channel_name2', 'channel_token2', 'channel_handler2', 'facebook_lead', 'facebook_lead_additional', 'facebook_hiring', 'sender_whatsapp_no', 'countries', 'ad_accounts'));
    }


    public function saveFbToken(Request $request)
    {
        $save_record = '';

        $save_record = Channel::create([
            'name'              =>      $request->facebook_leads_name,
            'token'             =>      $request->facebook_leads_add_id, 'account_id'        =>      $request->facebook_leads_account_id,
            'campaign_country'  =>      $request->facebook_leads_country,
            "type"              =>      "Facebook"
        ]);

        if ($save_record) {
            return response()->json(["type" => true, "message" => "record added"]);
        }
    }

    //919720003886
}
