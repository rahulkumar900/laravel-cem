<?php

namespace App\Console\Commands;

use App\Models\UserData as Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merge:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to merge database';

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
    public function degree()
    {
        $clients = DB::table('client_clientprofile')->whereNull('id_no')->get();
        foreach ($clients as $client) {
            echo 'processing client id : ' . $client->id . "\n";
            $degree = DB::table('client_degree')->where('id', $client->degree_id)->first();
            Profile::where('identity_number', $client->id)->update(['degree' => $degree ? $degree->name : null]);
        }
    }

    public function handle()
    {
        $clients = DB::table('client_clientprofile')->whereNull('id_no')->get();
        $this->degree();
        dd("Completed");
        foreach ($clients as $client) {
            if (!(Profile::where('identity_number', $client->id)->exists())) {
                echo 'processing client id : ' . $client->id . "\n";

                $preference = DB::table('userPreferences')->where('user_data_id', $client->id)->first();
                if ($preference) {

                    // manglik status
                    if ($preference->manglik == 1)
                        $preference->manglik = 'Manglik';
                    elseif ($preference->manglik == 2)
                        $preference->manglik = 'Anshik Manglik';
                    else
                        $preference->manglik = 'No';

                    //marital status
                    if ($preference->marital_status == 1)
                        $preference->marital_status = 'Divorcee';
                    elseif ($preference->marital_status == 2)
                        $preference->marital_status = 'Widowed';
                    else
                        $preference->marital_status = 'Never Married';
                    //food choice
                    if ($preference->food_choice == 0)
                        $preference->food_choice = 'Vegeterian';
                    elseif ($preference->food_choice == 1)
                        $preference->food_choice = 'Non-Vegeterian';

                    //occupation
                    if ($preference->occupation == 0)
                        $preference->occupation = 'Not Working';
                    elseif ($preference->occupation == 1)
                        $preference->occupation = 'Private Company';
                    elseif ($preference->occupation == 2)
                        $preference->occupation = 'Business/Self Employed';
                    elseif ($preference->occupation == 3)
                        $preference->occupation = 'Government Job';
                    elseif ($preference->occupation == 4)
                        $preference->occupation = 'Doctor';
                    elseif ($preference->occupation == 5)
                        $preference->occupation = 'Teacher';
                    elseif ($preference->occupation == null)
                        $preference->occupation = null;
                    else
                        $preference->occupation = 'Other';

                    if ($client->caste)
                        $client->caste = $client->caste->caste;
                    else
                        $client->caste = null;

                    // manglik status
                    if ($client->manglik == 1)
                        $client->manglik = 'Manglik';
                    elseif ($client->manglik == 2)
                        $client->manglik = 'Anshik Manglik';
                    else
                        $client->manglik = 'No';

                    //marital status
                    if ($client->marital_status == 1)
                        $client->marital_status = 'Divorcee';
                    elseif ($client->marital_status == 2)
                        $client->marital_status = 'Widowed';
                    else
                        $client->marital_status = 'Never Married';
                    //food choice
                    if ($client->food_choice == 0)
                        $client->food_choice = 'Vegeterian';
                    elseif ($client->food_choice == 1)
                        $client->food_choice = 'Non-Vegeterian';

                    //occupation
                    if ($client->occupation == 0)
                        $client->occupation = 'Not Working';
                    elseif ($client->occupation == 1)
                        $client->occupation = 'Private Company';
                    elseif ($client->occupation == 2)
                        $client->occupation = 'Business/Self Employed';
                    elseif ($client->occupation == 3)
                        $client->occupation = 'Government Job';
                    elseif ($client->occupation == 4)
                        $client->occupation = 'Doctor';
                    elseif ($client->occupation == 5)
                        $client->occupation = 'Teacher';
                    elseif ($client->occupation == null)
                        $client->occupation = null;
                    else
                        $client->occupation = 'Other';

                    //father occupation
                    if ($client->father_occupation == 0)
                        $client->father_occupation = 'Not Working';
                    elseif ($client->father_occupation == 1)
                        $client->father_occupation = 'Private Company';
                    elseif ($client->father_occupation == 2)
                        $client->father_occupation = 'Business/Self Employed';
                    elseif ($client->father_occupation == 3)
                        $client->father_occupation = 'Government Job';
                    elseif ($client->father_occupation == 4)
                        $client->father_occupation = 'Doctor';
                    elseif ($client->father_occupation == 5)
                        $client->father_occupation = 'Teacher';
                    elseif ($client->father_occupation == null)
                        $client->father_occupation = null;
                    else
                        $client->father_occupation = 'Other';

                    //mother occupation
                    if ($client->mother_occupation == 0)
                        $client->mother_occupation = 'Not Working';
                    elseif ($client->mother_occupation == 1)
                        $client->mother_occupation = 'Private Company';
                    elseif ($client->mother_occupation == 2)
                        $client->mother_occupation = 'Business/Self Employed';
                    elseif ($client->mother_occupation == 3)
                        $client->mother_occupation = 'Government Job';
                    elseif ($client->mother_occupation == 4)
                        $client->mother_occupation = 'Doctor';
                    elseif ($client->mother_occupation == 5)
                        $client->mother_occupation = 'Teacher';
                    elseif ($client->mother_occupation == null)
                        $client->mother_occupation = null;
                    else
                        $client->mother_occupation = 'Other';

                    //religion
                    if ($client->religion == 0)
                        $client->religion = 'Hindu';
                    elseif ($client->religion == 1)
                        $client->religion = 'Muslim';
                    elseif ($client->religion == 2)
                        $client->religion = 'Christian';
                    elseif ($client->religion == 3)
                        $client->religion = 'Sikh';
                    elseif ($client->religion == 4)
                        $client->religion = 'Jain';

                    //family type
                    if ($client->family_type == 0)
                        $client->family_type = 'Nuclear';
                    else
                        $client->family_type = 'Joint';

                    //house type
                    if ($client->house_type == 0)
                        $client->house_type = 'Owned';
                    else
                        $client->house_type = 'Rented';

                    //gender
                    if ($client->gender == 0)
                        $client->gender = 'Male';
                    else
                        $client->gender = 'Female';

                    //citizenship
                    if ($client->citizenship == 1)
                        $client->citizenship = 'NRI';
                    else
                        $client->citizenship = 'Indian';

                    //father_status
                    if ($client->father_status == 1)
                        $client->father_status = 'Dead';
                    else
                        $client->father_status = 'Alive';

                    //mother_status
                    if ($client->mother_status == 1)
                        $client->mother_status = 'Dead';
                    else
                        $client->mother_status = 'Alive';

                    //disability
                    if ($client->disability == 1)
                        $client->disability = 'Yes';
                    else
                        $client->disability = 'No';

                    //mother_tongue
                    if ($client->mother_tongue_id == 2)
                        $client->mother_tongue = 'Punjabi';
                    elseif ($client->mother_tongue_id == 3)
                        $client->mother_tongue = 'Sindhi';
                    elseif ($client->mother_tongue_id == 4)
                        $client->mother_tongue = 'Haryanvi';
                    elseif ($client->mother_tongue_id == 6)
                        $client->mother_tongue = 'Bengali';
                    else
                        $client->mother_tongue = 'Hindi';

                    //degree
                    if ($client->degree_id == 0)
                        $client->degree = 'High School';
                    elseif ($client->degree_id == 1)
                        $client->degree = 'Bachelors';
                    elseif ($client->degree_id == 2)
                        $client->degree = 'Masters';
                    elseif ($client->degree_id == 3)
                        $client->degree = 'PHD';
                    elseif ($client->degree_id == 4)
                        $client->degree = 'Other';
                    else
                        $client->degree = null;

                    //zodiac
                    if ($client->zodiac == 1)
                        $client->zodiac = 'Aries';
                    elseif ($client->zodiac == 2)
                        $client->zodiac = 'Gemini';
                    elseif ($client->zodiac == 3)
                        $client->zodiac = 'Cancer';
                    elseif ($client->zodiac == 4)
                        $client->zodiac = 'Leo';
                    elseif ($client->zodiac == 5)
                        $client->zodiac = 'Virgo';
                    elseif ($client->zodiac == 6)
                        $client->zodiac = 'Libra';
                    elseif ($client->zodiac == 7)
                        $client->zodiac = 'Scorpio';
                    elseif ($client->zodiac == 8)
                        $client->zodiac = 'Sagirttarius';
                    elseif ($client->zodiac == 9)
                        $client->zodiac = 'Capricorn';
                    elseif ($client->zodiac == 10)
                        $client->zodiac = 'Aquarius';
                    elseif ($client->zodiac == 11)
                        $client->zodiac = 'Pisces';

                    echo 'inserted client id: ' . $client->id . "\n";
                }
            }
        }
    }
}
