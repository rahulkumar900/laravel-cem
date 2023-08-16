<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getAllUsers()
    {
        return User::where('active_status', 1);
    }

    public function userDepartment()
    {
        $this->hasOne(Department::class);
    }

    public function leads()
    {
        $this->hasMany(Lead::class, 'assign_to', 'temple_id');
    }

    public function teamleader()
    {
        $this->hasMany(TeamLeader::class, 'temple_id', 'temple_id');
    }

    public static function getTempleDetails($temple_id)
    {
        return User::where('temple_id', $temple_id)->first();
    }

    protected static function getUserDetailsByPhone($mobile)
    {
        return User::where('mobile', 'LIKE', "'%$mobile%'")->first();
    }

    protected static function updateToken($user_id, $token)
    {
        return User::where('id', $user_id)->update([
            'verifyToken'       =>      $token
        ]);
    }

    public function userLeads()
    {
        return $this->hasMany(Lead::class, 'temlple_id', 'assign_to');
    }

    protected static function getAccessableTempleIdes($user_id)
    {
        return User::with('teamleader')->where('temple_id', $user_id)->get();
    }
}
