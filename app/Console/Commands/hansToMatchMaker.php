<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class hansToMatchMaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hans:matchmaker';

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
        $connect = mysqli_connect('127.0.0.1', 'root', 'anupam', 'hansdb');
        $query = mysqli_query($connect, "select p.* from hansdb.profiles p join hansdb.families f on f.id = p.id join hansdb.preferences pre on pre.temple_id = p.temple_id and pre.identity_number = p.identity_number  join hansdb.users on users.temple_id = p.temple_id where users.data_account = '0' and p.marital_status != 'Married' and  p.temple_id != '1551419155966' and p.temple_id != '1551267379367' and p.temple_id != '1520359943437'");

        $mmconnect = mysqli_connect('127.0.0.1', 'root', 'anupam', 'matchmakerz_db');
        while ($data = mysqli_fetch_array($query)) {
            $id_no = $data['identity_number'];
            $temple_id = $data['temple_id'];
            //profile data
            $profile_sql = "SELECT * FROM profiles where identity_number='" . $id_no . "' and  temple_id='" . $temple_id . "' ";
            $profile = mysqli_query($connect, $profile_sql);
            $prodata = mysqli_fetch_array($profile);
            //family data
            $family_sql = "SELECT * FROM families where identity_number='" . $id_no . "' and  temple_id='" . $temple_id . "' ";
            $family = mysqli_query($connect, $family_sql);
            $fdata = mysqli_fetch_array($family);
            //preference data
            $pref_sql = "SELECT * FROM preferences where identity_number='" . $id_no . "' and  temple_id='" . $temple_id . "' ";
            $pref = mysqli_query($connect, $pref_sql);
            $pdata = mysqli_fetch_array($pref);

            if (!empty($fdata['mobile'])) {
                if (!empty($data['name'])) {
                    $name = $data['name'];
                    $phone_number = $fdata['mobile'];

                    if ($data['gender'] == 'Female') {
                        $gender = 1;
                    } else {
                        $gender = 0;
                    }

                    $birth_date = $data['birth_date'];
                    if ($fdata['locality']) {
                        $current_city = $fdata['locality'];
                    } else {
                        $current_city = $fdata['city'];
                    }
                    $weight = $data['weight'];
                    $height = $data['height'];
                    $yearly_income = intval($data['monthly_income']);

                    if ($data['occupation'] == 'Private Company' || $data['occupation'] == 'Private Job' || $data['occupation'] == 'Manager' || $data['occupation'] == 'Financial Services/Accounting') {
                        $occupation = 1;
                    } elseif ($data['occupation'] == 'Business/Self Employed' || 'Business/Self-Employed' || $data['occupation'] == 'Businessperson') {
                        $occupation = 2;
                    } elseif ($data['occupation'] == 'Civil Services (IAS/ IFS/ IPS/ IRS)' || $data['occupation'] == 'Defence' || $data['occupation'] == 'Government Job' || $data['occupation'] == 'Govt. Services' || $data['occupation'] == 'Govt Job') {
                        $occupation = 3;
                    } elseif ($data['occupation'] == 'Doctor') {
                        $occupation = 4;
                    } elseif ($data['occupation'] == 'Teacher') {
                        $occupation = 5;
                    } else {
                        $occupation = 0;
                    }

                    if (!empty($data['manglik'])) {
                        if ($data['manglik'] == 'Non-Manglik' || $data['manglik'] == 'No') {
                            $manglik = 0;
                        } elseif ($data['manglik'] == 'Angshik' || $data['manglik'] == 'Anshik Manglik') {
                            $manglik = 2;
                        } elseif ($data['manglik'] == 'Manglik' || $data['manglik'] == 'Yes') {
                            $manglik = 1;
                        } else {
                            $manglik = 0;
                        }
                    } else {
                        $manglik = 0;
                    }

                    $birth_place = $data['birth_place'];
                    $birth_time = $data['birth_time'];
                    $education = $data['education'];
                    $sub_occupation = null;
                    $family_income = $fdata['family_income'];
                    $office_address = $data['office_address'];
                    $college = $data['college'];

                    if ($data['marital_status'] == 'Divorced' || $data['marital_status'] == 'Divorcee' || $data['marital_status'] == 'Awaiting Divorce') {
                        $marital_status = 1;
                    } elseif ($data['marital_status'] == 'Widow/Widower' || $data['marital_status'] == 'Widowed') {
                        $marital_status = 2;
                    } else {
                        $marital_status = 0;
                    }

                    $caste_name = $fdata['caste'];
                    $caste_id_sql = "SELECT * FROM client_caste WHERE caste='$caste_name' LIMIT 1";
                    $caste_id_query = mysqli_query($mmconnect, $caste_id_sql);

                    $caste_id = mysqli_fetch_array($caste_id_query);

                    if (!empty($caste_id)) {
                        $casteID = $caste_id['id'];
                    } else {
                        $casteID = 1;
                    }

                    $gotra = $fdata['gotra'];

                    if ($fdata['mother_status'] == 'Not Alive') {
                        $mother_status = 1;
                    } else {
                        $mother_status = 0;
                    }

                    if ($fdata['father_status'] == 'Not Alive') {
                        $father_status = 1;
                    } else {
                        $father_status = 0;
                    }

                    if ($data['food_choice'] == 'Non-Vegetarian' || $data['food_choice'] == 'Doesn\'t Matter' || $data['food_choice'] == 'Non-vegeterian') {
                        $food_choice = 1;
                    } else {
                        $food_choice = 0;
                    }


                    if ($data['disability'] = 'no' || $data['disability'] == 'n0' || $data['disability'] == 'Not Avaliable' || $data['disability'] == null || $data['disability'] == 'no`') {
                        $disability = 0;
                    } else {
                        $disability = 1;
                    }

                    $disabled_part = $data['disabled_part'];
                    $children = $data['children'];
                    $hometown = $fdata['locality'];

                    if ($data['citizenship'] == 'NRI') {
                        $citizenship = 1;
                    } else {
                        $citizenship = 0;
                    }

                    $landline = $fdata['landline'];

                    if ($fdata['house_type'] == 'Owned') {
                        $house_type = 0;
                    } else {
                        $house_type = 1;
                    }

                    if ($fdata['family_type'] == 'Joint' || $fdata['family_type'] == 'Joint Family') {
                        $family_type = 1;
                    } else {
                        $family_type = 0;
                    }

                    $married_daughter = $fdata['married_daughters'];
                    $married_son = $fdata['married_sons'];

                    if ($fdata['religion'] == 'Christian') {
                        $religion = 2;
                    } elseif ($fdata['religion'] == 'Jain') {
                        $religion = 4;
                    } elseif ($fdata['religion'] == 'Muslim') {
                        $religion = 1;
                    } elseif ($fdata['religion'] == 'Sikh') {
                        $religion = 3;
                    } elseif ($fdata['religion'] == 'Hindu') {
                        $religion = 0;
                    } elseif ($fdata['religion'] == 'Jewish') {
                        $religion = 7;
                    } elseif ($fdata['religion'] == 'Bahel') {
                        $religion = 8;
                    } else {
                        $religion = 5;
                    }

                    $unmarried_daughter = $fdata['unmarried_daughters'];
                    $unmarried_son = $fdata['unmarried_sons'];
                    $want_horoscope_match = 0;
                    $active_till = '2021-01-01';
                    $whatsapp_number = $data['whatsapp'];

                    $degree_name = $data['degree'];
                    $degree_id_sql = "SELECT * FROM client_degree WHERE name='$degree_name' LIMIT 1";
                    $degree_id_query = mysqli_query($mmconnect, $degree_id_sql);

                    $degree_id = mysqli_fetch_array($degree_id_query);

                    if (!empty($degree_id)) {
                        $degreeID = $degree_id['id'];
                    } else {
                        $degreeID = 1;
                    }
                    $profile_type = 0;

                    $about = $data['about'];
                    $profile_pic = app('App\Http\Controllers\AngularController')->carouselToPhoto(0, $fdata['id']);

                    $id_no = $data['id'];
                    try {
                        $client_id = DB::connection('mysql3')->insert("INSERT INTO client_clientprofile(name,mobile,gender,birth_date,working_city,weight,height,profile_photo,monthly_income,occupation,manglik,birth_place,birth_time,education,sub_occupation,family_income,office_address,college,marital_status,caste_id,gotra,mother_status,father_status,food_choice,disability,disabled_part,children,locality,citizenship,landline,house_type,family_type,is_active,matchmaker_id,married_daughter,married_son,religion,unmarried_daughter,unmarried_son,want_horoscope_match,whatsapp_number,degree_id,profile_type,id_no,mother_tongue_id,about) VALUES('$name','$phone_number','$gender','$birth_date','$current_city','$weight','$height','$profile_pic','$yearly_income','$occupation','$manglik','$birth_place','$birth_time','$education','$sub_occupation','$family_income','$office_address','$college','$marital_status','$casteID','$gotra','$mother_status','$father_status','$food_choice','$disability','$disabled_part','$children','$hometown','$citizenship','$landline','$house_type','$family_type',1,82,'$married_daughter','$married_son','$religion','$unmarried_daughter','$unmarried_son','$want_horoscope_match','$whatsapp_number','$degreeID','$profile_type','$id_no','1','$about')");
                        $client_id = DB::connection('mysql3')->select("SELECT id FROM client_clientprofile WHERE id_no='$id_no' LIMIT 1");
                        $clientID = $client_id[0]->id;

                        if (!empty($pdata['age_min'])) {
                            $min_age = date('Y-m-d', strtotime('-' . $pdata['age_min'] . ' years'));
                        } else {
                            $min_age = '2000-01-01';
                        }

                        if (!empty($pdata['age_max'])) {
                            $max_age = date('Y-m-d', strtotime('-' . $pdata['age_max'] . ' years'));
                        } else {
                            $max_age = '1970-01-01';
                        }

                        if (!empty($pdata['income_min'])) {
                            $min_income = $pdata['income_min'];
                        } else {
                            $min_income = 0;
                        }

                        if (!empty($pdata['income_max'])) {
                            $max_income = $pdata['income_max'];
                        } else {
                            $max_income = 0;
                        }

                        if (!empty($pdata['height_min'])) {
                            $min_height = $pdata['height_min'];
                        } else {
                            $min_height = 120;
                        }

                        if (!empty($pdata['height_max'])) {
                            $max_height = $pdata['height_max'];
                        } else {
                            $max_height = 180;
                        }

                        if ($pdata['marital_status'] == 'Divorced' || $pdata['marital_status'] == 'Divorcee' || $pdata['marital_status'] == 'Awaiting Divorce') {
                            $pref_marital_status = 1;
                        } elseif ($pdata['marital_status'] == 'Widow/Widower' || $pdata['marital_status'] == 'Widowed') {
                            $pref_marital_status = 2;
                        } else {
                            $pref_marital_status = 0;
                        }

                        if ($pdata['manglik'] == 'Non-Manglik' || $pdata['manglik'] == 'No') {
                            $pref_manglik = 0;
                        } elseif ($pdata['manglik'] == 'Angshik' || $pdata['manglik'] == 'Anshik Manglik') {
                            $pref_manglik = 2;
                        } elseif ($pdata['manglik'] == 'Manglik' || $pdata['manglik'] == 'Yes') {
                            $pref_manglik = 1;
                        } else {
                            $pref_manglik = 0;
                        }

                        if ($pdata['food_choice'] == 'Non-Vegetarian' || $pdata['food_choice'] == 'Non Veg' || $pdata['food_choice'] == 'Non Veg, Eggetarian') {
                            $pref_food_choice = 1;
                        } else {
                            $pref_food_choice = 0;
                        }

                        if ($pdata['occupation'] == 'Private Company' || $pdata['occupation'] == 'Private Job' || $pdata['occupation'] == 'Manager' || $pdata['occupation'] == 'Financial Services/Accounting') {
                            $pref_occupation = 1;
                        } elseif ($pdata['occupation'] == 'Business/Self Employed' || $pdata['occupation'] == 'Businessperson') {
                            $pref_occupation = 2;
                        } elseif ($pdata['occupation'] == 'Civil Services (IAS/ IFS/ IPS/ IRS)' || $pdata['occupation'] == 'Defence' || $pdata['occupation'] == 'Government Job' || $pdata['occupation'] == 'Govt. Services' || $pdata['occupation'] == 'Govt Job') {
                            $pref_occupation = 3;
                        } elseif ($pdata['occupation'] == 'Doctor') {
                            $pref_occupation = 4;
                        } elseif ($pdata['occupation'] == 'Teacher') {
                            $pref_occupation = 5;
                        } else {
                            $pref_occupation = 0;
                        }

                        if ($pdata['citizenship'] == 'NRI') {
                            $pref_citizenship = 1;
                        } else {
                            $pref_citizenship = 0;
                        }

                        $pref_insert = DB::connection('mysql3')->insert("INSERT INTO client_preferences(min_age, max_age, min_income, max_income, min_height, max_height, marital_status, manglik, food_choice, occupation, citizenship, client_id) VALUES('$min_age','$max_age','$min_income','$max_income','$min_height','$max_height','$pref_marital_status','$pref_manglik','$pref_food_choice','$pref_occupation','$pref_citizenship','$clientID')");
                        $pref_id = DB::connection('mysql3')->select("SELECT id FROM client_preferences WHERE client_id='$clientID' LIMIT 1");

                        $prefID = $pref_id[0]->id;

                        if (!empty($pdata['caste'])) {
                            $pcaste = explode(",", $pdata['caste']);

                            foreach ($pcaste as $caste) {
                                $mm_caste = "SELECT * FROM client_caste WHERE caste='" . $caste . "'";
                                $mm_query = mysqli_query($mmconnect, $mm_caste);
                                $mmcaste = mysqli_fetch_array($mm_query);

                                DB::connection('mysql3')->insert("INSERT INTO client_preferences_caste(preferences_id, caste_id) VALUES('" . $prefID . "','" . $mmcaste['id'] . "')");
                            }
                        } else {
                            $caste_sql = "SELECT * FROM client_caste";
                            $cast_query = mysqli_query($mmconnect, $caste_sql);

                            while ($caste = mysqli_fetch_array($cast_query)) {
                                $caste_id = $caste['caste'];
                                $mm_caste = "SELECT * FROM client_caste WHERE caste='" . $caste_id . "'";
                                $mm_query = mysqli_query($mmconnect, $mm_caste);
                                $mmcaste = mysqli_fetch_array($mm_query);

                                DB::connection('mysql3')->insert("INSERT INTO client_preferences_caste(preferences_id, caste_id) VALUES('" . $prefID . "','" . $mmcaste['id'] . "')");
                            }
                        }
                        echo "Done";
                        echo "\r\n";
                    } catch (\Illuminate\Database\QueryException $e) {
                        echo $e->getMessage();
                        echo "\r\n";
                    }
                }
            }
        }
    }
}
