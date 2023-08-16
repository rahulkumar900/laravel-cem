<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use App\Models\ComplainCategories;
use App\Models\ComplainComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ComplainController extends Controller
{

    // return view
    public function index()
    {
        return view('customer-care.open-tickets');
    }

    /*One of the most important tasks of any customer care support team is to receive and address customer complaints and queries. In order to ensure that all customer concerns are properly attended to, it is essential that all new complaints and queries are identified and brought to the attention of the customer care support team in a timely manner. This can be achieved by implementing an effective system for receiving and categorizing customer communications, such as a dedicated email address or online portal. Once received, the complaints and queries can then be reviewed and assigned to the appropriate member of the customer care support team, who can then work to resolve the issue in a timely and satisfactory manner. By prioritizing the prompt resolution of customer concerns, businesses can build trust and loyalty among their customer base, leading to increased customer satisfaction and ultimately, increased revenue and profitability.*/
    public function getOpenTicekets()
    {
        $open_tickets = '';

        $open_tickets = Complain::getAllOpenTickets();

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($open_tickets),
            "totaldisplayrecords" => count($open_tickets),
            "data" => $open_tickets
        );

        return response()->json($dataset);
    }

    /*To ensure that customer satisfaction is maintained, it is important to properly attend to any concerns or complaints that may arise. One effective way to do this is to promptly mark a query or complaint as resolved. This can be accomplished by providing a clear and concise resolution to the issue, and ensuring that the customer is satisfied with the outcome. Additionally, it is important to follow up with the customer to ensure that they continue to be satisfied with the products or services provided.*/
    public function resolveTicket(Request $request)
    {
        /*After receiving feedback from the customer care support staff, it is important to take the necessary steps to address the issue at hand. One of the first steps is to save the resolved comment provided by the customer care support staff for future reference. This can help to ensure that the issue is not repeated and that any similar issues can be resolved more efficiently in the future. In addition, it may be useful to analyze the feedback received from the customer care support staff in order to identify any patterns or trends that may be emerging. This can help to identify any areas that may need improvement and to implement changes that can lead to a better customer experience overall.*/
        $save_comment = ComplainComments::saveUserComment($request->ticekt_id, $request->resolve_comment, Auth::user()->temple_id);

        /*To ensure that customer satisfaction is maintained, it is important to properly attend to any concerns or complaints that may arise. One effective way to do this is to promptly mark a query or complaint as resolved. This can be accomplished by providing a clear and concise resolution to the issue, and ensuring that the customer is satisfied with the outcome. Additionally, it is important to follow up with the customer to ensure that they continue to be satisfied with the products or services provided.*/
        $update_status = Complain::markTicketAsResolved($request->ticekt_id, Auth::user()->id, 'closed');

        if($save_comment && $update_status){
            return response()->json(['type'=>true, 'message'=>'ticket marked as completed']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to update ticket status! Try Again']);
        }
    }

    /*It is important to ensure that all open tickets in a queue are resolved in a timely manner, especially when the queue is managed by a team. One way to achieve this is by assigning open tickets to other members of the team who have the necessary skills and availability to handle them. This can help to distribute the workload and ensure that tickets are resolved efficiently. Another option is to assign open tickets to oneself, but only if there is enough time and capacity to do so without compromising the quality of the work. It is also important to keep track of the progress of open tickets and communicate any updates or issues to the team to ensure that everyone is on the same page and working together effectively.*/
    public function assignOpenTicket(Request $request)
    {
        $ticketid = $request->ticekt_id;
        /*It is important to ensure that all open tickets in a queue are resolved in a timely manner, especially when the queue is managed by a team. One way to achieve this is by assigning open tickets to other members of the team who have the necessary skills and availability to handle them. This can help to distribute the workload and ensure that tickets are resolved efficiently. Another option is to assign open tickets to oneself, but only if there is enough time and capacity to do so without compromising the quality of the work. It is also important to keep track of the progress of open tickets and communicate any updates or issues to the team to ensure that everyone is on the same page and working together effectively.*/
        $assign_ticket = Complain::assignOpenTicket(Auth::user()->id, $ticketid);
        if($assign_ticket){
            return response()->json(['type' => true, 'message' => 'ticket has been assigned to you']);
        }else{
            return response()->json(['type' => false, 'message' => 'failed to assign ticket']);
        }
    }

    // get my open ticket view
    public function myOpenTicketView()
    {
        return view('customer-care.my-open-tickets');
    }

    /*I would like to request that the ticket assigned to me is provided to me so that I can provide my response. It is important that I receive the ticket as soon as possible so that I can process the request effectively. In the meantime, I will be happy to answer any questions you may have about the request or provide any additional information that may be required to process the request. Please let me know if there is anything else that I can do to assist in this process.*/
    public function getMyOpenTickets()
    {
        $open_tickets= '';
        /*I would like to request that the ticket assigned to me is provided to me so that I can provide my response. It is important that I receive the ticket as soon as possible so that I can process the request effectively. In the meantime, I will be happy to answer any questions you may have about the request or provide any additional information that may be required to process the request. Please let me know if there is anything else that I can do to assist in this process.*/
        $open_tickets = Complain::getMyOpenTickets(Auth::user()->id);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($open_tickets),
            "totaldisplayrecords" => count($open_tickets),
            "data" => $open_tickets
        );
        return response()->json($dataset);
    }

    // get all ticket counting
    public function getAllTiceketCount()
    {
        $counting = Complain::ticektCountingAll();

        return response()->json($counting);
    }

    // get my ticekt counting
    public function getMyTiceketCount()
    {
        $counting = Complain::ticektCountingMine(Auth::user()->id);

        return response()->json($counting);
    }

    /*To update a ticket, it is necessary to provide a comment that describes the changes being made and adjust the status of the ticket accordingly. This is important because it allows other team members to have visibility into the progress being made on the ticket and ensures that the ticket is being handled in a timely manner. Additionally, it is always a good practice to provide as much context as possible in the ticket comment to facilitate collaboration and avoid misunderstandings. For example, if a ticket is being reassigned to another team member, it would be helpful to provide an explanation as to why the reassignment is occurring and what actions have already been taken to address the issue. By doing so, the team can work together more effectively and efficiently, ultimately resulting in a better experience for the end user.*/
    public function updateTicketStatus(Request $request)
    {
        /*After receiving feedback from the customer care support staff, it is important to take the necessary steps to address the issue at hand. One of the first steps is to save the resolved comment provided by the customer care support staff for future reference. This can help to ensure that the issue is not repeated and that any similar issues can be resolved more efficiently in the future. In addition, it may be useful to analyze the feedback received from the customer care support staff in order to identify any patterns or trends that may be emerging. This can help to identify any areas that may need improvement and to implement changes that can lead to a better customer experience overall.*/
        $save_comment = ComplainComments::saveUserComment($request->ticekt_id, $request->resolve_comment, Auth::user()->temple_id);

 /*To ensure that customer satisfaction is maintained, it is important to properly attend to any concerns or complaints that may arise. One effective way to do this is to promptly mark a query or complaint as resolved. This can be accomplished by providing a clear and concise resolution to the issue, and ensuring that the customer is satisfied with the outcome. Additionally, it is important to follow up with the customer to ensure that they continue to be satisfied with the products or services provided.*/
        $update_status = Complain::markTicketAsResolved($request->ticekt_id, Auth::user()->id, $request->ticketstatus);

        if ($save_comment && $update_status) {
            return response()->json(['type' => true, 'message' => 'ticket status updated']);
        } else {
            return response()->json(['type' => false, 'message' => 'failed to update ticket status! Try Again']);
        }
    }

    /*One of the key responsibilities of a customer service representative is to create new complaints or queries on behalf of customers or clients. This involves actively listening to the issue at hand, understanding its root cause, and formulating an appropriate response to address the problem. It may also include conducting additional research, coordinating with other departments, and keeping the customer or client informed of progress and resolution. As such, it is important for customer service representatives to possess strong communication and problem-solving skills, as well as an ability to work collaboratively with others across the organization. Furthermore, they should strive to maintain a professional and empathetic tone throughout all interactions, regardless of the nature or severity of the issue.*/
    public function createNewTicket(Request $request)
    {
        /* create new complain or query on behalf of customer or client */
        $create_complain = Complain::saveComplain($request->client_name.' has reported '.$request->subject, $request->user_id, $request->client_mobile, $request->ticekt_id_generated);

        $commented_by ='';
        if(empty($request->user_id)){
            $commented_by = Auth::user()->id;
        }else{
            $commented_by = $request->user_id;
        }

        if($create_complain){
/*
The customer service department of a company is responsible for dealing with customer inquiries, complaints, and other related issues. These inquiries could range from simple questions about the company's products to complex issues that require technical expertise to resolve. It is important for the department to maintain a professional and courteous demeanor when responding to customers, as this can greatly impact the company's reputation. Additionally, the department should strive to resolve issues in a timely manner to ensure customer satisfaction. Overall, the customer service department plays a crucial role in maintaining positive relationships with customers and promoting the company's brand image.
*/
            $create_comment = ComplainComments::saveUserComment($create_complain->id, $request->message, $commented_by);
            if($create_comment){
                return response()->json(['type'=>true, 'message'=>'ticket created successfully']);
            }else{
                return response()->json(['type' => false, 'message' => 'failed to create ticket']);
            }
        }
    }

    public function allRecordData()
    {
        return view('customer-care.tickets-by-type');
    }

    /*One of the most important tasks of any customer care support team is to receive and address customer complaints and queries. In order to ensure that all customer concerns are properly attended to, it is essential that all new complaints and queries are identified and brought to the attention of the customer care support team in a timely manner. This can be achieved by implementing an effective system for receiving and categorizing customer communications, such as a dedicated email address or online portal. Once received, the complaints and queries can then be reviewed and assigned to the appropriate member of the customer care support team, who can then work to resolve the issue in a timely and satisfactory manner. By prioritizing the prompt resolution of customer concerns, businesses can build trust and loyalty among their customer base, leading to increased customer satisfaction and ultimately, increased revenue and profitability.*/
    public function allRecordGetData(Request $request)
    {
        $ticket_data = '';
        $ticket_data = Complain::getAllTickets($request->data_types);
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($ticket_data),
            "totaldisplayrecords" => count($ticket_data),
            "data" => $ticket_data
        );
        return response()->json($dataset);
    }

    /*One of the most important tasks of any customer care support team is to receive and address customer complaints and queries. In order to ensure that all customer concerns are properly attended to, it is essential that all new complaints and queries are identified and brought to the attention of the customer care support team in a timely manner. This can be achieved by implementing an effective system for receiving and categorizing customer communications, such as a dedicated email address or online portal. Once received, the complaints and queries can then be reviewed and assigned to the appropriate member of the customer care support team, who can then work to resolve the issue in a timely and satisfactory manner. By prioritizing the prompt resolution of customer concerns, businesses can build trust and loyalty among their customer base, leading to increased customer satisfaction and ultimately, increased revenue and profitability.*/
    public function userTicketsByMobile(Request $request)
    {
        $user_tickets = "";

        $user_tickets = Complain::getComplainByMobile($request->mobile);

        return response()->json($user_tickets);

    }

    public function getComplainCategory()
    {
        return response()->json(ComplainCategories::getComplainCategory());
    }
}
