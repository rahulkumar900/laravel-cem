<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use App\Models\userPhoto;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class saveApproValController extends Controller
{
    /**
     * @OA\Post(
     *     path="/approvals/approval-save-personal",
     *     Request ="userPersnol data take as input and return if data update successfully then stage else return 0",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function approvalSavePersonal(Request $request)
    {


        // religion data
        $religion_code = $request->religion;
        if ($request->religion) {
            $religion_string = DB::table('religion_mapping')->where('mapping_id', $request->religion)->first()->religion;
        }
        // gender data
        $gender_code = '';
        if ($request->lead_gender) {
            if ($request->lead_gender == 'Male') {
                $gender_code = 1;
            } else {
                $gender_code = 2;
            }
        }
        // caste data
        $caste_string = '';
        if ($request->castes) {
            $caste_string = DB::table('castes')->where('id', $request->castes)->first()->value;
        }
        // marital status data
        $marital_string = '';
        if ($request->marital_status) {
            $marital_string = DB::table('marital_status_mappings')->where('id', $request->marital_status)->first()->name;
        }
        // checking stage already completed
        $stage = UserData::where('id', $request->user_data)->get('stage')[0]->stage;
        if ($stage > $request->stage) {
            $request['stage'] = $stage;
        }
        $managedByCodeOrString = UserData::managedByCodeOrString($request->profile_creating_for);
        $foodChoiceCodeOrString = $this->foodChoiceCodeOrString($request->food_choice);
        $manglikStatusCodeOrString = UserData::manglikStatusCodeOrString($request->manglik_status);

        $data = [
            "stage"             =>          $request->stage,
            "relationCode"          =>          $request->profile_creating_for,
            "relation"              =>          $managedByCodeOrString,
            "genderCode_user"       =>          $gender_code,
            "gender"                =>          $request->lead_gender,
            "name"                  =>          $request->full_name,
            "food_choiceCode"       =>          $foodChoiceCodeOrString,
            "food_choice"           =>          $request->food_choice,
            "religion"              =>          $religion_string,
            "religionCode"          =>          $religion_code,
            "casteCode_user"        =>          $request->castes,
            "caste"                 =>          $caste_string,
            "birth_date"            =>          $request->birth_date . ' ' . $request->birth_time,
            "birth_time"            =>          $request->birth_time,
            "height"                =>          $request->user_height,
            "height_int"            =>          $request->user_height,
            "weight"                =>          $request->weight,
            "maritalStatusCode"     =>          $request->marital_status,
            "marital_status"        =>          $marital_string,
            "no_of_child"           =>          $request->no_of_child,
            "is_disable"            =>         $request->is_disable,
            "disability"            =>          $request->disability,
            "citizenship"           =>          $request->citizenship,
            "birth_place"           =>          $request->birth_place,
            "mobile_family"         =>          $request->alternate_number1,
            "whatsapp_family"       =>          $request->alternate_number2,
            "whatsapp"              =>          $request->whatsapp_no,
            "email_family"          =>          $request->email,
            "locality"              =>          $request->locality,
            "manglik"           =>          $request->manglik_status,
            "manglikCode"               =>          $manglikStatusCodeOrString,

        ];
        // // return "hello";
        // return ["data" => $data, "test" => $request->food_choice];
        if (UserData::where('id', $request->user_data)->update($data)) {
            return $request->stage;
        }
        return 0;
    }
    public function foodChoiceCodeOrString($data)
    {
        if ($data == "Doesn't Matter") {
            return 0;
        } else if ($data == "Vegetarian") {
            return 1;
        } else if ($data == "Non-Vegetarian") {
            return 2;
        } else if ($data == "Eggetarian") {
            return 3;
        } else {
            return 0;
        }
    }
    public function approvalSaveProfessional(Request $request)
    {
        // education data
        $education_string = '';
        if ($request->education_list) {
            $education_string = DB::table('educations')->where('id', $request->education_list)->first(['degree_name'])->degree_name;
        }

        // qualification data
        $occupation_string = '';
        if ($request->occupation_list) {
            $occupation_string = DB::table('occupation_mappings')->where('id', $request->occupation_list)->first()->name;
        }
        $stage = UserData::where('id', $request->user_data)->get('stage')[0]->stage;
        if ($stage > $request->stage) {
            $request['stage'] = $stage;
        }
        $data = [
            "stage"             =>          $request->stage,
            "education"             =>          $education_string,
            "educationCode_user"    =>          $request->education_list,
            "occupation"            =>          $occupation_string,
            "occupationCode_user"   =>          $request->occupation_list,
            "wishing_to_settle_abroad"  =>      $request->wish_to_go_abroad,
            "working_city"          =>          $request->search_working_city,
            "annual_income"        =>           $request->yearly_income,
            "about"                 =>          $request->about_me,
            "company"               =>          $request->company_name,
            "designation"           =>          $request->designation,
            "college"               =>          $request->college_name,
            "additional_qualification" =>       $request->additional_degree,
            "college_ug" =>       $request->college_ug,
            "education_ug" =>       $request->education_ug,
            "college_pg" =>       $request->college_pg,
            "education_pg" =>       $request->education_pg,
            "school_name" =>       $request->school_name,
        ];
        if (UserData::where('id', $request->user_data)->update($data)) {
            return $request->stage;
        }
        return 0;
    }
    public function approvalSaveFamily(Request $request)
    {
        $stage = UserData::where('id', $request->user_data)->get('stage')[0]->stage;
        if ($stage > $request->stage) {
            $request['stage'] = $stage;
        };
        $father_occ = '';
        if ($request->father_status) {
            $father_occ = DB::table('parrent_occupation_mappings')->where('id', $request->father_status)->first()->name;
        }
        $mother_occ = '';
        if ($request->mother_status) {
            $mother_occ = DB::table('parrent_occupation_mappings')->where('id', $request->mother_status)->first()->name;
        }
        $familytypestr = UserData::familyTypeCodeOrString($request->family_type);
        $housetypestr = UserData::houseTypeCodeOrString($request->house_type);
        $data = [
            "stage"             =>          $request->stage,
            "gotra"                 =>          $request->family_gotra,
            "family_income"         =>          $request->family_income,
            "occupation_father_code"     =>          $request->father_status,
            "occupation_father"     =>          $father_occ,
            "occupation_mother_code"     =>         $request->mother_status,
            "occupation_mother"     =>          $mother_occ,
            "unmarried_brothers"    =>         $request->brothers,
            "unmarried_sisters"     =>          $request->sisters,
            "married_brothers"      =>          $request->married_brothers,
            "married_sisters"       =>          $request->married_sisters,
            "houseTypeCode"         =>          $request->house_type,
            "house_type"         =>          $housetypestr,
            "familyTypeCode"        => $request->family_type,
            "family_type"        => $familytypestr,
            "city_family"           =>         $request->family_current_city,
            "native"                =>          $request->native_city,
            "contact_address"       =>         $request->contact_address,
            "about_family"          =>          $request->family_about,
        ];
        // return $data;
        if (UserData::where('id', $request->user_data)->update($data)) {
            return $request->stage;
        }
        return 0;
    }
    public function approvalSavePhoto(Request $request)
    {
        $stage = UserData::where('id', $request->user_data)->get('stage')[0]->stage;
        if ($stage > $request->stage) {
            $request['stage'] = $stage;
        }
        $data = [
            "stage"             =>          $request->stage,
            "photo_score"                 =>          $request->photo_score,
        ];
        DB::table('user_photos')->where('photo_status', 'pending')->where('user_data_id', $request->user_data)->update([
            'photo_status' => 'active',
            "approved_by"  => Auth::user()->id,
            "approved_at" => date('Y-m-d H:i:s')
        ]);
        if (UserData::where('id', $request->user_data)->update($data)) {
            return $request->stage;
        }
        return $request;
    }
    public function approvalSaveUserPreferences(Request $request)
    {
        $stage = UserData::where('id', $request->user_data)->get('stage')[0]->stage;
        if ($stage > $request->stage) {
            $request['stage'] = $stage;
        }
        // $religion_pref = [];
        // $religion_pref_str = [];
        // foreach ($request->religion_preference as $r) {
        //     $r = explode('@', $r);
        //     array_push($religion_pref, (int)$r[0]);
        //     array_push($religion_pref_str, $r[1]);
        // }
        $caste_perf_lists = [];
        $caste_perf_lists_str = [];
        foreach ($request->caste_perf_lists as $r) {
            $r = explode('@', $r);
            array_push($caste_perf_lists, (int)$r[0]);
            array_push($caste_perf_lists_str, $r[1]);
        }
        $now = date('Y-m-d');
        $data = [
            "is_approved"           =>          1,
            "is_approve_ready"      =>          1,
            "isApproved"            =>          1,
            "is_approved_on"        =>          "$now",
            "updated_at"        =>          "$now",
            "updated_at_profile"        =>          "$now",
            "approved_by"           =>          Auth::user()->id,
            "stage"             =>          $request->stage,
        ];
        $maritalStatusCodeOrStringPref = UserData::maritalStatusCodeOrString($request->marital_status_perf);
        $manglikStatusCodeOrStringPref = UserData::manglikStatusCodeOrString($request->manglik_pref);
        $foodChoiceCodeOrStringPref = UserData::foodChoiceCodeOrString($request->foodchoice_pref);
        $occupationCodeOrStringPref = UserData::occupationCodeOrString($request->occupation_status_perf);
        if (gettype($caste_perf_lists) == "array" && gettype($caste_perf_lists) == "array") {
            $caste_perf_lists            =    json_encode($caste_perf_lists);
            $caste_perf_lists_str            =   json_encode($caste_perf_lists_str);
            $religion_pref            =    json_encode($request->religion_preference);
            // $religion_pref_str            =   json_encode($religion_pref_str);
        }
        $data2 =  [
            "user_data_id" => $request->user_data,
            "castePref"             =>     $caste_perf_lists,
            "caste"             =>     $caste_perf_lists_str,
            "religionPref"          =>     $religion_pref,
            // "religion"          => $religion_pref_str,
            "manglikPref"           =>      $manglikStatusCodeOrStringPref,
            "food_choicePref"       =>      $request->foodchoice_pref,
            "age_min"               =>      $request->min_age,
            "age_max"               =>      $request->max_age,
            "height_min_s"          =>      $request->min_height,
            "height_max_s"          =>      $request->max_height,
            "height_min"            =>      $request->min_height,
            "height_max"            =>      $request->max_height,
            "marital_statusPref"    =>     $request->marital_status_perf,
            "food_choicePref"       =>      $request->foodchoice_pref,
            "workingPref"           =>      $request->occupation_status_perf,
            "income_min"            =>      $request->min_income,
            "income_max"            =>      $request->max_income,
            "marital_status"            =>      $maritalStatusCodeOrStringPref,
            "manglik"            =>      $request->manglik_pref,
            "citizenship"            =>      $request->citizenship_pref,
            "food_choice"            =>      $foodChoiceCodeOrStringPref,
            "occupation"            =>      $occupationCodeOrStringPref
        ];
        // return $data2;
        // return UserData::where('id', $request->user_data)->update($data);
        $count = UserPreference::where("user_data_id", $request->user_data)->get()->count();
        if ($count != 0) {
            $user_pref = UserPreference::where("user_data_id", $request->user_data)->update($data2);
        } else {
            $user_pref = UserPreference::insert($data2);
        }
        $user_data = UserData::where('id', $request->user_data)->update($data);
        return [$user_pref, $user_data];
        if ($user_pref ||  $user_data) {
            return $request->user_data;
        }
        return 0;
    }
    public function uploadUserImage(Request $request)
    {
        // $file = $request->file('user_image');
        // $file->move('upload', $file->getClientOriginalName());
        // $p = Storage::disk('s3')->put('uploads/' . $imageName, $image, 'public');

        // $save_image = userPhoto::saveUserImage($imageName, $request->user_id, 'active', Auth::user()->id, Auth::user()->id);
        // $image = $request->user_image;  // your base64 encoded
        // $image = str_replace('data:image/png;base64,', '', $image);
        // $image = str_replace(' ', '+', $image);
        // $imageName = date('h-i-s') . '.' . 'png';
        $base64 = $request->user_image;
        list($baseType, $image) = explode(';', $base64);
        list(, $image) = explode(',', $image);
        $image = base64_decode($image);

        $imageName = rand(111111111, 999999999) . '.png';
        // Storage::disk('public')->put('upload/' . $imageName, $image);
        // $p = Storage::disk('s3')->put('uploads/' . $imageName, $image, 'public');
        if (Storage::disk('s3')->put('uploads/' . $imageName, $image, 'public')) {
            if ($id = userPhoto::saveUserImage($imageName, $request->user_id, 'active', Auth::user()->id, Auth::user()->id)) {
                return  ['path' => 'https://makeajodi.s3.amazonaws.com/uploads/' . $imageName, 'id' => $id, 'user_id' => Auth::user()->id];
            }
        }
        return 'Somthing went Wrong';
    }
    public function saveincompleteleads(Request $request)
    {
        $data = UserData::create(['user_mobile' => '91' . substr($request->mobile, -10), 'temple_id' => 'online']);
        return  $data->id;
    }
}
