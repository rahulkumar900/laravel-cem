<?php

namespace App\Console\Commands;

use App\Models\UserData as Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class RazorpayUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'everyFiveMinutes:RazorpayUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Razor Payment';

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
      $api = new Api('rzp_live_XoqzhQ7N5CqQ9Z', '6fYvLPvt8i3t5kAjBvwG4XZf');
      $params = array(
        'from'  => 1400826740
      );

      $payments = $api->payment->all($params);
      foreach ($payments->items as $payment) {
        if($payment->status == 'captured' && Profile::where('user_mobile','LIKE',substr($payment->contact,-10))->first() != null)
        {
          // print_r($payment);
          $id = Profile::where('user_mobile','LIKE',substr($payment->contact,-10))->first()->id;
          $profile = DB::table('userCompatibilities')->where('user_data_id',$id)->first();
          if($profile != null){
            $point = ($profile->user_mobile_point == null)? 0 : $profile->user_mobile_point;
            $amt = $payment->amount;
            $arr = [];
            array_push($arr,$profile->amount_collected);
            if($amt<3100){
              $point += 0;
            }
            else if($amt>3100 && $amt<=5100){
              $point += 5;
            }
            else if($amt>=4500 && $amt<5100){
              $point += 10;
            }
            else {
              $point = null;
            }
            echo 's';
            array_push($arr,$payment->toArray());
            print_r($arr);
            echo json_encode($arr);
            if($profile->updated_at > date('Y-m-d H:i:s',$payment->created_at)){
              DB::table('userCompatibilities')->where('user_id',$profile->user_id)->update([ 'amount_collected' => json_encode($arr), 'user_mobile_point' => $point, 'updated_at' => date('Y-m-d H:i:s',$payment->created_at) ]);
            }
          }
        }
      }
      $this->info('Razorpay captured Succesfully!');
    }
}

