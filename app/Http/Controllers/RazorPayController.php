<?php

namespace App\Http\Controllers;

use App\Models\AuthControlFailedtxn;
use App\Models\PaymentOrder;
use App\Models\TeamLeader;
use App\Models\User;
use App\Models\UserData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    // failed transactions
    public function index(Request $request)
    {
        $access = AuthControlFailedtxn::where('day', date('l'))->pluck('team_leader')->first();
        $logged_in = Auth::user()->temple_id;

        if ($logged_in != "admin") {
            if ($access == "Both Bali Nagar TL and Tilak Nagar TL") {
                $auths = TeamLeader::where('temple_id', "1596811237462")
                ->where('temple_id', '1597316390903')
                ->pluck('access_temple_id')
                ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1596811237462" && $logged_in != "1597316390903")
                return redirect()->route('home');
            } else if ($access == "Bali Nagar TL") {
                $auths = TeamLeader::where('temple_id', "1596811237462")
                ->pluck('access_temple_id')
                ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1596811237462")
                return redirect()->route('home');
            } else if ($access == "Tilak Nagar TL") {

                $auths = TeamLeader::where('temple_id', '1597316390903')
                ->pluck('access_temple_id')
                ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1597316390903")
                return redirect()->route('home');
            } else if ($access == "Tilaknagar 2" || $access == "Tilak Nagar 2") {
                $auths = TeamLeader::where('temple_id', '1612469669002')
                ->pluck('access_temple_id')
                ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1612469669002")
                return redirect()->route('home');
            } /*else
            return redirect()->route('home');*/
        }

        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));

        //get data when form submitted
        $data             = $request->all();
        $from             = $request->from;
        $to               = $request->to;

        $result = DB::table('payment_orders')
        ->leftJoin('users as asssignbyname', 'asssignbyname.temple_id', 'payment_orders.assign_to')
        ->leftJoin('users as asssigntoname', 'asssigntoname.temple_id', 'payment_orders.assign_by')
        ->where('payment_orders.type', 'PAYTM');
        if ($from && $to) {
            $result = $result->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        }

        if ($request->assign_to) {
            $result = $result->where('payment_orders.assign_to', $request->assign_to);
        }

        if ($request->assign_by) {
            $result = $result->where('payment_orders.assign_by', $request->assign_by);
        }

        $count = $result->count();
        $result = $result->select(['payment_orders.*', 'asssignbyname.name as asssybn_by_name', 'asssigntoname.name as asssybn_to_name'])->orderBy('created_at', 'DESC')->paginate(15);

        $temples = User::where('type', '0')->where('role', 0)->select('id', 'name', 'temple_id')->get();
        $moderators = User::where('type', 0)->where('role', 5)->select('id', 'name', 'temple_id')->get();
        // dd(array_column($temples->toArray(), 'temple_id'));
        //dd(array_search("1562823549781", array_column($temples->toArray(), 'temple_id')));
        if (Auth::user()->role == 9) {
            return redirect()->back();
        }
        return view('crm.failed_transactions.failed-transactions', compact('result', 'count', 'month_start', 'week_start', 'today_date', 'last_three_day_date', 'temples', 'moderators'));
    }

    // paytm view
    public function paytmView()
    {
        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));

        $list = DB::table('payments')
            ->whereNull('razor_email')
            ->select('user_mobile as contact', 'amount', 'payment_id as id', 'method', 'created_at', 'status', 'user_id', 'order_id')
            ->orderBy('created_at', 'DESC')
            ->get();

        $count = DB::table('payments')->whereNull('razor_email')->count();
        $array = array();
        $result = array();


        foreach ($list as $values) {
            $lead = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')->where('mobile', $values->contact)->select('leads.assign_by', 'leads.assign_to', 'user_data.name')->first();
            if ($lead != null || $lead != []) {
                $assign_by = User::where('temple_id', $lead->assign_by)->select('name')->first();
                $assign_to = User::where('temple_id', $lead->assign_to)->select('name')->first();
                if (empty($assign_by->name)) {
                    $assign_by_name = 'N.A';
                } else {
                    $assign_by_name = $assign_by->name;
                }
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = $assign_to->name;
                $array['assign_by']     = $assign_by_name;
                $array['contact']         = $values->contact;
                $array['email']         = $lead->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);
            } else {
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = '';
                $array['assign_by']     = '';
                $array['contact']         = $values->contact;
                $array['email']         = '';
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);
            }
        }
        if (Auth::user()->role==9) {
            return redirect()->back();
        }
        return view('crm.failed_transactions.paytm-transactions', compact('result', 'count', 'month_start', 'week_start', 'today_date', 'last_three_day_date'));
    }

    public function paytmFilter(Request $request)
    {
        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));

        //get data when form submitted
        $data             = $request->all();
        $from             = $data['from'];
        $to               = $data['to'];

        $list = DB::table('payments')
            ->whereNull('razor_email')
            ->whereBetween('payments.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select('user_mobile as contact', 'amount', 'payment_id as id', 'method', 'created_at', 'status')
            ->orderBy('created_at', 'DESC')
            ->get();

        $count    = DB::table('payments')->whereBetween('payments.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->whereNull('razor_email')->count();
        $result = $list;
        return view('crm.failed_transactions.paytm-transactions', compact('list', 'count', 'month_start', 'week_start', 'today_date', 'last_three_day_date', 'from', 'to','result'));
    }

    //paytm payment details
    public function viewPaymentDetailP($id)
    {
        $list = DB::table('payments')
            ->whereNull('razor_email')
            ->where('order_id', $id)
            ->orWhere('order_id', $id)
            ->select('payments.*', 'user_mobile as contact', 'amount', 'payment_id as id', 'method', 'created_at', 'status')
            ->orderBy('created_at', 'DESC')
            ->first();
        return response()->json($list);
    }

    // razor pay view
    public function razorpayView()
    {
        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));

        $key_id = "rzp_live_AkjH8AZSSZBdRn";
        $secret_key = "9jDuywER4AX1aGoiFeYDziIV";
        $api = new Api($key_id, $secret_key);

        $params = array(
            'count' => 100
        );

        $payments = $api->payment->all($params);
        $list = $payments->items;
        //if(Auth::user()->temple_id == 'admin'){
        //	dd($list);
        //}
        $automated_count = 0;
        $assisted_count = 0;
        $success_count = 0;
        $fail_count = 0;
        $array = array();
        $result = array();

        foreach ($list as $values) {
            $customer_id = 'customer' . rand(111111, 999999);
            $orderId = 'RAZOR_' . rand(111111, 999999);
            $lead = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')->where('mobile', $values->contact)->select('leads.assign_by', 'leads.assign_to', 'user_data.name')->first();
            if ($lead != null || $lead != []) {
                $assign_by = User::where('temple_id', $lead['assign_by'])->select('name')->first();
                $assign_to = User::where('temple_id', $lead['assign_to'])->select('name')->first();
                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = $assign_to['name'];
                $array['assign_by']     = $assign_by['name'];
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {

                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = $lead->name;
                        $paymentData->assign_to         = $lead->assign_to;
                        $paymentData->assign_by         = $lead->assign_by;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    } else {
                        $data = PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first();
                        $data->assign_to         = $lead['assign_to'];
                        $data->assign_by         = $lead['assign_by'];
                        $data->save();
                    }
                }
            } else {
                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;

                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = '';
                $array['assign_by']     = '';
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {
                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = NULL;
                        $paymentData->assign_to         = NULL;
                        $paymentData->assign_by         = NULL;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    }
                }
            }
        }

        $count = $payments->count;
        return view('crm.failed_transactions.razorpay-transctions', compact('result', 'count', 'automated_count', 'assisted_count', 'month_start', 'week_start', 'today_date', 'last_three_day_date', 'success_count', 'fail_count'));
    }
    //razorpay details
    public function viewPaymentDetailR($id)
    {
        $key_id     = "rzp_live_AkjH8AZSSZBdRn";
        $secret_key = "9jDuywER4AX1aGoiFeYDziIV";
        $api = new Api($key_id, $secret_key);
        $payment = $api->payment->fetch($id);
        $list = $payment->toarray();
        return $list;
    }

    public function showRzpTransactions(Request $request)
    {
        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));
        $key_id = "rzp_live_AkjH8AZSSZBdRn";
        $secret_key = "9jDuywER4AX1aGoiFeYDziIV";
        $api = new Api($key_id, $secret_key);

        $params = array();

        if (!empty($request->total_days) && $request->total_days != "date_range") {
            $from = date('Y-m-d', strtotime("-$request->total_days days"));
            $params = array(
                'count' => 100,
                'from'  => strtotime($from),
                'to'    => strtotime(date('Y-m-d') . ' 59:59:59')
            );
        } else if (!empty($request->total_days) && $request->total_days == "date_range") {
            $from = $request->start_date;
            $to = $request->end_date;
            $params = array(
                'count' => 100,
                'from'  => strtotime($from),
                'to'    => strtotime($to)
            );
        } else {
            $params = array(
                'count' => 50,
                /*'from'  => strtotime(date('Y-m-d')),
                'to'    => strtotime(date('Y-m-d'))*/
            );
        }

        $payments = $api->payment->all($params);

        $list = $payments->items;
        $automated_count = 0;
        $assisted_count = 0;
        $success_count = 0;
        $fail_count = 0;
        $array = array();
        $result = array();

        foreach ($list as $values) {
            $customer_id = 'customer' . rand(111111, 999999);
            $orderId = 'RAZOR_' . rand(111111, 999999);
            $lead = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')->where('user_mobile', $values->contact)->select('assign_by', 'assign_to', 'user_data.name')->first();
            if ($lead != null || $lead != []) {
                $assign_by = User::where('temple_id', $lead['assign_by'])->select('name')->first();
                $assign_to = User::where('temple_id', $lead['assign_to'])->select('name')->first();
                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = $assign_to['name'] ?? "";
                $array['assign_by']     = $assign_by['name'] ?? "";
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {

                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = $lead->name;
                        $paymentData->assign_to         = $lead->assign_to;
                        $paymentData->assign_by         = $lead->assign_by;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    } else {
                        $data = PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first();
                        $data->assign_to         = $lead['assign_to'];
                        $data->assign_by         = $lead['assign_by'];
                        $data->save();
                    }
                }
            } else {
                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;

                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = '';
                $array['assign_by']     = '';
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {
                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = NULL;
                        $paymentData->assign_to         = NULL;
                        $paymentData->assign_by         = NULL;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    }
                }
            }
        }

        $count = $payments->count;
        return response()->json(['result' => $result, 'count' => $count, 'automated_count' => $automated_count, 'assisted_count' => $assisted_count, 'month_start' => $start_month, 'week_start' => $start_week, 'today_date' => $today_date, 'last_three_day_date' => $last_three_day_date, 'success_count' => $success_count, 'fail_count' => $fail_count]);
    }

    public function showPaytmTransactions(Request $request)
    {
        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));

        $list = DB::table('payments')
            ->whereNull('razor_email')
            ->select('payments.user_mobile as contact', 'amount', 'payment_id as id', 'method', 'payments.created_at', 'status', 'user_id', 'order_id');

        if (!empty($request->total_days) && $request->total_days != "date_range") {
            $from = date('Y-m-d', strtotime("-$request->total_days days"));
            $list = $list->whereBetween('payments.created_at', [$from . ' 00:00:00', date('Y-m-d') . ' 23:59:59']);
        } else if (!empty($request->total_days) && $request->total_days == "date_range") {
            $list = $list->whereBetween('payments.created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }/* else {
            $list = $list->whereBetween('payments.created_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59']);
        }*/

        if (empty($request->total_days)) {
            $list = $list->limit(500);
        }

        $list = $list->orderBy('payments.created_at', 'DESC')->get();

        $count = DB::table('payments')->whereNull('razor_email')->count();
        $array = array();
        $result = array();

        foreach ($list as $values) {
            $lead = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')->where('leads.user_data_id', $values->user_id)->select('assign_by', 'assign_to', 'email')->first();
            if (
                $lead != null || $lead != []
            ) {
                $assign_by = User::where('temple_id', $lead->assign_by)->select('name')->first();
                $assign_to = User::where('temple_id', $lead->assign_to)->select('name')->first();
                if (empty($assign_by)) {
                    $assign_by_name = 'N.A';
                } else {
                    $assign_by_name = $assign_by->name;
                }

                $assign_to_name = 'N.A';
                if (empty($assign_to)) {
                    $assign_to_name = 'N.A';
                } else {
                    $assign_to_name = $assign_by->name;
                }
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = $assign_to_name;
                $array['assign_by']     = $assign_by_name;
                $array['contact']         = $values->contact;
                $array['email']         = $lead->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);
            } else {
                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = 'N.A';
                $array['assign_by']     = 'N.A';
                $array['contact']         = $values->contact;
                $array['email']         = 'N.A';
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);
            }
        }

        return response()->json(['result' => $result, 'count' => $count, 'month_start' => $month_start, 'week_start' => $week_start, 'today_date' => $today_date, 'last_three_day_date' => $last_three_day_date]);
    }

    public function getPaytmFailedTxn(Request $request)
    {
        /*$access = AuthControlFailedtxn::where('day', date('l'))->pluck('team_leader')->first();
        $logged_in = Auth::user()->temple_id;

        if ($logged_in != "admin") {
            if ($access == "Both Bali Nagar TL and Tilak Nagar TL") {
                $auths = TeamLeader::where('temple_id', "1596811237462")
                    ->where('temple_id', '1597316390903')
                    ->pluck('access_temple_id')
                    ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1596811237462" && $logged_in != "1597316390903")
                    return redirect()->route('home');
            } else if ($access == "Bali Nagar TL") {
                $auths = TeamLeader::where('temple_id', "1596811237462")
                    ->pluck('access_temple_id')
                    ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1596811237462")
                    return redirect()->route('home');
            } else if ($access == "Tilak Nagar TL") {

                $auths = TeamLeader::where('temple_id', '1597316390903')
                    ->pluck('access_temple_id')
                    ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1597316390903")
                    return redirect()->route('home');
            } else if ($access == "Tilaknagar 2" || $access == "Tilak Nagar 2") {
                $auths = TeamLeader::where('temple_id', '1612469669002')
                    ->pluck('access_temple_id')
                    ->toArray();
                if (!in_array($logged_in, $auths) && $logged_in != "1612469669002")
                    return redirect()->route('home');
            } else
                return redirect()->route('home');
        }*/


        $result = DB::table('payment_orders')->leftJoin('users', 'users.temple_id', 'payment_orders.assign_to')->where('payment_orders.type', 'PAYTM');
        $count = $result->count();
        if (!empty($request->total_days) && $request->total_days != "date_range") {
            $from = date('Y-m-d', strtotime("-$request->total_days days"));
            $result = $result->whereBetween('payment_orders.created_at', [$from . ' 00:00:00', date('Y-m-d') . ' 23:59:59']);
        } else if (!empty($request->total_days) && $request->total_days == "date_range") {
            $result = $result->whereBetween('payment_orders.created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        /*else{
            $result = $result->whereBetween('payment_orders.created_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59']);
        }*/
        $result = $result->orderBy('payment_orders.created_at', 'DESC')->get();

        $temples = User::where('type', '0')->where('role', 0)->select('id', 'name', 'temple_id')->get();
        $moderators = User::where('type', 0)->where('role', 5)->select('id', 'name', 'temple_id')->get();

        //return view('payment view.failedTxn', compact('result', 'count', 'month_start', 'week_start', 'today_date', 'last_three_day_date', 'temples', 'moderators'));

        return response()->json(['result' => $result, 'count' => $count, 'temples' => $temples, 'moderators' => $moderators]);
    }

    public function razorpayFilterView(Request $request)
    {
        $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $today_date = date('Y-m-d');
        $last_three_day_date = (date("Y-m-d", strtotime("-2 day")));
        //get data when form submitted
        $data             = $request->all();
        $from             = $data['from'];
        $to               = $data['to'];

        $key_id     = "rzp_live_AkjH8AZSSZBdRn";
        $secret_key = "9jDuywER4AX1aGoiFeYDziIV";
        $api = new Api($key_id, $secret_key);

        $params = array(
            'count' => 100,
            'from'  => strtotime($from),
            'to'    => strtotime($to)
        );

        $payments = $api->payment->all($params);
        $list = $payments->items;

        // dd($list);

        $automated_count = 0;
        $assisted_count = 0;
        $success_count = 0;
        $fail_count = 0;
        $array = array();
        $result = array();

        foreach ($list as $values) {
            $customer_id = 'customer' . rand(111111, 999999);
            $orderId = 'RAZOR_' . rand(111111, 999999);
            $lead = UserData::leftJoin('leads', 'leads.user_data_id', 'user_data.id')->where('mobile', $values->contact)->select('leads.assign_by', 'leads.assign_to', 'user_data.name')->first();

            if ($lead != null || $lead != []) {
                $assign_by = User::where('temple_id', $lead->assign_by)->select('name')->first();
                $assign_to = User::where('temple_id', $lead->assign_to)->select('name')->first();

                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;

                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = $assign_to->name;
                $array['assign_by']     = $assign_by->name;
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {
                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = $lead->name;
                        $paymentData->assign_to         = $lead->assign_to;
                        $paymentData->assign_by         = $lead->assign_by;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    } else {
                        $data = PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first();

                        $data->assign_to         = $lead->assign_to;
                        $data->assign_by         = $lead->assign_by;
                        $data->save();
                    }
                }
            } else {
                if ($values->order_id == null)
                    $automated_count++;
                else
                    $assisted_count++;

                if ($values->status == 'captured')
                    $success_count++;
                else
                    $fail_count++;

                $array['id']             = $values->id;
                $array['order_id']         = $values->order_id;
                $array['assign_to']     = '';
                $array['assign_by']     = '';
                $array['contact']         = $values->contact;
                $array['email']         = $values->email;
                $array['amount']         = $values->amount;
                $array['status']         = $values->status;
                $array['method']         = $values->method;
                $array['created_at']     = $values->created_at;
                array_push($result, $array);

                if ($values->status == 'failed') {
                    if (!PaymentOrder::where('type', 'RAZORPAY')->where('txn_id', $values->id)->first()) {
                        $paymentData = new PaymentOrder();
                        $paymentData->type                 = "RAZORPAY";
                        $paymentData->order_id             = $orderId;
                        $paymentData->customer_id         = $customer_id;
                        $paymentData->customer_mobile     = $values->contact;
                        $paymentData->amount             = $values->amount / 100;
                        $paymentData->name                 = NULL;
                        $paymentData->assign_to         = NULL;
                        $paymentData->assign_by         = NULL;
                        $paymentData->order_from         = "WEB";
                        $paymentData->txn_id             = $values->id;
                        $paymentData->txn_date             = date('Y-m-d H:i:s', $values->created_at);
                        $paymentData->status             = $values->status;
                        $paymentData->error_code         = $values->error_code;
                        $paymentData->narration         = $values->error_description;
                        $paymentData->save();
                    }
                }
            }
        }

        $count = $payments->count;

        // dd($result);
        return view('crm.failed_transactions.razorpay-transctions', compact('result', 'count', 'automated_count', 'assisted_count', 'month_start', 'week_start', 'today_date', 'last_three_day_date', 'success_count', 'fail_count'));
    }
}
