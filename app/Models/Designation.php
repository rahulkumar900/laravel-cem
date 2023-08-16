<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $fillable = ['department_id','designation','is_active'];

    public function department(){
        return $this->hasOne(Department::class,'id','department_id');
    }


    protected static function getAllDesignations(){
        return Designation::with('department')->where(['is_active'=>1])->get();
    }
}
