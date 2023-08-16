@section('page-title', 'HRMS-Employees')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Welcome !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">HRMS</a></li>
                            <li class="breadcrumb-item active">Employees</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" style="overflow: scroll">
                        <h4 class="header-title">Employees List</h4>

                        <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Lead Created</th>
                                    <th>Followup Call</th>
                                    <th>Last Seen</th>
                                    <th>Comments</th>
                                    <th>Plan Pitch</th>
                                    <th>Engagement</th>
                                    <th>Visited</th>
                                    <th>Subscription</th>
                                    <th>Action</th>
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


    {{-- Add Employees modal Starts --}}
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
    {{-- Add Employees Modal Ends --}}


@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

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


            var table_data = $('#salescrm-table').DataTable({
                "order": [
                    [5, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('allleads') }}",
                "columns": [{
                        data: 'interest',
                    },
                    {
                        data: 'assign_to',
                    },
                    {
                        data: 'lead_name',
                    },
                    {
                        data: 'lead_contact',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'followup_call_on',
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: 'comments',
                    },
                    {
                        data: 'plan_pitched',
                    },
                    {
                        data: 'engagement_score',
                    },
                    {
                        data: 'visited',
                    },
                    {
                        data: 'subscriptions',
                    },
                    {
                        data: 'appointments',
                    },
                ]
            });
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
                                `<option value="${qualifications.id}" qualname="${qualifications.degree_name}">${qualifications.degree_name}</option>`;
                        }
                        $('#education_list').html(qual_html);
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
                console.log('clieked');
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
