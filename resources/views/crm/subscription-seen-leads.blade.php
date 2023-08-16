@section('page-title', 'Leads Management')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!--  start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">subscription Seen !</h4>
                    <div class="page-title-left">
                        <a href="#"
                            class="btn btn-sm btn-bordered btn-purple search_lead waves-effect waves-light float-right">Overall
                            Search</a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                            <li class="breadcrumb-item active">Subscription Seen</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <h4 class="header-title">Lead List</h4>
                    {{-- datatable --}}
                    <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Interest</th>
                                <th>Assigned At</th>
                                <th>Profile</th>
                                <th>Contact</th>
                                <th>Last Seen</th>
                                <th>Lead Created</th>
                                <th>Followup Call</th>
                                <th>Enquiry Date</th>
                                <th>Comments</th>
                                <th>Plan Pitch</th>
                                <th>Appointments</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    </div>

    {{-- fix aoppintment modal starts --}}

    <div class="modal fade" id="fix_appointment_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Fix Appoinement</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('makeanappointment') }}" method="post" id="appointmentForm" autocomplete="off">
                        @csrf
                        <input type="number" class="d-none form-control" readonly name="user_data_id" id="user_data_id">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="appoinemtnt_date" id="appoinemtnt_date"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Time</label>
                                <div class="col-md-9">
                                    <input type="time" class="form-control" name="appoinemtnt_time" id="appoinemtnt_time"
                                        value="{{ date('H:i:s') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">With</label>
                                <div class="col-md-9">
                                    <select name="appoinemtn_with" id="appoinemtn_with" class="form-select">

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Note</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="note" id="note"
                                        placeholder="Meeting Note" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 appointment_output"></div>
                                <div class="col-md-6 appointment_btn_div">
                                    <button type="submit" name="submit" class="btn btn-sm btn-warning">Save
                                        Appointment</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- fix aoopintment modal  ends --}}

    {{-- next follow up modal starts --}}

    <div class="modal fade" id="next_followup_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Next Followup Modal</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addleadsfollowp') }}" method="post" id="lead_followup_form"
                        autocomplete="off">
                        @csrf
                        <input type="text" class="d-none" id="followup_lead_id" name="followup_lead_id">
                        <div class="form-group mb-2">
                            <label for="">Status of Followup</label>
                            <textarea class="form-control" name="followup_status" id="followup_status" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Next Followup Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                                name="next_followup_date" id="next_followup_date" min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d', strtotime('+14 days')) }}" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Select Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select">
                                <option value="">Select Plan</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Interest Level</label>
                            <select name="lead_speed" id="lead_speed" class="form-select">
                                <option value="">Interest Level</option>
                                <option value="Very High" selected>Very High</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                                <option value="Less">Less</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 followup_message">
                        </div>
                        <div class="form-group mb-2 float-end">
                            <button class="btn btn-success" type="submit" name="submit">Add Followup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- next follow up modal endss --}}

    {{-- search lead modal starts --}}
    <div class="modal fade" id="search_lead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title text-white">Search Lead</h5>
                    <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <input type="number" class="form-control" name="search_mobile_number"
                                id="search_mobile_number" placeholder="Mobile Number" autocomplete="Off">
                        </div>
                        <div class="col-4 mt-1 search_btn_div">
                            <button
                                class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile"><i
                                    class="fas fa-search    "></i></button>
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
                    <h5 class="modal-title">Add Lead</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('addleadtelesales') }}" id="addLeadForm" autocomplete="off">
                        <div id="progressbarwizard">
                            @csrf
                            <input type="hidden" name="new_lead" value="new lead">
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
                                                <label class="col-md-3 col-form-label" for="userMobile">Mobile
                                                    Number</label>
                                                <div class="col-md-2">
                                                    <select name="country_code" class="form-select" id="country_code">
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="mobile"
                                                        id="new_lead_mobile" value="Lead Mobile Number" readonly>
                                                    <input type="text" class="form-control d-none" name="security_key"
                                                        id="security_key" value="Security Key" readonly>
                                                </div>
                                            </div>

                                            {{-- altername mob 1 --}}
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="userMobile">Alt. Mobile 1</label>
                                                <div class="col-md-2">
                                                    <select name="country_code_1" class="form-select" id="country_code_al1">
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="alt_mob_1" id="alt_mob_1" placeholder="Alternate Mobile Number">
                                                </div>
                                            </div>

                                            {{-- alternate mob 2 --}}
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="userMobile">Alt. Mobile 2</label>
                                                <div class="col-md-2">
                                                    <select name="country_code_2" class="form-select" id="country_code_al2">
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="alt_mob_2" id="alt_mob_2" placeholder="Alternate Mobile Number">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Source</label>
                                                <div class="col-md-9">
                                                    <select name="lead_source" class="form-select" id="lead_source">
                                                        <option value="Facebook" selected>Facebook</option>
                                                        <option value="Board/Sunpac/Banner">Board/Sunpac/Banner</option>
                                                        <option value="SMS">SMS</option>
                                                        <option value="Walk-In">Walk-In</option>
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
                                                        class="form-control"
                                                        value="{{ date('Y-m-d', strtotime('+1 days')) }}"
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
                                                    <select name="user_height" id="user_height" class="form-select">
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
                                                        <option value="">Select</option>
                                                        <option value="1.25">0-25 Lakh/Year</option>
                                                        <option value="3.75" selected>2.5-5 Lakh/Year</option>
                                                        <option value="6.25">5-7.5 Lakh/Year</option>
                                                        <option value="8.75">7.5-10 Lakh/Year</option>
                                                        <option value="12.5">10-15 Lakh/Year</option>
                                                        <option value="17.5">15-20 Lakh/Year</option>
                                                        <option value="22.5">20-25 Lakh/Year</option>
                                                        <option value="27.5">25-30 Lakh/Year</option>
                                                        <option value="42.5">35-50 Lakh/Year</option>
                                                        <option value="60.0">50-70 Lakh/Year</option>
                                                        <option value="85.0">70-100 Lakh/Year</option>
                                                        <option value="100">1Cr+ /Year</option>
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

    {{-- send message modal starts --}}
    <div class="modal fade" id="send_message" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Message</h5>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="send-message-form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="custom_w_message" id="custom_w_message"
                                placeholder="Type Message Here">
                            <input type="number" class="form-control d-none" name="custom_w_message"
                                id="custom_w_number" placeholder="Type Message Here">
                        </div>
                        <div class="form-group form_output_message">
                        </div>
                        <div class="form-group text-right mt-2">
                            <button class="btn btn-success float-end" type="submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- send modal ends --}}


    <!-- User Details Modal starts -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-5 diplayImage">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8 imageDisplayArea">

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <div>
                                                <div><span class="text-primary userCaste"></span></div>
                                                <h4 class="mb-1 userName"></h4>
                                                <div class="mt-3">
                                                    <h6 class="text-danger text-uppercase monthlyIncomeUser"></h6>
                                                    <h4>Education : <span
                                                            class="text-muted me-2 qualificationUser"><del></del></span>
                                                        <b class="occupationUser"></b>
                                                    </h4>
                                                </div>
                                                <div><span class="text-primary userCity"></span></div>
                                                <hr>

                                                <div>
                                                    <p class="aboutUser"></p>

                                                    <div class="mt-3">
                                                        <h5 class="font-size-14">Other Details :</h5>
                                                        <div class="row otherDetails">
                                                            <div class="col-md-6">
                                                                <ul class="list-unstyled product-desc-list">
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Height : <span class="userHeight"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Weight : <span class="userWeight"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Gender : <span class="genderUser"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Mobile : <span class="userMobile"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Family City : <span class="cityFamily"></span>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <ul class="list-unstyled product-desc-list">
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Brith Date : <span class="birthDateUser"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Food Choice : <span class="foodChoiceUser"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Manglik : <span class="manglikUser"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Marital Status : <span
                                                                            class="maritalStatusUser"></span>
                                                                    </li>
                                                                    <li><i
                                                                            class="mdi mdi-circle-medium me-1 align-middle"></i>
                                                                        Birth Place : <span class="workingCity"></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- user etails modal ends --}}

    {{-- alternate modal starts --}}
    <div class="modal fade" id="add_alternate_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Alternate Numbers</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('savealternatenumber')}}" method="post" id="add_alternamte_no">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="number" name="user_data_id" class="d-none" id="user_data_id_alternate">
                            <input type="number" name="alternate_one" id="alternate_one" class="form-control">
                        </div>
                        <div class="form-group mb-3 form_outputalternate">

                        </div>
                        <div class="form-group mb-3 float-end">
                            <button type="submit" name="submit" class="btn btn-success float-end">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- alternate modal ends --}}

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            var table_data = $('#salescrm-table').DataTable({
                "order": [
                    [5, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('allsubscriptionseenlist') }}",
                "columns": [{
                        data: 'interest',
                    },
                    {
                        data: 'assigned_at',
                    },
                    {
                        data: 'lead_name',
                    },
                    {
                        data: 'lead_contact',
                    },
                    {
                        data: null,
                        render : function(data){
                            if (data.last_seen=='1970-01-01') {
                                return 'N.A.';
                            }else{
                                return data.last_seen;
                            }
                        }
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'followup_call_on',
                    },
                    {
                        data: 'enquiry_date',
                    },
                    {
                        data: 'comments',
                    },
                    {
                        data: 'plan_pitched',
                    },
                    {
                        data: 'appointments',
                    },
                ]
            });

            // send message button
            $(document).on('click', '.send_whatsapp_message', function(e) {
                e.preventDefault();
                $('.form_output_message').html('');
                let mobile = $(this).attr('mobileNo');
                $('#custom_w_number').val(mobile);
                $('#send_message').modal('show');
            });

            $(document).on('click', '.add_alterante_no', function(e) {
                e.preventDefault();
                $('.form_outputalternate').html('');
                let userDataId = $(this).attr('mobileNo');
                $('#user_data_id_alternate').val(userDataId);
                $('#add_alternate_modal').modal('show');
            });

            $(document).on('submit', '#add_alternamte_no', function(e){
                e.preventDefault();
                $('.form_outputalternate').html('');
                $.ajax({
                    type : $(this).attr('method'),
                    url : $(this).attr('action'),
                    data : $(this).serialize(),
                    success : function(data_alternate){
                        if (data_alternate.type==true) {
                            $('.form_outputalternate').html(`<p class="text-success">${data_alternate.message}</p>`);
                            $('#add_alternamte_no')[0].reset();
                            $('#add_alternate_modal').modal('hide');
                            table_data.ajax.reload();
                        }else{
                            $('.form_outputalternate').html(`<p class="text-danger">${data_alternate.message}</p>`);
                        }
                    }
                });
            });

            $(document).on('click', '.view_user_profile', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('getuserdatabyid') }}",
                    type: "get",
                    data: {
                        "user_id": $(this).attr('user_data_id')
                    },
                    success: function(userResponse) {
                        let userHeight;
                        let userFt = parseInt(parseInt(userResponse.height_int) / 12);
                        let userInch = parseInt(userResponse.height_int) % 12;
                        userHeight = userFt + "Ft " + userInch + "In";
                        let userIncome = userResponse.monthly_income;
                        let birthDate = userResponse.birth_date.split(" ");
                        $('#profileSendDay').val(userResponse.profile_sent_day);

                        $('.imageDisplayArea').html(mainImageHtml);
                        $('.userCaste').text(userResponse.religion + ' : ' + userResponse
                        .caste);
                        $('.userName').text(userResponse.name);
                        $('.monthlyIncomeUser').text(userIncome + ' LPA');
                        $('.qualificationUser').text(userResponse.education);
                        $('.occupationUser').text('Occupation :' + userResponse.occupation);
                        $('.aboutUser').text(userResponse.about);
                        $('.userCity').text("Working City : " + userResponse.working_city);
                        $('.userHeight').text(userHeight);
                        $('.userWeight').text(userResponse.weight + "KG");
                        $('.genderUser').text(userResponse.gender);
                        $('.userMobile').text(userResponse.user_mobile);
                        $('.birthDateUser').text(birthDate[0]);
                        $('.foodChoiceUser').text(userResponse.food_choice);
                        $('.manglikUser').text(userResponse.manglik);
                        $('.maritalStatusUser').text(userResponse.marital_status);
                        $('.workingCity').text(userResponse.birth_place);
                        $('.cityFamily').text(userResponse.city_family);

                        // family details
                        $('.userUnmarriedBrothers').text(userResponse.unmarried_brothers);
                        $('.userUnmarriedSisters').text(userResponse.unmarried_sisters);
                        $('.userMarriedBrothers').text(userResponse.married_brothers);
                        $('.userMarriedSisters').text(userResponse.married_sisters);

                        $('.userFamilyType').text(userResponse.family_type);
                        $('.userHouseType').text(userResponse.house_type);
                        $('.fatherStatusUser').text(userResponse.father_status);
                        $('.motherStatusUser').text(userResponse.mother_status);

                        if (userResponse.photo_url) {
                            var parsed_url = JSON.parse(userResponse.photo_url);
                            var mainImageHtml = '';
                            mainImageHtml +=
                                `<img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${parsed_url[0]}" class="w-75">`;
                        }
                        $('#userDetailsModal').modal('show');
                    }
                });
            })

            // send message
            $(document).on('submit', '#send-message-form', function(e) {
                e.preventDefault();
                const messageData = $('#custom_w_message').val();
                const customerMNo = $('#custom_w_number').val();
                var settings = {
                    "url": "https://eazybe.com/api/v1/whatzapp/newCustomerMessageSchedule",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                        "name": "{{ Auth::user()->mobile }}",
                        "customer_mobile": customerMNo,
                        "user_mobile": "{{ Auth::user()->mobile }}",
                        "messageText": messageData,
                        "img_src": "undefined",
                        "scheduledDateTime": "{{ date('Y-m-d H:i:s', strtotime('+5 minutes')) }}",
                    }),
                };

                $.ajax(settings).done(function(response) {
                    $('.form_output_message').html(
                        '<div class="alert alert-primary" role="alert"><strong>Success</strong> Message Scheduled Successfully</div>'
                        );
                    $('#send-message-form')[0].reset();
                    window.setTimeout(function() {
                        $('#send_message').modal('hide');
                    }, 2000);
                });
            });

            //fix_appoinemtnt
            $(document).on('click', '.fix_appoinemtnt', function(e) {
                e.preventDefault();
                $('#fix_appointment_modal').modal('show');
                $('#user_data_id').val($(this).attr('id'));
            });

            //add_next_followup
            $(document).on('click', '.add_next_followup', function(e) {
                e.preventDefault();
                $('#followup_lead_id').val($(this).attr('lead_id'));
                $('.followup_message').html('');
                $('#next_followup_modal').modal('show');
            });

            //send_sample_profile
            $(document).on('click', '.send_sample_profile', function(e) {
                e.preventDefault();
                window.location.href = "{{ route('sampleprofile') }}";
            });

            //reject_leads
            $(document).on('click', '.reject_leads', function(e) {
                e.preventDefault();
                if (confirm('Are you sure to Reject?')) {
                    $.ajax({
                        url: "{{ route('rejectlead') }}",
                        type: "get",
                        data: {
                            "lead_id": $(this).attr('lead_id')
                        },
                        success: function(rejectLeadResp) {
                            if (rejectLeadResp.type == true) {
                                alert(rejectLeadResp.message);
                                table_data.ajax.reload();
                            }
                        }
                    });
                }
            });

            // saerch lead
            $(document).on('click', '.search_lead', function(e) {
                e.preventDefault();
                $('.search_details').html('');
                $('#search_mobile_number').val('');
                $('#search_lead_modal').modal('show');
            });

            $(document).on('click', '.search_lead_mobile', function(e) {
                e.preventDefault();
                var lead_mobile = $('#search_mobile_number').val();
                var leads_html = '';
                $('.search_btn_div').html(
                    '<div class="spinner-border text-danger m-2" role="status"></div>');
                if (lead_mobile == '') {
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
                                                        <th>Last Followup</th>
                                                        <td>${lead_deatsils.last_followup_date}</td>
                                                        <th>Followup Call On</th><td>${lead_deatsils.followup_call_on}</td>
                                                    </tr>`;
                                if (search_resp.data.temple_name == 'Hans Matrimony Online') {
                                    leads_html += `<tr>
                                                        <th colspan="4"><button type="button" class="btn btn-sm btn-success assgn_to_me_btn" leadId="${lead_deatsils.id}" templeId="{{ Auth::user()->temple_id }}">Assign To Me</button></th>
                                                </tr>`;
                                }
                                leads_html += `</table>`;
                                $('.search_details').html(leads_html);
                            } else {
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
                $('.form_output').html('');
                $('#add_lead_modal').modal('show');
            });

            // get crm lead plans
            getCrmPlans();

            function getCrmPlans() {
                plan_optins = `<option value="">Select Plan</option>`;
                $.ajax({
                    url: "{{ route('crmleadplans') }}",
                    type: "get",
                    success: function(plan_resp) {
                        for (let i = 0; i < plan_resp.length; i++) {
                            const plan_amounts = plan_resp[i];
                            var plan_name = plan_amounts.type.split("_");
                            plan_optins +=
                                `<option value="${plan_name[0]}">${plan_name[0]}</option>`;
                        }
                        $('#plan_id').html(plan_optins);
                    }
                });
            }

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
                        table_data.ajax.reload();
                    }
                });
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
                            religion_html +=
                                `<option value="${religion.mapping_id}-${religion.religion}">${religion.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                    }
                })
            }

            // select gender automatically
            $(document).on('change', '#profile_creating_for', function() {
                if ($(this).val() == "4,Sister" || $(this).val() == "2,Mother" || $(this).val() == "7,Daughter") {
                    $('#lead_gender').val(2);
                    $('#lead_gender').prop("disabled", true);
                } else if ($(this).val() == "3,Father" || $(this).val() == "5,Brother" || $(this).val() == "6,Son") {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", true);
                } else {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", false);
                }
            });

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
                                table_data.ajax.reload();
                            }, 1000);
                        } else {
                            var error_html =
                                '<div class="alert alert-danger" role="alert"><strong>Failed to Add. Try Again</strong></div>';
                        }
                        $('.form_output').html(error_html);
                        $('.submit_btn_li').html(
                            '<button type="submit" class="btn btn-primary">Submit</button>');
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


            //loadReligion();

            function loadReligion() {
                var religion_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('allreligion') }}",
                    success: function(religion_resp) {
                        for (let q = 0; q < religion_resp.length; q++) {
                            const religsion_data = religion_resp[q];
                            religion_html +=
                                `<option value="${religsion_data.mapping_id}-${religsion_data.religion}">${religsion_data.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                    }
                });
            }

            loadRelation();

            function loadRelation() {
                var relation_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getrelation') }}",
                    success: function(relation_resp) {
                        for (let p = 0; p < relation_resp.length; p++) {
                            const relation_data = relation_resp[p];
                            relation_html +=
                                `<option value="${relation_data.id},${relation_data.name}">${relation_data.name}</option>`;
                        }
                        $('#profile_creating_for').html(relation_html);
                    }
                });
            }

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
                var cities_lsit = getCitiesName($(this).val());
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

            loadcountries();

            function loadcountries() {
                var countryHtml = '';
                $.ajax({
                    url: "{{ route('getallcountries') }}",
                    type: "get",
                    success: function(countryResp) {
                        for (let k = 0; k < countryResp.length; k++) {
                            const countryData = countryResp[k];
                            countryHtml +=
                                `<option value="${countryData.phonecode}">${countryData.sortname}</option>`;
                        }
                        $('#country_code').html(countryHtml);
                        $('#country_code_al1').html(countryHtml);
                        $('#country_code_al2').html(countryHtml);
                        $('#country_code_al1').val(91);
                        $('#country_code_al2').val(91);
                         $('#country_code').val(91);
                    }
                });
            }

            // saving lead form
            $(document).on('submit', '#lead_followup_form', function(e) {
                e.preventDefault();
                var followup_html = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(data_followup) {
                        if (data_followup.type == true) {
                            followup_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> Followup Has Been Saved
                            </div>`;
                            table_data.ajax.reload();
                            $('#lead_followup_form')[0].reset();
                        } else {
                            followup_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Alert!</strong> Failed to Save Record
                            </div>`;
                        }
                        $('.followup_message').html(followup_html);
                    }
                });
            });

            // make an appointment
            $(document).on('submit', '#appointmentForm', function(e) {
                e.preventDefault();
                $('.appointment_btn_div').html(
                    '<button class="btn btn-success" type="button" disabled=""><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading...</button>'
                );
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(appoint_resp) {
                        console.log(appoint_resp);
                        if (appoint_resp.type == true) {
                            $('.appointment_output').html(
                                `<div class="alert alert-success" role="alert"><strong>${appoint_resp.message}</strong></div>`
                            );
                            window.setTimeout(function() {
                                $('.appointment_output').html('');
                                table_data.ajax.reload();
                            }, 1500);
                            $('#appointmentForm')[0].reset();
                            $('.appointment_btn_div').html(
                                '<button type="submit" name="submit" class="btn btn-sm btn-warning">Save Appointment</button>'
                            );
                            $('#fix_appointment_modal').modal('hide');
                        } else {
                            $('.appointment_output').html(
                                `<div class="alert alert-success" role="alert"><strong>${appoint_resp.message}</strong></div>`
                            );
                        }
                    },
                    error: function(error_response) {
                        var error_html = '<ul class="text-danger">';
                        error_string_data = error_response.responseJSON.errors;
                        $.each(error_string_data, function(key, value) {
                            error_html += '<li>' + value + '</li>';
                        });
                        error_html += '<ul>';
                        $('.form_output').html(error_html);
                        $('.appointment_btn_div').html(
                            '<button type="submit" name="submit" class="btn btn-sm btn-warning">Save Appointment</button>'
                        );
                    }
                });
            });
        });
    </script>
@endsection
