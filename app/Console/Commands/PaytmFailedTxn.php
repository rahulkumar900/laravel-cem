<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\User;
use App\Models\PaymentOrder;
use App\Models\UserData;
use App\paytm\paytmchecksum\PaytmChecksum;

class PaytmFailedTxn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaytmFailedTxn:Captured';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Paytm Order Details.';

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
        $data = PaymentOrder::whereNotNull('order_id')->where('status', NULL)->orWhere('status', 'PENDING')->whereRaw('DATE(created_at) > DATE_SUB(NOW(), INTERVAL 2 DAY) AND NOW()')->get();
        if ($data) {
            foreach ($data as $value) {
                $this->updateLeadName();
                if ($value->type == 'PAYTM') {
                    $res = $this->transactionStatus($value->order_id);
                    if ($res == 1) {
                        echo "<br> Success====" . $value->order_id . "<br>";
                    } else {
                        echo "<br>Error====" . $value->order_id . "<br>";
                    }
                }
            }
        } else {
            $this->updateLeadName();
            echo "NEW data is not Found. <br>";
        }
    }

    public function updateLeadName()
    {
        $data = PaymentOrder::where('name', NULL)
            ->Where('assign_by', NULL)
            ->Where('assign_to', NULL)
            ->get();
        if ($data) {
            foreach ($data as $value) {
                $lead = UserData::join('leads','leads.user_data_id', 'user_data.id')->where('mobile', 'like', '%' . substr($value->customer_mobile, -10))->select('assign_by', 'assign_to', 'email', 'name')->first(['leads.assign_to','leads.assign_by', 'name','leads.id']);

                if ($lead != null || $lead != []) {
                    $assign_by = User::where('temple_id', $lead->assign_by)->select('name')->first();
                    $assign_to = User::where('temple_id', $lead->assign_to)->select('name')->first();
                    if (PaymentOrder::where('id', $value->id)->update(['name' => $lead->name, 'assign_to' => $lead->assign_to, 'assign_by' => $lead->assign_by]))
                        echo "Successfully Updated";
                    else
                        echo "Error, Data Not Found";
                }
            }
        }
    }

    public function transactionStatus($order_id)
    {
        $paytmParams = array();

        //produciton
        $mid                    = 'Twango57803369412564';
        $mkey                   = '_1A%KCvb4&I4yS%6';
        $website                = 'WEBSTAGING';
        $paytmParams["ORDERID"] = $order_id;
        $paytmParams["MID"]     = $mid;
        /*
        * Generate checksum by parameters we have
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
        */
        $checksum                    = PaytmChecksum::generateSignature($paytmParams, $mkey);
        $paytmParams["CHECKSUMHASH"] = $checksum;
        $post_data                   = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        /* for Production */
        $url = "https://securegw.paytm.in/order/status";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        // dd($response);

        try {
            $check = PaymentOrder::where('order_id', $order_id)->first();
            if ($check) {
                $check->txn_id      = $response['TXNID'];
                $check->txn_date    = $response['TXNDATE'];
                $check->status      = $response['STATUS'];
                $check->error_code  = $response['RESPCODE'];
                $check->narration   = $response['RESPMSG'];
                $check->save();
            }

            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
