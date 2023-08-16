<?php

namespace App\Console\Commands;

use App\Models\Compatibility;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class whatsapppoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onetime:whatsapppoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allot whatsapp point for last six month users';

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
    }

    public function dfre()
    {
        $coms = Compatibility::where('user_id', 222262)->get();
        foreach ($coms as $com) {
            echo "Com id: {$com->id}\nUser Id: {$com->user_id}\n";
            $profile = Profile::find($com->user_id);
            if ($profile) {
                if ($profile->temple_id != 'st1' && $profile->temple_id != 'st3') {
                    $com->compatibility = null;
                    $com->save();
                    app('App\Http\Controllers\AngularController')->calculate($profile->id);
                }
            }
        }
    }


    public function last()
    {
        $msgs = DB::table('whatsapp_messages')
            ->orderBy('id', 'desc')->groupBy('from')->get();
        foreach ($msgs as $msg) {
            $alike = DB::table('whatsapp_messages')->where('from', 'like', '%' . $msg->from)->where('from', '!=', $msg->from)->first();
            if ($alike) {
            }
        }
    }

    public function mend()
    {
        $messages = DB::table('whatsapp_messages')->where('from', '9918419947')->where('message', 'like', '%"type":"profile"%')->get();
        if ($messages) {
            foreach ($messages as $message) {
                $data_sent = json_decode($message->message);
                $sent_id = $data_sent->apiwha_autoreply->id;
                $profile = Profile::whereRaw('user_data.user_mobile like "%9918419947%" or user_data.whats_app_no like "%9918419947%" or profiles.whatsapp like "%9918419947%"')
                    ->orderBy('profiles.id', 'desc')->first();
                if ($profile) {
                    if ($profile->temple != 'st1' && $profile->temple_id != 'st3') {
                        $com = DB::table('userCompatibilities')->where('user_id', $profile->id)->first();
                        if ($com) {
                            $profile_status = json_decode($com->profile_status);
                            $sent_ids = array_column($profile_status, 'user_id');
                            if (!in_array($sent_id, $sent_ids)) {
                                dd($sent_id);
                            }
                        }
                    }
                }
            }
        }
    }
}
