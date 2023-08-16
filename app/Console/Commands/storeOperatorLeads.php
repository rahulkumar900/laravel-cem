<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IncompleteLeads;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\FranchiseUser;


const MODERATORSROLEID = 5;

class storeOperatorLeads extends Command
{
  protected $developers_url = 'https://developers.myoperator.co/';
  //protected $token = '9fcb98b27dab61171ad25b3e7966aa77';
  protected $token ='07508166251c62479ffa75174cf4fecb';
  protected $page_size = 2000;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'store:operatorLeads';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Store Operator Leads';

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
  public function handle()
  {
    //
    $this->storeMyOperatorCalls();
  }

  /**
   *  Store MyOpreator API data in DB.
   */
  public function storeMyOperatorCalls()
  {
    DB::table('channels')->update(['is_running' => 0]);
    try {
      $channels = Channel::select('name', 'token', 'blank_lead_handler', 'last_execution_time')->where('name', 'LIKE', '%Channel1%')
        ->get()->toarray();
      foreach ($channels as $channel) {
        $channelToken =  $this->token;
        $channelName = $channel['name'];
        DB::enableQueryLog();

        $lastExecutionTime = $channel['last_execution_time'];
        //dd($lastExecutionTime );
        $moderators = $this->getModeratorTempletId();
        $url = $this->developers_url . 'search';
        $fields = array('token' => $channelToken, 'page_size' => $this->page_size);
        if ($lastExecutionTime) {
          $fields['from'] = $lastExecutionTime - 19799;
        }
       // dd($fields);
        $results = $this->_post_api($fields, $url);
      
        $results = json_decode($results);
       
        $count = 0; //dd(sizeof($results->data->hits));
        if (isset($results->data)) {
          if (sizeof($results->data->hits) > 0) {
            foreach ($results->data->hits as $result) {
              $anyReceivedCall = false;
              $receivedCallKey = null;
              $uniqid = uniqid();
              $data = $result->_source;


              $data->start_time = $data->start_time + 19800;
              //  if(strpos($data->department_name, 'Customer Support') === false){
              if ($data->department_name != 'Customer Support') {
                if (count($data->log_details)) {
                  foreach ($data->log_details as $key => $log_detail) {
                    if ($log_detail->action === 'received') {
                      $anyReceivedCall = true;
                      $receivedCallKey = $key;
                      break;
                    }
                  }
                  if ($anyReceivedCall) {
                    $templeID = $this->getTempleIdByPhone($data->log_details[$key]->received_by[0]->contact_number_raw);
                    IncompleteLeads::where('user_phone', $data->caller_number)->update(['isDelete' => 1]);
                    $res = $this->checkFranchiseUserNumber($data->caller_number);
                    if ($res) {
                      $incompleteLead = new IncompleteLeads();
                      $incompleteLead->call_time = date('Y-m-d H:i:s', $data->start_time);
                      $incompleteLead->call_duration = $data->duration;
                      $incompleteLead->recording_link = $data->filename;
                      $incompleteLead->assign_to = $templeID;
                      $incompleteLead->temple_name = $data->log_details[$key]->received_by[0]->name;
                      $incompleteLead->temple_phoneno = $data->log_details[$key]->received_by[0]->contact_number_raw;
                      $incompleteLead->status = $data->log_details[$key]->action;
                      $incompleteLead->parent_id = $data->caller_number;
                      $incompleteLead->area = isset($data->department_name) ? $data->department_name : null;
                      $incompleteLead->user_phone = $data->caller_number;
                      $incompleteLead->channel = $channelName;
                      $incompleteLead->token = $channelToken;
                      $incompleteLead->call_time_stamp = $data->start_time;
                      $incompleteLead->call_in = 1;
                      $incompleteLead->save();
                      ++$count;
                    }
                  } else {
                    foreach ($data->log_details as $log_detail) {
                      $templeID = $this->getTempleIdByPhone($log_detail->received_by[0]->contact_number_raw);
                      IncompleteLeads::where('user_phone', $data->caller_number)->update(['isDelete' => 1]);
                      $res = $this->checkFranchiseUserNumber($data->caller_number);
                      if ($res) {
                        $incompleteLead = new IncompleteLeads();
                        $incompleteLead->call_time = date('Y-m-d H:i:s', $data->start_time);
                        $incompleteLead->call_duration = $data->duration;
                        $incompleteLead->recording_link = $data->filename;
                        $incompleteLead->assign_to = $templeID;
                        $incompleteLead->temple_name = $log_detail->received_by[0]->name;
                        $incompleteLead->temple_phoneno = $log_detail->received_by[0]->contact_number_raw;
                        $incompleteLead->status = $log_detail->action;
                        $incompleteLead->parent_id = $data->caller_number;
                        $incompleteLead->area = isset($data->department_name) ? $data->department_name : null;
                        $incompleteLead->user_phone = $data->caller_number;
                        $incompleteLead->channel = $channelName;
                        $incompleteLead->token = $channelToken;
                        $incompleteLead->call_time_stamp = $data->start_time;
                        // $incompleteLead->call_in = 1;
                        $incompleteLead->save();
                        ++$count;
                      }
                    }
                  }
                } else {
                  $blankLeadHandlers = $this->getUserDateByPhone($channel['blank_lead_handler']);
                  if (count($blankLeadHandlers)) {
                    foreach ($blankLeadHandlers as $blankLeadHandler) {
                      IncompleteLeads::where('user_phone', $data->caller_number)->update(['isDelete' => 1]);
                      $res = $this->checkFranchiseUserNumber($data->caller_number);
                      if ($res) {
                        $incompleteLead = new IncompleteLeads();
                        $incompleteLead->call_time = date('Y-m-d H:i:s', $data->start_time);
                        $incompleteLead->call_duration = $data->duration;
                        $incompleteLead->recording_link = $data->filename;
                        $incompleteLead->area = $data->department_name;
                        $incompleteLead->user_phone = $data->caller_number;
                        $incompleteLead->parent_id = $data->caller_number;
                        $incompleteLead->channel = $channelName;
                        $incompleteLead->token = $channelToken;
                        $incompleteLead->call_time_stamp = $data->start_time;

                        $incompleteLead->status = 'blank';
                        $incompleteLead->assign_to = isset($blankLeadHandler->temple_id) ? $blankLeadHandler->temple_id : 'N/A';
                        $incompleteLead->temple_name = isset($blankLeadHandler->name) ? $blankLeadHandler->name : 'N/A';
                        $incompleteLead->temple_phoneno = isset($blankLeadHandler->mobile) ? $blankLeadHandler->mobile : 'N/A';
                        $incompleteLead->save();
                        ++$count;
                      }
                    }
                  } else {
                    IncompleteLeads::where('user_phone', $data->caller_number)->update(['isDelete' => 1]);
                    $res = $this->checkFranchiseUserNumber($data->caller_number);
                    if ($res) {
                      $incompleteLead = new IncompleteLeads();
                      $incompleteLead->call_time = date('Y-m-d H:i:s', $data->start_time);
                      $incompleteLead->call_duration = $data->duration;
                      $incompleteLead->recording_link = $data->filename;
                      $incompleteLead->area = $data->department_name;
                      $incompleteLead->user_phone = $data->caller_number;
                      $incompleteLead->parent_id = $data->caller_number;
                      $incompleteLead->channel = $channelName;
                      $incompleteLead->token = $channelToken;
                      $incompleteLead->call_time_stamp = $data->start_time;

                      $incompleteLead->status = 'blank';
                      $incompleteLead->assign_to = 'N/A';
                      $incompleteLead->temple_name = 'N/A';
                      $incompleteLead->temple_phoneno = 'N/A';
                      $incompleteLead->save();
                      ++$count;
                    }
                  }
                }
              }

              $lastExecutionTime = IncompleteLeads::select(DB::raw('max(call_time_stamp) as call_time_stamp'))->where(['channel' => $channelName])->get()->pluck('call_time_stamp')->first();

              $channel = Channel::where(['name' => $channelName])->get()->first();
              $channel->last_execution_time = $lastExecutionTime;
              $channel->save();
            }
          }
        }
      }
    } catch (Exception $e) {
      die('Some error occured');
    }
    DB::table('channels')->update(['is_running' => 0]);
    echo 'My Operator data is successfully Imported.<br />' . $count . ' Records inserted.';
  }
  /**
   * Get Temple ID by Phone.
   */


  public function checkFranchiseUserNumber($mobile)
  {
    $res = FranchiseUser::where('mobile', substr($mobile, -10))->first();
    if ($res) {
      return 0;
    } else {
      return 1;
    }
  }
  private function getTempleIdByPhone($phone)
  {
    $mobileData = User::where('mobile', $phone)->first();

    return $mobileData->temple_id ?? null;
  }
  /**
   * Get all moderators, as we want to assign call of status '2' to all moderator.
   */
  private function getModeratorTempletId($role = MODERATORSROLEID)
  {
    $moderatorsData = User::select('name', 'temple_id')->where('role', $role)->get();
    $moderators = array();
    foreach ($moderatorsData as $moderator) {
      $moderators[] = array('name' => $moderator->name, 'temple_id' => $moderator->temple_id);
    }

    return $moderators;
  }

  /**
   * Get Temple ID by Phone.
   */
  private function getUserDateByPhone($phone)
  {
    $phones = explode(',', $phone);
    $trimedPhones = [];
    foreach ($phones as $ph) {
      $trimedPhones[] = trim($ph);
    }
    $result = DB::table('mobile_numbers as m')
      ->join('users as u', 'u.temple_id', '=', 'm.temple_id')
      ->select('m.mobile', 'u.name', 'u.temple_id')
      ->whereIn('m.mobile', $trimedPhones)
      ->get();

    return $result;
  }


  /**
   * Get Data from My Operator API.
   */
  private function _post_api(array $fields, $url)
  {
    try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      $result = curl_exec($ch);
    } catch (Exception $e) {
      return false;
    }

    curl_close($ch);
    if ($result) {
      return $result;
    } else {
      die('Unable to get data from MyOperator');
    }
  }
}
