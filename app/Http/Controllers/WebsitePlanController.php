<?php

namespace App\Http\Controllers;

use App\Models\WebPlanCategory;
use App\Models\WebsitePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsitePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPlanDetailsById(Request $request)
    {
        $plan_detail = DB::table('contents')->where('id', $request->plan_id)->first();
        if(empty($plan_detail)){
            $plan_detail = DB::table('contents')->where("type","LIKE" ,"%$request->plan_name%")->first();
        }
        return response()->json($plan_detail);
    }


    public function showPage()
    {
        return view('admin.manage-websiteplan');
    }

    /**
     * get all created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCRMPlans(Request $request)
    {
        $crm_plans = WebsitePlan::getPlansByCategory(18);
        return response()->json($crm_plans);
    }


    public function show()
    {
        $webPlans = WebsitePlan::getPlanList();
        return response()->json($webPlans);
    }


    public function showCategory()
    {
        $plan_cats = WebPlanCategory::getAllPlanCategories();
        return response()->json($plan_cats);
    }


    public function manageCategory(Request $request)
    {
        $cat_status = "";

        if ($request->category_status==1) {
            $cat_status = 1;
        }
        else{
            $cat_status = 0;
        }

        if ($request->category_id_fet>0) {
            $create_cat = WebPlanCategory::where('id', $request->category_id_fet)->update([
                "category_name"     =>      $request->category_name_filled,
                "status"            =>      $cat_status
            ]);
            if ($create_cat) {
                return response()->json(['type' => true, 'message' => 'record updated']);
            }
        }else{
            //
            $create_cat = WebPlanCategory::create([
                "category_name"     =>      $request->category_name_filled,
                "status"            =>      $cat_status
            ]);
            if ($create_cat) {
                return response()->json(['type'=>true, 'message'=>'record added']);
            }
        }
    }

    public function getPlantByCategory(Request $request)
    {
        $plan_detail = WebsitePlan::getPlanById($request->plan_id);
        return response()->json($plan_detail);
    }


    public function postwebsitePllanData(Request $request)
    {
        $status_plan = 0;
        if ($request->plan_status==1) {
            $status_plan = 1;
        }

        if ($request->web_plan_id>0) {
            $create_plan = WebsitePlan::where('id', $request->web_plan_id)->update([
                'plan_name'             =>      $request->plan_name,
                'category'              =>      $request->web_plan_category,
                'plan_type'             =>      $request->plan_type,
                'content'               =>      $request->plan_content,
                'contacts'              =>      $request->plan_credit,
                'amount'                =>      $request->plan_amount,
                'discount'              =>      $request->plan_discount,
                'advance_discount'      =>      $request->advance_dis_amount,
                'validity'              =>      $request->plan_validity,
                'status'                =>      1
            ]);

            if ($create_plan) {
                return response()->json(['type' => true, 'message' => 'record updated']);
            }
        }else{
            $create_plan = WebsitePlan::create([
                'plan_name'             =>      $request->plan_name,
                'category'              =>      $request->web_plan_category,
                'plan_type'             =>      $request->plan_type,
                'content'               =>      $request->plan_content,
                'contacts'              =>      $request->plan_credit,
                'amount'                =>      $request->plan_amount,
                'discount'              =>      $request->plan_discount,
                'advance_discount'      =>      $request->advance_dis_amount,
                'validity'              =>      $request->plan_validity,
                'status'                =>      1
            ]);

            if ($create_plan) {
                return response()->json(['type'=>true, 'message'=>'record added']);
            }
        }
    }
}
