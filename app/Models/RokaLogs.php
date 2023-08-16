<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RokaLogs extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "message", "user_data_id"];

    protected static function saveLogs($user_id, $profile_id, $comments)
    {
        return RokaLogs::create([
            "user_id"           =>      $user_id,
            "message"           =>      $comments,
            "user_data_id"      =>      $profile_id
        ]);
    }
}
