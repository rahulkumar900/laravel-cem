<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class JeevansathiToHans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jeevansathi:hans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For transfering data from Jeevansathi Scrapped data to Hans Matrimony';

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
        $connect =  mysqli_connect('127.0.0.1','root','anupam','hansdb');
        $query = mysqli_query($connect, "SELECT * FROM profiles");

        function convert_to_cm($feet , $inches = 0) {
           $feet =  explode( '&', $feet);
            if(is_int($inches)){
                $inches = ($feet[0] * 12) + $inches;
                return (int) round($inches / 0.393701);
            }else{
                return 0;
            }
        }

        $f = fopen("https://s3.ap-south-1.amazonaws.com/matchmakerz/jeevansathi-db/output.csv", "r");
        while (($line = fgetcsv($f)) !== false) {
            if(!empty($line[3])){
                $height = explode(" ", $line[3]);

                $height_feet = rtrim($height[0], "'");
                $height_inch = rtrim($height[1], '"');

                $height_in_cm = (12 * $height_feet) + $height_inch;
            }else{
                $height_in_cm = '';
            }
            if(!empty($line[56])){
                $age = explode(" ",$line[56]);
                if(!empty($age)){
                    if(!empty($age[0])){
                        $min_age = $age[0];
                    }else{
                        $min_age = '';
                    }

                    if(!empty($age[2])){
                        $max_age = $age[2];
                    }else{
                        $max_age = '';
                    }
                }else{
                    $min_age = '';
                    $max_age = '';
                }
            }else{
                $min_age = '';
                $max_age = '';
            }

            if(!empty($line[57])){
                $dp_height = explode(' ',$line[57]);

                if(!empty($dp_height[3])){
                    $dp_height_max_feet = $dp_height[3];
                }else{
                    $dp_height_max_feet = 0;
                }

                if(!empty($dp_height[4])){
                    $dp_height_max_inch = $dp_height[4];
                }else{
                    $dp_height_max_inch = 0;
                }

                if(!empty($dp_height[0])){
                    $dp_height_min_feet = $dp_height[0];
                }else{
                    $dp_height_min_feet = 0;
                }

                if(!empty($dp_height[1])){
                    $dp_height_min_inch = $dp_height[1];
                }else{
                    $dp_height_min_inch = 0;
                }

                if(!empty($dp_height_max_feet)){
                    $dp_height_max_feet = rtrim($dp_height_max_feet, "'");
                }else{
                    $dp_height_max_feet = 0;
                }

                if(!empty($dp_height_max_inch)){
                    $dp_height_max_inch = rtrim($dp_height_max_inch, '"');
                }else{
                    $dp_height_max_inch = 0;
                }


                if(!empty($dp_height_min_feet)){
                    $dp_height_min_feet = rtrim($dp_height_min_feet, "'");
                }else{
                    $dp_height_min_feet = 0;
                }

                if(!empty($dp_height_min_inch)){
                    $dp_height_min_inch = rtrim($dp_height_min_inch, '"');
                }else{
                    $dp_height_min_inch = 0;
                }

                $height_max = convert_to_cm($dp_height_max_feet, $dp_height_max_inch);
                $height_min = convert_to_cm($dp_height_min_feet, $dp_height_min_inch);
            }else{
                $height_max = '';
                $height_min = '';
            }

            if(!empty($line[50])){
                $dob = date('Y-m-d', strtotime($line[50]));
            }else{
                $dob = '1970-01-01';
            }

            if(isset($line[49])){
                $line49 = $line[49];
            }else{
                $line49 = '';
            }

            if(isset($line[55])){
                $line55 = $line[55];
            }else{
                $line55 = '';
            }

            if(isset($line[35])){
                $line35 = $line[35];
            }else{
                $line35 = '';
            }

            if(isset($line[40])){
                $line40 = $line[40];
            }else{
                $line40 = '';
            }

            if(isset($line[62])){
                $line62 = $line[62];
            }else{
                $line62 = '';
            }

            if(isset($line[58])){
                $line58 = $line[58];
            }else{
                $line58 = '';
            }

            if(isset($line[64])){
                $line64 = $line[64];
            }else{
                $line64 = '';
            }

            if(isset($line[67])){
                $line67 = $line[67];
            }else{
                $line67 = '';
            }

            if(isset($line[66])){
                $line66 = $line[66];
            }else{
                $line66 = '';
            }

            if(isset($line[59])){
                $line59 = $line[59];
            }else{
                $line59 = '';
            }

            $id = $line[1];
            $select_exist_sql = "SELECT identity_number FROM profiles WHERE identity_number='".$id."'";
            $select_exist_query = mysqli_query($connect, $select_exist_sql);
            
               if(mysqli_num_rows($select_exist_query) == 0){
                try {
                    DB::insert("INSERT INTO profiles(temple_id, identity_number, name, gender, photo, photo_score, profiles_sent, whatsapp, facebook, aadhar, birth_place, birth_time, birth_date, weight, height, education, degree, college, occupation, sub_occupation, working_city, disability, food_choice, drink_smoke, manglik, skin_tone, monthly_income, marital_status, citizenship, office_address, about, created_at, updated_at, children, disabled_part, precedence, profile_type, abroad, is_invisible, wishing_to_settle_abroad, is_renewed, blatitude, bNS, blongitude, bEW) VALUES('jeevansathi','".$line[1]."','".$line[0]."','Male','','','','','','','".$line49."','','".$dob."','','".$height_in_cm."','".$line[13]."','".$line[16]."','".$line[18]."','".$line[8]."','','','','','','".$line55."','".$line35."','".$line[9]."','".$line[10]."','".$line40."','','".$line[11]."',now(),now(),'','','','','','0','','','','','','')");
                    DB::insert("INSERT INTO families(temple_id, identity_number, name, relation, locality, landline, family_photo, city, native, mobile, email, unmarried_sons, married_sons, unmarried_daughters, married_daughters, family_type, house_type, religion, caste, gotra, occupation, family_income, budget, office_address, father_status, mother_status, created_at, updated_at, whats_app_no, marriage_budget_not_applicable, email_not_available) VALUES('jeevansathi','".$line[1]."','".$line[0]."','','".$line[4]."','','','".$line[4]."','".$line[4]."','','','".$line[27]."','','".$line[27]."','','".$line[32]."','','".$line[5]."','".$line[6]."','".$line[28]."','".$line[25]."','".$line[23]."','','','','',now(),now(),'','','')");
                    DB::insert("INSERT INTO preferences(identity_number, temple_id, age_min, age_max, height_min, height_max, caste, marital_status, manglik, food_choice, working, occupation, sub_occupation, income_min, income_max, citizenship, description, membership, caste_no_bar, created_at, updated_at, source, amount_collected, insentive, validity, payment_method, receipt_url, status) VALUES('".$line[1]."','jeevansathi','".$min_age."','".$max_age."','".$height_min."','".$height_max."','".$line62."','".$line58."','".$line64."','".$line67."','".$line66."','".$line66."','".$line66."','','','".$line59."','','','',now(),now(),'jeevansathi','','','','','','')");
                }catch(\Illuminate\Database\QueryException $e){
                }
            }else{
            }
        }
        fclose($f);
    }
}

