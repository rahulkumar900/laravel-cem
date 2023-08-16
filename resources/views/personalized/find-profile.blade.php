@section('page-title', 'Double Approval')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title profileDetails" userId="{{ $_GET['user_id'] }}">Profile Details</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Find Match</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- tabbed panel starts --}}
                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Find Match</span>
                                </a>
                            </li>
                            <li class="nav-item sendProfileListNav">
                                <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span class="d-none d-sm-inline-block">Update Pending (<span
                                            class="sent_profile_count"></span>) </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#messages-b2" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link yesPendingNav">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-email-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Yes Pending (<span
                                            class="yes_pending_count"></span>)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link premiumMeetingNav">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span class="d-none d-sm-inline-block">Premium Meetings (<span
                                            class="premium_meeting_count"></span>)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings-b3" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link historyNav">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span class="d-none d-sm-inline-block">History</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="home-b2">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        {{-- filter starts --}}
                                        <form action="" method="post" id="filterForm">
                                            @csrf
                                            <input type="number" name="user_gender" id="user_denger" class="d-none">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-md-12">
                                                        <label for="Age Range">Age Range</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="number" name="min_age" id="min_age"
                                                                class="form-control"
                                                                value="{{ $user_preference['age_min'] }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" name="max_age" id="max_age"
                                                                class="form-control"
                                                                value="{{ $user_preference['age_max'] }}">
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Height Range</label>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <select name="min_height" id="min_height"
                                                                class="form-select"></select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="max_height" id="max_height"
                                                                class="form-select"></select>
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Castes</label>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <select class="col-md-12 " multiple data-live-search="true"
                                                                id="caste_lists" name="castes[]">
                                                                @foreach ($caste_list as $castes)
                                                                    @if (!empty($caste_pref) && in_array($castes->id, $caste_pref))
                                                                        <option selected value="{{ $castes->id }}">
                                                                            {{ $castes->value }}</option>
                                                                    @else
                                                                        <option value="{{ $castes->id }}">
                                                                            {{ $castes->value }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6"><button type="button"
                                                                class="btn btn-xs btn-success select_all"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Select all castes"><i class="fa fa-check"
                                                                    aria-hidden="true"></i></button></div>
                                                        <div class="col-md-6"><button type="button"
                                                                class="btn btn-xs btn-warning clear_all"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="remove caste filter"><i class="fa fa-times"
                                                                    aria-hidden="true"></i></button></div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Marital Status</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <select class="col-md-12 selectpicker" id="maritalStatus"
                                                                multiple name="marital_status">
                                                                {{-- {{ dd($marital_status) }} --}}
                                                                @foreach ($marital_status as $marital)
                                                                    @if ($user_preference['marital_statusPref'] == $marital->id)
                                                                        <option value="{{ $marital->id }}" selected>
                                                                            {{ $marital->name }}</option>
                                                                    @else
                                                                        <option value="{{ $marital->id }}">
                                                                            {{ $marital->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Manglik Status</label>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <select class="col-md-12 selectpicker" id="manglik_status2"
                                                                multiple name="manglik_status2">
                                                                @foreach ($manglik_status as $manglik)
                                                                    @if (!empty($user_preference['manglikPref']) && $manglik->id == $user_preference['manglikPref'])
                                                                        <option value="{{ $manglik->id }}" selected>
                                                                            {{ $manglik->name }}</option>
                                                                    @else
                                                                        <option value="{{ $manglik->id }}">
                                                                            {{ $manglik->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Food Choice</label>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <select class="form-select" name="food_choice"
                                                                id="foor_choice">
                                                                <option value="0">Doesn't Matter</option>
                                                                <option value="2" selected>Non-Vegetarian</option>
                                                                <option value="1">Vegetarian</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mt-3">
                                                            <label>Working</label>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <select class="selectpicker col-md-12" multiple
                                                                name="job_status" id="job_status">
                                                                @foreach ($workins as $occupation)
                                                                    <option value="{{ $occupation->id }}">
                                                                        {{ $occupation->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Income Range</label>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <select name="yearly_income" class="form-select"
                                                                id="yearly_income">
                                                                @foreach ($income_range as $annual_income)
                                                                    <option value="{{ $annual_income[0] }}">
                                                                        {{ $annual_income[1] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="yearly_income_max" class="form-select"
                                                                id="yearly_income_max">
                                                                @foreach ($income_range as $annual_income)
                                                                    <option value="{{ $annual_income[0] }}">
                                                                        {{ $annual_income[1] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12">

                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckChecked">Is Disabled</label>
                                                            <select class="form-select" id="disabled_profiles"
                                                                name="disabled_profiles">
                                                                <option value="2">All</option>
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">

                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckChecked">Wish To Settle Abroad</label>
                                                            <select class="form-select" id="wish_to" name="wish_to">
                                                                <option value="3">Select Option</option>
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">

                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckChecked">NRI</label>
                                                            <select class="form-select" id="nri" name="nri">
                                                                <option value="3">Select Option</option>
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-2 text-center">
                                                            <button class="btn btn-success btn-sm"><i class="fa fa-search"
                                                                    aria-hidden="true"></i> Search</button>
                                                        </div>
                                                        {{-- div ends --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        {{-- filter ends --}}
                                    </div>

                                    {{-- data display area --}}
                                    <div class="col-md-9 col-sm-12">
                                        <form action="{{ route('saveprofilelistsend') }}" method="post"
                                            id="selectedProfileForm">
                                            @csrf
                                            <input type="number" name="user_id_opened" class="d-none"
                                                value="{{ $_GET['user_id'] }}">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row mb-2">
                                                        <div class="col-sm-3 profile_btn_div">
                                                            <button type="button"
                                                                class="btn btn-danger profileDetails mb-2 mb-sm-0"> Profile
                                                                Detail</button>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <a href="#"
                                                                class="btn btn-warning mb-2 mb-sm-0 addUserNotes"
                                                                userId="{{ $_GET['user_id'] }}"> Update Note</a>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <a href="#"
                                                                class="btn btn-success mb-2 mb-sm-0 addBasicProfile"
                                                                userId="{{ $_GET['user_id'] }}">Add Basic Profile</a>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="float-sm-end btn_save">
                                                                <button type="sutmit" name="submit"
                                                                    class="btn btn-success mb-2 mb-sm-0 ">
                                                                    <i class="mdi mdi-whatsapp"></i></button>
                                                            </div>
                                                        </div><!-- end col-->
                                                    </div>
                                                    <!-- end row -->

                                                    <div class="table-responsive">
                                                        <div id="" class="dt-bootstrap4 no-footer">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table class="table table-centered "
                                                                        id="products-datatable" role="grid"
                                                                        aria-describedby="products-datatable_info">
                                                                        <thead class="table-light">
                                                                            <tr role="row">
                                                                                <th></th>
                                                                                <th class="all">Name</th>
                                                                                <th>User Id</th>
                                                                                <th>Kundli Score</th>
                                                                                <th>Caste</th>
                                                                                <th>Birth Date</th>
                                                                                <th>Height / Weight</th>
                                                                                <th>Income</th>
                                                                                <th>Food Choice</th>
                                                                                <th>NRI</th>
                                                                                <th>Disablity</th>
                                                                                <th>Wish to Settle Abroad</th>
                                                                                <th>Family Income</th>
                                                                                <th>Qualification</th>
                                                                                <th>Occupation</th>
                                                                                <th>City</th>
                                                                                <th>Marital Status</th>
                                                                                <th>Age</th>
                                                                                <th>Manglik Status</th>
                                                                                <th>Working City</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="filteredDataDiv">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- data display area ends --}}
                                </div>
                            </div>
                            <div class="tab-pane sentProfileList" id="profile-b2">
                                {{-- send profile list --}}
                                <div class="accordion" id="accordionExample">
                                </div>
                            </div>
                            <div class="tab-pane yesPendingDiv" id="messages-b2">
                                {{-- yes pending --}}
                            </div>
                            <div class="tab-pane" id="settings-b2">
                                {{-- premium meetings starts --}}
                                <div class="card-body">
                                    <h4 class="header-title mb-4">Default Tabs</h4>

                                    <ul class="nav nav-tabs">
                                        <li class="nav-item show">
                                            <a href="#home" data-bs-toggle="tab" aria-expanded="false"
                                                class="nav-link">
                                                <span class="d-inline-block d-sm-none"><i
                                                        class="mdi mdi-home-variant"></i></span>
                                                <span class="d-none d-sm-inline-block">Pending Meeting</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profile" data-bs-toggle="tab" aria-expanded="true"
                                                class="nav-link">
                                                <span class="d-inline-block d-sm-none"><i
                                                        class="mdi mdi-account"></i></span>
                                                <span class="d-none d-sm-inline-block">Yes Meeting</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#messages" data-bs-toggle="tab" aria-expanded="false"
                                                class="nav-link">
                                                <span class="d-inline-block d-sm-none"><i
                                                        class="mdi mdi-email-variant"></i></span>
                                                <span class="d-none d-sm-inline-block">No Meeting</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane premiumMettingLists active row" id="home">
                                            {{-- meeting pending --}}
                                        </div>
                                        <div class="tab-pane premiumMettingYesLists row" id="profile">
                                            {{-- meeting yes --}}
                                        </div>
                                        <div class="tab-pane premiumMettingNoLists row" id="messages">
                                            {{-- meeting no --}}
                                        </div>
                                    </div>
                                </div>
                                {{-- premium meeting ends --}}
                            </div>
                            <div class="tab-pane historyDisplay" id="settings-b3">
                                {{-- User History starts --}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-3">
                                                    <button type="button" class="btn btn-success"
                                                        id="generatePdf">Report</button>
                                                </div>
                                                <div class="col-9">
                                                    <form action="" method="post" id="historyDetailsFilter">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-4">
                                                                @php
                                                                    $max_date = date('Y-m-d', strtotime('-7 Days'));
                                                                @endphp
                                                                <input type="date" id="start_date" name="start_date"
                                                                    class="form-control" max="{{ $max_date }}"
                                                                    value="{{ $max_date }}">Start
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="date" id="end_date" name="end_date"
                                                                    class="form-control"
                                                                    max="{{ date('Y-m-d', strtotime('+1 Days')) }}"
                                                                    value="{{ date('Y-m-d') }}">End
                                                            </div>
                                                            <div class="col-4">
                                                                <button class="btn btn-success btn-sm"><i
                                                                        class="fa fa-search" aria-hidden="true"></i>
                                                                    Search</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container" id="historyDetails">

                                        </div>
                                        <div class="container" id="sentProfileDetails">

                                        </div>
                                        <div class="container" id="likedByMeProfileDetails">

                                        </div>
                                        <div class="container" id="rejectedByMeProfileDetails">

                                        </div>
                                        <div class="container" id="rejectedPremiumProfileDetails">

                                        </div>
                                        <div class="container" id="likedPremiumProfileDetails">

                                        </div>
                                        <div class="container" id="premiumMeetingResultDetails">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- tabbed panel ends --}}
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

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
                                                    <div id="product-carousel"
                                                        class="carousel slide product-detail-carousel"
                                                        data-bs-ride="carousel">

                                                        <div class="carousel-inner mainImage">

                                                        </div>
                                                    </div>
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

                                                    <div>
                                                        <form class="d-flex flex-wrap align-items-center mb-3">

                                                            <label class="my-1 me-2" for="quantityinput">Profile Send
                                                                Day</label>
                                                            <div class="me-sm-3">
                                                                <select class="form-select my-1" id="profileSendDay">
                                                                    <option value="">Select</option>
                                                                    <option value="Mon">Mon</option>
                                                                    <option value="Tue">Tue</option>
                                                                    <option value="Wed">Wed</option>
                                                                    <option value="Thu">Thu</option>
                                                                    <option value="Fri">Fri</option>
                                                                    <option value="Sat">Sat</option>
                                                                    <option value="Sun">Sun</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mb-4">Other Details</h4>

                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a href="#profile_family" data-bs-toggle="tab" aria-expanded="false"
                                                        class="nav-link active">
                                                        <span class="d-inline-block d-sm-none"><i
                                                                class="mdi mdi-home-variant"></i></span>
                                                        <span class="d-none d-sm-inline-block">Family Detail</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#profile_preferences" data-bs-toggle="tab"
                                                        aria-expanded="true" class="nav-link preferenceDetailsNav">
                                                        <span class="d-inline-block d-sm-none"><i
                                                                class="mdi mdi-account"></i></span>
                                                        <span class="d-none d-sm-inline-block">Preferences</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="profile_family">
                                                    <table class="table table-bordered table-centered mb-0">
                                                        <tr>
                                                            <th>Unmarried Brother</th>
                                                            <th>Unmarried Sister</th>
                                                            <th>Married Brother</th>
                                                            <th>Married Sister</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="userUnmarriedBrothers"></td>
                                                            <td class="userUnmarriedSisters"></td>
                                                            <td class="userMarriedBrothers"></td>
                                                            <td class="userMarriedSisters"></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Family Type</th>
                                                            <th>House Type</th>
                                                            <th>Father Status</th>
                                                            <th>Mother Status</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="userFamilyType"></td>
                                                            <td class="userHouseType"></td>
                                                            <td class="fatherStatusUser"></td>
                                                            <td class="motherStatusUser"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Father Occupation</th>
                                                            <th>Mother Occupation</th>
                                                            <th>Family Income</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="userFatherOccupation"></td>
                                                            <td class="userMotherOccupation"></td>
                                                            <td class="userFamilyIncome"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="profile_preferences">

                                                    <table class="table table-striped table-inverse">
                                                        <tr>
                                                            <div class="row">
                                                                <div class="col-sm-4"><strong>Height</strong> : <span
                                                                        class="minheight_txt"></span> - <span
                                                                        class="maxheight_txt"></span></div>
                                                                <div class="col-sm-4"><strong>Income</strong> : <span
                                                                        class="min_income_pref"></span> - <span
                                                                        class="max_income_pref"></span></div>
                                                                <div class="col-sm-4"><strong>Age</strong> : <span
                                                                        class="min_age_pref"></span> - <span
                                                                        class="max_age_pref"></span></div>
                                                                <div class="col-sm-4"></div>
                                                            </div>
                                                        </tr>
                                                        <tr>
                                                            <th>Caste</th>
                                                            <td colspan="3" class="caste_prefs"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Marital Status</th>
                                                            <td class="marital_status_pref"></td>
                                                            <th>Manglik</th>
                                                            <td class="manglik_pref"></td>
                                                        </tr>
                                                        <tr>
                                                            {{-- <th>City</th>
                                                            <td class="city_pref"></td> --}}
                                                            <th>Citizenship</th>
                                                            <td class="citizenship_pref" colspan="3"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>State</th>
                                                            <td class="state_pref"></td>
                                                            <th>Country</th>
                                                            <td class="coutnry_pref"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- user etails modal ends --}}

    {{-- update status modals starts --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User Status</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updatepremiummeetingstatus') }}" method="post" id="premiumMeetingForm">
                        @csrf
                        <input type="number" name="user_id" id="user_id" class="d-none"
                            value="{{ $_GET['user_id'] }}">
                        <input type="number" name="matched_user_id" id="matched_user_id" class="d-none">
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Select Status</label>
                            <select name="user_status" class="col-md-8 form-select" id="user_status" required>
                                <option value="">Select</option>
                                <option value="0">Negative</option>
                                <option value="2">Pending</option>
                                <option value="3">Meeting</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Next Update Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="next_update"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3 meetingDiv" style="display: none">
                            <label for="" class="col-md-4">Meeting Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="meeting_date"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Comment</label>
                            <textarea name="comments" id="" class="col-md-8 form-control" required></textarea>
                        </div>
                        <div class="row mb-3 formOutputMeetingStatus">

                        </div>
                        <div class="row mb-3">
                            <button type="submit" name="submit" class="btn btn-warning btn-sm">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update status modal ends --}}


    {{-- update meeting status modals starts --}}
    <div class="modal fade" id="updateMeetingStatusModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User Status</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updatepremiummeetingstatus') }}" method="post"
                        id="premiumMeetingStatusForm">
                        @csrf
                        <input type="number" name="user_id" id="user_id" class="d-none"
                            value="{{ $_GET['user_id'] }}">
                        <input type="number" name="matched_user_id" id="matched_user_id_premium" class="d-none">
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Select Status</label>
                            <select name="user_status" class="col-md-8 form-select" id="user_status" required>
                                <option value="">Select</option>
                                <option value="4">Yes</option>
                                <option value="5">No</option>
                                <option value="2">Pending</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Next Update Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="next_update"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3 meetingDiv" style="display: none">
                            <label for="" class="col-md-4">Meeting Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="meeting_date"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Comment</label>
                            <textarea name="comments" id="" class="col-md-8 form-control" required></textarea>
                        </div>
                        <div class="row mb-3 formOutputPremiumMeetingStatus">

                        </div>
                        <div class="row mb-3">
                            <button type="submit" name="submit" class="btn btn-warning btn-sm">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update meeting status modal ends --}}

    {{-- user notes starts --}}
    <div class="modal fade" id="addBasicProfileModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Add Basic Profile</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('savebasicprofile') }}" method="post" id="addBasicProfileForm">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="user_id" value="{{ $_GET['user_id'] }}" class="d-none">
                                </div>
                                <div class="tab-content b-0 pt-0 mb-0">
                                    <div class="tab-pane active" id="account-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Full name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="full_name" name="full_name"
                                                            class="form-control" placeholder="Full Name">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Mobile</label>
                                                    <div class="col-md-2">
                                                        <select name="country_code" class="form-select"
                                                            id="country_code">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control" name="mobile"
                                                            id="mobile" placeholder="Mobile Number">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Alternate Mobile</label>
                                                    <div class="col-md-2">
                                                        <select name="alt_country_code" class="form-select"
                                                            id="alt_country_code">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control" name="alt_mob"
                                                            id="alt_mob" placeholder="Alternate Mobile Number">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Gender</label>
                                                    <div class="col-md-3">
                                                        <select name="gender" class="form-select" id="gender">
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Birth Date</label>
                                                    <div class="col-md-3">
                                                        @php
                                                            $max_date = date('Y-m-d', strtotime('-18 years'));
                                                        @endphp
                                                        <input type="date" id="birth_date" name="birth_date"
                                                            class="form-control" max="{{ $max_date }}"
                                                            value="{{ $max_date }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="email1">Birth
                                                        Place</label>
                                                    <div class="col-md-3">
                                                        @php
                                                            $max_date = date('Y-m-d', strtotime('-18 years'));
                                                        @endphp

                                                        <input type="date" id="birth_date" name="birth_date"
                                                            class="form-control" max="{{ $max_date }}"
                                                            value="{{ $max_date }}" required>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Birth Time</label>
                                                    <div class="col-md-3">
                                                        <input type="time" id="birth_time" name="birth_time"
                                                            class="form-control" value="{{ date('H:i:s') }}" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Religion</label>
                                                    <div class="col-md-3">
                                                        <select name="religion" class="form-select" id="religion"
                                                            required>

                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Castes</label>
                                                    <div class="col-md-3">
                                                        <select name="castes" class="form-select"
                                                            id="basic_profile_castes">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Marital Status</label>
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
                                                    <label class="col-md-3 col-form-label">Food Choice</label>
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
                                                    <label class="col-md-3 col-form-label">Height</label>
                                                    <div class="col-md-3">
                                                        <select name="height" id="height" class="form-control">
                                                            <option value="">Select Height</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Weight</label>
                                                    <div class="col-md-3">
                                                        <input type="text" id="weight" name="weight"
                                                            class="form-control" value="60">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Education</label>
                                                    <div class="col-md-3">
                                                        <select name="education_list" class="form-select"
                                                            id="education_list">
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Occupation</label>
                                                    <div class="col-md-3">
                                                        <select name="occupation_list" class="form-select"
                                                            id="occupation_list">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Working City</label>
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
                                                    <label class="col-md-3 col-form-label">Yearly Income</label>
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
                                                <div class="row-mb-3">
                                                    <div class="col-md-6 offset-md-2 form_output text-capitalize"></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Gotra</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="gotra" id="gotra"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Father Status</label>
                                                    <div class="col-md-3">
                                                        <select name="father_status" id="father_status"
                                                            class="form-select" required>
                                                            <option value="">Select Father Status</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Mother Status</label>
                                                    <div class="col-md-3">
                                                        <select name="mother_status" id="mother_status"
                                                            class="form-select" required>
                                                            <option value="">Select Mother Status</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Wish To Go Abroad</label>
                                                    <div class="col-md-3">
                                                        <select name="wish_to_go_abroad" class="form-select"
                                                            id="wish_to_go_abroad">
                                                            <option selected value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Are You NRI</label>
                                                    <div class="col-md-3">
                                                        <select name="is_nri" class="form-select" id="is_nri">
                                                            <option selected value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Family Income</label>
                                                    <div class="col-md-3">
                                                        <select name="family_income" class="form-select"
                                                            id="family_income" required>
                                                            @foreach ($income_range as $annual_income)
                                                                <option value="{{ $annual_income[0] }}">
                                                                    {{ $annual_income[1] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Manglik Status</label>
                                                    <div class="col-md-3">
                                                        <select name="profile_manglik_status" class="form-select"
                                                            id="profile_manglik_status" required>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Any Disability</label>
                                                    <div class="col-md-3">
                                                        <select name="is_disable" class="form-select" id="is_disable">
                                                            <option selected value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Disability Part</label>
                                                    <div class="col-md-3">
                                                        <input type="text" id="disabled_part" name="disabled_part"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Company Name</label>
                                                    <div class="col-md-3">
                                                        <input type="text" id="company" name="company"
                                                            class="form-control">
                                                    </div>
                                                    <label class="col-md-3 col-form-label">Designation</label>
                                                    <div class="col-md-3">
                                                        <input type="text" id="designation" name="designation"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label">Upload Photo</label>
                                                    <div class="col-md-3">
                                                        <input type="file" name="photo" accept="image/*"
                                                            class="js-photo-upload">
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <div class="form-group float-end">
                                    <li class="next list-inline-item float-end submit_btn_li">
                                        <button class="btn btn-success waves-effect waves-light" type="submit"
                                            name="submit">Save</button>
                                    </li>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 mt-3 userNotesData" style="height: 30vh; overflow:scroll">
                        </div>
                    </div>
                </div>
                <div class="row-mb-3">
                    <div class="col-md-6 offset-md-2 form_output text-capitalize"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- user notes ends --}}
    <div class="d-none" id="hiddenUrl"></div>
    <div class="error"> </div>
    <style>
        .close {
            position: relative;
            right: 0;
            display: inline-block;
            margin-left: 20px;
            border: 0px;
            background-color: red;
            color: white;

        }

        .error {
            padding: 10px 5px;
            width: auto;
            position: fixed;
            top: 10px;
            right: 0;
            z-index: 99999999999999;
        }

        .img-fluid {
            height: 300px;
        }

        .dropdown-menu {
            overflow-y: auto !important;
            max-height: auto;
        }

        .populateKundliScore {
            cursor: pointer;
        }
    </style>

@endsection
@section('custom-scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script>
        populateHeight();
        $('.selectpicker').selectpicker();


        function populateHeight() {
            var height_values = '<option value="">Select Height</option>';
            let userMinHeightPref = "{{ $user_preference['height_min_s'] }}";
            let userMaxHeightPref = "{{ $user_preference['height_max_s'] }}";
            for (let k = 48; k < 96; k++) {
                height_values += `<option value="${k}">${Math.floor(k/12)} Ft ${k%12} In</option>`;
            }
            $('#min_height').html(height_values);
            $('#max_height').html(height_values);
            $('#min_height').val(userMinHeightPref);
            $('#max_height').val(userMaxHeightPref)
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
                    $('#basic_profile_castes').html(caste_html);
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
                    $('#profile_manglik_status').html(marital_status_html);
                    $('#profile_manglik_status').val(2);
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
                    $('#country_code').val(91);
                    $('#alt_country_code').html(countryHtml);
                    $('#alt_country_code').val(91);
                }
            });
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

        $(document).ready(function() {

            $(document).on('click', '.select_all', function(e) {
                e.preventDefault();
                $("#caste_lists > option").prop("selected", "selected");
                $(`#caste_lists`).chosen('destroy')
                $(`#caste_lists`).chosen({
                    hide_results_on_select: false,
                })
            });

            $(document).on('click', '.clear_all', function(e) {
                e.preventDefault();
                $('#caste_lists').val('');
                $(`#caste_lists`).chosen('destroy')
                $(`#caste_lists`).chosen({
                    hide_results_on_select: false,
                })
            });

            var table_data = '';
            $(document).on('click', '.profileDetails', function(e) {
                e.preventDefault();
                getUserDetails();
                $('#userDetailsModal').modal('show');
            });

            $(document).on('click', '.user_profile', function() {
                var userId = $(this).val();
                var selectedPorfilesArray = [];
                var prevData = localStorage.getItem("selectedProfilesArray");
            });

            $(document).on('click', '.addUserNotes', function(e) {
                e.preventDefault();
                $('#userNotesModel').modal('show');
            });

            $(document).on('click', '.addBasicProfile', function(e) {
                e.preventDefault();
                $('#addBasicProfileModel').modal('show');
            });


            // submit add basic profile form
            $(document).on('submit', '#addBasicProfileForm', function(e) {
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
                                '<div class="alert alert-success" role="alert"><strong>Profile Added Successfully</strong></div>';
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
                            '<button type="submit" class="btn btn-primary">Submit</button>'
                        );
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
                            '<button type="submit" class="btn btn-primary">Submit</button>'
                        );
                    }
                });
            });

            $(document).on('submit', '#selectedProfileForm', function(e) {
                e.preventDefault();
                $('.btn_save').html("Please Wait.........");
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(profileResponse) {
                        $('.btn_save').html(
                            '<button type="sutmit" name="submit" class="btn btn-success mb-2 mb-sm-0 "><i class="mdi mdi-whatsapp"></i></button>'
                        )
                        if (profileResponse != 'null') {
                            if (profileResponse.type == true) {
                                if (profileResponse.profile_list != '') {
                                    let link = document.createElement('a');
                                    link.href =
                                        `profiles-lists-pdf?user_ids=${profileResponse.profile_list}`;
                                    link.target = '_blank';
                                    link.click();
                                }
                                if (profileResponse.already_exit != '') {
                                    $('.error').html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <strong>Already Sent!</strong>Ids:[ ${profileResponse.already_exit}]
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                         <span aria-hidden="true">&times;</span>
                                                                         </button>
                                                            </div>`);
                                } else {
                                    $('.error').html('')
                                }
                            } else {
                                alert(profileResponse.message);
                            }
                        } else {
                            alert('Please Select Profile')
                        }
                    }
                });
            });

            table_data = $('#products-datatable').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getfilteredprofiles') }}",
                    "type": "get",
                    "processing": true,
                    "serverSide": true,
                    "deferLoading": 0,
                    "data": function(d) {
                        // localStorage.clear();
                        d.min_age = localStorage.getItem("minAge");
                        d.max_age = localStorage.getItem("maxAge");
                        d.min_height = localStorage.getItem("minHeight");
                        d.max_height = localStorage.getItem("maxHeight");
                        d.marital_status = localStorage.getItem("maritalStatus");
                        d.manglik_status = localStorage.getItem("manglikStatus");
                        d.food_choice = localStorage.getItem("foodChoice");
                        d.working = localStorage.getItem("workingStatus");
                        d.min_income = localStorage.getItem("minIncome");
                        d.max_income = localStorage.getItem('maxIncome');
                        d.filter_type = localStorage.getItem("filterType");
                        d.kundli_score = localStorage.getItem("kundliScore");
                        d.user_id = "{{ $_GET['user_id'] }}";
                        d.castes = localStorage.getItem("castes");
                        d.user_gender = localStorage.getItem("userGender");
                        d.wish_to = localStorage.getItem("wish_to");
                        d.nri = localStorage.getItem("nri");
                        d.disabled_profiles = localStorage.getItem("disabled_profiles");
                        // console.log(d);
                    }
                },
                "columns": [{
                        data: null,
                        orderable: false,
                        render: function(data) {
                            return ` 
                            ${data.is_premium == 1 ?'<i class="mdi mdi-crown m-auto" style="color:#f28f0d;font-size:25px"></i>' :''} 
                            <div class="form-check font-16 mb-0">
                                     
                                        <input id="${data.id}" name="profile_selected[]" class="user_profile" value="${data.id}" type="checkbox">
                                        <label class="form-check-label">&nbsp;</label>
                                    </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            var filteredData = '';
                            if (data.user_photos.length > 0) {
                                var photo = data.user_photos[0]
                                filteredData +=
                                    `<img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${photo.photo_url}" alt="image" class="avatar-sm rounded mt-2">
                                     <h5 class="m-0 d-inline-block align-middle">`;
                            }
                            if (data.is_premium == 1) {
                                filteredData +=
                                    `<a href="#" class="diplayUserDetails text-success" displayUser="${data.id}"> ${data.name}</a>`;
                            } else {
                                filteredData +=
                                    `<a href="#" class="text-dark diplayUserDetails" displayUser="${data.id}"> ${data.name}</a>`;
                            }
                            filteredData += '</h5>';
                            return filteredData;
                        }
                    },
                    {
                        data: 'id',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<span class="badge bg-success populateKundliScore userKundliNo${data.id}" displayUser="${data.id}" firstuser="{{ $_GET['user_id'] }}"><i class="mdi mdi-star"></i></span>`;
                        }
                    },
                    {
                        data: 'caste',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.birth_date != null) {
                                var spelitDate = data.birth_date.split(" ");
                                return spelitDate[0];
                            } else {
                                return "Not Given"
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.height_int) {
                                return `${parseInt(data.height_int/12)}ft ${parseInt(data.height_int%12)}in/ ${data.weight}Kg`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.annual_income > 1) {
                                return (data.annual_income +
                                    'lpa');
                            } else {
                                return data.annual_income + 'lpa';
                            }

                            return data.annual_income + 'lpa';
                        }
                    },
                    {
                        data: 'food_choice',
                    }, {
                        data: null,
                        render: function(data) {
                            if (data.working_city) {
                                if (data.working_city.search("India") > 0 || data
                                    .working_city == null || data.working_city ==
                                    undefined ||
                                    data.working_city == 'NA' || data.working_city ==
                                    'N.A.' || data.working_city ==
                                    'na') {
                                    return ' Indian';
                                } else {
                                    return ' NRI';
                                }
                            } else {
                                return ' Indian';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.is_disable == 0) {
                                return "No"
                            }
                            return "Yes"
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.wish_toabroad == 0) {
                                return "No"
                            }
                            return "Yes"
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.family_income > 100) {
                                return (parseFloat(data.family_income) / 100000).toFixed(2) +
                                    'lpa';
                            } else {
                                return data.family_income + 'lpa';
                            }
                        }
                    },
                    {
                        data: 'education',
                    },
                    {
                        data: 'occupation',
                    },
                    {
                        data: 'city'
                    },
                    {
                        data: 'marital_status'
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.birth_date != null) {
                                var today = new Date();
                                var birthDate = new Date(data.birth_date);
                                var age = today.getFullYear() - birthDate.getFullYear();
                                var m = today.getMonth() - birthDate.getMonth();
                                if (m < 0 || (m === 0 && today.getDate() < birthDate
                                        .getDate())) {
                                    age--;
                                }
                                return age;
                            } else {
                                return "NA";
                            }
                        }
                    },
                    {
                        data: 'manglik'
                    },
                    {
                        data: 'working_city'
                    }
                ],
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                }
            });

            $(document).on('submit', '#filterForm', function(e) {
                e.preventDefault();
                var dataForm = $(this).serialize();

                setLoadData()
            });
            setLoadData()

            function setLoadData() {
                var minAge = $('#min_age').val();
                var maxAge = $('#max_age').val();

                var minHeight = $('#min_height').val();
                var maxHeight = $('#max_height').val();

                var castes = $('#caste_lists').val();
                var maritalStatus = $('#maritalStatus').val();

                var manglikStatus = $('#manglik_status2').val();
                var foodChoice = $('#foor_choice').val();

                var workingStatus = $('#job_status').val();
                // yearly_income filter_type
                var minIncome = $('#yearly_income').val();
                var filterType = "";
                var maxIncome = $("#yearly_income_max").val();

                var disabled_profiles = $('#disabled_profiles').val();
                var no_caste_bar = $('#no_caste_bar').val();
                var kundliScore = $('#get_kundli_score').val();

                var userGender = $('#user_denger').val();
                var wish_to = $('#wish_to').val();
                var nri = $('#nri').val();

                localStorage.setItem("minAge", minAge ?? 18);
                localStorage.setItem("maxAge", maxAge ?? 70);

                localStorage.setItem("minHeight", minHeight ?? 4);
                localStorage.setItem("maxHeight", maxHeight ?? 10);

                localStorage.setItem("castes", castes ?? [0]);
                localStorage.setItem("maritalStatus", maritalStatus ?? [0]);

                localStorage.setItem("manglikStatus", manglikStatus ?? 0);
                localStorage.setItem("foodChoice", foodChoice ?? 0);

                localStorage.setItem("minIncome", minIncome ?? 0);
                localStorage.setItem('maxIncome', maxIncome ?? 100);
                localStorage.setItem("filterType", filterType ?? null);

                localStorage.setItem("disabled_profiles", disabled_profiles);
                localStorage.setItem("no_caste_bar", no_caste_bar);
                localStorage.setItem("kundliScore", kundliScore ?? undefined);
                localStorage.setItem("userGender", userGender ?? 1);
                localStorage.setItem("workingStatus", workingStatus ?? 1);
                localStorage.setItem("wish_to", wish_to ?? 3);
                localStorage.setItem("nri", nri ?? 3);
                // console.log(localStorage);
                table_data.ajax.reload();

            }
            $(document).on('submit', '#userNotesForm', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(notesResponse) {
                        if (notesResponse.type == true) {
                            $('#user_comments').val('');
                            var messageHtml =
                                `<span class="text-success">Record Addedd</span>`;
                            $('#userNotesForm')[0].reset();
                            getUserDetails();
                            $('#userDetailsModal').modal('hide');
                        } else {
                            var messageHtml =
                                `<span class="text-danger">Failed to Update Record</span>`;
                        }
                        $('.commentOutput').html(messageHtml);
                        window.setTimeout(function() {
                            $('.commentOutput').html('');
                        }, 2500);
                    }
                });
            });
            getUserDetails();

            function getUserDetails() {
                $('.profile_btn_div').html('<div class="spinner-border text-danger m-2" role="status"></div>');
                $.ajax({
                    url: "{{ route('getuserdatabyid') }}",
                    type: "get",
                    data: {
                        "user_id": "{{ $_GET['user_id'] }}"
                    },
                    success: function(userResponse) {
                        let userHeight;
                        let userFt = parseInt(parseInt(userResponse.height_int) / 12);
                        let userInch = parseInt(userResponse.height_int) % 12;
                        userHeight = userFt + "Ft " + userInch + "In";
                        let userIncome = userResponse.monthly_income;
                        $('#max_height').val(userResponse.userpreference.height_max);
                        $('#min_height').val(userResponse.userpreference.height_min);

                        $('#max_age').val(userResponse.userpreference.age_max);
                        $('#min_age').val(userResponse.userpreference.age_min);

                        $('#maritalStatus').val(userResponse.userpreference.marital_statusPref);
                        $('#manglik_status2').val(userResponse.userpreference.manglikPref);
                        $('#foor_choice').val(userResponse.userpreference.food_choicePref);
                        $('#min_income').val(userResponse.userpreference.income_min);
                        $('#max_income').val(userResponse.userpreference.income_max);
                        $('#user_denger').val(userResponse.genderCode_user);
                        $('#profileSendDay').val(userResponse.profile_sent_day);

                        $('.imageDisplayArea').html(mainImageHtml);
                        $('.userCaste').text(userResponse.religion + ' : ' + userResponse.caste);
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
                        $('.birthDateUser').text(userResponse.birth_date);
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

                        $('.userFatherOccupation').text(userResponse.occupation_father);
                        $('.userMotherOccupation').text(userResponse.occupation_mother);
                        $('.userFamilyIncome').text(userResponse.family_income);

                        localStorage.setItem("minAge", userResponse.userpreference.age_min);
                        localStorage.setItem("maxAge", userResponse.userpreference.age_max);

                        localStorage.setItem("minHeight", userResponse.userpreference.height_min);
                        localStorage.setItem("maxHeight", userResponse.userpreference.height_max);
                        localStorage.setItem("castes", userResponse.userpreference.castePref);
                        localStorage.setItem("maritalStatus", userResponse.userpreference
                            .marital_statusPref);

                        localStorage.setItem("manglikStatus", userResponse.userpreference
                            .manglikPref);
                        localStorage.setItem("foodChoice", userResponse.userpreference
                            .food_choicePref);

                        localStorage.setItem("minIncome", userResponse.userpreference.income_min);
                        localStorage.setItem("maxIncome", userResponse.userpreference.income_max);

                        localStorage.setItem("disabled_profiles", 0);
                        localStorage.setItem("no_caste_bar", 0);
                        localStorage.setItem("kundliScore", 0);
                        localStorage.setItem("userGender", userResponse.genderCode_user);
                        localStorage.setItem("workingStatus", 0);
                        if (userResponse.photo != null) {
                            // var parsed_url = JSON.parse(userResponse.photo_url);
                            var mainImageHtml = '';
                            mainImageHtml +=
                                `<img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${userResponse.photo}" class="w-75">`;
                            $('.mainImage').html(mainImageHtml);
                        }

                        //$('#userDetailsModal').modal('show');
                        $('.profile_btn_div').html(
                            '<button type="button" class="btn btn-danger profileDetails mb-2 mb-sm-0"> Profile Detail</button>'
                        );

                        let setUserPreferences = userResponse.userpreference;
                        $('.minheight_txt').text(setUserPreferences.height_min);
                        $('.maxheight_txt').text(setUserPreferences.height_max);

                        $('.min_income_pref').text(setUserPreferences.income_min);
                        $('.max_income_pref').text(setUserPreferences.income_max);

                        $('.caste_prefs').text(setUserPreferences.caste);

                        $('.citizenship_pref').text(setUserPreferences.citizenship);

                        $('.marital_status_pref').text(setUserPreferences.marital_status);

                        $('.manglik_pref').text(setUserPreferences.manglik);

                        $('.city_pref').text(setUserPreferences.pref_city);

                        $('.state_pref').text(setUserPreferences.pref_state);

                        $('.coutnry_pref').text(setUserPreferences.pref_country);
                        $('.min_age_pref').text(setUserPreferences.age_min);
                        $('.max_age_pref').text(setUserPreferences.age_max);

                        var descriptionDetails = setUserPreferences.description;

                        if (descriptionDetails) {
                            var htmlPrefs = "";
                            let splitDataPref = descriptionDetails.split(";");
                            for (let q = 0; q < splitDataPref.length; q++) {
                                const preferencesData = splitDataPref[q];
                                htmlPrefs += `<p>${preferencesData}</p>`;
                            }
                            $('.userNotesData').html(htmlPrefs);
                        }
                    }
                });
            }

            $(document).on('change', '#profileSendDay', function() {
                var sentDay = $(this).val();
                var userId = "{{ $_GET['user_id'] }}";
                $.ajax({
                    url: "{{ route('updtepropfilesentday') }}",
                    type: "get",
                    data: {
                        "sent_day": sentDay,
                        "user_id": userId
                    },
                    success: function(updateSentDayResponse) {
                        alert('Record Updated');
                        $('#userDetailsModal').modal('hide');
                        getUserDetails();
                    }
                });
            });

            $(document).on('click', '.populateKundliScore', function(e) {
                e.preventDefault();
                // var userDOB = "{{ $_GET['user_birth'] }}";
                var matchingId = $(this).attr('firstuser');
                var userId = $(this).attr('displayUser');
                var kundliScorematched = '';
                $.ajax({
                    url: "{{ route('matchkundliscore') }}",
                    type: "get",
                    data: {
                        "userId": userId,
                        "matchingId": matchingId
                    },
                    success: function(kundliResponse) {
                        kundliScorematched +=
                            `<i class="mdi mdi-star"></i>${kundliResponse.data.total.received_points}/${kundliResponse.data.total.total_points}`;
                        $('.userKundliNo' + userId).html(kundliScorematched);
                    }
                });
            });

            $(document).on('change', '#user_status', function() {
                var statusValue = '';
                if ($(this).val() == 3) {
                    $('.meetingDiv').show();
                } else {
                    $('.meetingDiv').hide();
                }
            });

            $(document).on('click', '.sendProfileListNav', function() {
                var userId = "{{ $_GET['user_id'] }}";
                var sendProfileHtml = '';
                $('.sentProfileList').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                $.ajax({
                    url: "{{ route('sentprofilefromusermatch') }}",
                    type: "get",
                    data: {
                        "user_id": userId
                    },
                    success: function(sendProfileLoadingResponse) {
                        sendProfileHtml += `<div class="row">`;
                        $('.sent_profile_count').text(sendProfileLoadingResponse.length);
                        for (let v = 0; v < sendProfileLoadingResponse.length; v++) {
                            const sendData = sendProfileLoadingResponse[v];

                            sendProfileHtml += `<div class="col-md-6 col-xl-3 div${sendData.user_id}">
                                    <div class="card product-box">

                                        <div class="product-img">
                                            <div class="p-3 text-center">
                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                    alt="profile-pic" class="img-fluid">
                                            </div>
                                            <div class="product-action">
                                                <div class="d-flex">
                                                    <a href="javascript: void(0);"
                                                        class="btn btn-purple d-block action-btn m-2" profileId="${sendData.user_id}" actionType="1"><i
                                                            class="ri-edit-2-fill align-middle"></i>
                                                        Yes</a>

                                                        <a href="javascript: void(0);"
                                                        class="btn btn-danger d-block action-btn m-2" profileId="${sendData.user_id}" actionType="0"><i
                                                            class="ri-edit-2-fill align-middle"></i>
                                                        No</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="product-info border-top p-3">
                                            <div>
                                                <h6 class="font-16 mt-0 mb-1"><a
                                                        href="#"
                                                        class="text-dark">${sendData.marital_status} (${sendData.user_mobile})</a>
                                                </h6>
                                                <p class="text-muted">
                                                    ${sendData.caste}
                                                </p>
                                                <h4 class="m-0"> <span
                                                        class="text-muted"> Name : ${sendData.name}</span>
                                                </h4>
                                            </div>

                                        </div> <!-- end product info-->

                                    </div>
                                </div>`;
                        }
                        sendProfileHtml += `</div>`;
                        $('.sentProfileList').html(sendProfileHtml);
                    }
                });
            });

            // update actions
            $(document).on('click', '.action-btn', function(e) {
                e.preventDefault();
                var userId = $(this).attr('profileid');
                var actionType = $(this).attr('actiontype');
                $.ajax({
                    url: "{{ route('updateuseraction') }}",
                    type: "get",
                    data: {
                        "match_id": userId,
                        "action": actionType,
                        "user_id": "{{ $_GET['user_id'] }}"
                    },
                    success: function(actionResponse) {
                        alert(actionResponse.message);
                        $('.div' + userId).hide();
                        $('.sent_profile_count').text(parseInt($('.sent_profile_count')
                            .text()) - 1);
                    }
                });
            });

            // get all yes peinding list
            $(document).on('click', '.yesPendingNav', function() {
                $('.yesPendingDiv').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                showYesPending();
            });

            // yes pending list
            function showYesPending() {
                var yesPrndingHtml = '';
                yesPrndingHtml += '<div class="row">';
                $.ajax({
                    url: "{{ route('getoverallyespendinguserdata') }}",
                    type: "get",
                    data: {
                        'user_id': "{{ $_GET['user_id'] }}",
                        'status': 2
                    },
                    success: function(contacteduserResponse) {
                        $('.yes_pending_count').text(contacteduserResponse.length);
                        for (let t = 0; t < contacteduserResponse.length; t++) {
                            const contactedUsers = contacteduserResponse[t];
                            yesPrndingHtml += `<div class="col-md-6 col-xl-3">
                                                        <div class="card product-box">

                                                            <div class="product-img">
                                                                <div class="p-3 text-center">
                                                                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${contactedUsers.photo}"
                                                                        alt="profile-pic" class="img-fluid">
                                                                </div>
                                                                <div class="product-action">
                                                                    <div class="d-flex">
                                                                        <a href="javascript: void(0);"
                                                                            class="btn btn-purple d-block updateStatusBtn m-2" profileId="${contactedUsers.user_id}" matchId="${contactedUsers.matched_id}" actionType="1"><i
                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                            Update Status</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="product-info border-top p-3">
                                                                <div>
                                                                    <h6 class="font-16 mt-0 mb-1"><a
                                                                            href="#"
                                                                            class="text-dark">${contactedUsers.marital_status} {${contactedUsers.user_mobile}}</a>
                                                                    </h6>
                                                                    <p class="text-muted">
                                                                       ${contactedUsers.caste}
                                                                    </p>
                                                                    <h4 class="m-0"> <span
                                                                            class="text-muted"> Name : ${contactedUsers.name}</span>
                                                                    </h4>
                                                                </div>

                                                            </div> <!-- end product info-->

                                                        </div>
                                                    </div>`;
                        }
                        yesPrndingHtml += '</div>';
                        $('.yesPendingDiv').html(yesPrndingHtml);
                    }
                });
            }

            $(document).on('submit', '#premiumMeetingForm', function(e) {
                e.preventDefault();
                var htmlMesage = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(meetingUpdateResponse) {
                        if (meetingUpdateResponse.type == true) {
                            $('#premiumMeetingForm')[0].reset();
                            htmlMesage =
                                '<div class="alert alert-success" role="alert"><strong>Message</strong> ' +
                                meetingUpdateResponse.message + '</div>';

                            showYesPending();
                            premiumMeetings();
                            window.setTimeout(function() {
                                $('#updateStatusModal').modal('hide');
                                $('.formOutputMeetingStatus').html('');
                            }, 1500);
                        } else {
                            htmlMesage =
                                '<div class="alert alert-primary" role="alert"><strong>Alert</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                        }
                        $('.formOutputMeetingStatus').html(htmlMesage);
                    }
                });
            });

            //premiumMeetingStatusForm
            $(document).on('submit', '#premiumMeetingStatusForm', function(e) {
                e.preventDefault();
                var htmlMesage = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(meetingUpdateResponse) {
                        if (meetingUpdateResponse.type == true) {
                            $('#premiumMeetingForm')[0].reset();
                            htmlMesage =
                                '<div class="alert alert-success" role="alert"><strong>Message</strong> ' +
                                meetingUpdateResponse.message + '</div>';

                            premiumMeetings();
                            window.setTimeout(function() {
                                $('#updateStatusModal').modal('hide');
                                $('.formOutputMeetingStatus').html('');
                            }, 1500);
                        } else {
                            htmlMesage =
                                '<div class="alert alert-primary" role="alert"><strong>Alert</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                        }
                        $('.formOutputPremiumMeetingStatus').html(htmlMesage);
                    }
                });
            });

            $(document).on('click', '.updateStatusBtn', function(e) {
                e.preventDefault();
                var amtchId = "";
                amtchId = $(this).attr('profileId');
                var userId = "{{ $_GET['user_id'] }}";
                if (userId == amtchId) {
                    amtchId = $(this).attr('matchid');
                } else {
                    amtchId = $(this).val();
                }
                $('#matched_user_id').val(amtchId);

                $('.formOutputMeetingStatus').html('');
                $('#updateStatusModal').modal('show');
            });

            // upadate premium meeting status
            $(document).on('click', '.updatePremiumStatusBtn', function(e) {
                e.preventDefault();
                $('#matched_user_id_premium').val($(this).attr('profileId'));
                $('.formOutputMeetingStatus').html('');
                $('#updateMeetingStatusModal').modal('show');
            });

            $(document).on('click', '#generatePdf', function(e) {
                e.preventDefault();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                window.open(
                    `history-details?user_id={{ $_GET['user_id'] }}&generate_pdf=true&start_date=${startDate}&end_date=${endDate}`
                );

            });

            // get history details
            $(document).on('click', '.historyNav', function() {
                $('#historyDetails').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                const today = new Date();
                const fifteenDaysAgo = new Date();
                fifteenDaysAgo.setDate(fifteenDaysAgo.getDate() - 15);
                historyDetails("{{ $max_date }}", "{{ date('Y-m-d', strtotime('+1 Days')) }}");
            });

            // get history details filter
            $(document).on('submit', '#historyDetailsFilter', function(e) {
                e.preventDefault();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                $('#historyDetails').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                historyDetails(startDate, endDate);
            });



            // history details list
            function historyDetails(startDate, endDate) {
                var historyDetailsHtml = '';
                var sendProfileHtml = '';
                var rejectedByMeProfileHtml = '';
                var likedByMeProfileHtml = '';
                var rejectedPremiumProfileHtml = '';
                var premiumMeetingResultHtml = '';
                $.ajax({
                    url: "{{ route('historydetails') }}",
                    type: "get",
                    data: {
                        'user_id': "{{ $_GET['user_id'] }}",
                        'start_date': startDate,
                        'end_date': endDate
                    },
                    success: function(data) {
                        sendProfileHtml += `<div class="row">
                                                <h4 class="text-primary mb-3 mt-3 text-left">Sent Profiles(${data.countsentprofilelist})</h4>
                                            </div>
                                            <div class="row">`;
                        for (let v = 0; v < data.sentprofilelist.length; v++) {
                            const sendData = data.sentprofilelist[v];
                            sendProfileHtml += `<div class="col-3">
                                                    <div class="card product-box">
                                                        <div class="product-img">
                                                            <div class="p-3 text-center">
                                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                    alt="profile-pic" height="150px" width="150px">
                                                            </div>
                                                        </div>
                                                        <div class="product-info border-top p-3">
                                                            <div>
                                                                <h5> Name : ${sendData.name}</h5>
                                                                <h5> Mobile : ${sendData.mobile}</h5>
                                                                <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                <h6> Caste : ${sendData.caste}</h6>
                                                                <h6> Profile Sent Date : ${sendData.created_at.split('T')[0]    }</h6>
                                                            </div>
                                                        </div> <!-- end product info-->
                                                    </div>
                                                </div>`;
                        }
                        sendProfileHtml += `</div>`;

                        likedByMeProfileHtml += `<div class="row">
                                                    <h4 class="text-primary mb-3 mt-3 text-left">Liked By Me Profiles(${data.countlikedbymeprofilelist})</h4>
                                                </div>
                                            <div class="row">`;
                        for (let v = 0; v < data.likedbymeprofilelist.length; v++) {
                            const sendData = data.likedbymeprofilelist[v];
                            likedByMeProfileHtml += `<div class="col-3">
                                                        <div class="card product-box">
                                                            <div class="product-img">
                                                                <div class="p-3 text-center">
                                                                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                        alt="profile-pic" height="150px" width="150px">
                                                                </div>
                                                            </div>
                                                            <div class="product-info border-top p-3">
                                                                <div>
                                                                    <h5> Name : ${sendData.name}</h5>
                                                                    <h5> Mobile : ${sendData.mobile}</h5>
                                                                    <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                    <h6> Caste : ${sendData.caste}</h6>
                                                                    <h6> Profile Liked Date : ${sendData.updated_at.split('T')[0]}</h6>
                                                                </div>
                                                            </div> <!-- end product info-->
                                                        </div>
                                                    </div>`
                        }
                        likedByMeProfileHtml += `</div>`;

                        rejectedByMeProfileHtml += `<div class="row">
                                                        <h4 class="text-primary mb-3 mt-3 text-left">Rejected By Me Profiles(${data.countrejectedbymelist})</h4>
                                                    </div>
                                                    <div class="row">`;
                        for (let v = 0; v < data.rejectedbymeprofilelist.length; v++) {
                            const sendData = data.rejectedbymeprofilelist[v];
                            rejectedByMeProfileHtml += `<div class="col-3">
                                                    <div class="card product-box">
                                                        <div class="product-img">
                                                            <div class="p-3 text-center">
                                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                    alt="profile-pic" height="150px" width="150px">
                                                            </div>
                                                        </div>
                                                        <div class="product-info border-top p-3">
                                                            <div>
                                                                <h5> Name : ${sendData.name}</h5>
                                                                <h5> Mobile : ${sendData.mobile}</h5>
                                                                <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                <h6> Caste : ${sendData.caste}</h6>
                                                                <h6> Profile Rejected Date : ${sendData.updated_at.split('T')[0]}</h6>
                                                            </div>
                                                        </div> <!-- end product info-->
                                                    </div>
                                                </div>`
                        }
                        rejectedByMeProfileHtml += `</div>`;

                        rejectedPremiumProfileHtml += `<div class="row">
                                                    <h4 class="text-primary mb-3 mt-3 text-left">Rejected By Match(${data.countrejectedpremiummeetingslist})</h4>
                                                </div>
                                            <div class="row">`;
                        for (let v = 0; v < data.rejectedpremiummeetingslist.length; v++) {
                            const sendData = data.rejectedpremiummeetingslist[v];
                            var comment = JSON.parse(sendData.comments)
                            rejectedPremiumProfileHtml += `<div class="col-3">
                                                    <div class="card product-box">
                                                        <div class="product-img">
                                                            <div class="p-3 text-center">
                                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                    alt="profile-pic" height="150px" width="150px">
                                                            </div>
                                                        </div>
                                                        <div class="product-info border-top p-3">
                                                            <div>
                                                                <h5> Name : ${sendData.name}</h5>
                                                                <h5> Mobile : ${sendData.mobile}</h5>
                                                                <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                <h6> Caste : ${sendData.caste}</h6>
                                                                <h6> Rejected By Match Date : ${sendData.updated_at.split('T')[0]}</h6>
                                                                <h6> comments :`
                            i = 0
                            comment.forEach(function(item) {
                                i++
                                rejectedPremiumProfileHtml += i + ')' + item.comment + ".<br>"
                            });
                            rejectedPremiumProfileHtml += `</h6>
                                                            </div>
                                                        </div> <!-- end product info-->
                                                    </div>
                                                </div>`
                        }
                        rejectedPremiumProfileHtml += `</div>`;

                        premiumMeetingResultHtml += `<div class="row">
                                                    <h4 class="text-primary mb-3 mt-3 text-left">Premium Meeting Result</h4>
                                                </div>
                                            <div class="row">`;
                        for (let v = 0; v < data.premiummeetingslist.length; v++) {
                            const sendData = data.premiummeetingslist[v];
                            var comment = JSON.parse(sendData.comments)
                            if (sendData.status == "3") {
                                premiumMeetingResultHtml += `<div class="col-3">
                                                    <div class="card product-box">
                                                        <div class="product-img">
                                                            <div class="p-3 text-center">
                                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                    alt="profile-pic" height="150px" width="150px">
                                                            </div>
                                                        </div>
                                                        <div class="product-info border-top p-3">
                                                            <div>
                                                                <h5> Name : ${sendData.name}</h5>
                                                                <h5> Mobile : ${sendData.user_mobile}</h5>
                                                                <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                <h6> Caste : ${sendData.caste}</h6>
                                                                <h6> Meeting Date: ${sendData.updated_at.split('T')[0]}</h6>
                                                                <h6> Status : Pending </h6>
                                                                <h6> comments :
                                                                `
                                i = 0
                                comment.forEach(function(item) {
                                    i++
                                    premiumMeetingResultHtml += i + ')' + item.comment +
                                        ".<br>"
                                });
                                premiumMeetingResultHtml += `
                                                            </div>
                                                        </div> <!-- end product info-->
                                                    </div>
                                                </div>`
                            }
                            if (sendData.status == "4") {
                                premiumMeetingResultHtml += `<div class="col-3">
                                                    <div class="card product-box">
                                                        <div class="product-img">
                                                            <div class="p-3 text-center">
                                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                    alt="profile-pic" height="150px" width="150px">
                                                            </div>
                                                        </div>
                                                        <div class="product-info border-top p-3">
                                                            <div>
                                                                <h5> Name : ${sendData.name}</h5>
                                                                <h5> Mobile : ${sendData.user_mobile}</h5>
                                                                <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                <h6> Caste : ${sendData.caste}</h6>
                                                                <h6> Meeting Date: ${sendData.updated_at.split('T')[0]}</h6>
                                                                <h6> Status : Yes </h6>
                                                                <h6> comments :`
                                i = 0
                                comment.forEach(function(item) {
                                    i++
                                    premiumMeetingResultHtml += i + ')' + item.comment +
                                        ".<br>"
                                });
                                premiumMeetingResultHtml += `
                                                            </div>
                                                        </div> <!-- end product info-->
                                                    </div>
                                                </div>`
                            }
                            if (sendData.status == "5") {
                                premiumMeetingResultHtml += `<div class="col-3">
                                                                <div class="card product-box">
                                                                    <div class="product-img">
                                                                        <div class="p-3 text-center">
                                                                            <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                                                alt="profile-pic" height="150px" width="150px">
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-info border-top p-3">
                                                                        <div>
                                                                            <h5> Name : ${sendData.name}</h5>
                                                                            <h5> Mobile : ${sendData.user_mobile}</h5>
                                                                            <h6> Marital Status : ${sendData.marital_status}</h6>
                                                                            <h6> Caste : ${sendData.caste}</h6>
                                                                            <h6> Meeting Date : ${sendData.updated_at.split('T')[0]}</h6>
                                                                            <h6> Status : No </h6>
                                                                            <h6> comments :
                                                                            `
                                i = 0
                                comment.forEach(function(item) {
                                    i++
                                    premiumMeetingResultHtml += i + ')' + item.comment +
                                        ".<br>"
                                });
                                premiumMeetingResultHtml += `
                                                                        </div>
                                                                    </div> <!-- end product info-->
                                                                </div>
                                                            </div>`
                            }
                        }
                        premiumMeetingResultHtml += `</div>`;

                        $('#sentProfileDetails').html(sendProfileHtml);
                        $('#historyDetails').html(historyDetailsHtml);
                        $('#rejectedByMeProfileDetails').html(rejectedByMeProfileHtml);
                        $('#likedByMeProfileDetails').html(likedByMeProfileHtml);
                        $('#rejectedPremiumProfileDetails').html(rejectedPremiumProfileHtml);
                        $('#premiumMeetingResultDetails').html(premiumMeetingResultHtml);
                    }
                });
            }


            // get all premium meeting list
            $(document).on('click', '.premiumMeetingNav', function() {
                premiumMeetings();
            });

            // premium meeting list
            function premiumMeetings() {
                $('.premiumMettingLists').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                var premiumMeetingList = '';
                var premiumMeetingYesList = '';
                var premiumMeetingNoList = '';
                premiumMeetingList += '<div class="row">';
                premiumMeetingYesList += '<div class="row">';
                premiumMeetingNoList += '<div class="row">';
                $.ajax({
                    url: "{{ route('getallpreimumeetinglist') }}",
                    type: "get",
                    data: {
                        "user_id": "{{ $_GET['user_id'] }}",
                        "status": "3,4,5"
                    },
                    success: function(premiumMeetingListResponse) {
                        $('.premium_meeting_count').text(premiumMeetingListResponse.length);
                        for (let t = 0; t < premiumMeetingListResponse.length; t++) {
                            const premiumUsers = premiumMeetingListResponse[t];
                            if (premiumUsers.status == 3 || premiumUsers.status == null) {
                                premiumMeetingList +=
                                    `<div class="col-md-6 col-xl-3">
                                        <div class="card product-box">
                                            <div class="product-img">
                                                <div class="p-3 text-center">
                                                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${premiumUsers.photo}"
                                                        alt="profile-pic" class="img-fluid">
                                                </div>
                                                <div class="product-action">
                                                    <div class="d-flex">
                                                        <!--<a href="javascript: void(0);"
                                                            class="btn btn-purple d-block updateStatusBtn m-2" profileId="${premiumUsers.matched_id}" actionType="1"><i
                                                                class="ri-edit-2-fill align-middle"></i>
                                                            Update Status</a>-->

                                                        <a href="javascript: void(0);"
                                                            class="btn btn-purple d-block updatePremiumStatusBtn m-2" profileId="${premiumUsers.matched_id}" matchId="${premiumUsers.matched_id}" actionType="1"><i
                                                                class="ri-edit-2-fill align-middle"></i>
                                                            Update Status</a>

                                                        <!--<a href="javascript: void(0);"
                                                            class="btn btn-warning d-block seeComments m-2" userId="${premiumUsers.matched_id}"><i
                                                                class="ri-edit-2-fill align-middle"></i>
                                                            Comments Status</a>-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-info border-top p-3">
                                                <div>
                                                    <h6 class="font-16 mt-0 mb-1"><a
                                                            href="ecommerce-product-detail.html"
                                                            class="text-dark">${premiumUsers.marital_status} {${premiumUsers.user_mobile}}</a>
                                                    </h6>
                                                    <p class="text-muted">
                                                    ${premiumUsers.caste}
                                                    </p>
                                                    <h4 class="m-0"> <span
                                                            class="text-muted"> Name : ${premiumUsers.name}</span>
                                                    </h4>
                                                </div>`;
                                var parsedData = [];
                                if (premiumUsers.comments != null) {
                                    parsedData.push(JSON.parse(premiumUsers.comments));
                                }
                                if ((parsedData != null || parsedData != []) && parsedData.length >
                                    0) {
                                    premiumMeetingList +=
                                        `<div style="display:block" class="user${premiumUsers.matched_id}"><div class="collapse" id="collapseComment${premiumUsers.matched_id}"><a class="" data-toggle="collapse" href="#collapseComment${premiumUsers.matched_id}" role="button" aria-expanded="false" aria-controls="collapseExample">Click To View</a>`;
                                    for (let rt = 0; rt < parsedData.length; rt++) {
                                        const parsedJson = parsedData[rt];
                                        if (parsedJson != null) {
                                            premiumMeetingList +=
                                                `<p>${parsedJson.meeting_date} :- ${parsedJson.comment}</p>`;
                                        }
                                    }
                                    premiumMeetingList += `</div></div>`;
                                }
                                premiumMeetingList += ` </div></div></div>`;
                            } else if (premiumUsers.status == 4) {
                                premiumMeetingYesList +=
                                    `<div class="col-md-6 col-xl-3">
                                                                        <div class="card product-box">
                                                                            <div class="product-img">
                                                                                <div class="p-3 text-center">
                                                                                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${premiumUsers.photo}"
                                                                                        alt="profile-pic" class="img-fluid">
                                                                                </div>
                                                                                <div class="product-action">
                                                                                    <div class="d-flex">
                                                                                        <!--<a href="javascript: void(0);"
                                                                                            class="btn btn-purple d-block updateStatusBtn m-2" profileId="${premiumUsers.matched_id}" actionType="1"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Update Status</a>-->

                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-purple d-block updatePremiumStatusBtn m-2" profileId="${premiumUsers.matched_id}" matchId="${premiumUsers.matched_id}" actionType="1"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Update Status</a>

                                                                                        <!--<a href="javascript: void(0);"
                                                                                            class="btn btn-warning d-block seeComments m-2" userId="${premiumUsers.matched_id}"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Comments Status</a>-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="product-info border-top p-3">
                                                                                <div>
                                                                                    <h6 class="font-16 mt-0 mb-1"><a
                                                                                            href="ecommerce-product-detail.html"
                                                                                            class="text-dark">${premiumUsers.marital_status}</a>
                                                                                    </h6>
                                                                                    <p class="text-muted">
                                                                                    ${premiumUsers.caste}
                                                                                    </p>
                                                                                    <h4 class="m-0"> <span
                                                                                            class="text-muted"> Name : ${premiumUsers.name}</span>
                                                                                    </h4>
                                                                                </div><div style="display:block" class="user${premiumUsers.matched_id}">`;
                                var parsedData = JSON.parse(premiumUsers.comments);
                                premiumMeetingYesList +=
                                    `<a class="" data-toggle="collapse" href="#collapseComment${premiumUsers.matched_id}" role="button" aria-expanded="false" aria-controls="collapseExample">Click To View</a>`;
                                if (parsedData != null || parsedData != []) {
                                    premiumMeetingYesList +=
                                        `<div class="collapse" id="collapseComment${premiumUsers.matched_id}">`;
                                    for (let rt = 0; rt < parsedData.length; rt++) {
                                        const parsedJson = parsedData[rt];
                                        if (parsedJson != null) {
                                            premiumMeetingYesList +=
                                                `<p>${parsedJson.meeting_date} :- ${parsedJson.comment}</p>`;
                                        }
                                    }
                                    premiumMeetingYesList += `</div>`;
                                }
                                premiumMeetingYesList += ` </div></div></div></div>`;
                            } else if (premiumUsers.status == 5) {
                                premiumMeetingNoList +=
                                    `<div class="col-md-6 col-xl-3">
                                                                        <div class="card product-box">

                                                                            <div class="product-img">
                                                                                <div class="p-3 text-center">
                                                                                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${premiumUsers.photo}"
                                                                                        alt="profile-pic" class="img-fluid">
                                                                                </div>
                                                                                <div class="product-action">
                                                                                    <div class="d-flex">
                                                                                        <!--<a href="javascript: void(0);"
                                                                                            class="btn btn-purple d-block updateStatusBtn m-2" profileId="${premiumUsers.matched_id}" actionType="1"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Update Status</a>-->

                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-purple d-block updatePremiumStatusBtn m-2" profileId="${premiumUsers.matched_id}" matchId="${premiumUsers.matched_id}" actionType="1"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Update Status</a>

                                                                                        <!--<a href="javascript: void(0);"
                                                                                            class="btn btn-warning d-block seeComments m-2" userId="${premiumUsers.matched_id}"><i
                                                                                                class="ri-edit-2-fill align-middle"></i>
                                                                                            Comments Status</a>-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="product-info border-top p-3">
                                                                                <div>
                                                                                    <h6 class="font-16 mt-0 mb-1"><a
                                                                                            href="ecommerce-product-detail.html"
                                                                                            class="text-dark">${premiumUsers.marital_status}</a>
                                                                                    </h6>
                                                                                    <p class="text-muted">
                                                                                    ${premiumUsers.caste}
                                                                                    </p>
                                                                                    <h4 class="m-0"> <span
                                                                                            class="text-muted"> Name : ${premiumUsers.name}</span>
                                                                                    </h4>
                                                                                </div><div style="display:block" class="user${premiumUsers.matched_id}">`;
                                var parsedData = JSON.parse(premiumUsers.comments);
                                premiumMeetingNoList +=
                                    `<a class="" data-toggle="collapse" href="#collapseComment${premiumUsers.matched_id}" role="button" aria-expanded="false" aria-controls="collapseExample">Click To View</a>`;
                                if (parsedData != null || parsedData != []) {
                                    premiumMeetingNoList +=
                                        `<div class="collapse" id="collapseComment${premiumUsers.matched_id}">`;
                                    for (let rt = 0; rt < parsedData.length; rt++) {
                                        const parsedJson = parsedData[rt];
                                        if (parsedJson != null) {
                                            premiumMeetingNoList +=
                                                `<p>${parsedJson.meeting_date} :- ${parsedJson.comment}</p>`;
                                        }
                                    }
                                    premiumMeetingNoList += `</div>`;
                                }
                                premiumMeetingNoList += ` </div></div></div></div>`;
                            }

                        }
                        premiumMeetingList += '</div></div>';
                        premiumMeetingYesList += '</div></div>';
                        premiumMeetingNoList += '</div></div>';
                        $('.premiumMettingLists').html(premiumMeetingList);
                        $('.premiumMettingYesLists').html(premiumMeetingYesList);
                        $('.premiumMettingNoLists').html(premiumMeetingNoList);
                    }
                });
            }

            // see user comments
            $(document).on('click', '.seeComments', function(e) {
                var userId = $(this).attr('id');
                $('.user' + userId).show();
            });

        });
    </script>
@endsection
