<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadPostRequest;
use App\Models\Compatibility;
use App\Models\FreeUser;
use App\Models\Lead;
use App\Models\LeadFamily;
use App\Models\LeadValue;
use App\Models\User;
use App\Models\UserData;
use App\Models\IncompleteLeads;
use App\Models\UserPreference;
use App\Models\UserRequestLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddLeadDirectly extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $income_range = config('constants.income_ranges');
        return view('outside_pages.add_lead_page', compact('income_range'));
    }

    /**
     * To add a new lead to the database, you can use a curl call. First, ensure that you have the necessary permissions to access the database. Next, use an existing dashboard to locate the relevant fields for the new lead. Once you have this information, you can use the curl command to send the lead data to the database. Keep in mind that it is important to ensure that the new data is consistent with the existing data in the database. To do this, you may need to perform some additional checks or data validation before submitting the lead information.
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upadteUserDataById(Request $request)
    {
        $update_user_data = UserData::updateDataById($request->user_id);
        if ($update_user_data) {
            return response()->json(['type' => true, 'data' => 'record updated']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadPostRequest $request)
    {
        $mobile = $request->mobile;
        $lead_count = Lead::where('mobile', 'like', "%$mobile%")->get()->count();
        $usercount = UserData::where('user_mobile', 'like', "%$mobile%")->get()->count();
        if ($lead_count > 0 && $usercount == 0) {
            Lead::where('mobile', 'like', "%$mobile%")->delete();
        }
        $lead_count = Lead::where([['mobile', 'like', "%$mobile%"], ['assign_by', '!=', 'online'], ['assign_to', '!=', 'online']])->get()->count();
        if ($lead_count == 0) {

            // $usercount = UserData::where('user_mobile', 'like', "%$mobile%")->get()->count();
            // return ['type' => false, 'data' => "$lead_count @ $usercount"];

            $relation_string = '';
            $relation_int = '';
            /*
             When programming, it is often necessary to convert integer data to strings. This can be done by using various methods depending on the language being used. In order to carry out this process, the program must first identify the integer data and then apply the appropriate conversion method. Once the conversion is complete, the resulting string can be used in a variety of ways, such as printing to the console or storing in a file. It is important to note that this process can vary in complexity depending on the specific requirements of the program and the data being used.
              */
            // relation data
            if ($request->profile_creating_for) {
                $expl_data = explode(",", $request->profile_creating_for);
                $relation_string = $expl_data[1];
                $relation_int = $expl_data[0];
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
            $religion_int = '';
            if ($request->religion) {
                $exp_relgn = explode("-", $request->religion);
                $religion_string = $exp_relgn[1];
                $religion_int = $exp_relgn[0];
            }

            // caste data
            $caste_int = '';
            $caste_string = '';
            if ($request->castes) {
                $expl_caste = explode(",", $request->castes);
                $caste_int = $expl_caste[0];
                $caste_string = $expl_caste[1];
            }

            // marital status data
            $marital_int = '';
            $marital_string = '';
            if ($request->marital_status) {
                $expl_mart = explode(",", $request->marital_status);
                $marital_int = $expl_mart[0];
                $marital_string = $expl_mart[1];
            }

            // education data
            $education_int = '';
            $education_string = '';
            if ($request->education_list) {
                $edu_expl = explode(",", $request->education_list);
                $education_int = $edu_expl[0];
                $education_string = $edu_expl[1];
            }

            // qualification data
            $occupation_string = '';
            $occupation_int = '';
            if ($request->occupation_list) {
                $qual_explode = explode(",", $request->occupation_list);
                $occupation_string = $qual_explode[1];
                $occupation_int = $qual_explode[0];
            }

            $mobile_no = '';
            $mobile = explode('+', $request->mobile);
            if (count($mobile) > 1) {
                $mobile_no = $mobile[1];
            } else {
                $mobile_no = $mobile[0];
            }
            $mobile_with_code = intval($request->country_code . $mobile_no);

            $total_comment = "";
            $user_name = User::where('temple_id', $request->temple_id)->first();
            if ($request->new_lead) {
                $total_comment = date('Y-m-d') . "  Lead moved from online and assigned to $user_name->name" . ";" . date('Y-m-d H:i:s') . ' ' . $request->followup_comment . " added by " . $user_name->name . ";";
            } else {
                $total_comment = $request->followup_comment . " added by " . $user_name->name . ";";
            }

            // save record into user data table
            $alt_1 = "";
            if (!empty($request->alt_mob_1)) {
                $alt_1 = $request->country_code_1 . $request->alt_mob_1;
            }

            $alt_2  = "";
            if (!empty($request->alt_mob_2)) {
                $alt_2 = $request->country_code_2 . $request->alt_mob_2;
            }
            /*
             Once the data has been approved, it is then stored in the user data table where it is easily accessible to the user. From there, the data is used to create a profile that contains all the relevant information. This profile can be used to personalize the user experience and to provide tailored recommendations based on the user's preferences. Additionally, the data in the user data table is constantly updated and can be used to track trends and patterns over time. This can provide valuable insights into user behavior and can help improve the overall user experience.
             */
            $weight = $request->weight;
            $save_user_data = UserData::saveRecord($mobile_with_code, $request->full_name, $request->user_height, $request->temple_id, $gender_string, $request->lead_gender, $religion_int, $religion_string, $caste_int, $caste_string, $education_int, $education_string, $occupation_int, $occupation_string, $request->birth_date, $relation_int, $relation_string, $marital_int, $marital_string, $request->yearly_income, null, $request->current_city, $request->search_working_city, $alt_1, $alt_2, $weight);

            /*
             To effectively prioritize leads, it is important to calculate their lead value. This involves considering various factors such as the lead's level of engagement, their likelihood to convert, and their potential value as a customer. Once these factors have been taken into account, a lead score can be assigned to each lead, which can then be used to determine the priority level. Additionally, it is important to regularly review and update lead scores as lead behavior and circumstances may change over time. By consistently calculating lead value and adjusting priorities accordingly, businesses can increase their chances of converting leads into customers.
             */
            $lead_value = LeadValue::calculateLeadValue(strtolower($gender_string), strtolower($relation_string), $request->yearly_income);
            /*
             To ensure that the sales team can effectively follow up on leads, it is important to add a lead to the lead table. This will allow for easy access to all relevant information when it is time to make contact with potential customers. In order to ensure the most effective follow-up process, it may also be useful to include additional details in the lead table, such as the date and time of initial contact, any specific needs or interests expressed by the potential customer, and any relevant notes from previous interactions. By taking these steps, the sales team can work more efficiently and increase their chances of closing deals with potential customers.
             */
            $save_lead = Lead::addDataToLead($request->temple_id, $request->enquiry_date, $request->followup_date, date('Y-m-d'), $request->followup_date, $total_comment, $request->interest_level, $request->lead_source, $lead_value, null, $save_user_data->id, $mobile_with_code, $request->full_name);
            $save_family = LeadFamily::create([
                "lead_id"       =>      $save_lead->id
            ]);

            // create compatblity
            $creat_compatblity = Compatibility::create([
                "user_data_id"      =>      $save_user_data->id
            ]);

            // updating incompleted leads with isDelete:1
            $incomplete_leads = IncompleteLeads::deleteIncompleteLead($mobile_with_code);

            /*
            We need to ensure that we have the most up-to-date information regarding our customers' preferences, specifically for potential leads. This will allow us to tailor our marketing strategies accordingly and increase our chances of success. Therefore, we must prioritize the process of saving lead preferences in our database. This includes not only the basic demographic information but also their interests, behavior patterns, and past interactions with our company. By doing so, we can analyze the data and make informed decisions on how to best approach each lead and ultimately convert them into loyal customers.
               */

            #height & age calculation
            $max_height = '';
            $min_height = '';
            $min_age = '';
            $max_age = '';
            $gender_pref = '';
            if ($request->lead_gender == 'Male') {
                $max_height = $request->user_height;
                $min_height = ($request->user_height - 12);

                $max_age = date('Y') - date('Y', strtotime($request->birth_date));
                $min_age = date('Y') - (date('Y', strtotime($request->birth_date)) + 10);

                $gender_pref = 2;
            } else {
                $max_height = ($request->user_height + 12);
                $min_height = $request->user_height;

                $max_age = date('Y') - date('Y', strtotime($request->birth_date)) + 10;
                $min_age = date('Y') - date('Y');

                $gender_pref = 1;
            }

            $caste_array = array($caste_int);
            $religion_array = array($religion_int);
            $create_preference = UserPreference::createPrefs($min_age, $max_age, $min_height, $max_height, json_encode($caste_array), $marital_int, json_encode($religion_array), $save_user_data->id, $gender_pref);
            $change_status = UserRequestLead::changeStatus($user_name->id, $request->lead_type, $request->pick_lead_id, 'picked_up');
            if ($save_user_data && $save_lead && $creat_compatblity && $create_preference) {
                return response()->json(['type' => true, 'message' => 'lead added successfully', 'id' => $save_user_data->id, 'mobile' => $mobile_with_code, 'data' => $save_user_data]);
            } else {
                return response()->json(['type' => false, 'message' => 'failed to add']);
            }
        }
        return response()->json(['type' => false, 'message' => 'Already Exist']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
