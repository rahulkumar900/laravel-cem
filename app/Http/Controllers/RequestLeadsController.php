<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestLeadsController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 9) {
            return redirect()->back();
        }
        $income_range = config('constants.income_ranges');
        return view('crm.reqeustnewleads', compact("income_range"));
    }

    public function requestWebsiteLeads()
    {
        if (Auth::user()->role == 9) {
            return redirect()->back();
        }
        $income_range = config('constants.income_ranges');
        return view('crm.reqeustwebsiteleads', compact("income_range"));
    }

    public function requestExhaust()
    {
        if (Auth::user()->role == 9) {
            return redirect()->back();
        }
        $income_range = config('constants.income_ranges');
        return view('crm.reqeustexhaustleads', compact("income_range"));
    }

    public function requestOperatorCalls()
    {
        if (Auth::user()->role == 9) {
            return redirect()->back();
        }
        $income_range = config('constants.income_ranges');
        return view('crm.reqeustoperatorleads', compact("income_range"));
    }
}
