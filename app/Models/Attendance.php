<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'temple_id',
        'check_in',
        'time',
        'status',
        'check_out'
    ];

    public function attUser()
    {
        return Attendance::hasOne(User::class, 'temple_id', 'temple_id');
    }

    protected static function getAttSummeryReport($month, $year)
    {
        return Attendance::whereRaw("MONTH(attendances.created_at) = $month and YEAR(attendances.created_at)=$year")
        ->join('users', 'users.temple_id', 'attendances.temple_id')
        ->groupBy('attendances.temple_id', DB::raw("DATE(attendances.created_at)"))
        ->get([DB::raw("SUM(((check_out - check_in)/3600)) as hours"), "name","mobile"]);
        //return DB::select("SELECT attendances.*, SUM(((check_out - check_in)/60)) as minutes FROM attendances where MONTH(created_at) = $month and YEAR(created_at)=$year group by temple_id;");
    }
}
