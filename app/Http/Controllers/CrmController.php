<?php

namespace App\Http\Controllers;

use App\Models\CRM;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Razorpay\Api\Api;

class CrmController extends Controller
{
    public function index()
    {
        $income_range = config('constants.income_ranges');
        return view('crm.renewalnupgradation', compact('income_range'));
    }

    /*To obtain all of the CRM leads that are assigned to a user. One way to accomplish this is by utilizing the software's search or filter functionality to narrow down the results to only show leads assigned to the user. This can be done by specifying the user's name or ID in the appropriate search criteria.
Another method to obtain all of the CRM leads assigned to a user is to run a report that shows all leads assigned to that user. This report can be customized to display additional information such as lead status, lead source, and lead owner. Running a report can provide a more comprehensive view of the leads assigned to a user, as well as provide insights into the user's sales pipeline and overall performance.
Overall, there are various ways to obtain all of the CRM leads assigned to a user, and it ultimately depends on the CRM software being used and the specific needs of the user. However, utilizing the search or filter functionality and running a customized report are two effective methods to accomplish this task.*/
    public function getAllCrmLeads()
    {

        $today = date('Y-m-d');
        $lead_data = array();

        $lead_details = Lead::join('user_data', 'leads.user_data_id', 'user_data.id')
        ->leftJoin('userCompatibilities', 'userCompatibilities.user_data_id', 'user_data.id')
        ->where(['is_done' => 1, 'leads.assign_to' => Auth::user()->temple_id])->orderBy('created_at', 'desc')->get(['leads.assign_to', 'user_data.name as lead_name', 'user_data.created_at', 'leads.followup_call_on', 'leads.comments', 'leads.amount_collected', 'leads.id', 'offline_score', 'online_score', 'total_score', 'visited_on', 'user_data.user_mobile', 'leads.assign_to as temple_id', 'speed', 'last_seen', 'leads.user_data_id', 'assigned_at', 'leads.enquiry_date', 'appointment_date', 'userCompatibilities.credit_available','userCompatibilities.amount_collected as coll_amt', 'mobile_family', 'whatsapp_family']);

        $i = 0;
        $assign_button = '';

        foreach ($lead_details as $lead_detail) {
            $different_scores = '';
            $different_scores = " <p>Online Score : $lead_detail->online_score</p><p>Offline Score : $lead_detail->offline_score</p> <p>Overall Score : " . ($lead_detail->online_score + $lead_detail->offline_score) . "</p>";

            if (!empty($lead_details[$i]['users']['name'])) {
                $lead_name = ucwords($lead_details[$i]['users']['name']);
            } else {
                $lead_name = 'Hans Matrimony Online';
            }

            $all_alternate = '';
            if (empty($lead_detail->mobile_family) || empty($lead_detail->whatsapp_family)) {
                $all_alternate = '<button type="button"class="btn btn-sm btn-danger add_alterante_no waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_data_id . '" title="Add Alternate Numbers"><i class="fa fa-list" aria-hidden="true"></i></button></div>';
            } else {
                $all_alternate = '';
            }

            $lead_name = $lead_name . $assign_button;

            $profile_name = '';
            $profile_name = $lead_detail->lead_name . '
             <div class="form-group mb-2">' . $all_alternate . '
                <div class="form-group mb-2"><button type="button" class="btn btn-sm btn-primary view_user_profile waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_mobile . '" user_data_id="' . $lead_detail->user_data_id . '" title="View profile"><i class="fas fa-user"></i> </button></div>';

            $contact_button = '';
            $moble_nos = '<p>' . $lead_detail->user_mobile . '</p>' . '<p>' . $lead_detail->whatsapp_family . '</p>' . '<p>' . $lead_detail->mobile_family . '</p>';
            $contact_button = $moble_nos . '<div class="row">
                    <div class="col-6 mb-2">
                        <button type="button" class="btn btn-sm btn-success call_client waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Call" mobile="' . $lead_detail->user_mobile . '"><i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="col-6 mb-2">
                        <button type="button"
                            class="btn btn-sm btn-success send_whatsapp_message waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top"
                            title="Send Whatsapp Message" mobileNo="' . $lead_detail->user_mobile . '">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="col-6 mb-2">
                        <button type="button"
                            class="btn btn-sm btn-primary send_sample_profile waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Send Sample Profile" mobile="' . $lead_detail->user_mobile . '">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                    <div class="col-6 mb-2"><button type="button"
                            class="btn btn-sm btn-warning fix_appoinemtnt waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top"
                            title="Fix Appointment For Visit or Video Call" mobile="' . $lead_detail->user_mobile . '" id="' . $lead_detail->user_data_id . '">
                            <i class="fas fa-calendar-check"></i></button>
                    </div>
                </div>';

            $followup = '';
            if (Auth::user()->temple_id == $lead_detail->temple_id || Auth::user()->temple_id == 'admin') {
                $followup = ' <div class="row">
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-success add_next_followup waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Add Next Followup" lead_id="' . $lead_detail->id . '" temple_name="' . $lead_detail->name . '">
                            <i class="fas fa-envelope-open-text"></i>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-danger reject_leads waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Mark Rejected Lead" lead_id="' . $lead_detail->id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>';
            } else {
                $followup = '';
            }

            $comments = '';
            $comments_raw = explode(';', $lead_detail->comments);

            $comments .= '<a href="#comments' . $i . '" class="" data-toggle="collapse">Expand</a>
            <div id="comments' . $i . '" class="collapse">';
            for ($j = 0; $j < count($comments_raw); $j++) {
                $comments .=    '<pstyle="white-space: pre-line; line-break:auto; width:250px; text-align:justify">
                                        ' . $comments_raw[$j] . '
                                </p>';
            }

            $comments .= '</div>' . $followup;

            if (empty($lead_detail->visited_on)) {
                $visited_on = 'NA';
            } else {
                $visited_on = date('d-M-Y H:i:s', strtotime($lead_detail->visited_on));
            }

            $followup_call = "";
            if ($lead_detail->followup_call_on < date('Y-m-d H:i:s')) {
                $followup_call = '<span class="text-danger">' . date('Y-m-d', strtotime($lead_detail->followup_call_on)) . '</span>';
            } else {
                $followup_call = '<span class="text-dark">' . date('Y-m-d', strtotime($lead_detail->followup_call_on)) . '</span>';
            }

            $subscription = "Credit : " . $lead_detail->credit_available . ' / Plan Amt :' . $lead_detail->coll_amt;

            $lead_data[] = array(
                'interest'              =>          $lead_detail->speed,
                'assign_to'             =>          Auth::user()->name,
                'lead_name'             =>          $profile_name,
                'lead_contact'          =>          $contact_button,
                'created_at'            =>          date('Y-m-d', strtotime($lead_detail->created_at)),
                'followup_call_on'      =>          $followup_call,
                'last_seen'             =>          date('Y-m-d', strtotime($lead_detail->last_seen)),
                'enquiry_date'          =>          date('Y-m-d', strtotime($lead_detail->enquiry_date)),
                'comments'              =>          $comments,
                'plan_pitched'          =>          $lead_detail->amount_collected,
                'engagement_score'      =>          $different_scores,
                'visited'               =>          $visited_on,
                'subscriptions'         =>          $subscription,
                'appointments'          =>          $lead_detail->appointment_date,
                'assigned_at'           =>          date('Y-m-d', strtotime($lead_detail->assigned_at))
            );
            $i++;
        }

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($lead_data),
            "totaldisplayrecords" => count($lead_data),
            "data" => $lead_data
        );

        return response()->json($dataset);
    }

    /*In order to ensure that the client's response is properly saved in the database, it is important to follow a thorough and detailed process. This process begins by carefully reviewing the client's response and identifying all relevant information. Once this information has been gathered, it should be entered into the database in a clear and organized manner. Additionally, it may be helpful to include any additional notes or comments that could be useful for future reference. By following these steps, we can ensure that our database is up-to-date and accurate, allowing us to provide the best possible service to our clients.*/
    public function updateFollowupCrm(Request $request)
    {
        $crm_details = CRM::where('id', $request->followup_lead_id)->first();

        $new_comment =date('d-M-Y').' - '.$request->followup_status.' By'. Auth::user()->name;

        $crm_details->comment = $crm_details->comment.';'.$new_comment;

        $crm_details->followup_call_on = $request->next_followup_date;

        $crm_details->last_followup_date = $crm_details->followup_call_on;
        $crm_details->updated_at = date('Y-m-d H:i:s');

        if($crm_details->save()){
            return response()->json(['type'=>true,'message'=>'followup updated']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to update followup']);
        }
    }

    /*In order to ensure that the client's response is properly saved in the database, it is important to follow a thorough and detailed process. This process begins by carefully reviewing the client's response and identifying all relevant information. Once this information has been gathered, it should be entered into the database in a clear and organized manner. Additionally, it may be helpful to include any additional notes or comments that could be useful for future reference. By following these steps, we can ensure that our database is up-to-date and accurate, allowing us to provide the best possible service to our clients.*/
    public function searchCrmLeads(Request $request)
    {
        $mobile = substr($request->lead_mobile_no, -10);
        $crm_data = Lead::checkCrmLeads($mobile);
        $temple_name = '';
        if(!empty($crm_data)){

            /*To ensure that our leads are properly managed, it is important to check their status. This means confirming whether a lead is still open or if it has been rejected. By doing so, we can determine the next steps to take in our lead management process. In addition, we can also use this information to make improvements to our lead generation strategies and identify potential areas for growth. Therefore, it is critical to regularly check the status of our leads to maximize our chances of success.*/
            if($crm_data->is_done == 0 || $crm_data->is_done == 2){
                return response()->json(['type' => false, 'message' => 'convert lead first then add', 'data' => null, 'add_lead'=>false]);
            } else if ($crm_data->is_done == 1 && empty($crm_data->crm_id)) {
                return response()->json(['type' => false, 'message' => 'lead status is converted u can add', 'data' => null, 'add_lead' => true]);
            }
            /*It is important to make sure that the first lead has been properly assigned to a sales representative. If it has not yet been assigned, it is important to do so as soon as possible. Additionally, it is important to verify whether the lead was generated online or through another source. This will help determine the best approach for following up with the lead and ensuring that it is properly nurtured. It is also important to keep in mind that effective lead management involves ongoing communication and relationship-building, so it is important to stay in touch with the lead and continue to provide value throughout the sales cycle.*/
            else if(!empty($crm_data->crm_id) &&  ($crm_data->assign_by=='online' || empty($crm_data->assign_by))){
                $temple_name = '<button class="assgn_to_me_btn btn btn-sm btn-primary" lead_id="'. $crm_data->id. '" temple_id="'.Auth::user()->temple_id.'">Assign To Me</button>';
            }
            /*It is important to make sure that the first lead has been properly assigned to a sales representative. If it has not yet been assigned, it is important to do so as soon as possible. Additionally, it is important to verify whether the lead was generated online or through another source. This will help determine the best approach for following up with the lead and ensuring that it is properly nurtured. It is also important to keep in mind that effective lead management involves ongoing communication and relationship-building, so it is important to stay in touch with the lead and continue to provide value throughout the sales cycle.*/
            else{
                $temple_data = User::getTempleDetails($crm_data->assign_by);
                $temple_name = $temple_data->name;
            }
            $data = array(
                'id'                    =>      $crm_data->id,
                'name'                  =>      $crm_data->name,
                'mobile'                =>      $crm_data->mobile,
                'followupcallon'        =>      $crm_data->followup_call_on,
                'last_followup_date'    =>      $crm_data->last_followup_date,
                'comment'               =>      $crm_data->comment,
                'assigned_to'           =>      $temple_name,
                'created_at'            =>      $crm_data->created_at,
            );
            return response()->json(['type'=>true,'message'=>'record found','data'=> $data, 'add_lead' => false]);
        }else{
            return response()->json(['type'=>false,'message'=>'add lead first','data'=>null]);
        }
    }

    /*After careful consideration of the team's strengths and weaknesses, the decision has been made to assign the lead role to one of our own team members. This will allow for a more streamlined process in terms of communication and decision-making, as well as ensuring that the project's goals are met in a timely and efficient manner. Additionally, by having someone from within the team take on this role, it will foster a sense of ownership and accountability among all team members, leading to greater motivation and productivity. Overall, we believe that this decision will be in the best interests of the project and the team as a whole.*/
    public function assignLeadToSelf(Request $request)
    {
        $updat_lead = CRM::assignLead($request->lead_id, Auth::user()->temple_id);
        if($updat_lead){
            return response()->json(['type'=>true, 'message'=>'lead assigned to you successfully']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to assign. Try Again']);
        }
    }

    /*We need to make sure that we add the information of the new lead into our CRM database. This is important because it allows us to keep track of potential clients and their needs. It also enables us to analyze the data and create reports to help us make informed decisions. Moreover, by having all our leads in one place, we can avoid duplication of efforts and ensure that our team is not wasting time and resources by contacting the same lead multiple times. Therefore, we should make it a priority to accurately input the new lead's information into our CRM database as soon as possible.*/
    public function addLeadCrm(Request $request)
    {
        $mobile = '91'.substr($request->mobile,-10);
        $save_record = '';

        /*In order to find information related to a specific client within our CRM, we can perform a search using their mobile. This search can be done through our existing PHP servers, which have been implemented specifically for data modification purposes. So, by utilizing our current technology, we can effectively and efficiently retrieve the necessary data for any given client using their mobile.*/
        $serached_data = Lead::searchLeadData($mobile);
        if(!empty($serached_data)){
            $user_id = $serached_data->id;
        }else{
            $user_id = hexdec(uniqid());;
        }
    /*We need to make sure that we add the information of the new lead into our CRM database. This is important because it allows us to keep track of potential clients and their needs. It also enables us to analyze the data and create reports to help us make informed decisions. Moreover, by having all our leads in one place, we can avoid duplication of efforts and ensure that our team is not wasting time and resources by contacting the same lead multiple times. Therefore, we should make it a priority to accurately input the new lead's information into our CRM database as soon as possible.*/
        $save_record = CRM::FunctionName($request->user_name, $mobile, $request->followup_date, date('Y-m-d H:i:s'), date('d-M-Y').' - '.$request->followup_comment.' By '.Auth::user()->name, $request->assign_to, $user_id);
        if($save_record){
            return response()->json(['type'=>true, 'message'=>'lead added succesfully']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to add']);
        }
    }

    /*To generate a payment link for the client to purchase a plan, we must first determine the appropriate payment gateway to use. There are several payment gateways available, each with their own unique features and pricing models. Once we have selected the payment gateway, we will need to set up the payment link by specifying the plan details, the payment amount, and any additional information that the client may need to provide. It is also important to ensure that the payment link is secure and that the client's payment information is protected. To do this, we may need to implement additional security measures such as two-factor authentication or SSL encryption. Once the payment link is set up, we can send it to the client via email or other communication channels, along with instructions on how to complete the payment process. Finally, we will need to monitor the payment link to ensure that payments are being processed correctly and that any issues are addressed in a timely manner.*/
    public function generatePaymentLink(Request $request)
    {
        $mobile = $request->user_mobile;
        $mobile = substr($mobile, -10);
        $name = "random";
        $amount = $request->payment_amount;
        $amount = (int)$amount * 100;
        $description = $request->description;
        $email = $request->email;
        $receipt = time();
        $key_id = 'rzp_live_AkjH8AZSSZBdRn';
        $secret_key = '9jDuywER4AX1aGoiFeYDziIV';
        $api = new Api($key_id, $secret_key);
       // dd($api->invoice->create(array('type' => 'link', 'amount' => $amount, 'description' => $description, 'receipt' => $receipt)));
        $link = $api->invoice->create(
            array(
                'type'          => 'link',
                'amount'        => $amount,
                'description'   => $description,
                'receipt'       => $receipt,
                'customer_details'      =>  array(
                                    'name' => $name,
                                    'contact' => $mobile,
                                    'email' => $email
                                    )
            )
        );
        $result = array('link' => $link->short_url, 'mobile' => $mobile);
        // For app api only
        if ($request->type)
            return response()->json(['status' => true, 'link' => $link->short_url, 'mobile' => $mobile], 200);
        else {
            return response()->json(['status' => false, 'link' => $link->short_url, 'mobile' => $mobile], 200);
        }
    }
}
