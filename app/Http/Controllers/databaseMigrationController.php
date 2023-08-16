<?php

namespace App\Http\Controllers;

use App\Models\UserCompatblity;
use App\Models\UserData;
use App\Models\UserMatches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class databaseMigrationController extends Controller
{
    public function index()
    {
        return view('datamigrate');
    }
    public function update(Request $request)
    {
        $profile_data = DB::table('orders')->select('id_number', 'order_list')->where("created_at", '>', "2023-02-09 00:00:00'")->offset($request->offset)->take($request->limit)->get();
        $ids = '';
        foreach ($profile_data as $key) {
            $ids = explode(',', $key->order_list);
            $idA = UserData::select('id')->where('profile_id', $key->id_number)->first();
            if ($idA == null) {
                continue;
            }
            $idA = $idA['id'];
            // return $idA;
            foreach ($ids as $key2) {
                $key2 = trim($key2);
                $idB = UserData::select('id')->where('profile_id', $key2)->first();
                if ($idB == null) {
                    continue;
                }
                $idB = $idB['id'];
                $existing_record = UserMatches::where([
                    'userAid'           =>          $idA,
                    'userBid'           =>         $idB
                ])->get()->count();
                if (isset($idB) && $idB != '' && $idB != null && $idB != ' ') {
                    if ($existing_record != 0) {

                        UserMatches::whereRaw("userAid = $key->id_number and userBid = $key2")->update([
                            'userAid'           =>         $idA,
                            'userBid'           =>          $idB,
                            'status'            =>          'A',
                            'is_sent'           =>          1,
                        ]);
                    } else {
                        UserMatches::create(
                            [
                                'userAid'           =>         $idA,
                                'userBid'           =>          $idB,
                                'status'            =>          'A',
                                'is_sent'           =>          1,
                                'isContacted'       =>          0,
                                'isRejected'        =>          0
                            ]
                        );
                    };
                }
            }
        }
        return ["count" => count($profile_data), "test" => "hello"];
    }
    public function migrateDb(Request $request)
    {
        $data = DB::table('user_data as us')
            ->leftjoin('userCompatibilities as uc', 'us.id', '=', 'uc.user_data_id')->whereRaw('uc.user_data_id IS NULL and us.profile_id is not null')
            ->selectRaw('us.id,uc.user_data_id')->offset($request->offset)->take($request->limit)
            ->get();
        $data2 = [];
        foreach ($data as $key) {
            array_push($data2, ["user_data_id" => "$key->id", "daily_quota" => "-2"]);
        }
        UserCompatblity::insert($data2);
        return ["count" => count($data), "test" => "hello"];
    }
}
