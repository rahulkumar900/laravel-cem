<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Add Leads</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('images/favicon.png') }}" />

    <!-- App css -->
    <link href="{{ url('css/default/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ url('css/default/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="{{ url('css/default/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" />
    <link href="{{ url('css/default/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

    <!-- icons -->
    <link href="{{ url('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="loading">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-8">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center m-auto">
                                <div class="auth-logo">
                                    <a href="index.html" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img class="w-100" src="{{ url('images/logo-dark.png') }}"
                                                alt="" />
                                        </span>
                                    </a>
                                </div>

                                <form method="post" action="{{ url('add-lead-telesales-without-login') }}"
                                    id="addLeadForm" autocomplete="off">
                                    <div id="progressbarwizard">
                                        @csrf
                                        <input type="hidden" name="new_lead" value="new lead">
                                        <input type="hidden" name="lead_type" value="{{ $_GET['lead_type'] }}">
                                        <input type="hidden" name="temple_id" value="{{ $_GET['temple_id'] }}">
                                        <input type="hidden" name="pick_lead_id" value="{{ $_GET['lead_id'] }}">
                                        <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                            <li class="nav-item">
                                                <a href="#account-2" data-bs-toggle="tab" data-toggle="tab"
                                                    class="nav-link source_link active">
                                                    <span class="number">1</span>
                                                    <span class="d-none d-sm-inline">Source</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab"
                                                    class="nav-link">
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
                                                            <label class="col-md-3 col-form-label"
                                                                for="userName1">Mobile
                                                                Number</label>
                                                            <div class="col-md-2">
                                                                <select name="country_code" class="form-select"
                                                                    id="country_code">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="number" class="form-control"
                                                                    name="mobile" id="new_lead_mobile"
                                                                    value="{{ (int) $_GET['mobile'] }}"
                                                                    value="Lead Mobile Number" readonly>
                                                                <input type="text" class="form-control d-none"
                                                                    name="security_key" id="security_key"
                                                                    value="Security Key" readonly>
                                                                <input type="number" name="lead_data_adding"
                                                                    class="d-none" value="1">
                                                            </div>
                                                        </div>

                                                        {{-- altername mob 1 --}}
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="userMobile">Alt.
                                                                Mobile
                                                                1</label>
                                                            <div class="col-md-2">
                                                                <select name="country_code_1" class="form-select"
                                                                    id="country_code_al1">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" class="form-control"
                                                                    name="alt_mob_1" id="alt_mob_1"
                                                                    placeholder="Alternate Mobile Number">
                                                            </div>
                                                        </div>

                                                        {{-- alternate mob 2 --}}
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="userMobile">Alt.
                                                                Mobile
                                                                2</label>
                                                            <div class="col-md-2">
                                                                <select name="country_code_2" class="form-select"
                                                                    id="country_code_al2">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" class="form-control"
                                                                    name="alt_mob_2" id="alt_mob_2"
                                                                    placeholder="Alternate Mobile Number">
                                                            </div>
                                                        </div>


                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Source</label>
                                                            <div class="col-md-9">
                                                                <select name="lead_source" class="form-select"
                                                                    id="lead_source">
                                                                    <option value="Facebook" selected>Facebook</option>
                                                                    <option value="Board/Sunpac/Banner">
                                                                        Board/Sunpac/Banner
                                                                    </option>
                                                                    <option value="SMS">SMS</option>
                                                                    <option value="Walk-In">Walk-In</option>
                                                                    <option value="Referal">Referal</option>
                                                                    <option value="Google">Google</option>
                                                                    <option value="JustDial">JustDial</option>
                                                                    <option value="Instagram">Instagram</option>
                                                                    <option value="NewsPaper">NewsPaper</option>
                                                                    <option value="Temple Branding">Temple Branding
                                                                    </option>
                                                                    <option value="Renwal">Renwal</option>
                                                                    <option value="Upgrade">Upgrade</option>
                                                                    <option value="Data Account">Data Account</option>
                                                                    <option value="Word of Mouth">Word of Mouth
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Interest
                                                                Level</label>
                                                            <div class="col-md-9">
                                                                <select name="interest_level" class="form-select"
                                                                    id="user_interest">
                                                                    <option value="">Select Interest</option>
                                                                    <option value="Very High" selected>Very High
                                                                    </option>
                                                                    <option value="High">High</option>
                                                                    <option value="Medium">Medium</option>
                                                                    <option value="Low">Low</option>
                                                                    <option value="Less">Less</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Assign
                                                                To</label>
                                                            <div class="col-md-9">
                                                                <select name="assign_to" id="assign_to"
                                                                    class="form-select" id="assign_to">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Enquiry
                                                                Date</label>
                                                            <div class="col-md-9">
                                                                <input type="date" name="enquiry_date"
                                                                    id="enquiry_date" class="form-control"
                                                                    value="@php echo date('Y-m-d'); @endphp"
                                                                    max="@php echo date('Y-m-d'); @endphp">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Followup
                                                                Date</label>
                                                            <div class="col-md-9">
                                                                <input type="date" name="followup_date"
                                                                    id="followup_date" class="form-control"
                                                                    value="{{ date('Y-m-d', strtotime('+1 days')) }}"
                                                                    max="{{ date('Y-m-d', strtotime('+10 days')) }}"
                                                                    min="{{ date('Y-m-d') }}">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Followup
                                                                Comment</label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="followup_comment"
                                                                    id="followup_comment" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->

                                                <ul class="pager wizard mb-0 list-inline text-end mt-2">
                                                    <li class="next list-inline-item">
                                                        <button type="button" class="btn btn-success">Go To Personal
                                                            Details <i class="mdi mdi-arrow-right ms-1"></i></button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- end tab pane -->

                                            <div class="tab-pane" id="profile-tab-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Profile
                                                                Creating
                                                                For</label>
                                                            <div class="col-md-3">
                                                                <select name="profile_creating_for"
                                                                    class="form-select" id="profile_creating_for">

                                                                </select>
                                                            </div>
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Gender</label>
                                                            <div class="col-md-3">
                                                                <select name="lead_gender" class="form-select"
                                                                    id="lead_gender">
                                                                    <option value="1">Male</option>
                                                                    <option value="2">Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Full
                                                                name</label>
                                                            <div class="col-md-9">
                                                                <input type="text" id="full_name" name="full_name"
                                                                    class="form-control" placeholder="Full Name">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="surname1">
                                                                Religion</label>
                                                            <div class="col-md-3">
                                                                <select name="religion" class="form-select"
                                                                    id="religion">

                                                                </select>
                                                            </div>
                                                            <label class="col-md-3 col-form-label" for="surname1">
                                                                Castes</label>
                                                            <div class="col-md-3">
                                                                <select name="castes" class="form-select"
                                                                    id="castes">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Birth
                                                                Date</label>
                                                            <div class="col-md-3">
                                                                @php
                                                                    $max_date = date('Y-m-d', strtotime('-18 years'));
                                                                @endphp
                                                                <input type="date" id="birth_date"
                                                                    name="birth_date" class="form-control"
                                                                    max="{{ $max_date }}"
                                                                    value="{{ $max_date }}">
                                                            </div>
                                                            <label class="col-md-3 col-form-label" for="surname1">
                                                                Marital
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
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Height</label>
                                                            <div class="col-md-3">
                                                                <select name="user_height" id="user_height"
                                                                    class="form-control">
                                                                    <option value="">Select Height</option>
                                                                </select>
                                                            </div>
                                                            <label class="col-md-3 col-form-label"
                                                                for="email1">Weight</label>
                                                            <div class="col-md-3">
                                                                <input type="number" id="weight" name="weight"
                                                                    class="form-control" value="60">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Education</label>
                                                            <div class="col-md-3">
                                                                <select name="education_list" class="form-select"
                                                                    id="education_list">
                                                                </select>
                                                            </div>
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Occupation</label>
                                                            <div class="col-md-3">
                                                                <select name="occupation_list" class="form-select"
                                                                    id="occupation_list">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Working
                                                                City</label>
                                                            <div class="col-md-3 row">
                                                                <div class="col-md-12">
                                                                    <input type="text" name="search_working_city"
                                                                        autocomplete="off" class="form-control"
                                                                        id="search_working_city">

                                                                </div>
                                                                <div class="col-md-12 cityListOptions">

                                                                </div>
                                                            </div>
                                                            <label class="col-md-3 col-form-label"
                                                                for="surname1">Yearly Income</label>
                                                            <div class="col-md-3">
                                                                <select name="yearly_income" class="form-select"
                                                                    id="yearly_income">
                                                                    @foreach ($income_range as $annual_income)
                                                                        <option value="{{ $annual_income[0] }}">
                                                                            {{ $annual_income[1] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label" for="name1">
                                                                Working
                                                                City</label>
                                                            <div class="col-md-3 row">
                                                                <div class="col-md-12">
                                                                    <input type="text" name="current_city"
                                                                        autocomplete="off" class="form-control"
                                                                        id="current_city">
                                                                </div>
                                                                <div class="col-md-12 cityListOptions">

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row mb-3">
                                                            <div
                                                                class="col-md-12 offset-md-2 form_output text-capitalize">
                                                            </div>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->

                                                <ul class="pager wizard mb-0 list-inline mt-2">
                                                    <li class="previous list-inline-item disabled">
                                                        <button type="button" class="btn btn-light"><i
                                                                class="mdi mdi-arrow-left me-1"></i> Back to
                                                            Source</button>
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
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <script>
            document.write(new Date().getFullYear());
        </script>
        &copy; HamsMatrimony <a href="#" class="text-dark">HansMatrimony</a>
    </footer>

    <!-- Vendor js -->
    <script src="{{ url('js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ url('js/app.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ url('libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ url('libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ url('libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ url('libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ url('js/pages/form-wizard.init.js') }}"></script>

    <script>
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

        loadAllTemples();

        function loadAllTemples() {
            var temple_html = '<option value="">Select User</option>';
            var login_user = "{{ $_GET['temple_id'] }}";
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
                            `<option value="${countryData.phonecode}" ${countryData.phonecode == 91 ? 'selected':''}>${countryData.sortname}</option>`;
                    }
                    $('#country_code').html(countryHtml);
                    $('#country_code_al1').html(countryHtml);
                    $('#country_code_al2').html(countryHtml);
                    // $('#country_code_al1').val(91);
                    // $('#country_code_al2').val(91);
                    //$('#country_code').val(91);
                }
            });
        }

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

        $(document).ready(function() {

            // select gender automatically
            $(document).on('change', '#profile_creating_for', function() {
                if ($(this).val() == "4,Sister" || $(this).val() == "2,Mother" || $(this).val() ==
                    "7,Daughter") {
                    $('#lead_gender').val(2);
                } else if ($(this).val() == "3,Father" || $(this).val() == "5,Brother" || $(this).val() ==
                    "6,Son") {
                    $('#lead_gender').val(1);
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
                            //$('#addLeadForm')[0].reset();
                        } else {
                            var error_html =
                                `<div class="alert alert-danger" role="alert"><strong>${submit_response.message}</strong></div>`;
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
        });
    </script>
</body>

</html>
