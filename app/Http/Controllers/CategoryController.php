<?php

namespace App\Http\Controllers;

use App\Models\AssignRelations;
use App\Models\FacebookQuery;
use App\Models\RelationCategory;
use App\Models\WebsiteQuery;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        return view('admin.category-relations');
    }

    // get relation category
    public function getRelationCategory()
    {
        $all_categories = RelationCategory::getAllRelationCategories();
        return response()->json($all_categories);
    }

    // save category relation
    public function saveRelationCategory(Request $request)
    {
        $retrn_data = array();

        if ($request->level_id > 0) {
            // update redord
            $create_record = RelationCategory::updateRecord($request->level_id, $request->caregory_name, $request->threshold_value);
            if ($create_record) {
                $retrn_data = array(
                    "type"      =>      true,
                    "message"   =>      "record added successfully"
                );
            } else {
                $retrn_data = array(
                    "type"      =>      false,
                    "message"   =>      "failed to add record"
                );
            }
        } else {
            // create new record
            $create_record = RelationCategory::saveRelation($request->caregory_name, $request->threshold_value);
            if ($create_record) {
                $retrn_data = array(
                    "type"      =>      true,
                    "message"   =>      "record added successfully"
                );
            } else {
                $retrn_data = array(
                    "type"      =>      false,
                    "message"   =>      "failed to add record"
                );
            }
        }
        return response()->json($retrn_data);
    }

    public function deleteRelationCategory(Request $request)
    {
        $retrn_data = array();
        $create_record = RelationCategory::deleteRecord($request->cat_id);
        if ($create_record) {
            $retrn_data = array(
                "type"      =>      true,
                "message"   =>      "record deleted successfully"
            );
        } else {
            $retrn_data = array(
                "type"      =>      false,
                "message"   =>      "failed to delete record"
            );
        }
        return response()->json($retrn_data);
    }

    // get relation category assign temple
    public function allTempleRelation()
    {
        $temple = AssignRelations::getAllAssignedRelation();
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($temple),
            "totaldisplayrecords" => count($temple),
            "data" => $temple
        );

        return response()->json($dataset);
    }

    public function saveAssignRelation(Request $request)
    {
        $return_array = array();
        $check_record = AssignRelations::checkExistingRecord($request->temple_select, $request->relation_select);
        if (!empty($check_record)) {
            $return_array = array(
                "type"      =>      false,
                "message"   =>      "record existed with same data"
            );
        } else {
            if ($request->assign_relation_id > 0) {
                // update record
                $update_relation = AssignRelations::updateAssignedRelation($request->relation_select, $request->temple_select, 1, $request->assign_relation_id);
                if ($update_relation) {
                    $return_array = array(
                        "type"      =>      true,
                        "message"   =>      "record updated successfully"
                    );
                } else {
                    $return_array = array(
                        "type"      =>      false,
                        "message"   =>      "failed to update record"
                    );
                }
            } else {
                // create record
                $save_record = AssignRelations::saveAssignedRelation($request->relation_select, $request->temple_select, 1);
                if ($save_record) {
                    $return_array = array(
                        "type"      =>      true,
                        "message"   =>      "record saved successfully"
                    );
                } else {
                    $return_array = array(
                        "type"      =>      false,
                        "message"   =>      "failed to save record"
                    );
                }
            }
        }
        return response()->json($return_array);
    }

    public function deleteRelaion(Request $request)
    {
        $update_relation = AssignRelations::updateAssignedRelation($request->relation_select, $request->temple_select, 2, $request->assign_relation_id);
        if ($update_relation) {
            $return_array = array(
                "type"      =>      true,
                "message"   =>      "record delated successfully"
            );
        } else {
            $return_array = array(
                "type"      =>      false,
                "message"   =>      "failed to delated record"
            );
        }
    }

    public function getFacebookQueryData()
    {
        return response()->json(FacebookQuery::getFacebookQuery());
    }

    public function getWebsiteQueryData()
    {
        return response()->json(WebsiteQuery::getWebQuery());
    }

    public function editFacebookQuery(Request $request)
    {
        $facebook_quey = FacebookQuery::editFacebookQuery($request->fb_query_id, $request->call_count_query, $request->days_back_query, $request->category_name_query);
        if ($facebook_quey) {
           return response()->json(['type'=>true,'message'=>'record update']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to update']);
        }
    }

    public function editWebQuery(Request $request)
    {
       //dd($request->all());
        $facebook_quey = WebsiteQuery::editWebsiteQuery($request->web_query_id, $request->call_count_query, $request->days_back_query, $request->category_name_query);
        if ($facebook_quey) {
            return response()->json(['type' => true, 'message' => 'record update']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to update']);
        }
    }

}
