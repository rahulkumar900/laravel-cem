<?php

namespace App\Console\Commands;
use App\Models\Subscription;
use App\Models\UserData  as Profile;
use App\Models\UserCompatblity as Compatibility;
use Carbon\Carbon;
use Razorpay\Api\Api;

use Illuminate\Console\Command;

class RecurringPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge users recurring payment';

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
        $thirty_days_before = Carbon::now()->subDays(30);
        $subscriptions = Subscription::where('last_payment_at', '<=', $thirty_days_before)->whereNotNull('token_id')->where('stopped', 0)->get();
        $this->recurring($subscriptions, 350000);
    }

    public function recurring($subscriptions, $amount)
    {
        $api = new Api(env("RZP_LIVE_KEY"), env("RZP_SECRET_KEY"));
        foreach ($subscriptions as $subscription) {
            $profile = Profile::find($subscription->profile_id);
            $previous_payments = explode(',', $subscription->payment_id);

            // create a new order for recurring payment
            $order = $api->order->create(
                array(
                    'amount' => $amount,
                    'payment_capture' => 1,
                    'currency' => 'INR',
                    'receipt' => 'rcpt_'.$profile->id,
                )
            );

            // create the recurring payments
            $fields = array(
                "email" => $subscription->email ? $subscription->email : "a@a.com",
                "contact" => $subscription->contact,
                "amount" => $amount,
                "currency" => "INR",
                "order_id" => $order->id,
                "customer_id" => $subscription->customer_id,
                "token" => $subscription->token_id,
                "recurring" => "1",
                "description" => "Creating ".sizeof($previous_payments)." recurring payment for ".$profile->name
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/payments/create/recurring');
            curl_setopt($ch, CURLOPT_USERPWD, env("RZP_LIVE_KEY").":".env("RZP_SECRET_KEY"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $headers = ['Accept: application/json', 'Content-Type: application/json'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $payment = curl_exec($ch);
            $payment = json_decode($payment);

            if (!curl_error($ch)) {
                // save the payment details
                $subscription->payment_id .= ','.$payment->razorpay_payment_id;
                $subscription->order_id .= ','.$payment->razorpay_order_id;
                $subscription->amount .= ','.$amount;
                $subscription->last_payment_at = Carbon::now()->addSeconds(19800);
                $subscription->save();

                Compatibility::where('user_id', $profile->id)->update(['whatsapp_point' => 30]);
            }
            curl_close($ch);
        }
    }
}
