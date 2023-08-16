<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('hrms.attendance-report');
    }


    public function getAttendanceReport(Request $request)
    {
        $month_no = $request->month_name;
        $year_no = $request->year_no;

        $att_report = '';

        //$att_report = Attendance::getAttSummeryReport($month_no, $year_no);
        $att_report = DB::select("SELECT SUM(((check_out - check_in)/3600)) as hour, `attendances`.`temple_id` from `attendances`
        where MONTH(attendances.created_at) = $month_no and YEAR(attendances.created_at)=$year_no
        group by `attendances`.`temple_id` ;");

        for ($i=0; $i < count($att_report); $i++) {
            $user_name = User::where('temple_id', $att_report[$i]->temple_id)->first();

            $att_report[$i]->temple_name = $user_name->name;
            $att_report[$i]->mobile = $user_name->mobile;
        }

        return response()->json($att_report);
    }

    public function getDetailedAttendanceReport()
    {
       return view('hrms.attendance-detailed-report');
    }

    public function getDetailedAttendanceData(Request $request)
    {
        $month_no = $request->month_no;
        $year_no = $request->year_no;
        $temple_id = $request->temple_id;
        $att_report = '';
        $att_report = DB::select("SELECT SUM(((check_out - check_in)/3600)) as hour, DATE(created_at) as att_date from `attendances`
        where MONTH(attendances.created_at) = $month_no and YEAR(attendances.created_at)=$year_no AND `attendances`.`temple_id` = $temple_id
        group by DATE(created_at) ORDER BY DATE(created_at) ;");
        return response()->json($att_report);
    }
}
