<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\AppointmentModel;
use App\Models\Lead;
use Illuminate\Http\Request;
use Auth;


class AppointmentModelController extends Controller
{
    /*
To save a new appointment for a lead follow-up by telesales, you will need to first gather all necessary information about the lead, including their contact information, their interests and needs, and any previous interactions they may have had with your company. Once you have this information, you can use it to personalize your approach and make the lead feel valued and heard. During the appointment, be sure to actively listen to the lead's concerns and offer solutions that are tailored to their specific situation. You may also want to use this opportunity to promote any current promotions or discounts that your company is offering. Finally, be sure to follow up with the lead after the appointment to thank them for their time and offer any additional assistance they may need.
    */
    public function createAppointment(AppointmentRequest $request)
    {
        $save_Record = AppointmentModel::createMeeting($request->user_data_id, $request->appoinemtn_with, Auth::user()->id, $request->appoinemtnt_date, $request->appoinemtnt_time, $request->note);

        $update_lead_table = Lead::where('user_data_id', $request->user_data_id)->update([
            'appointment_date'              =>      $request->appoinemtnt_date,
            'appointment_created_on'        =>      date('Y-m-d H:i:s'),
            'appointment_by'                =>      Auth::user()->name,
        ]);

        if($save_Record){
            return response()->json(['type'=>true, 'message'=>'appointment fixed successfully']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to fix appointment']);
        }

    }


    // my appointment list
    public function myAppointments()
    {
        $appointment_data = '';
/*
To obtain a complete list of upcoming appointments for telesales users, you should begin by accessing the appointment scheduling software. Once inside the software, navigate to the "Upcoming Appointments" section, where you will find a list of all scheduled appointments for telesales users. It is important to note that this list will only include appointments that have been scheduled in the system, so it is possible that some appointments may not be listed if they were scheduled outside of the software. If you need a complete list of all appointments, including those scheduled outside of the software, you may need to reach out to individual telesales users to gather this information.
*/
        $appointment_data = AppointmentModel::getMyAppointments(Auth::user()->id);
       // dd($appointment_data)->toArray();
        return view('crm.my-appointments', compact('appointment_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppointmentModel  $appointment
     * @return \Illuminate\Http\Response
     */
    public function myAppointmentNotes(AppointmentModel $appointment, Request $request)
    {
        $new_comment = $appointment->note.$request->note.';';
        $save_appointment = AppointmentModel::where('id', $appointment->id)->update([
            'note'      =>      $new_comment
        ]);
        if
        ($save_appointment){
            return response()->json(['type'=>true, 'message'=>'record updated successfully']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to udpate record']);
        }
    }
}
