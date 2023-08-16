<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['department_name','is_active'];

    public static function getallDepartmentList(){
        $department_list = Department::where('is_active',1);
        return $department_list;
    }

    public function designation(){
        $this->hasMany(Designation::class);
    }

    public function userDepartments(){
        $this->hasMany(User::class);
    }
}
