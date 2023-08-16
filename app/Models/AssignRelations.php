<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AssignRelations extends Model
{
    use HasFactory;

    protected $table= "lead_assign_relations";

    protected $fillable = ['category_id', 'temple_id', 'status'];

    // get assign relations according temple_id
    public static function getAssignedRelations($temple_id)
    {
        return DB::table('lead_assign_relations')
        ->join('relation_categories', 'relation_categories.id', 'lead_assign_relations.category_id')
        ->join('facebook_query', 'facebook_query.category_id','relation_categories.id')
        ->where('temple_id',$temple_id)->first();
    }

    public static function getAllAssignedRelation()
    {
        return AssignRelations::join('relation_categories', 'relation_categories.id', '=', 'lead_assign_relations.category_id')
        ->where('lead_assign_relations.status', 1)
        ->join('users', 'users.temple_id', '=', 'lead_assign_relations.temple_id')
        ->select('relation_categories.relation_name', 'users.name', 'lead_assign_relations.id as id', 'users.temple_id as user_id', 'relation_categories.id as lead_assign_id')
        ->get();
    }

    public static function saveAssignedRelation($cat_id, $temple_id, $status)
    {
        return AssignRelations::create([
            'category_id'       =>      $cat_id,
            'temple_id'         =>      $temple_id,
            'status'            =>      $status
        ]);
    }

    public static function updateAssignedRelation($cat_id, $temple_id, $status, $id)
    {
        return AssignRelations::where('id',$id)->update([
            'category_id'       =>      $cat_id,
            'temple_id'         =>      $temple_id,
            'status'            =>      $status
        ]);
    }

    public static function checkExistingRecord($temple_id, $cat_id)
    {
        return AssignRelations::where([
            'temple_id'         =>      $temple_id,
            'category_id'       =>      $cat_id,
            'status'            =>      1
        ])->first();
    }
}

