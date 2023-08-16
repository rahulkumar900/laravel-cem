<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCompatblity as Compatibility;

class countProfResponse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countProf:response';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculating the count of responses reject,shortlist,showninterest,contacted';

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
        $compatibilities = Compatibility::whereNotNull('profile_status')
              ->where('created_at','>','2019-01-01 00:00:00')
              ->whereNotNull('profile_status')
              ->get();
      $myfile = fopen("CountResponseLOG.txt", "w") or die("Unable to open file!");
      $count = 0;
      foreach($compatibilities as $compatibility)
      {
        $contacted_count = 0;
        $reject_count = 0;
        $shortlist_count = 0;
        $shown_interest_count = 0;
        $profile_status = json_decode($compatibility->profile_status);

        if($profile_status){
        foreach($profile_status as $key)
        {
          if($key->status == 'C')
          {
           $contacted_count++;
          }

          else if($key->status == 'S')
           {
            $shortlist_count++;
           }

          else if($key->status == 'R')
           {
            $reject_count++;
           }
           elseif($key->status == 'SI')
          {
           $shown_interest_count++;
          }
        }
    }
        $compatibility->contacted_count = $contacted_count;
        $compatibility->reject_count = $reject_count;
        $compatibility->shortlist_count = $shortlist_count;
        $compatibility->shown_interest_count = $shown_interest_count;
        $compatibility->save();
        $txt = "Last User_id" .$compatibility->user_id. "\n";
            fwrite($myfile, $txt);
        echo $txt;
        echo "\n";
        $count++;
        echo "Count";
        echo $count;
        echo "\n";

      }


    fclose($myfile);
    }
}
