<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthControlFailedtxn extends Model
{
    use HasFactory;
    protected $table = 'auth_control_failedtxn';
    protected $fillable = [
        'day',
        'team_leader',
        'created_at',
        'updated_at',
    ];

    public static function getControlList()
    {
        return AuthControlFailedtxn::get();
    }
}
