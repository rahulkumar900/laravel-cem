<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CheckIn extends Model
{
    use HasFactory;

    protected $table = 'checkIns';

    protected $fillable = ['id', 'temple_id', 'checkIn_date', 'checkIns', 'today_checkIn_count', 'created_at', 'updated_at'];

    protected static function searchRecord($temple_id, $date)
    {
        return CheckIn::where(['temple_id'=>$temple_id, 'checkIn_date'=>$date])->first();
    }

    protected static function createRecord($temple_id, $check_date, $check_in_array)
    {
        return CheckIn::create([
            'temple_id'             =>  $temple_id,
            'checkIn_date'          =>  $check_date,
            'checkIns'              =>  json_encode($check_in_array),
            'today_checkIn_count'   =>  1,
        ]);
    }

    protected static function updateRecord($check_in_array, $id)
    {
        return CheckIn::where('id', $id)->update([
            'checkIns'              =>  json_encode($check_in_array),
            'today_checkIn_count'   =>  DB::raw("today_checkIn_count+1"),
        ]);
    }

}
