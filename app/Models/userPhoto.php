<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userPhoto extends Model
{
    use HasFactory;

    protected $fillable = ["photo_url", "user_data_id", "photo_status", "approved_by", "approved_at", "uploaded_by"];

    public function userPhotoTempleRelation()
    {
        return $this->hasOne(User::class);
    }
    public function user()
    {
        return $this->belongsTo(UserData::class);
    }
    public function userDataRelation()
    {
        return $this->hasOne(UserData::class);
    }

    protected static function getAllPhotos($user_id)
    {
        return userPhoto::where("user_data_id", $user_id)->get();
    }

    private static function getUserIdUnapproved()
    {
        return userPhoto::where('photo_status', 'pending')->orderBy('id', 'desc')->take(1)->get(['user_data_id']);
    }

    public static function getUnApprovedUserPhoto()
    {
        $user_id = self::getUserIdUnapproved()->toArray();
        if (count($user_id) > 0) {
            $user_photos = userPhoto::where('user_data_id', $user_id[0]['user_data_id'])->get();
            return array(
                'user_id'       =>      $user_id,
                'user_photos'   =>      $user_photos
            );
        } else {
            return false;
        }
    }

    protected static function actionOnPics($image_id, $action, $action_by)
    {
        return userPhoto::where('id', $image_id)->update([
            "photo_status"      =>      $action,
            "approved_by"       =>      $action_by,
            "approved_at"       =>      date('Y-m-d H:i:s')
        ]);
    }

    protected static function getImageDetails($image_id)
    {
        return userPhoto::where('id', $image_id)->first();
    }

    public static function saveUserImage($photo_url, $user_id, $photo_status, $approved_by, $uploaded_by)
    {
        return userPhoto::insertGetId([
            "photo_url"         =>      $photo_url,
            "user_data_id"      =>      $user_id,
            "photo_status"      =>      $photo_status,
            "approved_by"       =>      $approved_by,
            "approved_at"       =>      date("Y-m-d H:i:s"),
            "uploaded_by"       =>      $uploaded_by,
            "updated_at"       =>      date("Y-m-d h:s:i")
        ]);
    }
}
