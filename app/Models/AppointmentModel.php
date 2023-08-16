<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentModel extends Model
{
    use HasFactory;
    protected $table = 'appointments';

    protected $fillable = ["appoint_lead_id", "appoint_to", "appoint_by", "appointment_date", "appointment_time", "note", "meeting_done"];

    public function appointWith(){
        return $this->hasOne(User::class, 'id', 'appoint_to');
    }

    public function userDetails()
    {
        return $this->hasOne(UserData::class, 'id', 'appoint_lead_id');
    }

    public static function createMeeting($lead_id, $appoint_to, $appoint_by, $appoint_date, $appoint_time, $appoint_note)
    {
        return AppointmentModel::create([
            "appoint_lead_id"           =>      $lead_id,
            "appoint_to"                =>      $appoint_to,
            "appoint_by"                =>      $appoint_by,
            "appointment_date"          =>      $appoint_date,
            "appointment_time"          =>      $appoint_time,
            "note"                      =>      $appoint_note.';',
            "meeting_done"              =>      0
        ]);
    }

    public static function getMyAppointments($appoint_by)
    {
        $date_list = date('Y-m-d', strtotime("-5 days"));
        return AppointmentModel::with('appointWith','userDetails')->where(['appoint_by'=> $appoint_by])->where('appointment_date', '>', $date_list)->get();
    }
}
