<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\User;
use App\Models\NearTemple;
use Illuminate\Support\Facades\DB;

class AssignLeadToWFH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To assign rejected leads of nearest nemple to the work from home user';

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
        $nearTemple = NearTemple::where('temple_id','!=','WFH1580988217054')
                        ->get();
        $myfile = fopen("AssignLeadToWFHLOG.txt", "w") or die("Unable to open file!");
        foreach($nearTemple as $nearTemp)
        {
            $pending_leads = Lead::where('assign_to',$nearTemp->temple_id)
                            ->where('is_done',0)
                            ->whereRaw("(followup_call_on <= '".date('Y-m-d',time())."' OR followup_call_on IS NULL)")
                            ->orderBY(DB::raw("'online_score'+'offline_score'"),'desc')
                            ->orderBy('followup_call_on','asc');
            $pending_leads_count = $pending_leads->count();

            if($pending_leads_count < 15)
            {
                $near_temp_arr = explode('*,', $nearTemp->near_temple);
                $length_near_temp = sizeof($near_temp_arr);
                $last_near_temp = $near_temp_arr[$length_near_temp-1];
                $last_near_temp = str_replace('*', '', $last_near_temp);
                $near_temp_arr[$length_near_temp -1] = $last_near_temp;

                $near_temple_id_arr = User::whereIn('name',$near_temp_arr)->pluck('temple_id')->toArray();
                $today_date_time = date('Y-m-d h:i:s', time());
                $today_strtime = strtotime($today_date_time);
                $month_old_date = date('Y-m-d h:i:s', strtotime("-1 month", $today_strtime));
                $quality_of_lead = ['Medium','Low'];
                $rejected_leads_near_temp = Lead::whereIn('assign_to',$near_temple_id_arr)
                    ->where('is_done',0)
                    ->whereIn('speed', $quality_of_lead)
                    ->whereNotIn('assign_by', $near_temple_id_arr)
                    ->where('followup_call_on','<=', date('Y-m-d H:i:s', time()))
                    ->where('created_at', '<',$month_old_date)
                    ->orderBy('created_at','desc')
                    ->take(40)
                    ->get();
                $flag = 0;
                if($rejected_leads_near_temp != null)
                {
                    foreach($rejected_leads_near_temp as $rej_lead_near_temp)
                    {
                        $rej_lead_near_temp->assign_to = $nearTemp->temple_id;
                        $rej_lead_near_temp->is_done = '0';
                        $rej_lead_near_temp->save();
                    }
                    $flag=1;
                }
            }
        if($flag = 1)
            echo "Lead added successfully of" .$nearTemp->temple_id;
            echo "\n";
            $near_temple_name = User::where('temple_id',$nearTemp->temple_id)->get()->pluck('name')->first();

            $txt = "Lead added successfully of" .$near_temple_name. "\n";
            fwrite($myfile, $txt);

        }
        fclose($myfile);
    }
}
