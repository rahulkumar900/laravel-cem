<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationCategory extends Model
{
    use HasFactory;

    protected $table = 'relation_categories';
    protected $fillable = ["relation_name", "threshold_value", "status", "category"];

    public static function getAllRelationCategories()
    {
        return RelationCategory::where('status',1)->get();
    }

    public static function saveRelation($relation_name, $threas_val)
    {
        return RelationCategory::create([
            "relation_name"     =>      $relation_name,
            "threshold_value"   =>      $threas_val,
            "status"            =>      1
        ]);
    }

    public static function updateRecord($id, $relation_name, $threas_val)
    {
        return RelationCategory::where('id',$id)->update([
            "relation_name"     =>      $relation_name,
            "threshold_value"   =>      $threas_val,
        ]);
    }

    public static function deleteRecord($id)
    {
        return RelationCategory::where('id', $id)->update([
            "status"            =>      2
        ]);
    }
}
