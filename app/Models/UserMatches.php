<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UserMatches extends Model
{
    use HasFactory;

    protected $table = "userMatches";

    protected $fillable = ['userAid', 'userBid', 'status', 'user_id', 'timestamp', 'platformId', 'isLiked', 'isRejected', 'isContacted', 'isMutualLike', 'is_sent', 'isPostponed', 'audioMessage', 'isProfileViewed', 'profileSectionId', 'isFreeProfile', 'isVirtualLike', 'expiryTimestamp'];

    // insert data into user matches when profile send with status A
    protected static function sendProfileList($user_id, $user_profile_sent)
    {
        $existing_record = UserMatches::where([
            'userAid'           =>          $user_id,
            'userBid'           =>          $user_profile_sent
        ])->get()->count();
        if ($existing_record != 0) {
            return false;
        }
        return UserMatches::create(
            [
                'userAid'           =>          $user_id,
                'userBid'           =>          $user_profile_sent,
                'status'            =>          'A',
                'is_sent'           =>          1,
                'isContacted'       =>          0,
                'isRejected'        =>          0
            ]
        );
    }

    // sent profile list
    protected static function sentProfileList($user_id)
    {
        return UserMatches::where('userAid', $user_id)->where('userBid', '!=', null)->get(['userBid']);
    }

    // update user matches
    protected static function updateUserMatches($user_a_id, $user_b_id, $action)
    {
        $column = '';
        $column_sec = "";
        if ($action == 1) {
            $column = 'isContacted';
            $column_sec = "isLiked";
        } else {
            $column = 'isRejected';
            $column_sec = "isProfileViewed";
        }

        // check existing data
        // $check_existing_record = UserMatches::where(['userAid' => $user_a_id, 'userBid' => $user_b_id])->first();
        // if(empty($check_existing_record)){
        //     return UserMatches::where(['userAid' => $user_a_id, 'userBid' => $user_b_id])->update([
        //         $column         =>          1,
        //         $column_sec     =>          1
        //     ]);
        // }else{
        //     return UserMatches::create([
        //         'userAid'       =>          $user_a_id,
        //         'userBid'       =>          $user_b_id,
        //         $column         =>          1,
        //         $column_sec     =>          1
        //     ]);
        // }

        // if($check_existing_record){
        //         return UserMatches::where(['userAid' => $user_a_id, 'userBid' => $user_b_id])->update([
        //             $column         =>          1,
        //             $column_sec     =>          1
        //         ]);
        // }

        return UserMatches::updateOrCreate(
            [
                'userAid' => $user_a_id,
                'userBid' => $user_b_id
            ],
            [
                'userAid'       =>  $user_a_id,
                'userBid'       =>  $user_b_id,
                $column         =>  1,
                $column_sec     =>  1
            ]
        );
    }

    // get all sent profile list
    protected static function getAllSentProfileList($user_id)
    {
        return UserMatches::join('user_data', 'user_data.id', 'userMatches.userBid')->where(['userAid' => $user_id, 'isContacted' => 0, 'isRejected' => 0, 'is_sent' => 1])->orderBy('userMatches.created_at', 'desc')->get(['name', 'photo', 'caste', 'marital_status', 'user_data.id as user_id', 'user_mobile']);
    }

    protected static function getAllSentProfilesList($user_id, $start_date, $end_date)
    {
        return UserMatches::join('user_data', 'user_data.id', 'userMatches.userBid')
            ->where(['userAid' => $user_id, 'is_sent' => 1])
            ->whereBetween('userMatches.created_at', [$start_date, $end_date])
            ->get(['name', 'photo', 'caste', 'userMatches.created_at', 'marital_status', 'user_data.id as user_id', 'user_data.user_mobile as mobile']);
    }

    protected static function getAllRejectedProfileList($user_id, $start_date, $end_date)
    {
        return UserMatches::join('user_data', 'user_data.id', 'userMatches.userBid')
            ->where(['userAid' => $user_id, 'is_sent' => 1, 'isRejected' => 1])
            ->whereBetween('userMatches.updated_at', [$start_date, $end_date])
            ->get(['name', 'photo', 'caste', 'userMatches.updated_at', 'marital_status', 'user_data.id as user_id', 'isLiked', 'isRejected', 'isMutualLike', 'userMatches.created_at', 'userMatches.updated_at', 'user_data.user_mobile as mobile']);
    }


    // get all contacted users
    protected static function getAllcontactedUsers($user_id)
    {
        return UserMatches::join('user_data', 'user_data.id', 'userMatches.userBid')->where(['userAid' => $user_id, 'isContacted' => 1])->orderBy('userMatches.created_at', 'desc')->get(['name', 'photo', 'caste', 'marital_status', 'user_data.id as user_id']);
    }

    public static function getAllPremiumLikes($user_id)
    {
        return UserMatches::join('user_data', 'userMatches.userBid', 'user_data.id')
            ->leftJoin("premium_meetings", function ($join) {
                $join->on("premium_meetings.user_id", "userMatches.userAId");
                $join->on("premium_meetings.matched_id", "userMatches.userBid");
            })
            //->distinct()
            ->whereRaw("premium_meetings.status != 3")
            ->where(['userAid' => $user_id, 'isLiked' => 1])

            //->orderBy('userMatches.created_at', 'desc')
            ->get([
                'user_data.name', 'photo', 'caste', 'marital_status',
                'user_data.id as user_id', 'userBId as matched_id', "premium_meetings.status",
                "premium_meetings.comments"
            ]);
    }

    public static function overallYesPendingDatas($user_id)
    {
        return UserMatches::join('user_data', 'userMatches.userBid', 'user_data.id')
            ->leftJoin("premium_meetings", function ($join) {
                $join->on("premium_meetings.user_id", "userMatches.userAId");
            })
            ->whereRaw("premium_meetings.status = 3")
            ->where(['userAid' => $user_id])
            ->orderBy('userMatches.created_at', 'desc')
            ->get([
                'user_data.name', 'photo', 'caste', 'marital_status',
                'user_data.id as user_id', 'userBId as matched_id', "premium_meetings.status",
                "premium_meetings.comments"
            ]);
    }

    public static function pdateUserMAtchTableAction($usera_id, $userb_id)
    {
        return UserMatches::updateOrCreate([
            'userAid'       =>      $usera_id,
            'userBid'       =>      $userb_id,
        ], [
            'userAid'       =>      $usera_id,
            'userBid'       =>      $userb_id,
            'timestamp'     =>      strtotime(date('Y-m-d H:i:s')),
            'platformId'    =>      1,
            'isLiked'       =>      1,
            'isRejected'    =>      0,
            'isContacted'   =>      0,
            'isMutualLike'  =>      0,
        ]);
    }
}
