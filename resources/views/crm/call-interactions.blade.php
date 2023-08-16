@section('page-title', 'Pending Profiles')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css"
        integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Intercation Client List !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Client Interaction</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Welcome Call</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span class="d-none d-sm-inline-block">Verification Call</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home-b2">
                                <span class="welcomeDoneMsg"></span>
                                <table class="table table-striped table-inverse" id="callinteraction_table" width="100%">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Income</th>
                                            <th>Mobile</th>
                                            <th>Marital Status</th>
                                            <th>Profile Complete %</th>
                                            <th>Last Seen</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="load_lead_data">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="profile-b2">
                                {{-- verification call --}}
                                <span class="verificationDoneMsg"></span>
                                <table class="table table-striped table-inverse" id="verificationcallinteraction_table"
                                    width="100%">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Income</th>
                                            <th>Mobile</th>
                                            <th>Marital Status</th>
                                            <th>Profile Complete %</th>
                                            <th>Last Seen</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="load_lead_data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- Add Lead modal Starts --}}
    <div class="modal fade" id="approveProfile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Update Profile</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('approveuserprofile') }}" id="addLeadForm" autocomplete="off"
                        class="form-horizontal was-validated">
                        <div id="progressbarwizard">
                            @csrf
                            <input type="number" class="d-none" name="user_data" id="user_data_id">
                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#profile-tab-1" data-bs-toggle="tab" data-toggle="tab" class="nav-link">
                                        <span class="number">1</span>
                                        <span class="d-none d-sm-inline">Personal</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link">
                                        <span class="number">2</span>
                                        <span class="d-none d-sm-inline">Professional</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-tab-3" data-bs-toggle="tab" data-toggle="tab" class="nav-link">
                                        <span class="number">3</span>
                                        <span class="d-none d-sm-inline">Family</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-tab-4" data-bs-toggle="tab" data-toggle="tab" class="nav-link">
                                        <span class="number">4</span>
                                        <span class="d-none d-sm-inline">Photo</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content b-0 pt-0 mb-0">

                                <div id="bar" class="progress mb-3" style="height: 7px;">
                                    <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"
                                        style="width: 14.2857%;"></div>
                                </div>

                                <!-- end tab pane -->

                                {{-- Personal --}}
                                <div class="tab-pane active" id="profile-tab-1">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Managed By</label>
                                                <div class="col-md-3">
                                                    <select name="profile_creating_for" class="form-select"
                                                        id="profile_creating_for" required>
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
                                                    <select name="lead_gender" class="form-select" id="lead_gender"
                                                        required>
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Full name</label>
                                                <div class="col-md-3">
                                                    <input type="text" id="full_name" name="full_name"
                                                        class="form-control" placeholder="Full Name" required>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Food Choice</label>
                                                <div class="col-md-3">
                                                    <select name="food_choice" id="food_choice" class="form-select"
                                                        required>
                                                        <option value="">Select Food Choice</option>
                                                        <option value="2" selected>Non-Vegetarian</option>
                                                        <option value="1">Vegetarian</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="surname1"> Religion</label>
                                                <div class="col-md-3">
                                                    <select name="religion" class="form-select" id="religion" required>

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
                                                        value="{{ $max_date }}" required>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="email1">Birth Time</label>
                                                <div class="col-md-3">
                                                    <input type="time" id="birth_time" name="birth_time"
                                                        class="form-control" value="{{ date('H:i:s') }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="email1">Height</label>
                                                <div class="col-md-3">
                                                    <select name="user_height" id="user_height" class="form-select"
                                                        required>
                                                        <option value="">Select Height</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="email1">Weight</label>
                                                <div class="col-md-3">
                                                    <input type="number" id="weight" name="weight"
                                                        class="form-control" value="60" required>
                                                </div>
                                            </div>
                                            <div class="row md-3">
                                                <label class="col-md-3 col-form-label" for="surname1"> Marital
                                                    Status</label>
                                                <div class="col-md-3">
                                                    <select name="marital_status" class="form-select" id="marital_status"
                                                        required>
                                                        <option value="Never Married">Never Married</option>
                                                        <option value="Awaiting Divorce">Awaiting Divorce</option>
                                                        <option value="Divorcee">Divorcee</option>
                                                        <option value="Widnowed">Widnowed</option>
                                                        <option value="Anulled">Anulled</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="surname1"> Manglik
                                                    Status</label>
                                                <div class="col-md-3">
                                                    <select name="manglik_status" class="form-select" id="manglik_status"
                                                        required>
                                                    </select>
                                                </div>
                                                {{-- <label class="col-md-3 col-form-label" for="email1">Weight</label>
                                                <div class="col-md-3">
                                                    <input type="number" id="weight" name="weight" class="form-control"
                                                        value="60">
                                                </div> --}}
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline mt-2">
                                        <li class="previous list-inline-item disabled">
                                            <button type="button" class="btn btn-light"><i
                                                    class="mdi mdi-arrow-left me-1"></i> Back to Source</button>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                        <li class="next list-inline-item float-end">
                                            <button type="button" class="btn btn-success">Go To Professional Details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </li>
                                        </li>
                                    </ul>
                                </div>


                                {{-- Professional --}}
                                <div class="tab-pane" id="profile-tab-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Education</label>
                                                <div class="col-md-3">
                                                    <select name="education_list" class="form-select" id="education_list"
                                                        required>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Occupation</label>
                                                <div class="col-md-3">
                                                    <select name="occupation_list" class="form-select"
                                                        id="occupation_list" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Wish To Go
                                                    Abroad</label>
                                                <div class="col-md-3">
                                                    <select name="wish_to_go_abroad" class="form-select"
                                                        id="wish_to_go_abroad">
                                                        <option selected value="0">No</option>
                                                        <option value="1">Yes</option>
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
                                                    <select name="yearly_income" class="form-select" id="yearly_income"
                                                        required>
                                                        @foreach ($income_range as $annual_income)
                                                            <option value="{{ $annual_income[0] }}">
                                                                {{ $annual_income[1] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="surname1">About</label>
                                                <div class="col-md-9">
                                                    <textarea name="about_me" id="about_me" cols="10" rows="3" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline mt-2">
                                        <li class="previous list-inline-item disabled">
                                            <button type="button" class="btn btn-light"><i
                                                    class="mdi mdi-arrow-left me-1"></i> Back to Personal</button>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                        <li class="next list-inline-item float-end">
                                            <button type="button" class="btn btn-success">Go To Family Details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </li>
                                        </li>
                                    </ul>
                                </div>

                                {{-- Family --}}
                                <div class="tab-pane" id="profile-tab-3">
                                    <div class="row">
                                        <div class="col-12">

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Gotra</label>
                                                <div class="col-md-3">
                                                    <input type="text" id="family_gotra" name="family_gotra"
                                                        class="form-control" placeholder="Gotra">
                                                </div>
                                                <label class="col-md-3 col-form-label" for="surname1">Family
                                                    Income</label>
                                                <div class="col-md-3">
                                                    <select name="family_income" class="form-select" id="family_income"
                                                        required>
                                                        <option value="">Select</option>
                                                        <option value="125">0-2.5 Lakh/Year</option>
                                                        <option value="375" selected>2.5-5 Lakh/Year</option>
                                                        <option value="625">5-7.5 Lakh/Year</option>
                                                        <option value="875">7.5-10 Lakh/Year</option>
                                                        <option value="1250">10-15 Lakh/Year</option>
                                                        <option value="1750">15-20 Lakh/Year</option>
                                                        <option value="2250">20-25 Lakh/Year</option>
                                                        <option value="2750">25-30 Lakh/Year</option>
                                                        <option value="4250">35-50 Lakh/Year</option>
                                                        <option value="6000">50-70 Lakh/Year</option>
                                                        <option value="8500">70-100 Lakh/Year</option>
                                                        <option value="10000">1Cr+ /Year</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Father
                                                    Status</label>
                                                <div class="col-md-3">
                                                    <select name="father_status" id="father_status" class="form-select"
                                                        required>
                                                        <option value="">Select Father Status</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Mother
                                                    Status</label>
                                                <div class="col-md-3">
                                                    <select name="mother_status" id="mother_status" class="form-select"
                                                        required>
                                                        <option value="">Select Father Status</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Brothers</label>
                                                <div class="col-md-3">
                                                    <input type="number" min="0" value="0"
                                                        class="form-control" name="brothers" id="brothers" required>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1"> Sisters</label>
                                                <div class="col-md-3">
                                                    <input type="number" min="0" value="0"
                                                        class="form-control" name="sisters" id="sisters" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> Married
                                                    Brothers</label>
                                                <div class="col-md-3">
                                                    <input type="number" value="0" class="form-control"
                                                        name="married_brothers" id="married_brothers">
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1">Married
                                                    Sisters</label>
                                                <div class="col-md-3">
                                                    <input type="number" value="0" class="form-control"
                                                        name="married_sisters" id="married_sisters">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label" for="name1"> House Type</label>
                                                <div class="col-md-3">
                                                    <select name="house_type" id="house_type" class="form-select"
                                                        required>
                                                        <option value="">Select House Type</option>
                                                        <option value="1" selected>Owned</option>
                                                        <option value="2">Rented</option>
                                                        <option value="3">Leased</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-3 col-form-label" for="name1">Family Type</label>
                                                <div class="col-md-3">
                                                    <select name="family_type" id="family_type" class="form-select"
                                                        required>
                                                        <option value="">Select Family Type</option>
                                                        <option value="1" selected>Nuclear</option>
                                                        <option value="2">Joint</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline mt-2">
                                        <li class="previous list-inline-item disabled form_output">
                                            <button type="button" class="btn btn-light"><i
                                                    class="mdi mdi-arrow-left me-1"></i> Back to Profession</button>
                                        </li>
                                        <li class="next list-inline-item float-end btn_div">
                                            <button type="button" class="btn btn-success">Go To Final Submit <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>

                                        </li>
                                    </ul>
                                </div>

                                {{-- Photo --}}
                                <div class="tab-pane" id="profile-tab-4">
                                    <div class="row">
                                        <div class="col-12 photo_viewer row">

                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <ul class="pager wizard mb-0 list-inline mt-2">
                                        <li class="previous list-inline-item disabled form_output">
                                            <button type="button" class="btn btn-light"><i
                                                    class="mdi mdi-arrow-left me-1"></i> Back to Family</button>
                                        </li>
                                        <li class="next list-inline-item float-end btn_div">
                                            <button type="button"
                                                class="btn btn-danger rejectProfile rejectButton">Reject</button>

                                            <button type="submit" name="submit" class="btn btn-success">Update</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
        integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <script>
        $(document).ready(function() {

            // load approved profiles
            var table_data = $('#callinteraction_table').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getwelcomecallprofiles') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.genderCode_user == 1) {
                                return "Male";
                            } else {
                                return "Female";
                            }
                        }
                    },
                    {
                        data: 'monthly_income',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: 'profile_percent',
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData =
                                `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.id}"
                                    user_id="${data.id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">View/Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-warning markWelcomeDone" userId="${data.id}"  userName="${data.name}">Mark Done</button>

                                <button class="btn btn-success btn-xs viewDetails" leadid="${data.id}">View Details</button>
                                 <button class="btn btn-danger btn-xs mark_married" user_id="${data.id}">Mark Married</button>
                                 <button class="btn btn-purple btn-xs mark_premium" user_id="${data.id}">Mark Premium</button>
                                 <button class="btn btn-primary btn-xs addCreditButton" user_id="${data.id}">Add Credits</button>
                                 <input type="file" name="photo" accept="image/*" class="js-photo-upload"  user_id="${data.id}">`;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            $(document).on('click', '.markWelcomeDone', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('markwelcomedone') }}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "user_id": $(this).attr('userId'),
                        "profile_name": $(this).attr('userName')
                    },
                    success: function(welcomeDoneResponse) {
                        var clrType;
                        var msgType;
                        var msgString;
                        if (welcomeDoneResponse.type == true) {
                            clrType = 'success';
                            msgType = 'Success';
                            msgString = `Marking Welcome Done For ${welcomeDoneResponse.name}`;
                        } else {
                            clrType = 'danger';
                            msgType = 'Warning';
                            msgString =
                                `Marking Welcome Failed For ${welcomeDoneResponse.name}`;
                        }
                        var htmlData = `<div class="alert alert-${clrType} alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>${msgType}</strong> ${msgString}
                                    </div>`;
                        $('.welcomeDoneMsg').html(htmlData);
                        table_data.ajax.reload();
                        window.setTimeout(function() {
                            $('.welcomeDoneMsg').html('');
                        }, 2000);
                    }
                })
            });


            var verificationtable_data = $('#verificationcallinteraction_table').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getverificationcallprofiles') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.genderCode_user == 1) {
                                return "Male";
                            } else {
                                return "Female";
                            }
                        }
                    },
                    {
                        data: 'monthly_income',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: 'profile_percent',
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData =
                                `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.id}"
                                    user_id="${data.id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">View/Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-warning markVerificationDone" userId="${data.id}"  userName="${data.name}">Mark Done</button>
                                 <button class="btn btn-success btn-xs viewDetails" leadid="${data.id}">View Details</button>
                                 <button class="btn btn-danger btn-xs mark_married" user_id="${data.id}">Mark Married</button>
                                 <button class="btn btn-purple btn-xs mark_premium" user_id="${data.id}">Mark Premium</button>
                                 <button class="btn btn-primary btn-xs addCreditButton" user_id="${data.id}">Add Credits</button>
                                 <input type="file" name="photo" accept="image/*" class="js-photo-upload"  user_id="${data.id}">`;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            $(document).on('click', '.markVerificationDone', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('markverificationdone') }}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "user_id": $(this).attr('userId'),
                        "profile_name": $(this).attr('userName')
                    },
                    success: function(welcomeDoneResponse) {
                        var clrType;
                        var msgType;
                        var msgString;
                        if (welcomeDoneResponse.type == true) {
                            clrType = 'success';
                            msgType = 'Success';
                            msgString = `Marking Welcome Done For ${welcomeDoneResponse.name}`;
                        } else {
                            clrType = 'danger';
                            msgType = 'Warning';
                            msgString =
                                `Marking Welcome Failed For ${welcomeDoneResponse.name}`;
                        }
                        var htmlVerData = `<div class="alert alert-${clrType} alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>${msgType}</strong> ${msgString}
                                    </div>`;
                        $('.verificationDoneMsg').html(htmlVerData);
                        verificationtable_data.ajax.reload();
                        window.setTimeout(function() {
                            $('.verificationDoneMsg').html('');
                        }, 2000);
                    }
                })
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
                                `<option value="${religion.religion}">${religion.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                        $('#religion_pref').html(religion_html);
                    }
                })
            }


            $(document).on('click', '.rejectProfile', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Reject")) {
                    $.ajax({
                        url: "{{ route('rejectuserprofile') }}",
                        type: "get",
                        data: {
                            "user_id": $(this).attr('userid')
                        },
                        success: function(rejectResponse) {
                            alert(rejectResponse.message);
                            table_data.ajax.reload();
                        }
                    });
                }
            });

            $(document).on('click', '.checkNUpdate', function(e) {
                e.preventDefault();
                $('#user_data_id').val($(this).attr('user_id'));
                $.ajax({
                    type: "get",
                    url: "{{ route('getuserdatabyid') }}",
                    data: {
                        "user_id": $(this).attr('user_id'),
                        "lead_id": $(this).attr('lead_id')
                    },
                    success: function(userResponse) {
                        $('#approveProfile').modal('show');
                        $("#profile_creating_for").val(userResponse.relationCode);
                        $("#lead_gender").val(userResponse.genderCode_user);
                        $("#full_name").val(userResponse.name);
                        $("#religion").val(userResponse.religionCode);
                        $("#castes").val(userResponse.casteCode_user);
                        var nBdate = userResponse.birth_date.split(" ");
                        $("#birth_date").val(nBdate[0]);
                        $("#marital_status").val(userResponse.maritalStatusCode);
                        $("#user_height").val(userResponse.height_int);
                        $("#weight").val(userResponse.weight);

                        if (userResponse.educationCode_user != null) {
                            $("#education_list").val(userResponse.educationCode_user);
                        }

                        if (userResponse.occupationCode_user != null) {
                            $("#occupation_list").val(userResponse.occupationCode_user);
                        }

                        if (userResponse.working_city != null) {
                            $("#search_working_city").val(userResponse.working_city);
                        }

                        if (userResponse.height_int != null) {
                            $("#user_height").val(userResponse.height_int);
                        }

                        if (userResponse.weight != null) {
                            $("#weight").val(userResponse.weight);
                        }

                        if (userResponse.manglikCode != null) {
                            $("#manglik_status").val(userResponse.manglikCode);
                        }

                        if (userResponse.wishing_to_settle_abroad != null) {
                            $("#wish_to_go_abroad").val(userResponse.wishing_to_settle_abroad);
                        }

                        if (userResponse.monthly_income != null) {
                            $("#yearly_income").val(userResponse.monthly_income);
                        }
                        //  family details
                        if (userResponse.gotra != null) {
                            $("#family_gotra").val(userResponse.gotra);
                        }

                        if (userResponse.family_income != null) {
                            $("#family_income").val(userResponse.family_income);
                        }

                        if (userResponse.father_statusCode != null) {
                            $("#father_status").val(userResponse.father_statusCode);
                        }

                        if (userResponse.mother_statusCode != null) {
                            $("#mother_status").val(userResponse.mother_statusCode);
                        }
                        //"unmarried_sisters":0,"married_sisters":0,
                        // "unmarried_brothers":0,"married_brothers":0
                        if (userResponse.unmarried_brothers != null) {
                            $("#brothers").val(userResponse.unmarried_brothers);
                        }

                        if (userResponse.unmarried_sisters != null) {
                            $("#sisters").val(userResponse.unmarried_sisters);
                        }

                        if (userResponse.married_brothers != null) {
                            $("#married_brothers").val(userResponse.married_brothers);
                        }

                        if (userResponse.married_sisters != null) {
                            $("#married_sisters").val(userResponse.married_sisters);
                        }

                        if (userResponse.houseTypeCode != null) {
                            $("#house_type").val(userResponse.houseTypeCode);
                        }

                        if (userResponse.familyTypeCode != null) {
                            $("#family_type").val(userResponse.familyTypeCode);
                        }

                        if (userResponse.about != null) {
                            $("#about_me").val(userResponse.about);
                        }

                        if (userResponse.user_working_city != null) {
                            $("#working_city").val(userResponse.user_working_city);
                        }

                        var iamgeData = JSON.parse(userResponse.photo_url);
                        var photoHtml = '';
                        for (let f = 0; f < iamgeData.length; f++) {
                            const imageElement = iamgeData[f];
                            photoHtml += `<div class="mb-3 col-md-3 p-1 text-center">
                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${imageElement}" class="w-100 rounded">
                                            </div>`;
                        }
                        $('.photo_viewer').html(photoHtml);
                        $('.rejectButton').attr("userId", $(this).attr('user_id'));
                    }
                });
            });


            $(document).on('submit', '#addLeadForm', function(e) {
                e.preventDefault();
                $('.btn_div').text(`Please Wait...............`);
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submitResponse) {
                        var messageHtml = ``;
                        if (submitResponse.type == true) {
                            messageHtml +=
                                `<div class="alert alert-success" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                            $('#addLeadForm')[0].reset();
                            window.setTimeout(function() {
                                $('.form_output').html('');
                                table_data.ajax.reload();
                                $('#approveProfile').modal('hide');
                            }, 2000);
                        } else {
                            messageHtml +=
                                `<div class="alert alert-danger" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                        }
                        $('.btn_div').html(
                            `<button type="button" class="btn btn-danger rejectProfile rejectButton">Reject</button><button type="submit" name="submit" class="btn btn-success">Update</button>`
                        );
                        $('.form_output').html(messageHtml);
                    }
                });
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
                                `<option value="${caste_list.id}">${caste_list.caste ?? caste_list.value}</option>`;
                        }
                        $('#castes').html(caste_html);
                        $('#castes_pref').html(caste_html);
                    }
                });
            }

            // get parent occupation
            getParentOccupation();

            function getParentOccupation() {
                $.ajax({
                    url: "{{ route('getparentoccupation') }}",
                    type: "get",
                    success: function(parentOcResp) {
                        var prentHtml = '';
                        for (let m = 0; m < parentOcResp.length; m++) {
                            const pOccupations = parentOcResp[m];
                            prentHtml +=
                                `<option value="${pOccupations.id}">${pOccupations.name}</option>`;
                        }
                        $("#father_status").html(prentHtml);
                        $("#mother_status").html(prentHtml);
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
                                `<option value="${religsion_data.id}">${religsion_data.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                    }
                });
            }

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
                            relation_html +=
                                `<option value="${relation_data.id}">${relation_data.name}</option>`;
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
                                `<option value="${mstatus_data.id}">${mstatus_data.name}</option>`;
                        }
                        $('#marital_status').html(marital_status_html);
                        $('#marital_status').val(1);
                    }
                });
            }

            // load manglik status
            loadManglikStatus();

            function loadManglikStatus() {
                var marital_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getmanglikstatus') }}",
                    success: function(mstastus_resp) {
                        for (let o = 0; o < mstastus_resp.length; o++) {
                            const mstatus_data = mstastus_resp[o];
                            marital_status_html +=
                                `<option value="${mstatus_data.id}">${mstatus_data.name}</option>`;
                        }
                        $('#manglik_status').html(marital_status_html);
                        $('#manglik_status').val(2);
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
                                `<option value="${occupation_data.id}">${occupation_data.name}</option>`;
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
                'income_range'(city_name);
            });

            function 'income_range'(cityName) {
                var birthDateVal = $('#birth_date').val();

                var today = new Date();
                var birthDate = new Date(birthDateVal);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                var manglikStatus = $('#manglik_status').val();
                var mStatus = '';
                if (manglikStatus == 1) {
                    mStatus = 'Manglik';
                } else if (manglikStatus == 2) {
                    mStatus = 'Non-Manglik';
                } else if (manglikStatus == 3) {
                    mStatus = 'Anshik Manglik';
                } else if (manglikStatus == 4) {
                    mStatus = "Don't Know";
                }

                var gender = $('#lead_gender').val();
                var gName = '';
                if (gender == 1) {
                    gName = 'Man';
                } else {
                    gName = 'Woman';
                }

                getQualificationById($('#education_list').val());
                var qualification = localStorage.getItem('userDegree');

                var occupation = $('#occupation_list').val();
                var occupationString = '';
                if (occupation != 7 || occupation != 6) {
                    var occupationName = '';
                    if (occupation == 1) {
                        occupationName = "Business/Self Employed";
                    } else if (occupation == 2) {
                        occupationName = "Doctor";
                    } else if (occupation == 3) {
                        occupationName = "Government Job";
                    } else if (occupation == 4) {
                        occupationName = "Teacher";
                    } else if (occupation == 5) {
                        occupationName = "Private Job";
                    }
                    occupationString = `currently working as ${occupationName} in ${cityName}`;
                } else {
                    occupationString = '';
                }

                var stringAbout =
                    `I am ${mStatus} ${gName} residing in ${cityName}. I've completed my ${qualification} ${occupationString}.`;

                $('#about_me').val(stringAbout);
            }

            // get qualification by id
            function getQualificationById(qualification) {
                $.ajax({
                    url: "{{ route('getqualificationById') }}",
                    type: "get",
                    data: {
                        "qualification": qualification
                    },
                    success: function(qualresponse) {
                        localStorage.setItem('userDegree', qualresponse.degree_name);
                    }
                });
            }

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
                                `<option value="${qualifications.id}" qualname="${qualifications.degree_name}">${qualifications.degree_name}</option>`;
                        }
                        $('#education_list').html(qual_html);
                    }
                });
            }

            $(".alert").alert();
        });
    </script>
@endsection
