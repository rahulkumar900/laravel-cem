<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class sendEveningNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notificatione';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send notifications in evening';

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
        $content = DB::table('contents')->where('type','notificatione')->first();
	$title= $content->plan_type;
	$message = $content->text;
	$profiles = DB::table('profiles')->where('fcm_id','!=','0')->where('fcm_id','!=','undefined')->get();
	$ids = DB::table('profiles_deviceID')->get();
	foreach($profiles as $profile){
	  app('App\Http\Controllers\AngularDashboardController')->pushNotifications($title,$message,$profile->fcm_id);
	}
	foreach($ids as $id){
	  app('App\Http\Controllers\AngularDashboardController')->pushNotifications($title,$message,$id->device_id);
	}
    }
}
