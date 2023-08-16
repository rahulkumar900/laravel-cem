<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FecebookLeadsUpdate extends Command
{

    protected $signature = 'facebook:leadsupdate';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $acquitistion_data = '';
        $today = date('Y-m-d');
        $acquitistion_data = DB::table('acquisition_channels')->whereRaw('fb_lead_id is not null')->whereRaw("fbtrace_id is null and DATE(created_at) = '$today' ")->where(['acquisition_channel' => 'number_drop'])->orderBy('id', 'desc')->get(['fb_lead_id']);

        foreach ($acquitistion_data as $acq_data) {
            $url = 'https://graph.facebook.com/v14.0/521474565715188/events?access_token=EAAG8XEt18kwBAOHCZA8P2qptgGQZAU0bCKrZArgzd7e78M6UhM4BZAcyANhv2UQZB2ube8Xc0nTe0axWz2wgGvdXmB4KIj95ZCfbhX8c8mxxMGvJs1O58AZASx6RsygZCcxoS3jIUPXJvJsMUfzLzZAZALx1I1DlxsiaZB8GMad24ItuqZAOAJVfzLOYahdw9COliowZD';

            // Create a new cURL resource
            $ch = curl_init($url);

            $event_array = ['initial', 'assign', 'followup', 'converted'];

            // Setup request to send json via POST

            $data['data'][] = array(
                'event_name'    =>  $event_array[0],
                'event_time'    =>  time(),
                'action_source' =>  "phone_call",
                'user_data'     =>  array(
                    "lead_id"   =>  $acq_data->fb_lead_id
                )
            );

            $payload = json_encode($data);
            //dd($payload);
            // Attach encoded JSON string to the POST fields
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                $payload
            );

            // Set the content type to application/json
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array('Content-Type:application/json')
            );

            // Return response instead of outputting
            curl_setopt(
                $ch,
                CURLOPT_RETURNTRANSFER,
                true
            );

            // Execute the POST request
            $result = curl_exec($ch);

            // Close cURL resource
            curl_close($ch);
            //dd($result);
            $trace_decoded = json_decode($result, true);
            //dd($trace_decoded['fbtrace_id']);
            $trace_id =  $trace_decoded['fbtrace_id'];
            $trace_array = array(
                "stage"     =>    1,
                "trace_id"  =>    $trace_id
            );

            $save_record = DB::table('acquisition_channels')->where('fb_lead_id', $acq_data->fb_lead_id)->update([
                "fbtrace_id"        =>  json_encode($trace_array),
                'fb_funnel_state'   =>  1
            ]);
        }
    }
}
