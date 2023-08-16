<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsitePlan extends Model
{
    use HasFactory;

    protected $table = "websitePlans";

    protected $fillable = ["plan_name", "category", "plan_type", "content", "contacts", "amount", "discount", "advance_discount", "validity", "is_timer", "timing", "status"];

    // get website plans plans
    public static function getPlansByCategory($category)
    {
        return Cache::remember('crmplans',60, function() use($category){
            return WebsitePlan::join("plan_categories", "plan_categories.id", "websitePlans.category")->where(['category' => $category, 'plan_categories.status' => 1, "websitePlans.status"=>1])->get(['websitePlans.*']);
        });
    }

    // get website plan by id
    public static function getPlanById($plan_id)
    {
        return WebsitePlan::where('id',$plan_id)->first();
    }

    public static function getPlanList()
    {
        return WebsitePlan::join('plan_categories', 'plan_categories.id', 'websitePlans.category')->get(['websitePlans.*', 'plan_categories.category_name']);
    }
}
