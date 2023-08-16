<?php

namespace App\Http\Controllers;

use App\Models\Compatibility;
use App\Models\Lead;
use App\Models\UserData;
use App\Models\userPhoto;
use App\Models\UserPreference;
use App\Models\IncompleteLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class UserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $income_range = config('constants.income_ranges');
        return view('profile.add_profile', compact('income_range'));
    }

    /**
     * Show resource of profile.
     *u
     * @return \Illuminate\Http\Response
     */
    public function approveUserDataProfiles()
    {
        $income_range = config('constants.income_ranges');
        $caste_list = DB::table('castes')->get();
        return view('approval.pending-approval', compact('income_range', 'caste_list'));
    }

    /**
     * Show resource of pending profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function approveUserDataPendingProfiles()
    {
        $income_range = config('constants.income_ranges');
        $caste_list = DB::table('castes')->get();
        return view('approval.profile-pending-approval', compact('income_range', 'caste_list'));
    }

    /**
     * Show resource of profile photo.
     *
     * @return \Illuminate\Http\Response
     */
    public function approveUserDataPhotoProfiles()
    {
        return view('approval.photo-approval');
    }

    /**
     * Show resource of profile double approval.
     *
     * @return \Illuminate\Http\Response
     */
    public function approveUserDataDoubleProfiles()
    {
        $income_range = config('constants.income_ranges');
        return view('approval.double-approval', compact('income_range'));
    }

    /**
     * Show resource of Pending profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDataPendingProfiles()
    {
        $profiles = UserData::getProfilesforApproval(Auth::user()->temple_id);
        $dataset = array();
        return response()->json($dataset);
    }


    /**
     * Store a newly created image in s3.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadUserImage(Request $request)
    {
        $base64 = $request->user_image;
        list($baseType, $image) = explode(';', $base64);
        list(, $image) = explode(',', $image);
        $image = base64_decode($image);

        $imageName = rand(111111111, 999999999) . '.png';
        $p = Storage::disk('s3')->put('uploads/' . $imageName, $image, 'public');

        $save_image = userPhoto::saveUserImage($imageName, $request->user_id, 'active', Auth::user()->id, Auth::user()->id);

        return response()->json(["file_name" => $imageName, 'file_url' => env('AWS_URL')]);
    }

    // get un approved photos
    public function getUnapprovedPhotos()
    {
        $unapproved_photos = "";
        $unapproved_photos = UserData::getUnApprovedPhotos()->toArray();
        if (!empty($unapproved_photos)) {
            $unapproved_photos['decoded_data'] = json_decode($unapproved_photos[0]['unapprove_carousel']);
        }
        return response()->json($unapproved_photos);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
    public function deleteUSerPic(Request $request)
    {
        // $user_details = UserData::getDetailsByIdWPref($request->user_id);

        // $photo_url = json_decode($user_details->photo_url, true);

        // unset($photo_url[$request->index_no]);

        // $save_newphotos = UserData::saveUpdatedUrl($request->user_id, array_values($photo_url));

        $save_newphotos = userPhoto::actionOnPics($request->index_no, "deleted", Auth::user()->id);

        if ($save_newphotos) {

            return response()->json(['type' => true, 'message' => 'image deleted successfully']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to delete image']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserData $userData)
    {
        //
    }

    // count lead messages
    public function countMessages(Request $request)
    {
        $date_from = '';
        $date_to = '';

        $date_range = explode('-', $request->day_range);

        $date_to = date('Y-m-d H:i:s', strtotime(-$date_range[0] . 'days'));

        $date_from = date('Y-m-d H:i:s', strtotime(-$date_range[1] . 'days'));
        $userList = array();

        $six_hrs_time = date('Y-m-d H:i:s', strtotime("-6 hours"));
        // lead and user data ka join hoga
        $count_different_message = Lead::messageCount($date_from, $date_to, Auth::user()->temple_id);

        $userList = '';

        return response()->json(['counting' => $count_different_message, 'user_list' => $userList]);
    }

    // function load day-range wise data
    public function dayRangeWiseData(Request $request)
    {
        $userList = array();

        $date_from = '';
        $date_to = '';

        $date_range = explode('-', $request->day_range);

        $date_to = date('Y-m-d H:i:s', strtotime(-$date_range[0] . 'days'));

        $date_from = date('Y-m-d H:i:s', strtotime(-$date_range[1] . 'days'));

        if (Auth::user()->role == 2) {
            $userList = UserData::dateRangeWiseData($request->message_type,  Auth::user()->temple_id, $date_from, $date_to);
            if (empty($userList->toArray())) {
                $userList = UserData::dateRangeWiseData($request->message_type, Auth::user()->temple_id, "", "");
            }
        }

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($userList),
            "totaldisplayrecords" => count($userList),
            "data" => $userList
        );
        return response()->json($dataset);
    }
    public function totalAprove(Request $request)
    {
        $now = date("Y-m-d");
        if ($request->cat == 'photo') {
            $data =  userPhoto::where(["approved_by" => Auth::user()->id, "photo_status" => "active"])->whereRaw("approved_at like '%$now%'")->count();
        } else {
            $data =  UserData::where(["is_approved" => 1, "approved_by" => Auth::user()->id])->whereRaw("updated_at like '%$now%'")->count();
        }

        return ["total" => $data];
    }
    // function load data  for profiles with photo approval
    public function profileDataApproval(Request $request)
    {
        $userList = UserData::join('leads', 'leads.user_data_id', 'user_data.id')->leftjoin('user_photos as p', 'p.user_data_id', 'user_data.id')->leftjoin('users as u', 'u.temple_id', 'leads.assign_to')
            ->where([
                'user_data.is_deleted' => 0,
                'user_data.not_interested' => 0,
                'user_data.is_approve_ready' => 0,
                'user_data.is_approved' => 0,
            ])
            ->where('user_data.maritalStatusCode', '!=', '2');
        $userList = $userList->whereRaw('(user_data.photo_url  is not null or p.id is not null)');
        $userList = $userList->select('user_data.name', 'u.name as assign_to', 'user_data.marital_status as marital_status', 'user_data.photo_url', 'p.id as photo_id', 'leads.name as lead_name', 'leads.mobile', 'leads.created_at', 'leads.lead_value', 'leads.messege_send_count', 'user_data.last_seen', 'user_data.profile_percent', 'user_data.user_mobile as mobile', 'leads.id as lead_id', 'annual_income', 'user_data.id as user_id', 'user_data.religion', 'user_data.caste', 'user_data.gender');
        $userList = $userList->orderBy('user_data.created_at', 'desc')->orderBy('user_data.genderCode_user', 'desc')->groupBy('user_id')->take(1000)->get();
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($userList),
            "totaldisplayrecords" => count($userList),
            "data" => $userList
        );
        return response()->json($dataset);
    }

    // sendApproval Message
    public function sendApprovalMessage(Request $request)
    {
        $name = '';
        $mobile_no = '';
        $userList = '';
        $send_message = '';

        $date_range = explode('-', $request->range);

        $date_to = date('Y-m-d H:i:s', strtotime(-$date_range[0] . 'days'));

        $date_from = date('Y-m-d H:i:s', strtotime(-$date_range[1] . 'days'));

        if (Auth::user()->temple_id == 'admin' || Auth::user()->is_approval_cce == 1 || Auth::user()->temple_id == 'amrendra_marketting') {
            $userList = UserData::dateRangeWiseData($request->message_type, Auth::user()->is_customer_support, Auth::user()->temple_id, $date_from, $date_to);
        }

        foreach ($userList as $user_data) {
            $name = '';
            $mobile_no = '';
            $name = $user_data->name;
            $mobile_no =  substr($user_data->mobile, -10);
            $user_id_no =  $user_data->id;

            $messages = $this->getMessages($request->message_number, $user_id_no, $name);
            // send whatsapp message
            $send_message = $this->sendWhatsappMessageCommonFunction(Auth::user()->mobile, $name, $mobile_no, $messages);
            $this->updateLeadMessageCount($user_data->id);
        }

        if ($send_message) {
            return response()->json(['type' => true, 'message' => 'message sheduled']);
        } else {
            return response()->json(['type' => true, 'message' => 'failed to shedule message']);
        }
    }

    // update lead message count
    private function updateLeadMessageCount($lead_id)
    {
        $update_lead = Lead::where('id', $lead_id)->update([
            'messege_send_count'            =>      DB::raw('messege_send_count+1'),
            'message_sent_datetime'         =>      date('Y-m-d H:i:s')
        ]);
    }

    // select ald return dynamic message
    private function getMessages($message_no, $mobile_no, $name)
    {
        $message[] = "
        Hi $name,
            Thank you🙏 to register with Hans Matrimony.
            Aapki profile incomplete hai. Abhi apni profile complete karen aur paaye  high-class family ke rishtey.
            Click to complete your profile https://hansmatrimony.com/fourReg/fullPage/edit/$mobile_no/1

            Thank you
            Regards
            Hans Matrimony Family
        ";

        $message[] = "Hi $name,

            Abhi tak aapki profile complete nahi hui hai. Abhi apni profile complete karen aur paaye  high-class family ke rishtey.
            Click to complete your profile

            Click to complete your profile https://hansmatrimony.com/fourReg/fullPage/edit/$mobile_no/1
             Thank you
            Regards
            Hans Matrimony Family
            ";

        $message[] =  "Hi $name,

            Hurry up!! Abhi bhi aapki profile complete nahi hui hai. Abhi apni profile complete karen aur paaye  high-class family ke rishtey.
            Click to complete your profile

            Click to complete your profile https://hansmatrimony.com/fourReg/fullPage/edit/$mobile_no/1

            Thank you
            Regards
            Hans Matrimony Family
            ";

        // select message from form request
        return $message[$message_no];
    }

    // get user details by id
    public function getUserDataById(Request $request)
    {
        return response()->json(UserData::getDetailsByIdWPref($request->user_id));
    }

    // update user data table while approval
    public function approveUserProfile(Request $request)
    {

        // dd($request->all());
        // relation data
        $relation_string = '';
        if ($request->profile_creating_for) {
            $relation_string = DB::table('relation_mappings')->where('id', $request->profile_creating_for)->first(['name'])->name;
        }

        // gender data
        $gender_string = '';
        if ($request->lead_gender) {
            if ($request->lead_gender == 1) {
                $gender_string = 'Male';
            } else {
                $gender_string = 'Female';
            }
        }

        // religion data
        $religion_string = '';
        if ($request->religion) {
            $religion_string = DB::table('religion_mapping')->where('id', $request->religion)->first()->religion;
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

        $managedByCodeOrString = UserData::managedByCodeOrString($request->profile_creating_for);
        $genderCodeOrString    = UserData::genderCodeOrString($request->lead_gender);
        $foodChoiceCodeOrString = UserData::foodChoiceCodeOrString($request->food_choice);
        $religionCodeOrString = UserData::religionCodeOrString($request->religion);
        $maritalStatusCodeOrString = UserData::maritalStatusCodeOrString($request->marital_status);
        $manglikStatusCodeOrString = UserData::manglikStatusCodeOrString($request->manglik_status);
        $educationCodeOrString = UserData::educationCodeOrString($request->education_list);
        $occupationCodeOrString = UserData::occupationCodeOrString($request->occupation_list);
        $houseTypeCodeOrString = UserData::houseTypeCodeOrString($request->house_type);
        // $maritalStatusCodeOrString = UserData::maritalStatusCodeOrString($request->family_type);
        $fatherStatusCodeOrStringVal = UserData::fatherStatusCodeOrString($request->father_status);
        $motherStatusCodeOrStringVal = UserData::motherStatusCodeOrString($request->mother_status);
        // dd($fatherStatusCodeOrStringVal,$motherStatusCodeOrStringVal,$request->father_status,$request->mother_status);
        $maritalStatusCodeOrStringPref = UserData::maritalStatusCodeOrString($request->marital_status_perf);
        $manglikStatusCodeOrStringPref = UserData::manglikStatusCodeOrString($request->manglik_pref);
        $foodChoiceCodeOrStringPref = UserData::foodChoiceCodeOrString($request->foodchoice_pref);
        $occupationCodeOrStringPref = UserData::occupationCodeOrString($request->occupation_status_perf);
        // dd($fatherStatusCodeOrStringVal,$motherStatusCodeOrStringVal,$request->father_status,$request->mother_status);
        $approve_profile = UserData::updateUserDataTable($request->user_data, $request->full_name, $request->lead_gender, $request->profile_creating_for, $request->religion, $request->castes, $request->birth_date . ' ' . $request->birth_time, $request->birth_time, $request->marital_status, $request->user_height, $request->weight, $request->manglik_status, $request->wish_to_go_abroad, $request->yearly_income, $request->family_gotra, $request->family_income, $request->father_status, $request->mother_status, $request->brothers, $request->sisters, $request->married_brothers, $request->married_sisters, $request->house_type, $request->family_type, $request->food_choice, $request->occupation_list, $request->education_list, auth::user()->temple_id, $request->search_working_city, $request->about_me, $relation_string, $gender_string, $religion_string, $caste_string, $marital_string, $education_string, $occupation_string, $request->current_city, $request->is_disable, $request->disability, $request->citizenship, $request->birth_place, $request->no_of_child, $request->alternate_number1, $request->alternate_number2, $request->whatsapp_no, $request->email, $request->company_name, $request->designation, $request->college_name, $request->additional_degree, $request->family_current_city, $request->native_city, $request->contact_address, $request->family_about, $request->photo_score, $request->locality, $managedByCodeOrString, $genderCodeOrString, $foodChoiceCodeOrString, $religionCodeOrString, $maritalStatusCodeOrString, $manglikStatusCodeOrString, $educationCodeOrString, $occupationCodeOrString, $houseTypeCodeOrString, $fatherStatusCodeOrStringVal, $motherStatusCodeOrStringVal);

        $caste_array = array();

        $caste_array = json_encode($request->caste_perf_lists);
        $update_preference = UserPreference::updateUserPreference($request->user_data, $caste_array, json_encode($request->religion_preference), $request->min_age, $request->max_age, $request->min_height, $request->max_height, $request->min_income, $request->max_income, $request->manglik_pref, $request->marital_status_perf, $request->occupation_status_perf, $request->foodchoice_pref, $maritalStatusCodeOrStringPref, $manglikStatusCodeOrStringPref, $foodChoiceCodeOrStringPref, $occupationCodeOrStringPref);
        // dd($update_preference);
        if ($approve_profile) {
            return response()->json(["type" => true, "message" => "profile approved successfully"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to approve"]);
        }
    }

    // reject user profile
    public function rejectUserProfile(Request $request)
    {
        $not_interested = UserData::rejectProfile($request->user_id);
        if ($not_interested) {
            return response()->json(["type" => true, "message" => "profile rejected successfully"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to reject"]);
        }
    }
    public function markMarrieduserprofile(Request $request)
    {
        $mark_married = UserData::where('id', $request->user_id)->update(["marital_status" => "Married", "maritalStatusCode" => 2]);
        if ($mark_married) {
            return response()->json(["type" => true, "message" => "profile Mark Married successfully"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to Mark Married"]);
        }
    }

    // rejected profiles
    public function rejectedProfiles()
    {
        $income_range = config('constants.income_ranges');
        return view('approval.rejected-profiles', compact('income_range'));
    }

    // get rejected profiles data
    public function getRejectedProfiles()
    {
        $userList = UserData::rejectedProfiles();
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($userList),
            "totaldisplayrecords" => count($userList),
            "data" => $userList
        );
        return response()->json($dataset);
    }

    // get approved profiles data
    public function getApprovedProfiles()
    {
        $userList = UserData::approvedProfiles(Auth::user()->temple_id);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($userList),
            "totaldisplayrecords" => count($userList),
            "data" => $userList
        );
        // 1540621902683
        return response()->json($dataset);
    }

    // update user data table while approval
    public function updateUserProfile(Request $request)
    {
        // relation data
        $relation_string = '';
        if ($request->profile_creating_for) {
            $relation_string = DB::table('relation_mappings')->where('id', $request->profile_creating_for)->first(['name'])->name;
        }

        // gender data
        $gender_string = '';
        if ($request->lead_gender) {
            if ($request->lead_gender == 1) {
                $gender_string = 'Male';
            } else {
                $gender_string = 'Female';
            }
        }

        // religion data
        $religion_string = '';
        if ($request->religion) {
            $religion_string = DB::table('religion_mapping')->where('id', $request->religion)->first()->religion;
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

        $approve_profile = UserData::updateUserDataTable($request->user_data, $request->full_name, $request->lead_gender, $request->profile_creating_for, $request->religion, $request->castes, $request->birth_date . ' ' . $request->birth_time, $request->birth_time, $request->marital_status, $request->user_height, $request->weight, $request->manglik_status, $request->wish_to_go_abroad, $request->yearly_income, $request->family_gotra, $request->family_income, $request->father_status, $request->mother_status, $request->brothers, $request->sisters, $request->married_brothers, $request->married_sisters, $request->house_type, $request->family_type, $request->food_choice, $request->occupation_list, $request->education_list, auth::user()->temple_id, $request->search_working_city, $request->about_me, $relation_string, $gender_string, $religion_string, $caste_string, $marital_string, $education_string, $occupation_string, $request->current_city);

        if ($approve_profile) {
            return response()->json(["type" => true, "message" => "profile approved successfully"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to approve"]);
        }
    }

    // get approved profile
    public function approvedProfiled()
    {
        $income_range = config('constants.income_ranges');
        return view('approval.approved-profiles', compact('income_range'));
    }

    // double approve a profile
    public function doubleApproveProfile(Request $request)
    {
        // relation data
        $relation_string = '';
        if ($request->profile_creating_for) {
            $relation_string = DB::table('relation_mappings')->where('id', $request->profile_creating_for)->first(['name'])->name;
        }

        // gender data
        $gender_string = '';
        if ($request->lead_gender) {
            if ($request->lead_gender == 1) {
                $gender_string = 'Male';
            } else {
                $gender_string = 'Female';
            }
        }

        // religion data
        $religion_string = '';
        if ($request->religion) {
            $religion_string = DB::table('religion_mapping')->where('id', $request->religion)->first()->religion;
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

        $approve_profile = UserData::updateDoubleUserData($request->user_data, $request->full_name, $request->lead_gender, $request->profile_creating_for, $request->religion, $request->castes, $request->birth_date . ' ' . $request->birth_time, $request->birth_time, $request->marital_status, $request->user_height, $request->weight, $request->manglik_status, $request->wish_to_go_abroad, $request->yearly_income, $request->family_gotra, $request->family_income, $request->father_status, $request->mother_status, $request->brothers, $request->sisters, $request->married_brothers, $request->married_sisters, $request->house_type, $request->family_type, $request->food_choice, $request->occupation_list, $request->education_list, auth::user()->temple_id, $request->current_city, $request->about_me, $relation_string, $gender_string, $religion_string, $caste_string, $marital_string, $education_string, $occupation_string);

        if ($approve_profile) {
            return response()->json(["type" => true, "message" => "profile approved successfully"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to approve"]);
        }
    }


    // database
    public function myDatabase()
    {
    }


    // double approved profiles
    public function getDoubleApproveProfile(Request $request)
    {
        $userList = UserData::doubleApprovalProfiles(Auth::user()->temple_id);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($userList),
            "totaldisplayrecords" => count($userList),
            "data" => $userList
        );
        // 1540621902683
        return response()->json($dataset);
    }


    // send whatsapp message
    public function sendWhatsAppMessageCommon(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $name = '';
        $mobile_no = '';
        $userList = '';
        $send_message = '';

        $date_range = explode('-', $request->range);

        $date_to = date('Y-m-d H:i:s', strtotime(-$date_range[0] . 'days'));

        $date_from = date('Y-m-d H:i:s', strtotime(-$date_range[1] . 'days'));

        if (Auth::user()->temple_id == 'admin' || Auth::user()->is_approval_cce == 1 || Auth::user()->temple_id == 'amrendra_marketting') {
            $userList = Lead::join('user_data', 'user_data.id', '=', 'leads.user_data_id')
                ->where('leads.is_done', '0')
                ->where('leads.profile_created', '0')
                ->where('user_data.is_deleted', '0')
                ->where('user_data.is_deleted', '0')
                ->where('user_data.not_interested', '0')
                ->where('user_data.is_approve_ready', '0')
                ->whereRaw('user_data.name not like "%test%"')
                ->where('user_data.name', '!=', 'Hans Lead')
                ->where('leads.messege_send_count', '=', $request->message_number)
                ->where('user_data.annual_income', '>', 250000);
            if (Auth::user()->is_customer_support == 1) {
                $userList = $userList->where('leads.pending_temple_id', Auth::user()->temple_id);
            }

            $userList = $userList->whereBetween('leads.created_at', [$date_from, $date_to]);
            $userList = $userList->select('user_data.name', 'user_data.user_mobile as mobile', 'leads.id');
            $userList = $userList->orderBy('user_data.gender', 'asc')->orderBy('user_data.annual_income', 'desc')->limit(50)->get();

            foreach ($userList as $user_data) {
                $name = '';
                $mobile_no = '';
                $name = $user_data->name;
                $mobile_no =  substr($user_data->mobile, -10);
                $user_id_no =  $user_data->id;

                $messages = $this->getMessages($request->message_number, $user_id_no, $name);
                // send whatsapp message
                $send_message = $this->sendWhatsappMessageCommonFunction(Auth::user()->mobile, $name, $mobile_no, $messages);
                $this->updateLeadMessageCount($user_data->id);
            }

            if ($send_message) {
                return response()->json(['type' => true, 'message' => 'message sheduled']);
            } else {
                return response()->json(['type' => true, 'message' => 'failed to shedule message']);
            }
        }
    }


    // common function for sending message with eazeebe
    public function sendWhatsappMessageCommonFunction($user_mobile, $client_name, $client_mobile, $message)
    {
        date_default_timezone_set('Asia/Kolkata');
        //dd($user_mobile.' | '.$client_name.' | '.$client_mobile.' | '.$message);
        $schedule_data = array();

        $url = 'https://eazybe.com/api/v1/whatzapp/newCustomerMessageSchedule';

        $schedule_time = date("Y-m-d H:i" . ":00", strtotime("+4 minute"));
        if (strlen($user_mobile) == 10) {
            $user_mob = '91' . $user_mobile;
        } else {
            $user_mob = $user_mobile;
        }
        $schedule_data = array(
            "name"                  =>  $client_name,
            "img_src"               =>  "null",
            "customer_mobile"       =>  '91' . substr($client_mobile, -10),
            "user_mobile"           =>  $user_mob,
            "messageText"           =>  $message,
            "scheduledDateTime"     =>  $schedule_time
        );
        $postdata = json_encode($schedule_data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        $response_data = json_decode($result);
        return true;
    }

    public function viewCallInteractPage()
    {
        $income_range = config('constants.income_ranges');
        return view('approval.call-interactions', compact('income_range'));
    }

    public function getWelcomeCallData()
    {
        $tele_contact_date = UserData::getTeleContactData("welcome_call_done");
        return array(
            "echo" => 1,
            "totalrecords" => count($tele_contact_date),
            "totaldisplayrecords" => count($tele_contact_date),
            "data" => $tele_contact_date
        );
    }


    public function markWelcomeDone(Request $request)
    {
        $mark_done = UserData::markTeleContactDone("welcome_call_done", "welcome_call_time", $request->user_id);
        if ($mark_done) {
            return response()->json(['type' => true, 'message' => 'welcome completed', 'name' => $request->profile_name]);
        } else {
            return response()->json(['type' => false, 'message' => 'welcome completed', 'name' => $request->profile_name]);
        }
    }

    public function markVerificationDone(Request $request)
    {
        $mark_done = UserData::markTeleContactDone("verification_call_done", "verifcation_call_time", $request->user_id);
        if ($mark_done) {
            return response()->json(['type' => true, 'message' => 'welcome completed', 'name' => $request->profile_name]);
        } else {
            return response()->json(['type' => false, 'message' => 'welcome completed', 'name' => $request->profile_name]);
        }
    }

    public function getVerificationCallData()
    {
        $tele_contact_date = UserData::getTeleContactData("verification_call_done");
        return array(
            "echo" => 1,
            "totalrecords" => count($tele_contact_date),
            "totaldisplayrecords" => count($tele_contact_date),
            "data" => $tele_contact_date
        );
    }

    public function userByMobile(Request $request)
    {
        $user_details = UserData::getUserDataByMobile($request->user_mobile);
        if (!empty($user_details)) {
            return response()->json(["type" => true, "data" => $user_details]);
        } else {
        }
    }

    public function addUserCredit(Request $request)
    {
        $update_credit = Compatibility::where('user_data_id', $request->user_id)->update([
            "credit_available"      =>      $request->user_credit
        ]);

        if ($update_credit) {
            return response()->json(["type" => true, "message" => "credit added"]);
        }
    }

    public function actionOnImages(Request $request)
    {
        $user_data = "";
        $photo_array = "";
        $decoded_data = "";
        $photo_url = '';
        $prev_pic = '';
        $new_pic = '';

        $user_data = UserData::getDetailsByIdWPref($request->user_id);
        // unapprocved carousel
        $photo_array = $user_data->unapprove_carousel;
        $decoded_data = json_decode($photo_array, true);
        $count_array = count($decoded_data);

        // photo url
        $photo_url = $user_data->photo_url;
        $decoded_photo_data = json_decode($photo_url, true);

        if ($request->action_type == "approved") {
            // check previous pic
            $prev_pic = $request->old_image;
            $new_pic = $request->new_image;

            array_push($decoded_photo_data, $new_pic);
            unset($decoded_data[$request->image_index]);
            $user_data->photo_url = json_encode(array_values($decoded_photo_data));
            $user_data->unapprove_carousel = json_encode(array_values($decoded_data));

            if ($user_data->save()) {

                return response()->json(["type" => true, "message" => "image updated", "count" => ($count_array - 1)]);
            }
        } else if ($request->action_type === "rejected") {
            // reject image
            unset($decoded_data[$request->image_index]);
            $user_data->unapprove_carousel = json_encode(array_values($decoded_data));

            if ($user_data->save()) {
                return response()->json(["type" => true, "message" => "image updated", "count" => ($count_array - 1)]);
            }
        } else {
            return response()->json(["type" => false, "message" => "invalid action"]);
        }
    }

    public function myCrmDatabse()
    {
        $caste_list = DB::table('castes')->get();
        $income_range = config('constants.income_ranges');
        return view('crm.my-database', compact('caste_list', 'income_range'));
    }
    public function myCrmDatabsepending()
    {
        $caste_list = DB::table('castes')->get();
        $income_range = config('constants.income_ranges');
        return view('crm.databse-profile-pending', compact('caste_list', 'income_range'));
    }

    public function markMarried(Request $request)
    {
        $user_id = $request->user_id;
        $update_married = UserData::markMarried($user_id);
        if ($update_married) {
            return response()->json(["type" => true, "message" => "mark married"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to update"]);
        }
    }

    public function markPremium(Request $request)
    {
        $user_id = $request->user_id;
        $update_married = UserData::markPremium($user_id);
        if ($update_married) {
            return response()->json(["type" => true, "message" => "mark married"]);
        } else {
            return response()->json(["type" => false, "message" => "failed to update"]);
        }
    }

    public function incompleteLeadsPending()
    {
        $income_range = config('constants.income_ranges');
        return view('approval.incomplete-leads-pending', compact('income_range'));
    }

    public function incompleteLeadsPendingList()
    {

        $incomplete_leads_list = DB::table('incomplete_leads')
            ->where('call_counts', '>', 2)
            ->where('isDelete', 0)
            ->leftJoin('user_data', function ($join) {
                $join->on(DB::raw("user_data.user_mobile"), 'LIKE', DB::raw("CONCAT('%', SUBSTR(incomplete_leads.user_phone, -10), '%')"));
            })
            ->whereNull('user_data.user_mobile')
            ->where('incomplete_leads.request_by', null)
            ->where('incomplete_leads.request_by_at', null)
            ->where('incomplete_leads.channel', 'like', '%facebook%')
            ->whereBetween('incomplete_leads.call_time', [Carbon::now()->subMonths(4), Carbon::now()->subDays(10)])
            ->get(['incomplete_leads.id as id', 'user_phone', 'channel', 'status', 'call_time as created_at'])->groupBy('user_phone');
        $data = [];
        foreach ($incomplete_leads_list as $key => $value) {
            array_push($data, $value[0]);
            # code...
        }
        // dd($incomplete_leads_list);
        $dataset = array(
            "echo" => 1,
            "data" => $data
        );
        return response()->json($dataset);
    }

    public function overAllSearch(Request $request)
    {
        $search_type = $request->search_type;
        $search_data = $request->search_data;
        $search_data_query = UserData::with('userPhotos')->leftjoin('users', 'user_data.temple_id', 'users.temple_id')->leftjoin('userPreferences as p', 'user_data.id', 'p.user_data_id')->leftjoin('userCompatibilities as c', 'user_data.id', 'c.user_data_id');
        if ($search_type == 1) {
            $mobile = substr($search_data, -10);
            $search_data_query = $search_data_query->whereRaw("RIGHT(user_mobile,10) = '$mobile'");
        } else if ($search_type == 2) {
            $name_data = strtolower($search_data);
            $search_data_query = $search_data_query->whereRaw("LOWER(user_data.name) LIKE '%$name_data%' ");
        } else {
            $user_id = strtolower($search_data);
            $search_data_query = $search_data_query->where('user_data.id', $user_id);
        }
        //dd($search_data_query->toSql());
        $search_data_query = $search_data_query->select(['user_data.name', 'user_data.is_paid as paid', 'user_data.created_at', 'user_data.is_premium', 'user_data.user_mobile', 'user_data.is_approved', 'user_data.id', 'user_data.birth_date', 'users.name as temple_name', 'p.validity as validity', 'p.amount_collected as amount', 'p.amount_collected_date as amount_collected_at', 'c.credit_available as credit'])->get();


        if (!empty($search_data_query)) {
            return response()->json(['type' => true, 'data' => $search_data_query]);
        } else {
            return response()->json(['type' => true, 'data' => []]);
        }
    }
}
