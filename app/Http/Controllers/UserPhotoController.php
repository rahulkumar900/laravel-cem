<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use App\Models\userPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPhotoController extends Controller
{
    public function getUSerPics()
    {
        $user_photos = userPhoto::getUnApprovedUserPhoto();
        if ($user_photos) {
            $user_details = UserData::getDetailsByIdWPref($user_photos['user_id'][0]['user_data_id']);
            return response()->json(['user_photos' => $user_photos['user_photos'], 'user_data' => $user_details]);
        } else {
            return response()->json(['user_photos' => "", 'user_data' => ""]);
        }
    }

    public function actionOnImages(Request $request)
    {
        DB::beginTransaction();
        $update_replacement = "";
        if ($request->replacement_of && $request->replacement_of > 0) {
            $update_replacement = userPhoto::actionOnPics($request->replacement_of, 'replaced', Auth::user()->id);
        }
        $id = userPhoto::where('id', $request->image_index)->get(['user_data_id'])->first();
        UserData::where('id', $id->user_data_id)->update(['is_approved' => 1]);
        $update_status = userPhoto::actionOnPics($request->image_index, $request->action_type, Auth::user()->id);

        if ($update_status) {
            DB::commit();
            return response()->json(['type' => true, 'message' => 'photo ' . $request->action_type . ' successfully', 'id' => $id]);
        } else {
            DB::rollBack();
            return response()->json(['type' => false, 'message' => 'falied to ' . $request->action_type, 'id' => $id]);
        }
    }
}
