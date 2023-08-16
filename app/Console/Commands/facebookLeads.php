<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IncompleteLeads;
use App\Models\IncompleteHiringLeads;
use App\Models\Channel;
use App\Models\User;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class FacebookLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add leads from facebook to incomplete table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    /**
     * Handle to save the facebook leads(facebook graph api, an external api) incomplete_leads table .
     *
     * @SWG\Post(path="/cronTostoreFacebookLeads",
     *   summary="store facebook leads in incomplete_leads table",
     * description=" business logic ->store all the faebook advt. leads in incomplete_leas table and tele executive can make call, it runs in every 30 min interval table used ->incomplete_leas(save the leads of facebook),channels(advt tokens),mobile_numbers(tele executive mobile numbers) variable & function used=>fb_channels ->details of the advt. running on facebook fb_ad_id ->the account name from which the advt. running, there are two account in current, can be seen in table ad_accounts access_token ->unique token of fb advt. chekMobileWithOpenLead() ->check the fb leads mobile number is matching with aa open lead(is_done=0) in any leads table blank_lead_handler ->this variable is used that, we do get a leads and that has not assigned any mobile number, then blank_lead_handler will have a default mobile number code logic=>fetch all the fb_channels and loop over channels, corresponding to respective channel get the access_id(account id), and add these to fb url and hit the url with curl, save the curl response in incomplete_leas table ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="login user",
     *     description="JSON Object which login user",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", example="user@mail.com"),
     *         @SWG\Property(property="password", type="string", example="password"),
     *     )
     *   ),
     *   @SWG\Response(response="200", description="Return token or error message") eazybe
     * )
     *
     */
    public function handle()
    {
        $fb_channel_exists = Channel::where('type', 'Facebook')->exists();

        if ($fb_channel_exists) {
            $fb_channels = Channel::where('type', 'Facebook')->latest()->get();
            foreach ($fb_channels as $fb_channel) {

                if (strpos($fb_channel->name, "Hiring") == false && strpos($fb_channel->name, "EazyBe") == false) {
                    $this->storeFacebookLeads($fb_channel->name);
                } else {
                    $this->storeFacebookHiringLeads($fb_channel->name);
                }
            }
        }
        $this->info('Fb Leads added succesfully!');
    }

    public function storeFacebookLeads($fb_channel_name)
    {
        $fb_channel = Channel::where('name', $fb_channel_name)->first();
        $fb_ad_id = $fb_channel->token;

        $lastExecutionTime = $fb_channel->last_execution_time;

        $temple_id = User::whereRaw("mobile LIKE '%$fb_channel->blank_lead_handler%' ")->first()->temple_id;  //

        $temple = User::where('temple_id', $temple_id)->first();
        $access_token = $fb_channel->access_token;
        $url = 'https://graph.facebook.com/v14.0/' . $fb_ad_id . '/leads?access_token=' . $access_token . '&limit=500';
        // dd("\n".$url);
        $ctr = 0;
        $latestLeadTime = $lastExecutionTime;
        do {
            $fb_leads = $this->_post_fb_api($url);
            // echo $fb_channel_name."   <br>";
            $fb_leads = json_decode($fb_leads);
            $i = 0;

            if (isset($fb_leads->data)) {
                //dd($fb_leads->data);
                foreach ($fb_leads->data as $leads) {
                    //print_r($leads);
                    if ($ctr == 0) {
                        $latestLeadTime = strtotime($leads->created_time) + 19800; // As first one is the latest lead
                    }
                    $ctr++;
                    if (strtotime($leads->created_time) + 19800 > $lastExecutionTime) {

                        $incompleteLead = new IncompleteLeads(); //creating an objecto of incoplete leads table/model
                        foreach ($leads->field_data as $data) {
                            $lead_id = '';
                            if (!empty($fb_leads->data[$i]->id)) {
                                $lead_id = $fb_leads->data[$i]->id;
                            } else {
                                $lead_id = null;
                            }
                            if ($data->name == 'phone_number') {
                                $openCheck = $this->checkMobileWithOpenLead($data->values[0]);
                                if ($openCheck) {
                                    $incompleteLead->user_phone = $data->values[0];
                                    $incompleteLead->parent_id = $data->values[0];
                                    $incompleteLead->call_time_stamp = strtotime($leads->created_time) + 19800;
                                    $incompleteLead->call_time = date('Y-m-d H:i:s', $incompleteLead->call_time_stamp);
                                    $incompleteLead->status = 'blank';
                                    $incompleteLead->recording_link = 'N/A';
                                    $incompleteLead->channel = $fb_channel->name;
                                    $incompleteLead->assign_to = $temple_id;
                                    $incompleteLead->temple_name = $temple->name;
                                    $incompleteLead->temple_phoneno = $fb_channel->blank_lead_handler;
                                    $incompleteLead->token = $fb_channel->token;
                                    $incompleteLead->save();
                                    $this->sendMessage($data->values[0]);

                                    //DB::table('acquisition_channels')->;
                                    // insert into acquisition table
                                    //$max_id = DB::table('incomplete_leads')->max('id');
                                    $insert_qcqui = DB::table('acquisition_channels')->insert([
                                        'incomplete_lead_id'    =>    $incompleteLead->id,
                                        'source'                =>    'Social_facebook',
                                        'acquisition_channel'   =>    'number_drop',
                                        'ad_id'                 =>    $fb_channel->token,
                                        'mobile'                =>    $data->values[0],
                                        'fb_lead_id'            =>    $lead_id,
                                        //'fb_funnel_state'       =>    1
                                    ]);
                                    // insert into lead interaction table marked lead created
                                    $lead_interaction = DB::table('lead_interactions')->insert([
                                        'incomplete_lead_id'      =>      $incompleteLead->id,
                                        'interaction_details'     =>    'incomplete lead inserted into incomplete table via number drop',
                                        'current_status'          =>    0,
                                        'mobile'                  =>    $data->values[0]
                                    ]);
                                } else {
                                    //DB::table('acquisition_channels')->;
                                    // insert into acquisition table
                                    $insert_qcqui = DB::table('acquisition_channels')->insert([
                                        'source'                =>    'Social_facebook',
                                        'acquisition_channel'   =>    'number_drop',
                                        'ad_id'                 =>    $fb_channel->token,
                                        'mobile'                =>    $data->values[0],
                                        'fb_lead_id'            =>    $lead_id,
                                        //'fb_funnel_state'       =>    1
                                    ]);
                                    // insert into lead interaction table marked lead created
                                    $lead_interaction = DB::table('lead_interactions')->insert([
                                        'interaction_details'     =>    'incomplete lead inserted into incomplete table via number drop',
                                        'current_status'          =>    0,
                                        'mobile'                  =>    $data->values[0]
                                    ]);
                                }
                            }
                            $i++;
                        }
                    }
                }
            }
            if (isset($fb_leads->paging->next))
                $url = $fb_leads->paging->next;
        } while (isset($fb_leads->paging->next));
        $lastExecutionTime = $latestLeadTime;
        $fb_channel->last_execution_time = $lastExecutionTime;
        $fb_channel->save();
    }

    public function sendMessage($mobile)
    {
        $count1 = 0;
        $count2 = 0;
        if (strpos($mobile, ',') !== False)
            $mobile = substr($mobile, -11, 10);
        else
            $mobile = substr($mobile, -10);


        $msg = "
      	Thank You for showing interest in Hans Matrimony on Facebook. Download the app now https://hans.onelink.me/dYIy/fblearnmore call 9697989697";
        $sender = env('STATICKING_SENDER');
        $msg = urlencode($msg);
        $url = "https://staticking.org/index.php/smsapi/httpapi/?uname=sagar10&password=123456&sender=" . $sender . "&receiver=" . $mobile . "&route=TA&msgtype=1&sms=" . $msg;
        try {
            $result = file_get_contents($url);
        } catch (\Exception $e) {
        }
    }

    public function checkMobileWithOpenLead($mobile)
    {
        $profile = Lead::whereRaw('mobile like "%' . $mobile . '%"')->first();
        if ($profile) {
            if ($profile->is_done == 2) {
                $profile->is_done = 0;
                $profile->assign_to = 'online';
                $profile->assign_by = 'online';
                $profile->created_at = date('Y-m-d H:i:s');
                $profile->save();
            } else if ($profile->is_done == 0) {
                $profile->created_at = date('Y-m-d H:i:s');
                $profile->save();
            } else if ($profile->is_done == 1) {
                #check crm table first for existinng entry
                $lead_mobile = substr($profile->mobile, -10);
                $crm_data = DB::table('crms')->whereRaw("mobile like '%" . $lead_mobile . "%'")->first();
                $comments = "record has been converted and found existing data during facebook cron";
                #if empty then insert new record into CRM
                if (empty($crm_data->id)) {
                    DB::table('crms')->insert([
                        'user_id'   =>    $profile->id,
                        'name'      =>    $profile->name,
                        'mobile'    =>    $lead_mobile,
                        'is_done'   =>    1,
                        'comment'   =>    $comments,
                        'assign_by' =>    'online'
                    ]);
                }
                # update record into crm
                else {
                    DB::table('crms')->where('id', $crm_data->id)->update([
                        'created_at'    =>      date('Y-m-d H:i:s'),
                        'assign_by'     =>      'online'
                    ]);
                }
            }
            return 0;
        } else {
            return 1;
        }
    }

    public function storeFacebookHiringLeads($fb_channel_name)
    {
        $fb_channel = Channel::where('name', $fb_channel_name)->first();

        $fb_ad_id = $fb_channel->token;
        $lastExecutionTime = $fb_channel->last_execution_time;
        $latestLeadTime = $lastExecutionTime;
        $access_token = $fb_channel->access_token;

        $url = 'https://graph.facebook.com/v12.0/' . $fb_ad_id . '/leads?access_token=' . $access_token . '&limit=500';
        //dd($url);
        $ctr = 0;
        do {
            $fb_leads = $this->_post_fb_api($url);
            $fb_leads = json_decode($fb_leads);

            // print_r($fb_leads);die;
            if (isset($fb_leads->data)) {
                foreach ($fb_leads->data as $leads) {
                    // print_r($fb_leads);

                    // echo $access_token." access_token #";
                    if ($ctr == 0) {
                        $latestLeadTime = strtotime($leads->created_time) + 19800; // As first one is the latest lead
                    }
                    $ctr++;
                    // echo strtotime($leads->created_time) + 19800;die;
                    if (strtotime($leads->created_time) + 19800 > $lastExecutionTime) {
                        echo "success";
                    } else {
                        echo "error";
                    }
                    if (strtotime($leads->created_time) + 19800 > $lastExecutionTime) {
                        // echo "testing";
                        $incompleteLead = new IncompleteHiringLeads();
                        // dd($leads->field_data[2]->values[0]);
                        foreach ($leads->field_data as $data) {
                            if ($data->name == 'phone_number') {
                                $incompleteLead->user_phone = $data->values[0];
                                echo $data->values[0];
                                $incompleteLead->parent_id = $data->values[0];
                            }
                        }
                        $incompleteLead->call_time_stamp = strtotime($leads->created_time) + 19800;
                        $incompleteLead->call_time = date('Y-m-d H:i:s', $incompleteLead->call_time_stamp);
                        $incompleteLead->status = 'blank';
                        $incompleteLead->recording_link = 'N/A';
                        $incompleteLead->channel = $fb_channel->name;
                        $incompleteLead->token = $fb_channel->token;

                        $incompleteLead->save();
                    }
                }
            }
            if (isset($fb_leads->paging->next))
                $url = $fb_leads->paging->next;
        } while (isset($fb_leads->paging->next));
        $lastExecutionTime = $latestLeadTime;
        $fb_channel->last_execution_time = $lastExecutionTime;
        $fb_channel->save();
    }

    private function _post_fb_api($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $result = curl_exec($ch);

        curl_close($ch);
        if ($result) {
            return ($result);
        } else {
            print_r($result);
            die('Unable to get data from facebook');
        }
    }
}
