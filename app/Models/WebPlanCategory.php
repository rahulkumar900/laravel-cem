<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPlanCategory extends Model
{
    use HasFactory;

    protected $table = "plan_categories";

    protected $fillable = ['category_name', 'status'];

    // get all plan categories
    public static function getAllPlanCategories()
    {
        return WebPlanCategory::get();
    }
}
