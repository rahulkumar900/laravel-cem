<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendende extends Model
{
    use HasFactory;

    protected $fillable = [ 'temple_id', 'check_in', 'time', 'status','check_out'];

    // create checkin
    public function addCheckIn($temple_id, $checkin_timestamp)
    {
        return Attendance::create([
            'temple_id'     =>      $temple_id,
            'check_in'      =>      $checkin_timestamp,
        ]);
    }

    // update logout timestamp and update time
    public function updateLogout($temple_id, $checkout, $total_time)
    {
        return Attendance::where()->upadte([
            'temple_id'         =>      $temple_id,
            'check_out'         =>      $checkout,
            'time'              =>      DB::raw("TIMESTAMPDIFF(MINITES, check_in, $checkout)"),
        ]);
    }
}
