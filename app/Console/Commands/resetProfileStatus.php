<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class resetProfileStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:resetProfileStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the profile status for Chatbot';

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
      $users = DB::table('userCompatibilities')->where('profile_status','!=','')->get();
      foreach ($users as $user) {
        $profile_status = json_decode($user->profile_status);
        $profile_status[sizeof($profile_status)-1]->status = "R";
        DB::table('userCompatibilities')->where('user_id',$user->user_id)->update(['profile_status'=>json_encode($profile_status)]);
      }
     $this->info('Reset done!');
	 //
    }
}
