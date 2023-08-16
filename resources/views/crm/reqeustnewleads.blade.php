@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Request Facebook Leads || <span class="text-success">Limit : <span
                                    class="leadLimit"></span></span> || <span class="text-danger">Requested : <span
                                    class="leadRequested"></span></span></h4>
                        <span class="message_title"></span>
                        {{-- facebook leads --}}
                        <div class="row">
                            <div class="col-md-12 text-center leadrequestdiv1">
                                <button class="btn btn-success btn-sm requestFbLead" dataType="1">Request Facebook
                                    Leads</button>
                            </div>
                            <div class="col-md-12 displaydiv1"></div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- Add Lead modal Starts --}}
    <div class="modal fade" id="add_lead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Add Lead</h5>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('addleadtelesales') }}" id="addLeadForm" autocomplete="off">
                        <div id="progressbarwizard">
                            @csrf
                            <input type="hidden" name="new_lead" value="new lead">
                            <input type="hidden" name="lead_type" value="1">
                            <input type="hidden" name="pick_lead_id" id="pick_lead_id">
                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#account-2" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link source_link active">
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
                                                    <input type="number" class="form-control" name="mobile"
                                                        id="new_lead_mobile" value="Lead Mobile Number" readonly>
                                                    <input type="text" class="form-control d-none" name="security_key"
                                                        id="security_key" value="Security Key" readonly>
                                                    <input type="number" name="lead_data_adding" class="d-none"
                                                        value="1">
                                                </div>
                                            </div>

                                            {{-- altername mob 1 --}}
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="userMobile">Alt. Mobile
                                                    1</label>
                                                <div class="col-md-2">
                                                    <select name="country_code_1" class="form-select" id="country_code_al1">
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="alt_mob_1"
                                                        id="alt_mob_1" placeholder="Alternate Mobile Number">
                                                </div>
                                            </div>

                                            {{-- alternate mob 2 --}}
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="userMobile">Alt. Mobile
                                                    2</label>
                                                <div class="col-md-2">
                                                    <select name="country_code_2" class="form-select" id="country_code_al2">
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="alt_mob_2"
                                                        id="alt_mob_2" placeholder="Alternate Mobile Number">
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
                                                        <option value="1">Never Married</option>
                                                        <option value="3">Awaiting Divorce</option>
                                                        <option value="4">Divorcee</option>
                                                        <option value="5">Widnowed</option>
                                                        <option value="6">Anulled</option>
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
                                                            <input type="text" name="current_city" readonly
                                                            class="form-control d-none" id="current_city">
                                                    </div>
                                                    <div class="col-md-12 cityListOptions">

                                                    </div>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1">Current
                                                    City</label>
                                                <div class="col-md-3 row">
                                                    <div class="col-md-12">
                                                        <input type="text" name="current_city"
                                                            class="form-control" id="current_city">
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

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.leadLimit').html(localStorage.getItem('lead_limit'));
            $(document).on('click', '.requestFbLead', function() {
                $('.leadrequestdiv' + $(this).attr('dataType') + '').html(
                    '<div class="spinner-border"></div>');
                var leadType = $(this).attr('dataType');
                var leadSpeed = $('#lead_speed').val();
                var leadIncome = $('#income_range').val();
                var credit = 0;
                loadLeadByType(leadType, leadSpeed, leadIncome, credit);
            });

            $(document).on('click', '.requestExhaustLead', function() {
                $('.leadrequestdiv' + $(this).attr('dataType') + '').html(
                    '<div class="spinner-border"></div>');
                var leadType = $(this).attr('dataType');
                var leadSpeed = $('#lead_speed_exhaust').val();
                var leadIncome = $('#income_range_exhaust').val();
                var credit = $("#lead_credit").val();
                loadLeadByType(leadType, leadSpeed, leadIncome, credit);
            });

            function loadLeadByType(leadType, leadSpeed, leadIncome, credit) {
                $.ajax({
                    url: "{{ route('getfacebookleads') }}",
                    type: "get",
                    data: {
                        "lead_type": leadType,
                        "lead_speed": leadSpeed,
                        "lead_income": leadIncome,
                        "lead_credits": credit
                    },
                    success: function(lfbleadResp) {
                        $('.tab-pane').removeClass('active');
                        $('.nav-link').removeClass('active');
                        $('.link' + lfbleadResp.lead_type + '').addClass('active');
                        $('.div' + lfbleadResp.lead_type + '').addClass('active');
                        if (lfbleadResp.type == true) {
                            message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>Message!</strong> ${lfbleadResp.message}.
                                        </div>`;
                            $('.message_title').html(message_html);
                            requestedLeads();
                            /* window.setTimeout(() => {
                                 $('.message_title').html('');
                             }, 2000);*/
                            var table_html = '';
                            table_html += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Mobile</th>`;
                            table_html += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            localStorage.setItem('lead_count', lfbleadResp.data.length);
                            localStorage.setItem('lead_type', lfbleadResp.lead_type);
                            $('.leadrequestdiv0').html('');
                            $('.leadrequestdiv1').html('');
                            $('.leadrequestdiv3').html('');
                            for (let i = 0; i < lfbleadResp.data.length; i++) {
                                const leadsData = lfbleadResp.data[i];
                                table_html += `<tr class="fbrow${leadsData.id}">
                                                <td scope="row">${parseInt(i)+1}</td>
                                                <td>${leadsData.user_phone ?? leadsData.mobile}</td>`;
                                table_html +=
                                    `<td>
                                        <button class="btn btn-sm btn-success btnPickup" id="${leadsData.id}" mobile="${leadsData.user_phone ?? leadsData.mobile}">Pickup</button></td><td> <button class="btn btn-sm btn-danger btnNotpickFbLead" id="${leadsData.id}">Not Pickup</button></td>
                                    </tr>`;
                            }
                            table_html += `</tbody></table>`;
                            $('.displaydiv1').html(table_html);
                        }
                    }
                });
            }

            function loadLeadByTypeFirstCheck(leadType) {
                $.ajax({
                    url: "{{ route('getlatrequestedleads') }}",
                    type: "get",
                    data: {
                        "lead_type": leadType
                    },
                    success: function(lfbleadRespNew) {
                        $('.tab-pane').removeClass('active');
                        $('.nav-link').removeClass('active');
                        $('.link' + lfbleadRespNew.lead_type + '').addClass('active');
                        $('.div' + lfbleadRespNew.lead_type + '').addClass('active');
                        if (lfbleadRespNew.type == true) {
                            let leadtTypeString = "";
                            let redirectUrl = "";
                            if (lfbleadRespNew.lead_type == 0) {
                                leadtTypeString = "Website Lead";
                                redirectUrl = "request-website-leads";
                            } else if (lfbleadRespNew.lead_type == 2) {
                                leadtTypeString = "Operator Calls";
                                redirectUrl = "request-operator-lcall-leads";
                            } else if (lfbleadRespNew.lead_type == 3) {
                                leadtTypeString = "Exhaust Leads";
                                redirectUrl = "request-exhaust-leads";
                            } else if (lfbleadRespNew.lead_type == 1) {
                                leadtTypeString = "Facebook Leads";
                                redirectUrl = "request-facebook-leads";
                            }
                            message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>${leadtTypeString}!</strong> ${lfbleadRespNew.message}.
                                        </div>`;
                            $('.message_title').html(message_html);
                            requestedLeads();
                            // window.setTimeout(() => {
                            //     $('.message_title').html('');
                            // }, 2000);
                            if (lfbleadRespNew.lead_type != 1) {
                                window.location.href=redirectUrl;
                            }
                            var table_html = '';
                            table_html += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Mobile</th>`;
                            table_html += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            localStorage.setItem('lead_count', lfbleadRespNew.data.length);
                            $('.leadrequestdiv0').html('');
                            $('.leadrequestdiv1').html('');
                            $('.leadrequestdiv3').html('');
                            for (let i = 0; i < lfbleadRespNew.data.length; i++) {
                                const leadsData = lfbleadRespNew.data[i];
                                table_html += `<tr class="fbrow${leadsData.id}">
                                                <td scope="row">${parseInt(i)+1}</td>
                                                <td>${leadsData.user_phone}</td>`;
                                table_html += `<td><button class="btn btn-sm btn-success btnPickup" id="${leadsData.id}" mobile="${leadsData.user_phone}" >Pickup</button></td>
                                                <td><button class="btn btn-sm btn-danger btnNotpickFbLead" id="${leadsData.id}" mobile_no="${leadsData.user_phone}">Not Pickup</button></td>
                                            </tr>`;
                            }
                            table_html += `</tbody></table>`;
                            $('.displaydiv1').html(table_html);
                        } else {
                            $('.leadrequestdiv1').html(
                                '<button class="btn btn-success btn-sm requestFbLead" dataType="1">Request FacebookLeads</button>'
                            );

                            $('.leadrequestdiv0').html(
                                '<button class="btn btn-success btn-sm requestFbLead" dataType="0">Request Website Leads</button>'
                            );

                            $('.leadrequestdiv3').html(
                                '<button class="btn btn-success btn-sm requestExhaustLead" dataType="3">Request Exhaust Leads</button>'
                            );
                        }
                    }
                });
            }

            $(document).on('click', '.btnNotpickFbLead', function(e) {
                e.preventDefault();
                var leadId = $(this).attr('id');
                $('.btnNotpickFbLead').text('Please Wait');
                $.ajax({
                    url: "{{ route('notpickupincompleteleads') }}",
                    type: "get",
                    data: {
                        "lead_id": leadId,
                        "lead_type": 1
                    },
                    success: function(notpickupResponse) {
                        if (notpickupResponse.type == true) {
                            $('.fbrow' + leadId + '').hide();
                            $('.btnNotpickFbLead').text('Not Pickup');
                            var prevRemainLead = parseInt(localStorage.getItem(
                                'lead_count'));
                            var remainLead = parseInt(prevRemainLead) - 1;

                            localStorage.setItem('lead_count', remainLead);
                            if (remainLead == 0) {
                                localStorage.setItem('lead_count', remainLead);
                                $('.leadrequestdiv1').html(
                                    '<button class="btn btn-success btn-sm requestFbLead" dataType="1">Request FacebookLeads</button>'
                                );

                                $('.leadrequestdiv0').html(
                                    '<button class="btn btn-success btn-sm requestFbLead" dataType="0">Request Website Leads</button>'
                                );

                                $('.leadrequestdiv3').html(
                                    '<button class="btn btn-success btn-sm requestFbLead3" dataType="3">Request Exhaust Leads</button>'
                                );
                            }
                        } else {
                            $('.btnNotpickFbLead').text('Not Pickup');
                        }
                    }
                });
            });

            if (localStorage.getItem('lead_count') != null) {
                loadLeadByTypeFirstCheck(localStorage.getItem('lead_type'));
                window.setTimeout(function() {
                   // $('.leadrequestdiv0').html('');
                   // $('.leadrequestdiv1').html('');
                  //  $('.leadrequestdiv3').html('');
                }, 800);
            }

            $(document).on('click', '.btnPickup', function(e) {
                e.preventDefault();
                $('.form_output').html("");
                var leadId = $(this).attr('id');
               // getLeadDetailsById(leadId);
                $('.fbrow' + leadId + '').hide();
                $('#pick_lead_id').val(leadId);
                var prevRemainLead = parseInt(localStorage.getItem('lead_count'));
                var remainLead = parseInt(prevRemainLead) - 1;

                localStorage.setItem('lead_count', remainLead);
                if (remainLead == 0) {
                    localStorage.setItem('lead_count', remainLead);
                    $('.leadrequestdiv1').html(
                        '<button class="btn btn-success btn-sm requestFbLead" dataType="1">Request FacebookLeads</button>'
                    );

                    $('.leadrequestdiv0').html(
                        '<button class="btn btn-success btn-sm requestFbLead" dataType="0">Request Website Leads</button>'
                    );

                    $('.leadrequestdiv3').html(
                        '<button class="btn btn-success btn-sm requestFbLead3" dataType="3">Request Exhaust Leads</button>'
                    );
                }
                $('#new_lead_mobile').val(parseInt($(this).attr('mobile')));
                $('#security_key').val(localStorage.getItem('security_key'));
                $('#add_lead_modal').modal('show');
                $('.source_link').trigger('click');
            });

            function getLeadDetailsById(leadId) {
                $.ajax({
                    url: "{{ route('getleadeatailsbyid') }}",
                    type: "get",
                    data: {
                        lead_id: leadId
                    },
                    success: function(searchResp) {
                        if (searchResp != null) {
                            $('#profile_creating_for').val(searchResp.relationCode);
                            $('#lead_gender').val(searchResp.genderCode_user);
                            $('#full_name').val(searchResp.name);
                            $('#religion').val(searchResp.religionCode);
                            $('#castes').val(searchResp.casteCode_user);
                            $('#birth_date').val(searchResp.birth_date);
                            $('#marital_status').val(searchResp.maritalStatusCode);
                            $('#user_height').val(searchResp.height_int);
                            $('#weight').val(searchResp.weight);
                            $('#education_list').val(searchResp.educationCode_user);
                            $('#occupation_list').val(searchResp.occupationCode_user);
                            $('#occupation_list').val(searchResp.occupationCode_user);
                            $('#yearly_income').val(searchResp.monthly_income);
                        }
                    }
                });
            }

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
                                `<option value="${religion.religion}">${religion.religion}</option>`;
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



            //add_next_followup
            $(document).on('click', '.add_next_followup', function(e) {
                e.preventDefault();
                var leadId = $(this).attr('id');
                $('.fbrow' + leadId + '').hide();
                $('#followup_lead_id').val(leadId);
                $('.followup_message').html('');
                $('#next_followup_modal').modal('show');
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

            loadReligion();

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

            // requested leads
            requestedLeads();

            function requestedLeads() {
                $.ajax({
                    url: "{{ route('countrequestedleads') }}",
                    type: "get",
                    success: function(requestedResponse) {
                        $('.leadRequested').html(requestedResponse.length);
                    }
                });
            }

            // set user categort and other data to localStorage
            loadUserCategoryData();

            function loadUserCategoryData() {
                $.ajax({
                    url: "{{ route('usercategoryrelations') }}",
                    type: "get",
                    success: function(detailsResponse) {
                        localStorage.setItem("call_count", detailsResponse.call_count);
                        localStorage.setItem("category", detailsResponse.category);
                        localStorage.setItem("category_id", detailsResponse.category_id);
                        localStorage.setItem("creation_date_time", detailsResponse.creation_date_time);
                        localStorage.setItem("lead_limit", detailsResponse.lead_limit);
                        localStorage.setItem("relation_name", detailsResponse.relation_name);
                        localStorage.setItem("temple_id", detailsResponse.temple_id);
                        localStorage.setItem("threshold_value", detailsResponse.threshold_value);
                    }
                });
            }

            // populate website leads
            $(document).on('click', '.populateWebsiteLead', function(e) {
                e.preventDefault();
                var websiteLeadTable = '';
                $(this).text("Please Wait");
                $(this).attr("disabled", true);
                var leadType = localStorage.getItem("");
                $.ajax({
                    url: "{{ route('getwebsiteleads') }}",
                    type: "get",
                    success: function(websiteLeadResponse) {
                        if (websiteLeadResponse.type == true) {
                            $('.populateWebsiteLead').text("Website Lead List");
                            websiteLeadTable += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Mobile</th>`;
                            websiteLeadTable += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            $('.populateWebsiteLead').attr("disabled", false);
                            for (let k = 0; k < websiteLeadResponse.data.length; k++) {
                                const webLeads = websiteLeadResponse.data[k];
                                websiteLeadTable += `<tr class="fbrow${webLeads.id}">
                                                <td scope="row">${parseInt(k)+1}</td>
                                                <td>${webLeads.mobile}</td>
                                                <td>    <a class="btn btn-primary" data-toggle="collapse" href="#contentId${k}" aria-expanded="false" aria-controls="contentId">Show
                                                        </a>
                                                    </p>
                                                    <div class="collapse" id="contentId${k}">`;
                                var splittedData = webLeads.comments.split(";");
                                for (let t = 0; t < splittedData.length; t++) {
                                    const comment = splittedData[t];
                                    websiteLeadTable += comment;
                                }
                                websiteLeadTable += `</div></td></tr>`;
                            }
                            $('.requestFbLead').show();
                        } else {
                            $(this).text("Website Lead List");
                            $('.populateWebsiteLead').attr("disabled", false);
                            websiteLeadTable = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <strong>Alert!</strong> Something Went Wrong Try Again.
                                </div>`;
                        }
                        $('.displaydiv1').html(websiteLeadTable);
                        $(this).attr("disabled", false);
                    }
                });
            });


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
                        //$('#country_code').html(countryHtml);
                        $('#country_code_al1').html(countryHtml);
                        $('#country_code_al2').html(countryHtml);
                        $('#country_code_al1').val(91);
                        $('#country_code_al2').val(91);
                        //$('#country_code').val(91);
                    }
                });
            }
        });
    </script>
@endsection
