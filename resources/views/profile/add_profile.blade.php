@section('page-title', 'Leads Management')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Add Receipts !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                            <li class="breadcrumb-item active">Add Receipt</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title">Passbook Details</h4>
                            </div>
                            <div class="col-md-6 text-center"><button class="btn btn-sm btn-success btnSearchLeadCrm">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button></div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse" id="pasbook-table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice Id</th>
                                        <th>Debit</th>
                                        <th>Narration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- search lead modal starts --}}
    <div class="modal fade" id="search_lead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title text-white">Search Lead</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <input type="number" class="form-control" name="search_mobile_number" id="search_mobile_number"
                                placeholder="Mobile Number" autocomplete="Off">
                        </div>
                        <div class="col-4 mt-1 search_btn_div">
                            <button class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile_crm"><i
                                    class="fas fa-search"></i></button>
                        </div>
                        <div class="col-12 search_details">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- search lead modal ends --}}

    {{-- Add Lead modal Starts --}}
    <div class="modal fade" id="add_lead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Add Receipt</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('addleadtelesales') }}" id="addLeadForm" autocomplete="off">
                        <div id="progressbarwizard">
                            @csrf
                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#account-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                        <span class="number">1</span>
                                        <span class="d-none d-sm-inline">Source</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link">
                                        <span class="number">2</span>
                                        <span class="d-none d-sm-inline">Personal</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content b-0 pt-0 mb-0">

                                <div id="bar" class="progress mb-3" style="height: 7px;">
                                    <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"
                                        style="width: 14.2857%;"></div>
                                </div>

                                <div class="tab-pane active" id="account-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="userName1">Mobile
                                                    Number</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="mobile"
                                                        id="new_lead_mobile" value="Lead Mobile Number" readonly>
                                                    <input type="text" class="form-control d-none" name="security_key"
                                                        id="security_key" value="Security Key" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Source</label>
                                                <div class="col-md-9">
                                                    <select name="lead_source" class="form-select" id="lead_source">
                                                        <option value="Facebook">Facebook</option>
                                                        <option value="Board/Sunpac/Banner">Board/Sunpac/Banner</option>
                                                        <option value="SMS">SMS</option>
                                                        <option value="Walk-In" selected>Walk-In</option>
                                                        <option value="Referal">Referal</option>
                                                        <option value="Google">Google</option>
                                                        <option value="JustDial">JustDial</option>
                                                        <option value="Instagram">Instagram</option>
                                                        <option value="NewsPaper">NewsPaper</option>
                                                        <option value="Temple Branding">Temple Branding</option>
                                                        <option value="Renwal">Renwal</option>
                                                        <option value="Upgrade">Upgrade</option>
                                                        <option value="Data Account">Data Account</option>
                                                        <option value="Word of Mouth">Word of Mouth</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Interest
                                                    Level</label>
                                                <div class="col-md-9">
                                                    <select name="interest_level" class="form-select" id="user_interest">
                                                        <option value="">Select Interest</option>
                                                        <option value="Very High" selected>Very High</option>
                                                        <option value="High">High</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="Low">Low</option>
                                                        <option value="Less">Less</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Assign To</label>
                                                <div class="col-md-9">
                                                    <select name="assign_to" id="assign_to" class="form-select"
                                                        id="assign_to">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Enquiry Date</label>
                                                <div class="col-md-9">
                                                    <input type="date" name="enquiry_date" id="enquiry_date"
                                                        class="form-control" value="@php echo date('Y-m-d'); @endphp"
                                                        max="@php echo date('Y-m-d'); @endphp">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Followup
                                                    Date</label>
                                                <div class="col-md-9">
                                                    <input type="date" name="followup_date" id="followup_date"
                                                        class="form-control" value="{{ date('Y-m-d') }}"
                                                        max="{{ date('Y-m-d', strtotime('+10 days')) }}"
                                                        min="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Followup
                                                    Comment</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="followup_comment" id="followup_comment"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline text-end mt-2">
                                        <li class="next list-inline-item">
                                            <button type="button" class="btn btn-success">Go To Personal Details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end tab pane -->

                                <div class="tab-pane" id="profile-tab-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Managed By</label>
                                                <div class="col-md-3">
                                                    <select name="profile_creating_for" class="form-select"
                                                        id="profile_creating_for">
                                                        <option value="Myself" selected>Myself
                                                        <option value="Son">Son</option>
                                                        <option value="Daughter">Daughter</option>
                                                        <option value="Brother">Brother</option>
                                                        <option value="Sister">Sister</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Gender</label>
                                                <div class="col-md-3">
                                                    <select name="lead_gender" class="form-select" id="lead_gender">
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Full name</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="full_name" name="full_name"
                                                        class="form-control" placeholder="Full Name">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="surname1"> Religion</label>
                                                <div class="col-md-3">
                                                    <select name="religion" class="form-select" id="religion">

                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="surname1"> Castes</label>
                                                <div class="col-md-3">
                                                    <select name="castes" class="form-select" id="castes">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Birth Date</label>
                                                <div class="col-md-3">
                                                    @php
                                                        $max_date = date('Y-m-d', strtotime('-18 years'));
                                                    @endphp
                                                    <input type="date" id="birth_date" name="birth_date"
                                                        class="form-control" max="{{ $max_date }}"
                                                        value="{{ $max_date }}">
                                                </div>
                                                <label class="col-md-3 col-form-label" for="surname1"> Marital
                                                    Status</label>
                                                <div class="col-md-3">
                                                    <select name="marital_status" class="form-select"
                                                        id="marital_status">
                                                        <option value="Never Married">Never Married</option>
                                                        <option value="Awaiting Divorce">Awaiting Divorce</option>
                                                        <option value="Divorcee">Divorcee</option>
                                                        <option value="Widnowed">Widnowed</option>
                                                        <option value="Anulled">Anulled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Height</label>
                                                <div class="col-md-3">
                                                    <select name="user_height" id="user_height" class="form-control">
                                                        <option value="">Select Height</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="email1">Weight</label>
                                                <div class="col-md-3">
                                                    <input type="number" id="weight" name="weight"
                                                        class="form-control" value="60">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Education</label>
                                                <div class="col-md-3">
                                                    <select name="education_list" class="form-select"
                                                        id="education_list">
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Occupation</label>
                                                <div class="col-md-3">
                                                    <select name="occupation_list" class="form-select"
                                                        id="occupation_list">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Working
                                                    City</label>
                                                <div class="col-md-3 row">
                                                    <div class="col-md-12">
                                                        <input type="text" name="search_working_city"
                                                            autocomplete="off" class="form-control"
                                                            id="search_working_city">
                                                        <input type="text" name="current_city" readonly
                                                            class="form-control d-none" id="working_city">
                                                    </div>
                                                    <div class="col-md-12 cityListOptions">

                                                    </div>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="surname1">Yearly
                                                    Income</label>
                                                <div class="col-md-3">
                                                    <select name="yearly_income" class="form-select" id="yearly_income">
                                                        @foreach ($income_range as $annual_income)
                                                            <option value="{{ $annual_income[0] }}">
                                                                {{ $annual_income[1] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-mb-3">
                                                <div class="col-md-6 offset-md-2 form_output text-capitalize"></div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline mt-2">
                                        <li class="previous list-inline-item disabled">
                                            <button type="button" class="btn btn-light"><i
                                                    class="mdi mdi-arrow-left me-1"></i> Back to Source</button>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                        <li class="next list-inline-item float-end submit_btn_li">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </li>
                                        </li>
                                    </ul>
                                </div>
                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Lead Modal Ends --}}

    {{-- Add Receipt Model Starts --}}
    <div class="modal fade" id="addReceiptModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Renew / Upgrade Plan</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('saverecivingamount') }}" method="post" id="addReceiptform"
                        autocomplete="off">
                        @csrf
                        <input type="number" name="receipt_user_id" id="receipt_user_id" class="d-none">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Invoice Number</label>
                                <div class="col-md-9">
                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control d-none"
                                        readonly>
                                    <input type="text" class="d-none" id="contacts" name="contacts">
                                    <input type="text" name="invoice_id_display" id="invoice_id_display"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Mobile</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="customer_mobile"
                                        id="customer_mobile" required readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Payment Mode</label>
                                <div class="col-md-9">
                                    <select name="payment_mode" id="payment_mode" class="form-select">
                                        <option value="cash">Cash</option>
                                        <option value="online">Online</option>
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Select Plan</label>
                                <div class="col-md-9">
                                    <select name="plan_name" id="user_plan" class="form-select"></select>
                                </div>
                            </div>
                            <div class="row mb-3 rmDiv" style="display: none">
                                <label class="col-md-3 col-form-label" for="userName1">Select Rm</label>
                                <div class="col-md-9">
                                    <select name="rm_name" id="rmDataList" class="form-select"></select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Plan Amount</label>
                                <div class="col-md-9">
                                    <input type="number" name="plan_amount" id="plan_amount" class="form-control"
                                        placeholder="Plan Amount">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Receiving Amount</label>
                                <div class="col-md-9">
                                    <input type="number" name="receiving_amount" id="receiving_amount"
                                        class="form-control" placeholder="Receiving Amount">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Discount</label>
                                <div class="col-md-9">
                                    <input type="text" name="discount" id="discount" class="form-control"
                                        placeholder="Discount Percent" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Validity Months</label>
                                <div class="col-md-9">
                                    <input type="number" name="validity" id="validity" required class="form-control"
                                        placeholder="Validity">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 form_output"></div>
                                <div class="col-md-4">
                                    <button type="submit" name="submit"
                                        class="btn btn-sm text-center btn-success">Generate
                                        Receipt</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Receipt Model Ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search_lead_modal').modal('show');

            $(document).on('click', '.btnSearchLeadCrm', function() {
                $('#search_lead_modal').modal('show');
            });

            // assign to me
            $(document).on('click', '.add_receipt_btn', function(e) {
                e.preventDefault();
                var user_id = $(this).attr('id');
                var invoice_id = uniqueid();
                $('#receipt_user_id').val(user_id);
                $("#invoice_id").val(invoice_id);
                var chunk_str = invoice_id.match(/.{1,5}/g);
                $('#invoice_id_display').val(chunk_str[0] + '-' + chunk_str[1] + '-' + chunk_str[3] + '-' +
                    chunk_str[3]);
                $('#addReceiptModal').modal('show');
                $('#search_lead_modal').modal('hide');
            });

            function uniqueid() {
                // always start with a letter (for DOM friendlyness)
                var idstr = String.fromCharCode(Math.floor((Math.random() * 25) + 65));
                do {
                    var ascicode = Math.floor((Math.random() * 42) + 48);
                    if (ascicode < 58 || ascicode > 64) {
                        idstr += String.fromCharCode(ascicode);
                    }
                } while (idstr.length < 20);

                return (idstr);
            }

            // get plan details by id
            $(document).on('change', '#user_plan', function() {
                var plan_id = $(this).val();
                if (plan_id == 78 || plan_id == 114 || plan_id == 9101 || plan_id == 9103) {
                    $('.rmDiv').show();
                } else {
                    $('.rmDiv').hide();
                    $('#rmDataList').val('');
                }

                $.ajax({
                    url: "{{ route('getplandetailsbyid') }}",
                    type: "get",
                    data: {
                        "plan_id": plan_id
                    },
                    success: function(planDetails) {
                        $('#plan_amount').val(planDetails.plan_amount);
                        $('#receiving_amount').val(planDetails.plan_amount);
                        $('#validity').val(planDetails.validity);
                        var plan_amount = planDetails.plan_amount;
                        var receiving_amount = planDetails.plan_amount;
                        var discount = (100 - ((parseInt(receiving_amount) * 100) /
                            plan_amount));
                        $("#contacts").val(planDetails.credits);
                        $('#discount').val(discount.toFixed(2));
                    }
                });
            });

            // calculate discount
            $(document).on('blur', '#receiving_amount', function() {
                var plan_amount = $("#plan_amount").val();
                var receiving_amount = $("#receiving_amount").val();
                var discount = (100 - ((parseInt(receiving_amount) * 100) / plan_amount));
                $('#discount').val(discount.toFixed(2));
            });

            // saerch lead
            $(document).on('click', '.search_lead', function(e) {
                e.preventDefault();
                $('.search_details').html('');
                $('#search_mobile_number').val('');
                $('#search_lead_modal').modal('show');
            });

            $(document).on('click', '.search_lead_mobile_crm', function(e) {
                e.preventDefault();
                var lead_mobile = $('#search_mobile_number').val();
                var leads_html = '';
                $('.search_btn_div').html(
                    '<div class="spinner-border text-danger m-2" role="status"></div>');
                if (lead_mobile == '' || lead_mobile.length < 10) {
                    $('.search_details').html(
                        '<div class="mt-3 alert alert-danger" role="alert"><strong>Warning</strong> Please Fill Mobile Number Carefully</div>'
                    );
                } else {
                    $.ajax({
                        url: "{{ route('searchleads') }}",
                        type: "get",
                        data: {
                            "lead_mobile_no": lead_mobile
                        },
                        success: function(search_resp) {
                            $('.search_btn_div').html(
                                ' <button class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile"><i class="fas fa-search"></i></button>'
                            );
                            if (search_resp.type == true) {
                                lead_deatsils = search_resp.data.lead_details;
                                leads_html += `<table class="table table-striped table-inverse">
                                                    <tr>
                                                        <th colspan="4">${search_resp.data.heading} Lead Based On Your Search</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Name</th> <td>${lead_deatsils.name}</td>
                                                        <th>Mobile</th> <td>${lead_deatsils.mobile}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Assigned To</th> <td>${search_resp.data.temple_name}</td>
                                                        <th>Enquiry Date</th><td>${lead_deatsils.enquiry_date}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Followup Call On</th><td>${lead_deatsils.followup_call_on}</td>
                                                        <th>Las Followup</th><td>${lead_deatsils.last_followup_date}</td>
                                                    </tr>`;
                                if (search_resp.data.temple_name == 'Hans Matrimony Online') {
                                    leads_html +=
                                        `<tr>
                                                        <th colspan="4"><button type="button" class="btn btn-sm btn-success assgn_to_me_btn" leadId="${search_resp.data.lead_details.id}" templeId="{{ Auth::user()->temple_id }}">Assign To Me</button></th></tr>`;
                                } else {
                                    leads_html += `<tr>
                                                        <th colspan="4"><button type="button" class="btn btn-sm btn-success add_receipt_btn" id="${lead_deatsils.id}" templeId="{{ Auth::user()->temple_id }}">Add New Receipt</button></th>
                                                </tr>`;
                                }
                                leads_html += `</table>`;
                                $("#customer_mobile").val(lead_deatsils.mobile);
                                $('.search_details').html(leads_html);
                            } else {
                                $("#customer_mobile").val(lead_mobile);
                                $('.search_details').html(
                                    '<div class="form-group"><div class="alert alert-danger" role="alert"><strong>No Record Found For This Mobile</strong></div></div><div class="form-group"><a href="#" class="btn btn-sm btn-bordered btn-success waves-effect waves-light add_lead" mobile="' +
                                    lead_mobile + '">Add This To Lead</a></div>');
                            }
                        }
                    });
                }
            });

            // add lead
            $(document).on('click', '.add_lead', function(e) {
                e.preventDefault();
                $('.search_details').html('');
                $('#search_mobile_number').val('');
                $('#search_lead_modal').modal('hide');
                $('#new_lead_mobile').val(parseInt($(this).attr('mobile')));
                $('#security_key').val(localStorage.getItem('security_key'));
                $('#add_lead_modal').modal('show');
            });

            // lead assign to self
            $(document).on('click', '.assgn_to_me_btn', function(e) {
                e.preventDefault();
                var lead_id = $(this).attr('leadId');
                var temple_id = $(this).attr('templeId');
                $.ajax({
                    url: "{{ route('updateassignto') }}",
                    type: "get",
                    data: {
                        "temple_id": temple_id,
                        "lead_id": lead_id
                    },
                    success: function(assign_response) {
                        $(".search_lead_mobile").trigger('click');
                    }
                });
            });

            // get crm lead plans
            getCrmPlans();

            function getCrmPlans() {
                plan_ids = `<option value="">Select Plan</option>`;
                $.ajax({
                    url: "{{ route('crmleadplans') }}",
                    type: "get",
                    success: function(plan_resp_resp) {
                        for (let i = 0; i < plan_resp_resp.length; i++) {
                            const plan_amounts_daat = plan_resp_resp[i];
                            var plan_name = plan_amounts_daat.type.split("_");
                            plan_ids +=
                                `<option value="${plan_amounts_daat.id}">${plan_name[0]}</option>`;
                        }
                        $('#user_plan').html(plan_ids);
                    }
                });
            }

            // select gender automatically
            $(document).on('change', '#profile_creating_for', function() {
                if ($(this).val() == "4,Sister" || $(this).val() == "7,Daughter") {
                    $('#lead_gender').val(2);
                    $('#lead_gender').prop("disabled", true);
                } else if ($(this).val() == "5,Brother" || $(this).val() == "6,Son") {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", true);
                }else {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", false);
                }
            });

            // get religion
            getReligion();

            function getReligion() {
                religion_html = `<option value="">Select Religion</option>`;
                $.ajax({
                    url: "{{ route('allreligion') }}",
                    type: "get",
                    success: function(religions) {
                        for (let i = 0; i < religions.length; i++) {
                            const religion = religions[i];
                            religion_html +=`<option value="${religion.mapping_id}-${religion.religion}">${religion.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                        $('#religion').val("1-Hindu");
                    }
                })
            }

            // submit add lead form
            $(document).on('submit', '#addLeadForm', function(e) {
                e.preventDefault();
                $('.submit_btn_li').html(
                    '<button class="btn btn-success" type="button" disabled=""><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading...</button>'
                );
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submit_response) {
                        if (submit_response.type == true) {
                            var error_html =
                                '<div class="alert alert-success" role="alert"><strong>Lead Added Successfully</strong></div>';
                            $('#addLeadForm')[0].reset();
                            window.setTimeout(() => {
                                $('#add_lead_modal').modal('hide');
                            }, 1000);
                        } else {
                            var error_html =
                                '<div class="alert alert-danger" role="alert"><strong>Failed to Add. Try Again</strong></div>';
                        }
                        $('.form_output').html(error_html);
                        $('.submit_btn_li').html(
                            '<button type="submit" class="btn btn-primary">Submit</button>');
                        var user_id = submit_response.id;
                        var invoice_id = uniqueid();
                        $('#receipt_user_id').val(user_id);
                        $("#invoice_id").val(invoice_id);
                        $("#customer_mobile").val(submit_response.mobile);
                        var chunk_str = invoice_id.match(/.{1,5}/g);
                        $('#invoice_id_display').val(chunk_str[0] + '-' + chunk_str[1] + '-' +
                            chunk_str[3] + '-' +
                            chunk_str[3]);
                        $('#addReceiptModal').modal('show');
                        $('#search_lead_modal').modal('hide');
                        $('#add_lead_modal').hide();
                    },
                    error: function(error_response) {
                        var error_html = '<ul class="text-danger">';
                        error_string_data = error_response.responseJSON.errors;
                        $.each(error_string_data, function(key, value) {
                            error_html += '<li>' + value + '</li>';
                        });
                        error_html += '<ul>';
                        $('.form_output').html(error_html);
                        $('.submit_btn_li').html(
                            '<button type="submit" class="btn btn-primary">Submit</button>');
                    }
                });
            });

            // submit receipt button
            $(document).on('submit', '#addReceiptform', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(receiptResponse) {
                        if (receiptResponse.type == true) {
                            var btn_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Message !</strong> Receipt Has Been Generated.
                            </div>`;
                            table_data.ajax.reload();
                            $('.form_output').html(btn_html);
                            $('#addReceiptform')[0].reset();
                            $('#addReceiptModal').modal('hide');
                        } else {
                            var btn_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Alert !</strong> Failed to Generate Receipt.
                            </div>`;
                            $('.form_output').html(btn_html);
                        }
                    }
                });
            });

            // load user passbook
            var table_data = $('#pasbook-table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('getpassbookdata') }}",
                "columns": [{
                        data: 'formatted_date',
                    },
                    {
                        data: 'invoice_id',
                    },
                    {
                        data: 'debit_amt',
                    },
                    // {
                    //     data: 'credit_amt',
                    // },
                    {
                        data: 'narration',
                    },
                    // {
                    //     data: 'closing_balance',
                    // },
                ]
            });

            // load castes
            loadAllCastes();

            function loadAllCastes() {
                var caste_html = '<option value="">Select Caste</option>';
                $.ajax({
                    url: "{{ route('getallcastes') }}",
                    type: "get",
                    success: function(caste_Response) {
                        for (let k = 0; k < caste_Response.length; k++) {
                            const caste_list = caste_Response[k];
                           caste_html +=
                                `<option value="${caste_list.id},${caste_list.caste ?? caste_list.value}">${caste_list.caste ?? caste_list.value}</option>`;
                        }
                        $('#castes').html(caste_html);
                    }
                });
            }

            // load all users
            loadAllTemples();

            function loadAllTemples() {
                var temple_html = '<option value="">Select User</option>';
                var login_user = "{{ Auth::user()->temple_id }}";
                var temple_id_html = '';
                $.ajax({
                    url: "{{ route('getalltemples') }}",
                    type: "get",
                    success: function(temple_response) {
                        for (let l = 0; l < temple_response.length; l++) {
                            const temple_list = temple_response[l];
                            if (temple_list.temple_id == login_user) {
                                temple_html +=
                                    `<option selected="selected" value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option selected="selected" value="${temple_list.id}">${temple_list.name}</option>`;
                            } else {
                                temple_html +=
                                    `<option value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option value="${temple_list.id}">${temple_list.name}</option>`;
                            }
                        }
                        $('#assign_to').html(temple_html);
                        $("#appoinemtn_with").html(temple_id_html);
                    }
                });
            }

            // load religion
            // loadReligion();

            // function loadReligion() {
            //     var religion_html = '';
            //     $.ajax({
            //         type: "get",
            //         url: "{{ route('allreligion') }}",
            //         success: function(religion_resp) {
            //             for (let q = 0; q < religion_resp.length; q++) {
            //                 const religsion_data = religion_resp[q];
            //                 religion_html +=
            //                     `<option value="${religsion_data.id}">${religsion_data.religion}</option>`;
            //             }
            //             $('#religion').html(religion_html);
            //         }
            //     });
            // }

            // load relation
            loadRelation();

            function loadRelation() {
                var relation_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getrelation') }}",
                    success: function(relation_resp) {
                        for (let p = 0; p < relation_resp.length; p++) {
                            const relation_data = relation_resp[p];
                            // relation_html +=
                            //     `<option value="${relation_data.id}">${relation_data.name}</option>`;
                            relation_html +=
                                `<option value="${relation_data.id},${relation_data.name}">${relation_data.name}</option>`;
                        }
                        $('#profile_creating_for').html(relation_html);
                    }
                });
            }

            // load marital status
            loadMaritalStatus();

            function loadMaritalStatus() {
                var marital_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getmaritalstatus') }}",
                    success: function(mstastus_resp) {
                        for (let o = 0; o < mstastus_resp.length; o++) {
                            const mstatus_data = mstastus_resp[o];
                            marital_status_html +=
                                `<option value="${mstatus_data.marital_status_id},${mstatus_data.name}">${mstatus_data.name}</option>`;
                        }
                        $('#marital_status').html(marital_status_html);
                    }
                });
            }

            // load occupations
            loadOccupations();

            function loadOccupations() {
                var occupation_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getoccupation') }}",
                    success: function(occupation_resp) {
                        for (let n = 0; n < occupation_resp.length; n++) {
                            const occupation_data = occupation_resp[n];
                            occupation_status_html +=
                                `<option value="${occupation_data.id},${occupation_data.name}">${occupation_data.name}</option>`;
                        }
                        $('#occupation_list').html(occupation_status_html);
                    }
                });
            }

            // polulate heights
            populateHeight();

            function populateHeight() {
                var height_values = '<option value="">Select Height</option>';
                for (let k = 48; k < 96; k++) {
                    height_values += `<option value="${k}">${Math.floor(k/12)} Ft ${k%12} In</option>`;
                }
                $('#user_height').html(height_values);
                $('#user_height').val(65);
            }


            $(document).on('keyup', '#search_working_city', function() {
                if ($(this).val().length > 3) {
                   var cities_lsit = getCitiesName($(this).val());
                }
            });

            function getCitiesName(city_name) {
                var city_html = ' <ul class="list-group city_list">';
                $.ajax({
                    url: "{{ route('getallcities') }}",
                    type: "get",
                    data: {
                        "city_name": city_name
                    },
                    success: function(city_response) {
                        for (let i = 0; i < city_response.length; i++) {
                            const city_names = city_response[i];
                            city_html +=
                                `<li class="list-group-item city_name" id="${city_names.id}" cityname="${city_names.city}, ${city_names.state}, ${city_names.country}">${city_names.city}, ${city_names.state}, ${city_names.country}</li>`;
                        }
                        city_html += '</ul>';
                        $('.cityListOptions').html(city_html);
                    }
                });
            }

            $(document).on('click', '.city_name', function() {
                var id = $(this).attr('id');
                var city_name = $(this).attr('cityname');
                $('.cityListOptions').html('');
                $('#working_city').val(id);
                $('#search_working_city').val(city_name);
            });

            // load qualifications
            loadQualifications();

            function loadQualifications() {
                var qual_html = '';
                $.ajax({
                    url: "{{ route('getalleducations') }}",
                    type: "get",
                    success: function(qualification_resp) {
                        for (let j = 0; j < qualification_resp.length; j++) {
                            const qualifications = qualification_resp[j];
                            qual_html +=
                                `<option value="${qualifications.id}, ${qualifications.degree_name}" qualname="${qualifications.degree_name}">${qualifications.degree_name}</option>`;
                        }
                        $('#education_list').html(qual_html);
                    }
                });
            }

            // load all rms
            loadAllRmList();

            function loadAllRmList() {
                $.ajax({
                    url: "{{ route('allrmlist') }}",
                    type: "get",
                    success: function(rmResp) {
                        let htmlData = '';
                        htmlData += `<option value="">Select Rm</option>`;
                        for (let i = 0; i < rmResp.length; i++) {
                            const rmData = rmResp[i];
                            htmlData += `<option value="${rmData.temple_id}">${rmData.name}</option>`;
                        }
                        $('#rmDataList').html(htmlData);
                    }
                })
            }
        });
    </script>
@endsection
