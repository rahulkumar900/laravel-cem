<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\TeamLeader;
use App\Models\TransferLead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamLeaderController extends Controller
{
    public function index()
    {
        return view('teamleader.transfer-leads');
    }

    // accessable temple ides
    public function getAccessableTemples()
    {
        $accessable_temple = '';
        // $accessable_temple = User::getAccessableTempleIdes(Auth::user()->temple_id);
        $accessable_temple = TeamLeader::getAccessableTempleIdes(Auth::user()->temple_id);
        return response()->json($accessable_temple);
    }

    public function getCountOfLeads(Request $request)
    {
        $count_leads = Lead::getAllLeadscounts($request->temple_id);
        return response()->json($count_leads);
    }

    public function transferLeads(Request $request)
    {
        DB::beginTransaction();
        $lead_details = Lead::getLimitedLeads($request->transfer_from, $request->no_of_leads);

        $leads_ids = array_column($lead_details->toArray(), 'id');

        // update lead assign to
        $update_laeds = Lead::updateAssignTo($leads_ids, $request->transfer_to);
        if ($update_laeds) {
            // save into transfer lead record
            $save_record = TransferLead::saveRecord($request->transfer_from, $request->transfer_to, Auth::user()->temple_id, json_encode($leads_ids));
            if ($save_record) {
                DB::commit();
                return response()->json(['type' => true, 'message' => 'lead transfered successfully']);
            } else {
                DB::rollBack();
                return response()->json(['type' => false, 'message' => 'failed to transfer']);
            }
        }
    }

    public function manageTeamLeader()
    {
        return view('admin.manage-teamleaders');
    }

    public function leadTeamLeaders()
    {
        $team_leader = "";
        $team_leader = User::where(['role' => 7, 'active_status' => 1])->get(['id', 'name', 'temple_id']);
        return response()->json($team_leader);
    }

    public function getTeamList(Request $request)
    {
        $team_list = "";
        $my_team_list = "";
        $team_ids = "";
        $my_team_ids = "";

        $team_ids = array_column(TeamLeader::get()->toArray(), "access_temple_id");

        if (Auth::user()->role == 9) {
            $my_team_ids = array_column(TeamLeader::where(['temple_id' => $request->temple_id])->get()->toArray(), "access_temple_id");
        } else {
            $my_team_ids = array_column(TeamLeader::where(['temple_id' => Auth::user()->temnple_id])->get()->toArray(), "access_temple_id");
        }

        $team_list = User::whereNotIn('temple_id', $team_ids)->where(['active_status' => 1, 'matchmaker_is_user' => 'no'])->get(['id', 'temple_id', 'name', 'mobile']);

        $my_team_list =  User::whereIn('temple_id', $my_team_ids)->where('active_status', 1)->get(['id', 'temple_id', 'name', 'mobile']);

        return response()->json(['my_team' => $my_team_list, 'team_list' => $team_list]);
    }

    public function manageTeamleraderList(Request $request)
    {
        $temple_id = "";
        if (Auth::user()->role == 9) {
            $temple_id = $request->temple_id;
        } else {
            $temple_id = Auth::user()->temple_id;
        }

        if ($request->add_team == 1) {
            DB::beginTransaction();
            $create_team = "";
            if (!empty($request->open_team_ids)) {
                for ($i = 0; $i < count($request->open_team_ids); $i++) {
                    $create_team = TeamLeader::create([
                        'temple_id'         =>      $temple_id,
                        'access_temple_id'  =>      $request->open_team_ids[$i]
                    ]);
                }
                if ($create_team) {
                    DB::commit();
                    return response()->json(['type' => true, 'message' => 'member added']);
                } else {
                    DB::rollBack();
                    return response()->json(['type' => false, 'message' => 'failed to add']);
                }
            } else {
                return response()->json(['type' => false, 'message' => 'no id selected']);
            }
        } else if ($request->remove_team == 1) {
            $delete_team = "";
            DB::beginTransaction();
            if ($request->my_team_ids) {
                for ($j = 0; $j < count($request->my_team_ids); $j++) {
                    $delete_team = TeamLeader::where([
                        'temple_id'         =>      $temple_id,
                        'access_temple_id'  =>      $request->my_team_ids[$j]
                    ])->delete();
                }
                if ($delete_team) {
                    DB::commit();
                    return response()->json(['type' => true, 'message' => 'member removed']);
                } else {
                    DB::rollBack();
                    return response()->json(['type' => false, 'message' => 'failed to remove']);
                }
            } else {
                return response()->json(['type' => false, 'message' => 'no id selected']);
            }
        }
    }
}
