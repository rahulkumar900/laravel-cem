<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\UserCompatblity as Compatibility;
use Illuminate\Console\Command;


class updatePreferencesCal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:preferences';
    protected $mobile_arr = array();

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        echo "cron is running \n";
        dd();

        $count1 = 1;
        $count2 = 1;

        try {
            $profiles = Profile::whereNotNull('unapprove_audio_profile')->get();


            foreach ($profiles as $profile) {
                $save_audio = $profile->unapprove_audio_profile;
                $temp_check = json_decode($profile->unapprove_audio_profile, true);
                if ($temp_check == null) {
                    $array = array();
                    $array[1] = $profile->unapprove_audio_profile;
                    $abc = json_encode($array);
                    $saveAudio = Profile::where('id', $profile->id)->first();
                    $saveAudio->unapprove_audio_profile = $abc;
                    //  dd($saveAudio->unapprove_audio_profile);
                    $saveAudio->save();
                    echo $count1++;
                    echo "\n";
                } else {
                    //     dd(__LINE__);
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function isJsProfile($id)
    {
        $arr_words = array("Y", "Z", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "A", "B");
        $flag_j = false;
        foreach ($arr_words as $word) {
            if (($pos = strpos($id, $word)) !== false) {
                $flag_j = true;
                break;
            }
        }
        return $flag_j;
    }
    public function removeDupProfileStatus($user_id)
    {

        try {
            $compatibility = Compatibility::where('user_id', $user_id)->first();
            if ($compatibility->profile_status != null && $compatibility->profile_status != '' && $compatibility->profile_status != 'null' && $compatibility->profile_status != 'undefined') {

                $profile_status = json_decode($compatibility->profile_status, true);

                $new_proifle_status =  $this->unique_key($profile_status, 'user_id');
                $compatibility->profile_status = json_encode($new_proifle_status);
                //dd($compatibility->profile_status);
                $compatibility->save();

                $contacted_count = 0;
                $reject_count = 0;
                $shortlist_count = 0;
                $shown_interest_count = 0;
                $profile_status = json_decode($compatibility->profile_status);

                if ($profile_status) {
                    foreach ($profile_status as $key) {
                        if ($key->status == 'C') {
                            $contacted_count++;
                        } else if ($key->status == 'S') {
                            $shortlist_count++;
                        } else if ($key->status == 'R') {
                            $reject_count++;
                        } elseif ($key->status == 'SI') {
                            $shown_interest_count++;
                        }
                    }

                    $compatibility->contacted_count = $contacted_count;
                    $compatibility->reject_count = $reject_count;
                    $compatibility->shortlist_count = $shortlist_count;
                    $compatibility->shown_interest_count = $shown_interest_count;
                    $compatibility->save();
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function unique_key($array, $keyname)
    {
        try {
            $new_array = array();
            $keyname2 = 'status';
            foreach ($array as $key => $value) {
                $b = $value[$keyname] . '' . $value[$keyname2];
                if (!isset($new_array[$b])) {
                    $a = $value[$keyname] . '' . $value[$keyname2];
                    $new_array[$a] = $value;
                    //dd($new_array);
                }
            }
            // print_r($new_array);
            $new_array = array_values($new_array);
            return $new_array;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
