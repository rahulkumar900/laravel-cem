<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    protected $table= 'users_login';

    protected $fillable = ["id", "temple_id", "login_time", "logout_time", "login_status", "ip_address"];

    public function UserRelation(){
        return $this->hasOne(User::class,'temple_id', 'temple_id');
    }

    protected static function getAllLoginDetails($date)
    {
        return UserLogin::with('UserRelation')->whereRaw("date(login_time) = '$date'")->orderBy('temple_id')->get();
    }
}
