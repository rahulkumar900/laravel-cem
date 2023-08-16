<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserData as Profile;
use Illuminate\Support\Facades\DB;


class matchmakerViewedMe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matchmaker:ViewedMe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add profile to userMatch table for the viewed me purpose';

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
        $profiles = Profile::where(['profiles.matchmaker_is_profile'=>1,'profiles.is_delete'=>0,'profiles.is_approved'=>1])
        ->where('marital_status','!=','Married')
        //->where('profiles.id',406034)
        ->get();
        foreach($profiles as $profile){
            echo "\nsending to $profile->id\n";
            // to get random number of profiles
            $random_number = rand(5,12);

            $viewed_profile = Profile::where(['profiles.is_delete'=>0,'profiles.is_approved'=>1])->
            join('families', function ($join) {
                $join->on('families.identity_number', '=', 'profiles.identity_number');
                $join->on('families.temple_id', '=', 'profiles.temple_id');
            });

            // select opposite gender
            $viewed_profile = $viewed_profile->where('profiles.gender','!=',$profile->gender);

            // select same religion
            $viewed_profile = $viewed_profile->where('families.religion','=',$profile->religion);

            if($profile->gender=='Male'){
                // height
                $viewed_profile = $viewed_profile->where('profiles.height','<',$profile->height);

                // date of birth
                $viewed_profile = $viewed_profile->where('profiles.birth_date','<',$profile->birth_date);
            }else if($profile->gender=='Female'){
                // height
                $viewed_profile = $viewed_profile->where('height','>',$profile->height);

                // date of birth
                $viewed_profile = $viewed_profile->where('birth_date','>',$profile->birth_date);
            }

            // sorting and selecting profiles
            $viewed_profile = $viewed_profile->orderBy('is_premium','desc')->orderBy('profiles.created_at','desc')->select('profiles.id')->take($random_number)->get();

            // saving / updating records to match table
            foreach($viewed_profile as $view){
                $db_check = DB::table('userMatches')->where(['userAid'=>$profile->id,'userBid'=>$view->id])->first();
                if(empty($db_check)){
                    $date_add = DB::table('userMatches')->insert([
                        'userAid'           =>      $profile->id,
                        'userBid'           =>      $view->id,
                        'isProfileViewed'   =>      1,
                        'expiryDateTime'    =>      date('Y-m-d H:i:s',strtotime("+2 days")),
                        'created_at'        =>      date('Y-m-d h:i:s'),
                        'updated_at'        =>      date('Y-m-d h:i:s'),
                    ]);
                }else{
                    $date_add = DB::table('userMatches')->where(['userAid'=>$profile->id,'userBid'=>$view->id])->update([
                        'isProfileViewed'   =>      1,
                        'expiryDateTime'    =>      date('Y-m-d H:i:s',strtotime("+2 days")),
                        'updated_at'        =>      date('Y-m-d h:i:s'),
                    ]);
                }
            }

        }
        echo "\ndone\n";
    }


}

