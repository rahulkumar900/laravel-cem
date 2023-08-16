<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncompleteLeadRequest;
use App\Models\IncompleteLeads;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LeadControllerApi extends Controller
{
    //get all incomplete leads
    public function incompleteLeads(IncompleteLeadRequest $request)
    {
        // check lead is existed or not
        $lead_data = '';
        $lead_data = Lead::searchLeadData($request->mobile);
        if(!empty($lead_data)){
            // record existed
            return response()->json(['message' => 'This lead already exists. Try different number', 'save_status' => 'N']);
        }
        else if(empty($lead_data)){
            // record not existed
            $post_request = array(
                "user_mobile"           =>  '91' . substr($request->mobile, -10),
                "birth_date"            =>  $request->birth_date,
                "name"                  =>  $request->full_name,
                "email"                 =>  "",
                "relationCode"          =>  $request->relation,
                "genderCode_user"       =>  $request->lead_gender,
                "height"                =>  $request->user_height,
                "maritalStatusCode"     =>  $request->marital_status,
                "religionCode"          =>  $request->religion,
                "casteCode_user"        =>  $request->castes,
                "is_disable"            =>  0,
                "disabled_part"         =>  "",
                "channel"               =>  "web",
                "mode"                  =>  1,
                "educationCode_user"    =>  $request->education_list,
                "occupationCode_user"   =>  $request->occupation_list,
                "annual_income"         =>  $request->yearly_income,
                "user_working_city"     =>  $request->current_city,
                "fcm_id"                =>  "",
                "about"                 =>  ""
            );

            $url = "https://hansmatrimony.com/api/v1/updateBasicFormOne";

            $crl = curl_init();

            $headr = array();
            $headr[] = 'Content-type: application/json';
            $headr[] = 'X-HANS-KEY: ' . $request->security_key;
            curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($crl, CURLOPT_URL, $url);
            curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, json_encode($post_request));
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

            $rest = curl_exec($crl);

            $json_array_response = json_decode($rest, true);

            // assign lead to user and update followup comments
            $user_data_id = $json_array_response['userDetailsData']['id'];

            // update user_data table (temple_id)
            $update_user_data = DB::table('user_data')->where('id', $user_data_id)->update([
                'temple_id'     =>      $request->assign_to
            ]);

            // update leads table (assign_by, assign_to, followup_call_at, comments, interest_level)
            $update_user_data = DB::table('leads')->where('user_data_id', $user_data_id)->update([
                'assign_by'             =>      $request->assign_by,
                'assign_to'             =>      $request->assign_to,
                'followup_call_at'      =>      $request->followup_date,
                'last_followup_date'    =>      $request->followup_date,
                'comments'              =>      date('d-M-Y H:i:s') . '-' . $request->followup_comment . ';',
                'interest_level'        =>      $request->user_interest,
                'source'                =>      $request->lead_source,
                'speed'                 =>      $request->user_interest,
            ]);

            if ($request->lead_data_adding == 1) {
                // update incomplete lead
                $update_incomplete = '';
                $update_incomplete = IncompleteLeads::updatePickup($request->mobile, $user_data_id);
            }

            if ($json_array_response['status'] == true) {
                return response()->json(['type' => true, 'message' => 'lead added successfully', 'id' => $user_data_id, 'mobile' => '91' . substr($request->mobile, -10)]);
            } else {
                return response()->json(['type' => false, 'message' => 'failed to add']);
            }
        }
    }

    // get all leads of users
    public function getAllLeads(Request $request)
    {
        $all_leads = '';

        $all_leads = Lead::getAllLeads($request->assign_to);
        dd($all_leads);
    }
}
