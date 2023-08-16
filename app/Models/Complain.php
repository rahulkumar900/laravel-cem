<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    protected $table = "ticket_details";

    protected $fillable = ["subject", "user_id", "due_date", "mobile", "ticket_status", "ticket_priority", "ticket_no", "resolved_on", "assigned_to"];

    // comment relations
    public function comments(){
        return $this->hasMany(ComplainComments::class, 'ticket_id', 'id');
    }

    // user relations
    public function user_data()
    {
        return $this->hasOne(UserData::class, 'id', 'user_id');
    }

    // assigned user
    public function users()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }


    protected static function saveComplain($subject, $user_id, $mobile, $ticket_id)
    {
        return Complain::create([
            "subject"           =>      $subject,
            "user_id"           =>      $user_id,
            "due_date"          =>      date('Y-m-d H:i:s', strtotime("+2 days")),
            "mobile"            =>      $mobile,
            "ticket_no"         =>      $ticket_id
        ]);
    }

    protected static function getAllOpenTickets(){
        return Complain::with('comments')->join('user_data', 'user_data.id', 'ticket_details.user_id')->where('ticket_status', 'open')->whereRaw('assigned_to is null')->get(['ticket_details.*', 'user_data.name', 'user_data.photo']);
    }

    // get my open tickets
    protected static function getMyOpenTickets($user_id)
    {
        return Complain::with('comments')->join('user_data', 'user_data.id', 'ticket_details.user_id')->whereRaw("ticket_status IN ('open','pending')")->where(['assigned_to'=>$user_id])->get(['ticket_details.*', 'user_data.name', 'user_data.photo']);
    }

    // mark ticket as resolved
    protected static function markTicketAsResolved($ticket_id, $user_id, $ticket_status)
    {
        return Complain::where('id', $ticket_id)->update([
            "ticket_status"         =>          $ticket_status,
            "resolved_on"           =>          date('Y-m-d H:i:s'),
            "assigned_to"           =>          $user_id
        ]);
    }

    // assign ticket to  himself
    protected static function assignOpenTicket($user_id, $ticketid)
    {
        $check_assigned = Complain::where('id', $ticketid)->first();
        if(empty($check_assigned->user_id)){
            return Complain::where('id', $ticketid)->update([
                "assigned_to"       =>      $user_id,
                "ticket_status"     =>      'pending'
            ]);
        }else{
            $first_open = Complain::whereRaw('assigned_to is null')->first();
            return Complain::where('id', $first_open->id)->update([
                "assigned_to"       =>      $user_id,
                "ticket_status"     =>      'pending'
            ]);
        }
    }

    // get ticket count status
    protected static function ticektCountingAll()
    {
        $all_ticekts = Complain::count();

        $open_ticekts = Complain::where('ticket_status', 'open')->count();

        $pending_tickets = Complain::where('ticket_status','pending')->count();

        $closed_ticekts = Complain::where('ticket_status', 'closed')->count();

        $ret_array = array(
            'all_ticket'        =>      $all_ticekts,
            'open_ticekt'       =>      $open_ticekts,
            'pending_ticekt'    =>      $pending_tickets,
            'closed_ticket'     =>      $closed_ticekts
        );
        return $ret_array;
    }

    // get ticket count status
    protected static function ticektCountingMine($user_id)
    {
        $all_ticekts = Complain::where('assigned_to', $user_id)->count();

        $open_ticekts = Complain::where('assigned_to', $user_id)->where('ticket_status', 'open')->count();

        $pending_tickets = Complain::where('assigned_to', $user_id)->where('ticket_status', 'pending')->count();

        $closed_ticekts = Complain::where('assigned_to', $user_id)->where('ticket_status', 'closed')->count();

        $ret_array = array(
            'all_ticket'        =>      $all_ticekts,
            'open_ticekt'       =>      $open_ticekts,
            'pending_ticekt'    =>      $pending_tickets,
            'closed_ticket'     =>      $closed_ticekts
        );
        return $ret_array;
    }

    protected static function getAllTickets($record_type)
    {
        $record_set = '';
        $record_set = Complain::with('comments', 'user_data:id,name', 'users:id,name');
        if($record_type != 'all'){
            $record_set = $record_set->where('ticket_status', $record_type);
        }
        $record_set = $record_set->get();

        return $record_set;
    }

    protected static function getComplainByMobile($mobile)
    {
        return Complain::with('comments')->where("mobile", "LIKE", "%$mobile%")->get();
    }


}
