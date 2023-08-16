<?php

namespace App\Console\Commands;
use App\Compatibility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Order;

class ProfileSentToProfileStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RecordToCompatibilityTab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this is to shift profiles sent to clients recorded in orders table to profile status in compatibilities table ';

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
        \Log::info(" ProfileSentToProfileStatus Cron is working fine!");
        //
        $users = DB::table('orders')->select('id_number','order_list','created_at')
                                    ->whereYear('created_at','>=', '2019')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        foreach($users as $user){
            $compatibility = Compatibility::where('user_id', $user->id_number)->first();
            if($compatibility){
                $proStatus = $compatibility->profile_status;
                $proStatus = ($proStatus == null || $proStatus == '' || $proStatus == 'null')? [] : json_decode($proStatus);
                $hashT = array();
                if(sizeof($proStatus)>=1){
                    foreach($proStatus as $entity){
                        // print_r($entity);
                        if(gettype($entity)=="object"){
                        print_r(gettype($entity));
                        print_r("\n");
                        // $entity = json_decode($entity,true);
                        print_r($entity);
                        print_r("\n");
                        // $entity=json_decode($entity,true);
                        $ans = $entity->user_id;
                        if($ans){
                            if(!isset($hashT[strval($ans)]))
                            {
                                $key = strval($ans);
                                $hashT[$key]=1;
                            }
                        }
                      }
                    }
                }
                if($user->order_list){
                    $selections= explode (",", $user->order_list);
                    $contacted_count = $compatibility->contacted_count;
                    if(sizeof($proStatus)>=1){
                        foreach ($selections as $selection) 
                        {
                            if( !isset($hashT[strval($selection)])){
                                $elem = array();
                                $elem['user_id'] = (int)$selection;
                                $elem['status'] = 'C';
                                $elem['timestamp'] = strtotime($user->created_at);
                                $contacted_count++;
                                array_splice($proStatus,sizeof($proStatus)-1,0,[$elem]);
                                $hashT[strval($selection)]=1;
                            }
                        }
                    }
                    else{
                        foreach ($selections as $selection) 
                        {                        
                            $elem = array();
                            $elem['user_id'] = (int)$selection;
                            $elem['status'] = 'C';
                            $elem['timestamp'] = strtotime($user->created_at);
                            $contacted_count++;
                            array_push($proStatus,$elem);
                            $hashT[strval($selection)]=1;
                        }
                    }
                    if($proStatus){
                        $proStatus = json_encode($proStatus);
                        Compatibility::where('user_id', $user->id_number)->update(['profile_status' => $proStatus,'contacted_count'=>$contacted_count]);
                    }
                }
            }
        }
        //
        $this->info('RecordToCompatibilityTab Cummand Run successfully!');
    }
}
