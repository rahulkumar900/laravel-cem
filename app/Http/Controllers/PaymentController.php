<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Lead;
use App\Models\TempleTransaction;
use App\Models\UserCompatblity;
use App\Models\UserData;
use App\paytm\paytmchecksum\PaytmChecksum;

class PaymentController extends Controller
{
    // create payment from daahboard
    /**
     * Setps to save payment and generate accounts log
     * Save data into payment order table
     * save data into temple_transaction_logs
     * get plan details and update user validity and credits
     */
    public function makePaymentManual(Request $request)
    {
        DB::beginTransaction();
        $get_lead_details = Lead::leadDetailsById($request->receipt_user_id);
        $type = $request->payment_mode;
        $closing_balance = '';

        $closing_balance_fet = TempleTransaction::getUserOpeningBalance(Auth::user()->temple_id)->toArray();
        $closing_balance = $closing_balance_fet[0]['closing_balance'];

        // find record in content table for plan amount
        $plan_amount = Content::where("type",$request->plan_name."_msg")->first();
        if (empty($plan_amount)) {
            $plan_amount = Content::where("id", $request->plan_name)->first();
        }

        // saving data into payment order
        $create_payment = PaymentOrder::savePaymentRecord($type, $request->invoice_id, $request->receipt_user_id, $get_lead_details->assign_to, $get_lead_details->assign_by, $request->receiving_amount, "dashboard", $request->invoice_id, date("Y-m-d H:i:s"), $plan_amount->id, Auth::user()->temple_id, $request->discount, $request->plan_amount, $request->customer_mobile);

        // save data into temple transaction
        $temple_tr_log = TempleTransaction::saveTrLogs($request->invoice_id, $request->receiving_amount, 0, Auth::user()->temple_id, '', "cash received by ".Auth::user()->name." from client  $get_lead_details->name order id $create_payment->id, via $type",$closing_balance, $type);

        if($create_payment && $temple_tr_log){
            // update validity in preference table
            $validity = $request->validity;
            $valid_till = date('Y-m-d h:i:s', strtotime("+$validity months"));

            // make entry into CRM table
           /* DB::table('crms')->updateOrInsert(
                ['user_id'=> $request->receipt_user_id],
                [
                    'assign_by'         =>  Auth::user()->temple_id,
                    'last_assigned_id'  =>  Auth::user()->temple_id,
                    'last_assigned_date'=>  date('Y-m-d'),
                    'request_by'        =>  Auth::user()->temple_id,
                    'is_done'           =>  0,
                    'leads_of'          =>  Auth::user()->temple_id,
                ]
            );*/
            // update preference table
            DB::table("userPreferences")->where('user_data_id', $request->receipt_user_id)->update([
                "validity"              =>      $valid_till,
                "amount_collected"      =>      $request->receiving_amount,
                "amount_collected_date" =>      date("Y-m-d H:i:s")
            ]);

            // update contacts
            $contacts = UserCompatblity::firstOrNew(["user_data_id"=> $request->receipt_user_id]);
            $contacts->credit_available = $request->contacts;
            $contacts->amount_collected = $request->receiving_amount;
            $contacts->save();
            DB::table("leads")->where("user_data_id", $request->receipt_user_id)->update([
                "is_done"          =>      1
            ]);

            // update temple_id
            if ($request->rm_name) {
                DB::table("user_data")->where('id', $request->receipt_user_id)->update([
                    "temple_id"                 =>      $request->rm_name,
                    "is_premium"                =>      1,
                    "is_paid"                   =>      1,
                    "is_delete"                 =>      0,
                    "not_interested"            =>     0,
                    "welcome_call_done"         =>      0,
                    "verification_call_done"    =>      0
                ]);
            }
            // is premium
            //if ($request->receiving_amount>12000) {
                DB::table("user_data")->where('id', $request->receipt_user_id)->update([
                    "is_paid"                   =>      1,
                    "is_delete"                 =>      0,
                    "not_interested"            =>      0,
                    "welcome_call_done"         =>      0,
                    "verification_call_done"    =>      0
                ]);
            //}


            DB::commit();
            $temple_name = Auth::user()->name;

            // sms filter according plan amount
            $sms ="";
            if($request->receiving_amount<=3500){
                $sms = "A warm welcome to Hans Matrimony! You are now a member of the Hans group. You have been registered at $temple_name with registration ID $request->receipt_user_id and paid an amount of $request->receiving_amount. Under this plan you will get 6-10 Profile Introductions per day. You would be entitled to 50 credits. You will have to make the contact on your own. Validity of the plan is VALIDITY months. Please note that the registration fee is non-refundable. Contact: ".Auth::user()->mobile."

                Thank You!
                Hans Matrimony Team";
            }
            else if($request->receiving_amount > 3500 && $request->receiving_amount <= 5500){
                $sms = "A warm welcome to Hans Matrimony! You are now a member of the Hans group. You have been registered at $temple_name with registration ID $request->receipt_user_id and paid an amount of $request->receiving_amount. Under this plan you will get 6-10 Profile Introductions per day. You would be entitled to 100 credits. You will have to make the contact on your own. Validity of the plan is VALIDITY months. Please note that the registration fee is non-refundable. Contact: ".Auth::user()->mobile."

                Thank You!
                Hans Matrimony Team";
            }
            else if($request->receiving_amount > 5500 && $request->receiving_amount <= 8500){
                $sms = "A warm welcome to Hans Matrimony! You are now a member of the Hans group. You have been registered at $temple_name with registration ID $request->receipt_user_id and paid an amount of $request->receiving_amount. Under this plan you will get 2-7 Profile Introductions per day. You would be entitled to 200 credits. You will have to make the contact on your own. Validity of the plan is Unlimited till all the credits are exhausted. Please note that the registration fee is non-refundable. Contact: ".Auth::user()->mobile."

                Thank You!
                Hans Matrimony Team";
            } else if ($request->receiving_amount > 8500 && $request->receiving_amount <= 11000) {
                $sms = "A warm welcome to Hans Matrimony! You are now a member of the Hans group. You have been registered at $temple_name with registration ID $request->receipt_user_id and paid an amount of $request->receiving_amount. Under this plan you will get 2-7 Profile Introductions per day. You would be entitled to 300 credits. You will have to make the contact on your own. Validity of the plan is Unlimited till all the credits are exhausted. Please note that the registration fee is non-refundable. Contact: ".Auth::user()->mobile."

                Thank You!
                Hans Matrimony Team";
            }

            // blocked message send use this while using SSL too
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $myURL = "https://staticking.org/index.php/smsapi/httpapi/?uname=" . env('STATICKING_USERNAME') . "&password=" . env('STATICKING_PASSWORD') . "&sender=" . env('STATICKING_SENDER') . "&receiver=" . $get_lead_details->user_mobile . "&route=TA&tempid=1207161528215747306&msgtype=1&sms=" . urlencode($sms);
                $result = file_get_contents($myURL);
            }

            return response()->json(['type'=>true,'message'=>'receipt generated']);
        }else{
            DB::rollBack();
            return response()->json(['type' => true, 'message' => 'failed to generate receipt']);
        }
    }

    /** paytm payment controller */
    public function initiatePayment(Request $request)
    {
        //production
        $mid = 'Twango57803369412564';
        $mkey = '_1A%KCvb4&I4yS%6';

        $website = 'WEBSTAGING';
        $orderId = $request->order_id;
        $amount = $request->amount;
        $mobile = $request->mobile;
        $customer_id = 'customer' . time();
        $paytmParams = array();
        $paytmParams["body"] = array(
            "requestType"   => "Payment",
            "mid"           => $mid,
            "websiteName"   => $website,
            "orderId"       => $orderId,
            "callbackUrl"   => "https://hansmatrimony.com/chat?verifyPayment",
            "txnAmount"     => array(
                "value"     => $amount,
                "currency"  => "INR",
            ),
            "userInfo"      => array(
                "custId"    => $customer_id,
                "mobile"    => $mobile,
            ),
        );

        /*
		* Generate checksum by parameters we have in body
		* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
		*/
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $mkey);

        $paytmParams["head"] = array(
            "signature"    => $checksum
        );

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        /* for Production */
        $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $mid . "&orderId=" . $orderId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        $array = array();
        $array['response'] = $response;
        $array['order_id'] = $orderId;
        return response()->json($array);
    }

    public function transactionStatus(Request $request)
    {
        $paytmParams = array();
        //produciton
        $mid = 'Twango57803369412564';
        $mkey = '_1A%KCvb4&I4yS%6';

        // TEsting
       /* $mid = 'bkjPis66135619933053';
        $mkey = '4XRSQq9PVv4meivb';*/

        $website = 'WEBSTAGING';
        $paytmParams["ORDERID"] = $request->order_id;
        $paytmParams["MID"]     = $mid;
        /*
		* Generate checksum by parameters we have
		* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
		*/
        // added matchmaker_id for payment of matchmaker video meeting on 24-07-2021 by meraj

        $checksum = PaytmChecksum::generateSignature($paytmParams, $mkey);
        $paytmParams["CHECKSUMHASH"] = $checksum;
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        /* for Production */
        $url = "https://securegw.paytm.in/order/status";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        return response()->json($response);
    }
}
