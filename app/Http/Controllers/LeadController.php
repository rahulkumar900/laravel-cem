<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadPostRequest;
use App\Models\AssignRelations;
use App\Models\Compatibility;
use App\Models\CRM;
use App\Models\FreeUser;
use App\Models\IncompleteLeads;
use App\Models\Lead;
use App\Models\LeadFamily;
use App\Models\LeadValue;
use App\Models\Profile;
use App\Models\RequestLead;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserPreference;
use App\Models\UserRequestLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $income_ranges = config('constants.income_ranges');
        return view('crm.leads', compact("income_ranges"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function listAllUnAssignedLeads()
    {
        $income_ranges = config('constants.income_ranges');
        return view('crm.unassigned-leads', compact("income_ranges"));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLeadData(Request $request)
    {

        $today = date('Y-m-d');
        $lead_data = array();

        // check if admin login or user login
        if (Auth::user()->temple_id != 'admin') {
            $lead_details = Lead::join('user_data', 'leads.user_data_id', 'user_data.id')

                ->where(['is_done' => 0, 'leads.assign_to' => Auth::user()->temple_id])->orderBy('created_at', 'desc')
                ->get([
                    'leads.assign_to', 'user_data.name as lead_name', 'user_data.created_at', 'leads.followup_call_on', 'leads.comments',
                    'amount_collected', 'leads.id as lead_id', 'offline_score', 'online_score', 'total_score', 'visited_on', 'user_data.user_mobile',
                    'leads.assign_to as temple_id', 'speed', 'last_seen', 'user_data_id', 'assigned_at', 'leads.enquiry_date',
                    'appointment_date', 'mobile_family', 'whatsapp_family'
                ]);
        } else {
            $lead_details = Cache::remember('all_leads', 1, function () {
                $today = date('Y-m-d');
                //$sixmonths = date('Y-m-d',strtotime("-1 Months"));
                return Lead::with(array(
                    'users' => function ($query) {
                        $query->select('temple_id', 'name');
                    },
                ))
                    ->join('user_data', 'leads.user_data_id', 'user_data.id')
                    ->where(['is_done' => 0])->whereRaw("DATE(leads.followup_call_on) <= '$today' AND leads.assign_to != '' ")->where('leads.assign_to', Auth::user()->temple_id)->orderBy('created_at', 'desc')->get(['leads.assign_to', 'user_data.name as lead_name', 'user_data.created_at', 'leads.followup_call_on', 'leads.comments', 'amount_collected', 'leads.id', 'offline_score', 'online_score', 'total_score', 'visited_on', 'user_data.user_mobile', 'leads.assign_to as temple_id', 'speed', 'last_seen', 'enquiry_date']);
            });
        }

        $i = 0;
        $assign_button = '';
        // dd($lead_details);
        foreach ($lead_details as $lead_detail) {
            // scores
            $different_scores = '';
            $different_scores = " <p>Online Score : $lead_detail->online_score</p><p>Offline Score : $lead_detail->offline_score</p> <p>Overall Score : " . ($lead_detail->online_score + $lead_detail->offline_score) . "</p>";

            // lead name with assign to button
            /*$assign_button = '';
            if (Auth::user()->temple_id == $lead_detail->temple_id) {
                $assign_button = '<div class="form-group mb-2">
                <button type="button" class="btn btn-sm btn-warning assign_to_me waves-effect waves-light" id="' . $lead_detail->temple_id . '" lead_id="' . $lead_detail->id . '" data-toggle="tooltip" data-placement="top" title="Assign To Me"><i class="fas fa-list"></i></button></div>';
            } else {
                $assign_button = ''; 7982454551 gaurvi
            }*/
            if (!empty($lead_details[$i]['users']['name'])) {
                $lead_name = ucwords($lead_details[$i]['users']['name']);
            } else {
                $lead_name = 'Hans Matrimony Online';
            }
            $lead_name = $lead_name . $assign_button;

            // profile name with app link & temple address button
            /*
                <div class="form-group mb-2">
                <button type="button"class="btn btn-sm btn-danger send_whatsapp_message waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_data_id . '" title="Add Alternate Numbers"><i class="fab fa-google-play"></i></button></div>
            */
            $profile_name = '';
            $all_alternate = '';
            if (empty($lead_detail->mobile_family) || empty($lead_detail->whatsapp_family)) {
                // mobile_family whatsapp_family
                $all_alternate = '<button type="button"class="btn btn-sm btn-danger add_alterante_no waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_data_id . '" title="Add Alternate Numbers"><i class="fa fa-list" aria-hidden="true"></i></button></div>';
            } else {
                $all_alternate = '';
            }
            $profile_name = $lead_detail->lead_name . '
             <div class="form-group mb-2">' . $all_alternate . '
                <div class="form-group mb-2"><button type="button" class="btn btn-sm btn-primary view_user_profile waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_mobile . '" user_data_id="' . $lead_detail->user_data_id . '" title="View profile"><i class="fas fa-user"></i> </button></div>';

            // contact number with call, send whatsapp message, share sample profile, fix appointment
            $contact_button = '';
            // mobile_family whatsapp_family
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

            // followup button with next followup and reject lead 919811104453
            $followup = '';
            if (Auth::user()->temple_id == $lead_detail->temple_id) {
                $followup = ' <div class="row">
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-success add_next_followup waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Add Next Followup" lead_id="' . $lead_detail->lead_id . '" temple_name="' . $lead_detail->name . '">
                            <i class="fas fa-envelope-open-text"></i>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-danger reject_leads waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Mark Rejected Lead" lead_id="' . $lead_detail->lead_id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>';
            } else {
                $followup = '';
            }
            //dd($lead_detail->comments);
            // comments
            $comments = '';
            $comments_raw = explode(';', $lead_detail->comments);

            $comments .= '<a href="#comments' . $i . '" class="" data-toggle="collapse">Expand</a>
            <div id="comments' . $i . '" class="collapse">';
            for ($j = 0; $j < count($comments_raw); $j++) {
                $comments .=    '<p style="white-space: pre-line; line-break:auto; width:250px; text-align:justify"> ' . $comments_raw[$j] . ' </p>';
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
                'subscriptions'         =>          '',
                'appointments'          =>          $lead_detail->appointment_date,
                'assigned_at'           =>          date('Y-m-d', strtotime($lead_detail->assigned_at))
            );
            $i++;
        }

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($lead_data),
            "totaldisplayrecords" => count($lead_data),
            "data" => $lead_data,
            "test" => Auth::user()->temple_id
        );

        return response()->json($dataset);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showUnassignLeads(Request $request)
    {
        $startDate = '2023-06-01';
        $endDate = '2023-06-30';
        $lead_data = array();
        $arrayOfStatus = [0,2];


        $lead_details = Lead::join('user_data', 'leads.user_data_id', 'user_data.id')
            ->where('leads.assign_to', 'online')
            ->whereIn('is_done', $arrayOfStatus)
            ->whereBetween('user_data.created_at', [$startDate, $endDate]) // Adjusted line
            ->orderBy('leads.created_at', 'desc') // Adjusted line
            ->get([
                'leads.assign_to', 'leads.is_done', 'user_data.name as lead_name', 'user_data.created_at',
                'user_data.id as lead_id', 'user_data.user_mobile',
                'leads.assign_to as temple_id', 'user_data_id', 'leads.assigned_at',
            ]);


        // data Feaching Block End Here
        $i = 0;
        $assign_to_me_button = "";
        $reject_lead_button = "";
        foreach ($lead_details as $lead_detail) {
            $templeId = Auth::user()->temple_id;

            $assign_to_me_button = '<button type="button" class="btn btn-sm btn-success assgn_to_me_btn" leadId="' . $lead_detail['lead_id'] . ' "key="' . $lead_detail['lead_id'] .    '" templeId="' . $templeId . '">Assign To Me</button>';

            // ////////////////////////////////////////////////////////





            // if (Auth::user()->temple_id == $lead_detail->temple_id) {
            $reject_lead_button = ' <div class="row">
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-danger reject_leads waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Mark Rejected Lead" lead_id="' . $lead_detail->lead_id . '">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>';
            // // } else {
            //     $reject_lead_button = 'Not Allowed';
            // }
            //dd($lead_detail->comments);
            // comments


            $delete_lead_button = ' <div class="row">
            <div class="col-6">
                <button type="button"
                    class="btn btn-sm btn-danger delete_leads waves-effect waves-light"
                    data-toggle="tooltip" data-placement="top" title="Delete Lead" lead_id="' . $lead_detail->lead_id . '">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>';





            // ////////////////////////////////////////////////////////////////////////////////////////













            ///////////////////////////////////////////////////////////////////////

            $status = '';
            $isDone = $lead_detail->is_done;
            if ($isDone === '0') {
                $status  = 'Open';
            } elseif ($isDone === '1') {
                $status = 'Converted';
            } else {
                $status = 'Rejected';
            }

            $lead_data[] = array(
                'lead_name'             =>          $lead_detail->lead_name,
                'mobile'                =>          $lead_detail->user_mobile,
                // 'status'                =>          $status,
                'status' =>                         $lead_detail->is_done,
                'assigned_to'           =>          $lead_detail->temple_id,
                'created_at'            =>          date('Y-m-d', strtotime($lead_detail->created_at)),
                'assign_to_me'          =>          $assign_to_me_button,
                'reject'                =>          $reject_lead_button,
                'delete'                =>          $delete_lead_button,
            );
            $i++;
        };

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($lead_data),
            "totaldisplayrecords" => count($lead_data),
            "data" => $lead_data,
            "test" => Auth::user()->temple_id
        );
        return response()->json($dataset);
    }


    public function subSeenView()
    {
        return view('crm.subscription-seen-leads');
    }

    public function showSubscriptionSeenData()
    {
        $dataset = array();
        $lead_details = Lead::join('user_data', 'leads.user_data_id', 'user_data.id')
            ->where(['is_done' => 0, 'leads.assign_to' => Auth::user()->temple_id, 'user_data.is_subscription_view' => 1])->orderBy('created_at', 'desc')->get(['leads.assign_to', 'user_data.name as lead_name', 'user_data.created_at', 'leads.followup_call_on', 'leads.comments', 'amount_collected', 'leads.id', 'offline_score', 'online_score', 'total_score', 'visited_on', 'user_data.user_mobile', 'leads.assign_to as temple_id', 'speed', 'last_seen', 'user_data_id', 'assigned_at', 'leads.enquiry_date', 'appointment_date', 'mobile_family', 'whatsapp_family']);
        $i = 0;
        $assign_button = '';
        if (count($lead_details->toArray()) == 0) {
            $dataset = array(
                "echo" => 1,
                "totalrecords" => 0,
                "totaldisplayrecords" => 0,
                "data" => []
            );
        } else {
            foreach ($lead_details as $lead_detail) {
                // scores
                $different_scores = '';
                $different_scores = " <p>Online Score : $lead_detail->online_score</p><p>Offline Score : $lead_detail->offline_score</p> <p>Overall Score : " . ($lead_detail->online_score + $lead_detail->offline_score) . "</p>";

                // lead name with assign to button
                /*$assign_button = '';
            if (Auth::user()->temple_id == $lead_detail->temple_id) {
                $assign_button = '<div class="form-group mb-2">
                <button type="button" class="btn btn-sm btn-warning assign_to_me waves-effect waves-light" id="' . $lead_detail->temple_id . '" lead_id="' . $lead_detail->id . '" data-toggle="tooltip" data-placement="top" title="Assign To Me"><i class="fas fa-list"></i></button></div>';
            } else {
                $assign_button = '';
            }*/
                if (!empty($lead_details[$i]['users']['name'])) {
                    $lead_name = ucwords($lead_details[$i]['users']['name']);
                } else {
                    $lead_name = 'Hans Matrimony Online';
                }
                $lead_name = $lead_name . $assign_button;

                // profile name with app link & temple address button
                /*
                <div class="form-group mb-2">
                <button type="button"class="btn btn-sm btn-danger send_whatsapp_message waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_data_id . '" title="Add Alternate Numbers"><i class="fab fa-google-play"></i></button></div>
            */
                $profile_name = '';
                $all_alternate = '';
                if (empty($lead_detail->mobile_family) || empty($lead_detail->whatsapp_family)) {
                    // mobile_family whatsapp_family
                    $all_alternate = '<button type="button"class="btn btn-sm btn-danger add_alterante_no waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_data_id . '" title="Add Alternate Numbers"><i class="fa fa-list" aria-hidden="true"></i></button></div>';
                } else {
                    $all_alternate = '';
                }
                $profile_name = $lead_detail->lead_name . '
             <div class="form-group mb-2">' . $all_alternate . '
                <div class="form-group mb-2"><button type="button" class="btn btn-sm btn-primary view_user_profile waves-effect waves-light" data-toggle="tooltip" data-placement="top" mobileNo="' . $lead_detail->user_mobile . '" user_data_id="' . $lead_detail->user_data_id . '" title="View profile"><i class="fas fa-user"></i> </button></div>';

                // contact number with call, send whatsapp message, share sample profile, fix appointment
                $contact_button = '';
                // mobile_family whatsapp_family
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

                // followup button with next followup and reject lead
                $followup = '';
                if (Auth::user()->temple_id == $lead_detail->temple_id || Auth::user()->temple_id == 'admin') {
                    $followup = ' <div class="row">
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-sm btn-success add_next_followup waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top" title="Add Next Followup" lead_id="' . $lead_detail->user_data_id . '" temple_name="' . $lead_detail->name . '">
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
                //dd($lead_detail->comments);
                // comments
                $comments = '';
                $comments_raw = explode(';', $lead_detail->comments);

                $comments .= '<a href="#comments' . $i . '" class="" data-toggle="collapse">Expand</a>
            <div id="comments' . $i . '" class="collapse">';
                for ($j = 0; $j < count($comments_raw); $j++) {
                    $comments .=    '<p style="white-space: pre-line; line-break:auto; width:250px; text-align:justify">
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
                    'subscriptions'         =>          '',
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
        }


        return response()->json($dataset);
    }

    /**
     * Search Lead Details by mobile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchLeadDetails(Request $request)
    {
        $mobile = substr($request->lead_mobile_no, -10);
        $heading = '';
        $search_leads = '';
        $assign_to = '';
        $searched_data = '';
        $fetched_array = array();

        $search_leads = Lead::searchLeadData($mobile);

        // return response with heading
        if (empty($search_leads)) {
            $type = false;
            $message = 'no record found';
        } else {
            if (!empty($search_leads)) {
                $assign_to = $search_leads->assign_to;
                if ($search_leads->is_done == 0) {
                    $heading = 'Open';
                } else if ($search_leads->is_done == 1) {
                    $heading = 'Converted';
                } else if ($search_leads->is_done == 2) {
                    $heading = 'Rejected';
                }
                $searched_data = $search_leads;
            }
            $temple_name = User::where('temple_id', $assign_to)->first();
            $fetched_array = array(
                'lead_details'      =>      $searched_data,
                'temple_name'       =>      $temple_name->name,
                'heading'           =>      $heading
            );
            $type = true;
            $message = 'record found';
        }

        return \response()->json(['type' => $type, 'message' => $message, 'data' => $fetched_array]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function leadPlans()
    {
        $lead_plan = Cache::rememberForever('lead_plan', function () {
            return DB::table('contents')->where('type', '!=', '')->whereRaw("RIGHT(type,4)='_msg'")->get();
        });
        return response()->json($lead_plan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function saveLeadFollowup(Request $request)
    {
        $user_name = Auth::user()->name;
        $temple_id = "";
        if (empty($request->temple_id)) {
            $temple_id = Auth::user()->temple_id;
        } else {
            $temple_id = $request->temple_id;
        }

        $save_followup = Lead::saveFollowups(
            $request->followup_lead_id,
            $user_name,
            $request->followup_status,
            $request->next_followup_date,
            $request->amount,
            $request->lead_speed,
            $temple_id,
            $user_name
        );

        /** remove from requeted leads */
        if ($request->pick_lead_id) {
            $update_req_lead = UserRequestLead::changeStatus(Auth::user()->id, $request->lead_type, $request->pick_lead_id, 'picked_up');
        }

        if ($save_followup) {
            return response()->json(['type' => true, 'message' => 'followup updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to save record']);
        }
    }

    public function saveLeadCrmFollowup(Request $request)
    {
        $user_name = Auth::user()->name;
        //dd($request->followup_lead_id);
        $save_followup = Lead::saveCrmFollowups($request->followup_lead_id, $user_name, $request->followup_status, $request->next_followup_date, $request->amount, $request->lead_speed, Auth::user()->temple_id);

        if ($save_followup) {
            return response()->json(['type' => true, 'message' => 'followup updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to save record']);
        }
    }

    /**
     * Get Facebook leads.
     * first check into requestLead table for previous requested leads
     * check their corrospondent table for requested leads
     * if there is no any requested leads then return new lead else return previus requested leads
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function getFacebookLeads(Request $request)
    {
        $check_prev_lead = UserRequestLead::checkRequestedLeads(Auth::user()->id);

        $check_rquested_lead = "";
        if (!empty($check_prev_lead->toArray())) {
            $lead_type = array_unique(array_column($check_prev_lead->toArray(), "lead_type"));
            if ($lead_type == 0 || $lead_type == 3) {
                // fetch data from leads $check_prev_lead->toArray()
                $implode_string = implode(",", array_column($check_prev_lead->toArray(), "lead_id"));
                $check_rquested_lead = Lead::leftJoin('user_data', 'user_data.id', 'leads.user_data_id')->whereRaw("leads.id in($implode_string)")->select(["leads.user_data_id", "user_data.name", "user_data.mobile", "leads.comments"])->get();
            } else if ($lead_type == 1 || $lead_type == 2) {
                // fetch data from incomplete leads
                $lead_ids = array_column($check_prev_lead->toArray(), "lead_id");
                $check_rquested_lead = IncompleteLeads::whereIn('id', $lead_ids)->get();
            }
            return response()->json(['type' => true, 'message' => 'you have already requested leads', 'data' => $check_rquested_lead, 'lead_type' => $lead_type]);
        } else {
            // get temple assign relation

            $temple_relation = AssignRelations::getAssignedRelations(Auth::user()->temple_id);

            $lead_data = '';
            # 1= facebook leads
            if ($request->lead_type == 1) {
                if ($temple_relation->category == "D") {
                    $lead_data = IncompleteLeads::dLevelTempleLeads()->toArray();
                    return response()->json(['type' => true, 'message' => 'new requested leads', 'data' => $lead_data, 'lead_type' => $request->lead_type]);
                } else {
                    // return new leads
                    $return_data = array();
                    $lead_data = IncompleteLeads::requestFacebookLeads($temple_relation->call_count, $temple_relation->creation_date_time)->toArray();
                    $j = 0;
                    //dd($lead_data);
                    for ($i = 0; $i < count($lead_data); $i++) {
                        $create_record = '';
                        // check for existing number in leads table then return array
                        $check_mobile = Lead::searchLeadData($lead_data[$i]['user_phone']);

                        if (empty($check_mobile->id)) {
                            // assign lead to user
                            $return_data[] = array(
                                "mobile"        =>      $lead_data[$i]['user_phone'],
                                "id"            =>      $lead_data[$i]['id'],
                                "name"          =>      $lead_data[$i]['fname'],
                            );
                            // add record into user requested leads
                            $create_record = UserRequestLead::createRecord(Auth::user()->id, $lead_data[$i]['id'], 1);

                            // update incomplete table
                            IncompleteLeads::updateRequestedLeads($lead_data[$i]['user_phone'], Auth::user()->temple_id);
                            $j++;
                        } else {
                            // mark incomplete lead as deleted
                            IncompleteLeads::deleteIncompleteLead($lead_data[$i]['user_phone']);
                        }
                    }
                }
                // update request leads table
                $update_rquest_lead = RequestLead::updateRequestLead(Auth::user()->temple_id, $request->lead_type);
                return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $return_data, 'lead_type' => $request->lead_type]);
            }

            # 2=operator calls
            if ($request->lead_type == 2) {
                $lead_data = IncompleteLeads::requestOperatorCalls()->toArray();
                //dd($lead_data);
                // update request leads
                $lead_ids = implode(",", array_column($lead_data, "id"));
                // upadte rquest by and check eigther lead is assigned to anyone
                $update_requestby = IncompleteLeads::whereRaw("id in ($lead_ids)")->update([
                    "request_by"        =>      Auth::user()->temple_id,
                    "request_by_at"     =>      date("Y-m-d H:i:s")
                ]);

                $ret_data = array();
                foreach ($lead_data as $single_data) {
                    $int_mobile = (int)$single_data['user_phone'];
                    $checkMobile = UserData::whereRaw("user_mobile LIKE '%$int_mobile%' ")->first();
                    if (empty($checkMobile)) {
                        $ret_data[] = $single_data;
                    }
                }

                $ret_ids = array_column($ret_data, 'id');
                for ($i = 0; $i < count($ret_data); $i++) {
                    // add record into user requested leads
                    $create_record = UserRequestLead::createRecord(Auth::user()->id, $ret_data[$i]['id'], 2);
                }


                return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $ret_data, 'lead_type' => $request->lead_type]);
            }
            # 3=leads with status 2 (converted)
            if ($request->lead_type == 3) {
                $credit = $request->lead_credits;
                $total_leads = Lead::getExhaustLeads(5, $request->lead_speed, $request->lead_income, $credit)->toArray();
                if (count($total_leads) > 0) {
                    //dd($total_leads);
                    $lead_ids = array_column($total_leads, 'id');

                    $update_lead = Lead::updateRequestBy(implode(',', $lead_ids), Auth::user()->temple_id);
                    $update_rquest_lead = RequestLead::updateRequestLead(Auth::user()->temple_id, 0);
                    return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $total_leads, 'lead_type' => $request->lead_type,  "lead_ids", implode(',', $lead_ids)]);
                } else {
                    return response()->json(['type' => false, 'lead_type' => $request->lead_type, 'message' => 'no lead to request', 'data' => '', 'lead_type' => $request->lead_type]);
                }
            }
            # 0=website leads (leads with status 0)
            if ($request->lead_type == 0) {

                $total_leads = Lead::getWebsiteLeads(5, $request->lead_speed, $request->lead_income)->toArray();
                if (count($total_leads) > 0) {
                    $lead_ids = array_column($total_leads, 'id');

                    $update_lead = Lead::updateRequestBy(implode(',', $lead_ids), Auth::user()->temple_id);

                    for ($i = 0; $i < count($lead_ids); $i++) {
                        $create_record = UserRequestLead::createRecord(Auth::user()->id, $lead_ids[$i], 0);
                    }

                    $update_rquest_lead = RequestLead::updateRequestLead(Auth::user()->temple_id, 0);
                    return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $total_leads, 'lead_type' => $request->lead_type]);
                } else {
                    return response()->json(['type' => false, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => "", 'lead_type' => $request->lead_type]);
                }
            }
        }
    }

    // get facebook leads
    public function requestWebsiteLeadData(Request $request)
    {
        $check_prev_lead = UserRequestLead::checkRequestedLeads(Auth::user()->id);
        $check_rquested_lead = "";
        if (!empty($check_prev_lead->toArray())) {
            $lead_type = array_unique(array_column($check_prev_lead->toArray(), "lead_type"));
            if ($lead_type == 0 || $lead_type == 3) {
                // fetch data from leads
                $implode_string = implode(",", array_column($check_prev_lead->toArray(), "lead_id"));
                $lead_detail_data = Lead::leftJoin('user_data', 'user_data.id', 'leads.user_data_id')->whereRaw("leads.id in($implode_string)")->select(["leads.user_data_id", "user_data.name", "user_data.mobile", "leads.comments", "leads.id"])->get();
            } else if ($lead_type == 1 || $lead_type == 2) {
                // fetch data from incomplete leads
                $lead_ids = array_column($check_prev_lead->toArray(), "lead_id");
                $lead_detail_data = IncompleteLeads::whereIn('id', $lead_ids)->get();
            }
        } else {
            $total_leads = Lead::getWebsiteLeads(5, $request->lead_speed, $request->lead_income)->toArray();
            if (count($total_leads) > 0) {
                $lead_ids = array_column($total_leads, 'id');

                $update_lead = Lead::updateRequestBy(implode(',', $lead_ids), Auth::user()->temple_id);

                for ($i = 0; $i < count($lead_ids); $i++) {
                    $create_record = UserRequestLead::createRecord(Auth::user()->id, $lead_ids[$i], 0);
                }

                $update_rquest_lead = RequestLead::updateRequestLead(Auth::user()->temple_id, 0);
                return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $total_leads, 'lead_type' => $request->lead_type]);
            } else {
                return response()->json(['type' => false, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => "", 'lead_type' => $request->lead_type]);
            }
        }
    }

    /**
     * check already requested leads
     * 0 = website leads
     * 1 = facebook leads
     * 2 = data acount
     * 3 = crm leads
     * 4 = converted leads
     * 5 = website leads (subscription view)
     * 6 =
     * 7 = incomplete leads channel1 (operator missed calls)
     * 8 = incomplete leads data channel (data leads)
     */
    public function checkPrevRequestedLeads()
    {
        return UserRequestLead::checkRequestedLeads(Auth::user()->id);
    }

    /* not pickupleads */
    public function leadsNotPickUp(Request $request)
    {
        $lead_type = $request->lead_type;
        $change_status = UserRequestLead::changeStatus(Auth::user()->id, $lead_type, $request->lead_id, 'rejected');
        $update_notpick = Lead::updateNotPickup($request->lead_id, Auth::user()->temple_id);
        if ($update_notpick) {
            return response()->json(['type' => true, 'message' => 'record updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to update']);
        }
    }

    // get exhaust leads
    public function getConvertedLeads(Request $request)
    {
        $credit = $request->lead_credits;
        $total_leads = Lead::getExhaustLeads(5, $request->lead_speed, $request->lead_income, $credit)->toArray();
        if (count($total_leads) > 0) {
            //dd($total_leads);
            $lead_ids = array_column($total_leads, 'id');

            $update_lead = Lead::updateRequestBy(implode(',', $lead_ids), Auth::user()->temple_id);
            $update_rquest_lead = RequestLead::updateRequestLead(Auth::user()->temple_id, 3);

            for ($i = 0; $i < count($lead_ids); $i++) {
                $create_record = UserRequestLead::createRecord(Auth::user()->id, $lead_ids[$i], 3);
            }

            return response()->json(['type' => true, 'lead_type' => $request->lead_type, 'message' => 'new requested leads', 'data' => $total_leads, 'lead_type' => $request->lead_type,  "lead_ids", implode(',', $lead_ids)]);
        } else {
            return response()->json(['type' => false, 'lead_type' => $request->lead_type, 'message' => 'no lead to request', 'data' => '', 'lead_type' => $request->lead_type]);
        }
    }

    /**
     * get previous requested leads
     * if user has request leads and requests again then return
     * previous leads
     * returned data with their request types
     */
    public function getPreviousRequestLeads($temple_id, $lead_type)
    {
        $countRequestLeads = '';
        $type = '';
        // check previous request leads if there is any
        $previous_request = $this->checkPrevRequestedLeads();
        // dd($lead_type);
        // dd(count($previous_request->toArray())>0 && ($lead_type==0 || $lead_type == 3 || $lead_type == 5 || $lead_type == 4));
        if (count($previous_request->toArray()) > 0 && ($lead_type == 0 || $lead_type == 3 || $lead_type == 5 || $lead_type == 4)) {
            $countRequestLeads = Lead::checkRequestedLeads(Auth::user()->temple_id)->toArray();
            $type = $previous_request;
        } else if (count($previous_request->toArray()) > 0 && ($lead_type == 1 || $lead_type == 7 || $lead_type == 8)) {
            $countRequestLeads = IncompleteLeads::checkFaceBookLeads(Auth::user()->temple_id)->toArray();
            $type = $previous_request;
        } else {
            //check count in leads table where follwoup,request_by are null and assign_to is equal to online or null
            if ($lead_type == 0) {
                $countRequestLeads = Lead::checkRequestedLeads(Auth::user()->temple_id)->toArray();
                $type = $lead_type;
            }
            //check count in incompleteLeads table in request_by column corresponding to the request_by temple_id
            else if ($lead_type == 1) {
                $countRequestLeads = IncompleteLeads::checkFaceBookLeads(Auth::user()->temple_id)->toArray();
                $type = $lead_type;
            }
            //check count in data account of profiles
            else if ($lead_type == 2) {
                return 1;
            } else if ($lead_type == 3) {
                $countRequestLeads = Profile::where('crm_created', 0)->where('request_by', $temple_id)->where('call_count', '<', 2)
                    ->where('exhaust_reject', '<', '2')
                    ->where('profiles.marital_status', '!=', 'Married')
                    ->get();
            } else if ($lead_type == 5) {
                $countRequestLeads = Lead::subscriptionView(Auth::user()->temple_id)->toArray();
            } else if ($lead_type == 4) {
                $today_date = date('Y-m-d', time());
                $week_old_date = date('Y-m-d', strtotime($today_date . "-7day")) . ' 00:00:00';
                $six_months_old = date('Y-m-d', strtotime($today_date . "-180day")) . ' 00:00:00';
                $countRequestLeads = Lead::convertedLeads(Auth::user()->temple_id, $week_old_date, $six_months_old)->toArray();
            } else if ($lead_type == 7) {
                $countRequestLeads = IncompleteLeads::channelOneLeads(Auth::user()->temple_id)->toArray();
            } else if ($lead_type == 8) {
                $countRequestLeads = IncompleteLeads::checkDataChannel(Auth::user()->temple_id)->toArray();
            }
        }
        return array('data' => $countRequestLeads, 'type' => $type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function addLeadTeleSales(LeadPostRequest $request)
    {
        DB::beginTransaction();
        $relation_string = '';
        $relation_int = '';

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
        //var_dump($mobile_with_code);
        //die; exit;
        $total_comment = "";
        $user_name = Auth::user()->name;
        if ($request->new_lead) {
            $total_comment = date('Y-m-d') . "  Lead moved from online and assigned to $user_name" . ";" . date('Y-m-d H:i:s') . ' ' . $request->followup_comment . " added by " . $user_name . ";";
        } else {
            $total_comment = $request->followup_comment . " added by " . $user_name . ";";
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

        $weight = $request->weight;
        $save_user_data = UserData::saveRecord($mobile_with_code, $request->full_name, $request->user_height, Auth::user()->temple_id, $gender_string, $request->lead_gender, $religion_int, $religion_string, $caste_int, $caste_string, $education_int, $education_string, $occupation_int, $occupation_string, $request->birth_date, $relation_int, $relation_string, $marital_int, $marital_string, $request->yearly_income, null, $request->current_city, $request->search_working_city, $alt_1, $alt_2, $weight);

        // save Lead Data
        $lead_value = LeadValue::calculateLeadValue(strtolower($gender_string), strtolower($relation_string), $request->yearly_income);

        $save_lead = Lead::addDataToLead(Auth::user()->temple_id, $request->enquiry_date, $request->followup_date, date('Y-m-d'), $request->followup_date, $total_comment, $request->interest_level, $request->lead_source, $lead_value, null, $save_user_data->id, $mobile_with_code, $request->full_name);

        $save_free_user = FreeUser::create([
            "lead_id"       =>      $save_lead->id,
            "mobile"        =>      $mobile_with_code,
            "temple_id"     =>      Auth::user()->temple_id,
            "age"           =>      25,
            "caste"         =>      "Arora",
            "education"     =>      "Graduation",
            "height"        =>      62,
            "marital_status" =>     "Unmarried",
            "occupation"    =>      "Not Working",
            "gender"        =>      "Male",
        ]);

        $save_family = LeadFamily::create([
            "lead_id" => $save_lead->id
        ]);

        // create compatblity
        $creat_compatblity = Compatibility::create([
            "user_data_id" => $save_user_data->id
        ]);

        //save Lead Preference

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

        $change_status = UserRequestLead::changeStatus(Auth::user()->id, $request->lead_type, $request->pick_lead_id, 'picked_up');

        if ($save_user_data && $save_lead && $creat_compatblity && $create_preference) {
            DB::commit();
            return response()->json(['type' => true, 'message' => 'lead added successfully', 'id' => $save_user_data->id, 'mobile' => $mobile_with_code, 'data' => $save_user_data]);
        } else {
            DB::rollBack();
            return response()->json(['type' => false, 'message' => 'failed to add']);
        }
    }

    // update not pickup in in complete leads by id
    public function notPickupIncompleteLeads(Request $request)
    {
        $update_notpick = '';
        $lead_type = $request->lead_type;
        $change_status = UserRequestLead::changeStatus(Auth::user()->id, $lead_type, $request->lead_id, 'rejected');
        // dd($change_status);
        if ($change_status && $lead_type == 1 || $lead_type == 2) {
            $update_notpick = IncompleteLeads::updateNotPickup($request->lead_id, Auth::user()->temple_id);
        } else if ($change_status && $lead_type == 0 || $lead_type == 3) {
            $update_notpick = Lead::updateNotPickup($request->lead_id, Auth::user()->temple_id);
        }
        if ($update_notpick) {
            return response()->json(['type' => true, 'message' => 'record updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to update']);
        }
    }

    //get templa relation category and other data
    public function getTemplerelation()
    {
        $temple_relation = AssignRelations::getAssignedRelations(Auth::user()->temple_id);
        return response()->json($temple_relation);
    }

    // count requested leads
    public function countRequestedLeads()
    {
        $begin_of_day = date('Y-m-d H:i:s', strtotime("today", time()));
        $end_of_day   = date('Y-m-d H:i:s', strtotime("tomorrow", time()) - 1);
        $total_leads = '';
        $total_leads = UserRequestLead::requestedLeads(Auth::user()->id, $begin_of_day, $end_of_day)->toArray();
        return response()->json($total_leads);
    }

    // get website leads list
    public function getWebsiteLeads()
    {
        $website_list = Lead::getWebsiteLeads(20)->toArray();
        if (empty($website_list)) {
            return response()->json(['type' => false, 'data' => '']);
        } else {
            return response()->json(['type' => true, 'data' => $website_list]);
        }
    }

    // update assign to
    public function assignToMe(Request $request)
    {
        $temple_name = Auth::user()->name;
        // dd($temple_name);
        // dd($request->lead_id);
        $update_assign_to = Lead::assignToMe(Auth::user()->temple_id, $request->lead_id, $temple_name);
        if ($update_assign_to) {
            $update_profile = DB::table("user_data")->where("id", $request->lead_id)->update(["temple_id" => Auth::user()->temple_id]);
            return  response()->json(["type" => true, 'message' => 'assigned']);
        } else {
            return  response()->json(["type" => false, 'message' => 'failed to assign']);
        }
    }

    // reject lead
    public function rejectLead(Request $request)
    {
        $reject_lead = Lead::rejectLead($request->lead_id, date('Y-m-d') . " lead has been rejected by(" . Auth::user()->name . ")");
        if ($reject_lead) {
            return response()->json(["type" => true, 'message' => 'rejected successfully']);
        } else {
            return response()->json(["type" => false, 'message' => 'failed to reject']);
        }
    }

    // requested leads page view
    public function requestedLeads()
    {
        return view('crm.today-requested-leads');
    }

    // all fb requested leads of today by an user
    public function allRequestedFbLeads()
    {
        $temple_id = Auth::user()->temple_id;

        $today = date('Y-m-d');

        $requested_data = DB::table('incomplete_leads')->whereRaw("DATE(request_by_at)='$today' AND (request_by=$temple_id OR request_by = 'Not-Pick $temple_id')")->get(['user_phone', 'request_by_at', 'request_by']);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($requested_data),
            "totaldisplayrecords" => count($requested_data),
            "data" => $requested_data
        );

        return response()->json($dataset);
    }

    // all website requested leads of today by an user
    public function allRequestedWebLeads()
    {
        $temple_id = Auth::user()->temple_id;

        $today = date('Y-m-d');

        $requested_data = DB::table('leads')->whereRaw("DATE(updated_at)='$today' AND is_done=0 AND (request_by=$temple_id OR request_by = 'Not-Pick $temple_id')")->get(['mobile', 'updated_at', 'request_by']);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($requested_data),
            "totaldisplayrecords" => count($requested_data),
            "data" => $requested_data
        );

        return response()->json($dataset);
    }

    public function allRequestedExhaustLeads()
    {
        $temple_id = Auth::user()->temple_id;

        $today = date('Y-m-d');

        $requested_data = DB::table('leads')->whereRaw("DATE(updated_at)='$today' AND is_done=2 AND (request_by=$temple_id OR request_by = 'Not-Pick $temple_id')")->get(['mobile', 'updated_at', 'request_by']);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($requested_data),
            "totaldisplayrecords" => count($requested_data),
            "data" => $requested_data
        );

        return response()->json($dataset);
    }


    // todays followup from where user can see to whom he folllups
    public function todaysFollowup()
    {
        return view('crm.today-followups');
    }

    public function todaysFollowupData()
    {
        $today_followup = '';

        $today_followup = Lead::todaysFolloup(Auth::user()->temple_id);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($today_followup),
            "totaldisplayrecords" => count($today_followup),
            "data" => $today_followup
        );

        return response()->json($dataset);
    }

    // my pending leads
    public function myPendingLeads()
    {
        return view('crm.pending-leads');
    }

    public function myPendingLeadsData()
    {
        $my_pending_leads = '';

        $my_pending_leads = Lead::myPendingLeads(Auth::user()->temple_id);

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($my_pending_leads),
            "totaldisplayrecords" => count($my_pending_leads),
            "data" => $my_pending_leads
        );

        return response()->json($dataset);
    }

    public function getLastRequestedLeads(Request $request)
    {
        $countRequestLeads = '';
        $type = '';
        $lead_type = '';
        $message = '';
        /*if ($request->lead_type == 0) {
            $countRequestLeads = Lead::checkRequestedLeads(Auth::user()->temple_id)->toArray();
            $type = true;
            $lead_type = $request->lead_type;
            $message = "You Have Already Requestd Leads";
        } else if ($request->lead_type == 1) {
            $countRequestLeads = IncompleteLeads::checkFaceBookLeads(Auth::user()->temple_id)->toArray();
            $type = true;
            $lead_type = $request->lead_type;
            $message = "You Have Already Requestd Leads";
        }*/
        $countRequestLeads = UserRequestLead::checkRequestedLeads(Auth::user()->id);
        if (!empty($countRequestLeads) && count($countRequestLeads->toArray()) > 0) {
            $type = true;
            $message = "You Have Already Requestd Leads";
            $lead_type = array_unique(array_column($countRequestLeads->toArray(), 'lead_type'))[0];
            $lead_ids = array_column($countRequestLeads->toArray(), 'lead_id');
            $lead_detail_data = array();
            //dd($led_type);
            // check requested type and return data
            if ($lead_type == 0 || $lead_type == 3) {
                // fetch data from leads
                $implode_string = implode(",", $lead_ids);
                //dd($implode_string);
                $lead_detail_data = Lead::join('user_data', 'user_data.id', 'leads.user_data_id')->whereRaw("leads.id in($implode_string)")->select(["leads.user_data_id", "user_data.name", "user_data.user_mobile as mobile", "leads.comments", "leads.id"])->get();
            } else if ($lead_type == 1 || $lead_type == 2) {
                // fetch data from incomplete leads
                $lead_detail_data = IncompleteLeads::whereIn('id', $lead_ids)->get();
            }
            return array('data' => $lead_detail_data, 'type' => $type, 'lead_type' => $lead_type, 'message' => $message);
        } else {
            return array('data' => '', 'type' => false, 'lead_type' => $lead_type, 'message' => 'Request New Lead');
        }
    }

    public function getLeadDetailsById(Request $request)
    {
        $lead_data = Lead::where('leads.id', $request->lead_id)->join('user_data', 'user_data.id', 'leads.user_data_id')->first(['user_data.*']);
        return response()->json($lead_data);
    }

    public function upadteUserDataId()
    {
        $update_lead = '';
        $lead_data = Lead::orderBy('id', 'desc')->get();
        foreach ($lead_data as $lead) {
            $search_user_data = UserData::whereRaw("user_mobile like '%$lead->mobile%'")->first();
            if (!empty($search_user_data)) {
                $update_lead = Lead::where('id', $lead->id)->update([
                    "user_data_id"      =>      $search_user_data->id
                ]);
            }
        }

        if ($update_lead) {
            return response()->json(["type" => true]);
        }
    }

    public function saveAlternateNumber(Request $request)
    {
        $user_mobile_one = $request->alternate_one;
        $user_data_id = $request->user_data_id;

        $search_first_mobile = UserData::whereRaw("user_mobile like '%$user_mobile_one%' OR mobile_family like '%$user_mobile_one%' OR whatsapp_family LIKE '%$user_mobile_one%'")->first();

        if (empty($search_first_mobile)) {
            $save_alt = "";

            $search_user_data = UserData::where("id", $user_data_id)->first();

            if (empty($search_user_data->mobile_family) && empty($search_user_data->whatsapp_family)) {
                //dd("asdasda");
                $save_alt = UserData::where("id", $user_data_id)->update([
                    "mobile_family" => $user_mobile_one
                ]);
                // dd("search_first_mobile", $save_alt);
                return response()->json(['type' => true, 'message' => 'record added']);
            } else if (!empty($search_user_data->mobile_family) && empty($search_user_data->whatsapp_family)) {
                //dd("asdasda 2");
                $save_alt = UserData::where("id", $user_data_id)->update([
                    "whatsapp_family" => $user_mobile_one
                ]);
                return response()->json(['type' => true, 'message' => 'record added']);
            }
            return response()->json(['type' => false, 'message' => 'something went wrong, check manually']);
            // if ($save_alt) {
            //     return response()->json(['type' => true, 'message' => 'record added']);
            // }
        } else {
            // record existed
            return response()->json(['type' => false, 'message' => 'number existed. try different']);
        }
    }

    public function showOperatorCallsView()
    {
        return view('crm.operator-calls');
    }
}
