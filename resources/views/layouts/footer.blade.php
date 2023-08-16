@php
    $income_ranges = config('constants.income_ranges');
@endphp
<!-- Right Sidebar -->
<div class="right-bar">
    <div data-simplebar class="h-100">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-bordered nav-justified" role="tablist">
            <li class="nav-item"> <a class="nav-link py-2" data-bs-toggle="tab" href="#chat-tab" role="tab"> <i
                        class="mdi mdi-message-text-outline d-block font-22 my-1"></i> </a> </li>
            <li class="nav-item"> <a class="nav-link py-2" data-bs-toggle="tab" href="#tasks-tab" role="tab"> <i
                        class="mdi mdi-format-list-checkbox d-block font-22 my-1"></i> </a> </li>
            <li class="nav-item"> <a class="nav-link py-2 active" data-bs-toggle="tab" href="#settings-tab"
                    role="tab"> <i class="mdi mdi-cog-outline d-block font-22 my-1"></i> </a> </li>
        </ul> <!-- Tab panes -->
        <div class="tab-content pt-0">
            <div class="tab-pane" id="chat-tab" role="tabpanel">
                <form class="search-bar p-3">
                    <div class="position-relative"> <input type="text" class="form-control" placeholder="Search...">
                        <span class="mdi mdi-magnify"></span>
                    </div>
                </form>
                <h6 class="fw-medium px-3 mt-2 text-uppercase">Group Chats</h6>
                <div class="p-2"> <a href="javascript: void(0);"
                        class="text-reset notification-item ps-3 mb-2 d-block"> <i
                            class="mdi mdi-checkbox-blank-circle-outline me-1 text-success"></i> <span
                            class="mb-0 mt-1">App Development</span> </a> <a href="javascript: void(0);"
                        class="text-reset notification-item ps-3 mb-2 d-block"> <i
                            class="mdi mdi-checkbox-blank-circle-outline me-1 text-warning"></i> <span
                            class="mb-0 mt-1">Office Work</span> </a> <a href="javascript: void(0);"
                        class="text-reset notification-item ps-3 mb-2 d-block"> <i
                            class="mdi mdi-checkbox-blank-circle-outline me-1 text-danger"></i> <span
                            class="mb-0 mt-1">Personal Group</span> </a> <a href="javascript: void(0);"
                        class="text-reset notification-item ps-3 d-block"> <i
                            class="mdi mdi-checkbox-blank-circle-outline me-1"></i> <span
                            class="mb-0 mt-1">Freelance</span> </a> </div>
                <h6 class="fw-medium px-3 mt-3 text-uppercase">Favourites <a href="javascript: void(0);"
                        class="font-18 text-danger"><i class="float-end mdi mdi-plus-circle"></i></a></h6>
                <div class="p-2"> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status"></span> <img
                                    src="{{ url('/images/users/avatar-10.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Andrew Mackie</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">It will seem like simplified English.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status"></span> <img
                                    src="{{ url('/images/users/avatar-1.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Rory Dalyell</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">To an English person, it will seem like simplified
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status busy"></span> <img
                                    src="{{ url('/images/users/avatar-9.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Jaxon Dunhill</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">To achieve this, it would be necessary.</p>
                                </div>
                            </div>
                        </div>
                    </a> </div>
                <h6 class="fw-medium px-3 mt-3 text-uppercase">Other Chats <a href="javascript: void(0);"
                        class="font-18 text-danger"><i class="float-end mdi mdi-plus-circle"></i></a></h6>
                <div class="p-2 pb-4"> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status online"></span> <img
                                    src="{{ url('/images/users/avatar-2.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Jackson Therry</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">Everyone realizes why a new common language.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status away"></span> <img
                                    src="{{ url('/images/users/avatar-4.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Charles Deakin</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">The languages only differ in their grammar.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status online"></span> <img
                                    src="{{ url('/images/users/avatar-5.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Ryan Salting</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">If several languages coalesce the grammar of the
                                        resulting.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status online"></span> <img
                                    src="{{ url('/images/users/avatar-6.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Sean Howse</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">It will seem like simplified English.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status busy"></span> <img
                                    src="{{ url('/images/users/avatar-7.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Dean Coward</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">The new common language will be more simple.</p>
                                </div>
                            </div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset notification-item">
                        <div class="d-flex align-items-start">
                            <div class="position-relative me-2"> <span class="user-status away"></span> <img
                                    src="{{ url('/images/users/avatar-8.jpg') }}" class="rounded-circle avatar-sm"
                                    alt="user-pic"> </div>
                            <div class="flex-1 overflow-hidden">
                                <h6 class="mt-0 mb-1 font-14">Hayley East</h6>
                                <div class="font-13 text-muted">
                                    <p class="mb-0 text-truncate">One could refuse to pay expensive translators.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="text-center mt-3"> <a href="javascript:void(0);" class="btn btn-sm btn-white"> <i
                                class="mdi mdi-spin mdi-loading me-2"></i> Load more </a> </div>
                </div>
            </div>
            <div class="tab-pane" id="tasks-tab" role="tabpanel">
                <h6 class="fw-medium p-3 m-0 text-uppercase">Working Tasks</h6>
                <div class="px-2"> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">App Development<span class="float-end">75%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">Database Repair<span class="float-end">37%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 37%"
                                aria-valuenow="37" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">Backup Create<span class="float-end">52%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 52%"
                                aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> </div>
                <h6 class="fw-medium px-3 mb-0 mt-4 text-uppercase">Upcoming Tasks</h6>
                <div class="p-2"> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">Sales Reporting<span class="float-end">12%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 12%"
                                aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">Redesign Website<span class="float-end">67%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 67%"
                                aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                        <p class="text-muted mb-0">New Admin Design<span class="float-end">84%</span></p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 84%"
                                aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </a> </div>
                <div class="d-grid p-3 mt-2"> <a href="javascript: void(0);"
                        class="btn btn-success waves-effect waves-light">Create Task</a> </div>
            </div>
            <div class="tab-pane active" id="settings-tab" role="tabpanel">
                <h6 class="fw-medium px-3 m-0 py-2 font-13 text-uppercase bg-light"> <span class="d-block py-1">Theme
                        Settings</span> </h6>
                <div class="p-3">
                    <div class="alert alert-warning" role="alert"> <strong>Customize </strong> the overall color
                        scheme, sidebar menu, etc. </div>
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Color Scheme</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input tmeheSwitcher"
                            type="checkbox" name="color-scheme-mode" value="light" id="light-mode-check">
                        <label class="form-check-label" for="light-mode-check">Light Mode</label>
                    </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input tmeheSwitcher"
                            type="checkbox" name="color-scheme-mode" value="dark" id="dark-mode-check"> <label
                            class="form-check-label" for="dark-mode-check">Dark Mode</label> </div> <!-- Width -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Width</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="width" value="fluid" id="fluid-check" checked> <label class="form-check-label"
                            for="fluid-check">Fluid</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="width" value="boxed" id="boxed-check"> <label class="form-check-label"
                            for="boxed-check">Boxed</label> </div> <!-- Topbar -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Topbar</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="topbar-color" value="dark" id="darktopbar-check" checked> <label
                            class="form-check-label" for="darktopbar-check">Dark</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="topbar-color" value="light" id="lighttopbar-check"> <label
                            class="form-check-label" for="lighttopbar-check">Light</label> </div>
                    <!-- Menu positions -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Menus Positon <small>(Leftsidebar and
                            Topbar)</small></h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="menus-position" value="fixed" id="fixed-check" checked> <label
                            class="form-check-label" for="fixed-check">Fixed</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="menus-position" value="scrollable" id="scrollable-check"> <label
                            class="form-check-label" for="scrollable-check">Scrollable</label> </div>
                    <!-- Left Sidebar-->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Left Sidebar Color</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-color" value="light" id="light-check" checked> <label
                            class="form-check-label" for="light-check">Light</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-color" value="dark" id="dark-check"> <label class="form-check-label"
                            for="dark-check">Dark</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-color" value="brand" id="brand-check"> <label
                            class="form-check-label" for="brand-check">Brand</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-color" value="gradient" id="gradient-check"> <label
                            class="form-check-label" for="gradient-check">Gradient</label> </div> <!-- size -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Left Sidebar Size</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-size" value="default" id="default-size-check" checked> <label
                            class="form-check-label" for="default-size-check">Default</label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-size" value="condensed" id="condensed-check"> <label
                            class="form-check-label" for="condensed-check">Condensed <small>(Extra Small
                                size)</small></label> </div>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-size" value="compact" id="compact-check"> <label
                            class="form-check-label" for="compact-check">Compact <small>(Small
                                size)</small></label> </div> <!-- User info -->
                    <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Sidebar User Info</h6>
                    <div class="form-check form-switch mb-1"> <input class="form-check-input" type="checkbox"
                            name="leftsidebar-user" value="fixed" id="sidebaruser-check"> <label
                            class="form-check-label" for="sidebaruser-check">Enable</label> </div>
                    <div class="d-grid mt-4"> <button class="btn btn-primary" id="resetBtn">Reset to
                            Default</button> </div>
                </div>
            </div>
        </div>
    </div> <!-- end slimscroll-menu-->
</div>
{{-- payment link modal starts --}}
<div class="modal fade" id="paymentLinkModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Payment Link</h5> <button type="button" class="btn-close"
                    data-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('genpaymentlink') }}" method="post" id="generatePaymentLinkForm"> @csrf
                    <div class="form-group mb-2"> <label for="">User Mobile</label> <input type="number"
                            class="form-control" name="user_mobile"> </div>
                    <div class="form-group mb-2"> <label for="">User Email</label> <input type="email"
                            class="form-control" name="user_email"> </div>
                    <div class="form-group mb-2"> <label for="">Plan</label> <select name="user_plan"
                            id="user_plan" class="form-select"> </select> </div>
                    <div class="form-group mb-2"> <label for="">Payment Amount</label> <input type="number"
                            class="form-control" name="payment_amount"> </div>
                    <div class="form-group mb-2"> <label for="">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-2 formOutputMessage"> </div>
                    <div class="form-group mb-2 float-end btnDiv"> <button
                            class="btn btn-sm btn-success">Generate</button> </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- payment link modal ends --}}

{{-- checkin modal --}}
<div class="modal fade" id="check-in-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check In!</h5> <button type="button" class="btn-close" data-dismiss="modal"
                    aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group text-warning mb-4"> <img src="{{ url('/images/warning-animated.gif') }}"
                        alt="" width="110px;"> </div>
                <div class="form-group">
                    <p class="text-danger">Click on checkin to prevent yourself from autologout!!!</p>
                </div>
                <div class="row">
                    <div class="col-md-6"> <button type="button"
                            class="btn btn-info btn-rounded waves-effect waves-light check-in-button">Check
                            In</button> </div>
                    <div class="col-md-6"> <button type="button"
                            class="btn btn-danger btn-rounded waves-effect waves-light modal-cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- chckin modal ends --}}


@if (
    ((Route::is('database') || Route::is('mydatabsecrm')) &&
        (Auth::user()->role == config('constants.roles_ineger')['relationship_mananger'] ||
            Auth::user()->role == config('constants.roles_ineger')['approvals'] ||
            Auth::user()->role == config('constants.roles_ineger')['customer_support'])) ||
        Auth::user()->role == config('constants.roles_ineger')['telesales']
)
    {{-- search lead modal starts --}}
    <div class="modal fade" id="search_lead_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="margin-top:5px">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title text-white">Search Profile</h5> <button type="button"
                        class="btn-close text-white" data-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4"> <select name="select_search_type" id="select_search_type"
                                class="form-select">
                                <option value="1">Search By Mobile</option>
                                <option value="2">Search By Name</option>
                                <option value="3">Search By Userid</option>
                            </select> </div>
                        <div class="col-4"> <input type="number" class="form-control" name="search_mobile_number"
                                id="search_mobile_number" placeholder="Mobile Number" autocomplete="Off"> </div>
                        <div class="col-4 mt-1 search_btn_div"> <button
                                class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile"><i
                                    class="fas fa-search    "></i></button> </div>
                        <div class="col-12 search_details" style="max-height: 70vh; overflow:auto;"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- search lead modal ends --}}

    <!-- User Details Modal starts -->

    {{-- user etails modal ends --}}

    {{-- Update User Details modal Starts --}}
    {{-- Update User Details Modal Ends --}}



    {{-- add photos modal starts --}}

    {{-- add photos modal ends --}}
@endif
{{-- Add Credit Starts --}}
<div class="modal fade" id="addCreditModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Credit</h5> <button type="button" class="btn-close"
                    data-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('addusercredit') }}" method="post" id="addCreditForm">
                    @csrf
                    <div class="form-group row mb-3"> {{-- <label for="" class="col-md-3">Enter Mobile</label> --}} <div class="col-md-9">
                            {{-- <input type="number" class="form-control" name="user_mobile" id="user_mobile"> --}} <input type="number" class="form-control d-none" name="user_id"
                                id="user_id_fetched"> </div>
                    </div>
                    <div class="form-group row mb-3"> <label for="" class="col-md-3">Total
                            Credits</label>
                        <div class="col-md-9">
                            <input type="number" name="user_credit" placeholder="5" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3 formOutputDiv"> </div>
                    <div class="form-group float-end submitDiv">
                        <button class="btn btn-sm btn-danger submit_btn_credit" type="submit"
                            name="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Add Credit Ends --}}
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5> <button type="button" class="btn-close"
                    data-dismiss="modal" aria-label="Close"></button>
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
                                                    <div class="carousel-inner mainImage"></div>
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
                                                                        class="maritalStatusUser"></span> </li>
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
                                </div> <!-- end row -->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-4">Other Details</h4>
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"> <a href="#profile_family" data-bs-toggle="tab"
                                                    aria-expanded="false" class="nav-link active"> <span
                                                        class="d-inline-block d-sm-none"><i
                                                            class="mdi mdi-home-variant"></i></span> <span
                                                        class="d-none d-sm-inline-block">Family Detail</span>
                                                </a> </li>
                                            <li class="nav-item"> <a href="#profile_preferences" data-bs-toggle="tab"
                                                    aria-expanded="true" class="nav-link preferenceDetailsNav"> <span
                                                        class="d-inline-block d-sm-none"><i
                                                            class="mdi mdi-account"></i></span> <span
                                                        class="d-none d-sm-inline-block">Preferences</span>
                                                </a> </li>
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
                                                        <th>Father Occupation</th>
                                                        <th>Mother Occupation</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="userFamilyType"></td>
                                                        <td class="userHouseType"></td>
                                                        <td class="fatherStatusUser"></td>
                                                        <td class="motherStatusUser"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gotra</th>
                                                        <th>Family Income</th>
                                                        <th>Family Current City</th>
                                                        <th>Native City</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="userGotra"></td>
                                                        <td class="userIncome"></td>
                                                        <td class="userCurrentCity"></td>
                                                        <td class="userNativeCity"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact Address</th>
                                                        <td class="contactAdress" colspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Family About</th>
                                                        <td class="familyAdress" colspan="3"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="profile_preferences">
                                                <table class="table table-striped table-inverse">
                                                    <tr>
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Height</strong> :
                                                                <span class="minheight_txt"></span> - <span
                                                                    class="maxheight_txt"></span>
                                                            </div>
                                                            <div class="col-sm-4"><strong>Income</strong> :
                                                                <span class="min_income_pref"></span> - <span
                                                                    class="max_income_pref"></span>
                                                            </div>
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
                                                        <th>Religion</th>
                                                        <td colspan="3" class="religion_prefs"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td class="marital_status_pref"></td>
                                                        <th>Manglik</th>
                                                        <td class="manglik_pref"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Citizenship</th>
                                                        <td class="citizenship_pref" colspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Occupation</th>
                                                        <td class="occupation_pref"></td>
                                                        <th>Food Choice</th>
                                                        <td class="food_pref"></td>
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
                                        <div class="d-flex mt-2 float-end approve_button"> </div>
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
<div class="rightbar-overlay"></div>
@include('layouts.scripts-footer')

<script>
    $(document).ready(function() {
        $('.loader').hide();
    });
    $(document).on('click', '.tmeheSwitcher', function() {
        localStorage.setItem("theme", $(this).val());
    });
    $('.button-menu-mobile').trigger('click');
    $(document).on('click', '.btn-close', function(e) {
        e.preventDefault();
        $('.modal').modal('hide');
    });
    $(document).on('click', '.generatePaymentLink', function(e) {
        e.preventDefault();
        $('#paymentLinkModal').modal('show');
    });
    $(document).on('click', '.addCreditButton', function(e) {
        e.preventDefault();
        $('#addCreditButton').modal('show');
    });


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
                    plan_optins += `<option value="${plan_name[0]}">${plan_name[0]}</option>`;
                }
                $('#user_plan').html(plan_optins);
            }
        });
    }

    $(document).on('submit', '#generatePaymentLinkForm', function(e) {
        e.preventDefault();
        $('.btnDiv').html('Please Wait ..........');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(data) {
                $('.formOutputMessage').html(`<span class="text-danger">${data.link}</span>`);
                $('#generatePaymentLinkForm')[0].reset();
                $('.btnDiv').html(' <button class="btn btn-sm btn-success">Generate</button>');
            }
        })
    });


    $(document).ready(function() {
        $('.loader').hide();
        $(document).on('click', '.logoutButtonNav', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('markcheckout') }}",
                type: "get",
                success: function(checkoutResp) {
                    if (checkoutResp.type == true) {
                        $('.logoutSubmitButton').trigger('click');
                    }
                }
            });
        });
    });

    if ("{{ Auth::user()->role }}" == "{{ config('constants.roles_ineger')['relationship_mananger'] }}" ||
        "{{ Auth::user()->role }}" == "{{ config('constants.roles_ineger')['approvals'] }}" ||
        "{{ Auth::user()->role }}" == "{{ config('constants.roles_ineger')['customer_support'] }}" ||
        "{{ Auth::user()->role }}" == "{{ config('constants.roles_ineger')['telesales'] }}") {
        /* saerch lead*/
        $(document).on('click', '.search_lead', function(e) {
            e.preventDefault();
            $('.search_details').html('');
            $('#search_mobile_number').val('');
            $('#search_lead_modal').modal('show');
        });

        $(document).on('click', '.search_lead_mobile', function(e) {
            e.preventDefault();
            var lead_mobile = $('#search_mobile_number').val();
            var serch_type = $('#select_search_type').val();
            var leads_html = '';
            $('.search_btn_div').html('<div class="spinner-border text-danger m-2" role="status"></div>');
            if (lead_mobile == '') {
                $('.search_details').html(
                    '<div class="mt-3 alert alert-danger" role="alert"><strong>Warning</strong> Please Fill Mobile Number Carefully</div>'
                );
                $('.search_btn_div').html(
                    ' <button class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile"><i class="fas fa-search"></i></button>'
                );
            } else {
                $.ajax({
                    url: "{{ route('overallsearch') }}",
                    type: "get",
                    data: {
                        "search_data": lead_mobile,
                        "search_type": serch_type
                    },
                    success: function(search_resp) {
                        var today = new Date()
                        // console.log(today.getdate())
                        $('.search_btn_div').html(
                            ' <button class="btn btn-purple btn-rounded btn-sm waves-effect waves-light search_lead_mobile"><i class="fas fa-search"></i></button>'
                        );
                        let profileStatus = "";
                        if (search_resp.type == true) {
                            for (let i = 0; i < search_resp.data.length; i++) {
                                const lead_deatsils = search_resp.data[i];
                                if (lead_deatsils.validity != null) {
                                    var validity = lead_deatsils.validity.split(' ')[0]
                                } else {
                                    var validity = "00-00-00"
                                }
                                validity_date = new Date(validity)
                                if (lead_deatsils.is_approved == 1) {
                                    profileStatus = "Approved";
                                } else {
                                    profileStatus = "Un-Approved";
                                }
                                leads_html +=
                                    `<table class="table table-striped table-inverse"> <tr> <th colspan="4">${profileStatus} Profile Based On Your Search   <a class="btn btn-xs btn-pink" target="_blank" href="{{ route('pdfprofiles') }}?user_ids=${lead_deatsils.id}"  data-toggle="tooltip" data-placement="top" title="Download Pdf"><i class="fas fa-file-pdf"></i></a></th> </tr> <tr><th colspan="4">`;

                                if (lead_deatsils.user_photos[0] != undefined) {
                                    if (src = lead_deatsils.user_photos[0].photo_url) {
                                        leads_html +=
                                            `<img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${src}" width="100px">`;
                                    }
                                }
                                leads_html +=
                                    `</th></tr>
                                     <tr> 
                                        <th>Name</th> <td>${lead_deatsils.name}</td> 
                                        <th>Mobile</th> <td>${lead_deatsils.user_mobile}</td>
                                    </tr> 
                                     <tr> 
                                        <th>Registration Date</th> <td>${lead_deatsils.created_at.split('T')[0]}</td> 
                                        <th>Premium User</th> <td>${lead_deatsils.is_premium != 0?'Yes' : 'No'}</td>
                                    </tr> 
                                     <tr> 
                                        <th>Payment Date</th> <td>${lead_deatsils.is_paid != 0 && lead_deatsils.is_paid != null?lead_deatsils.amount_collected_at.split(' ')[0]:'Not Paid'}</td> 
                                        <th >Expire At </th> <td style="color:${validity_date > today?'green':'red'}">${lead_deatsils.is_paid != 0?validity:'No Active Plan'}</td>
                                    </tr> 
                                     <tr> 
                                        <th>Paid Amount</th> <td>${lead_deatsils.amount}</td> 
                                        <th>Credit</th> <td>${lead_deatsils.credit}</td>
                                    </tr> 
                                    <tr> 
                                        <th>Assigned To</th> 
                                    <td>`;
                                leads_html += lead_deatsils.temple_name;
                                leads_html += `</td> 
                                
                                </tr>`;
                                leads_html += `<tr> 
                                <th colspan="4">`;
                                leads_html +=
                                    `<button type="button" class="btn btn-sm btn-success float-end viewDetails" leadId="${lead_deatsils.id}">View Full Details</button>`;
                                leads_html +=
                                    `
                                    <button class="btn btn-danger btn-xs mark_married" user_id="${lead_deatsils.id}">Mark Married2</button>
                                    <button class="btn btn-purple btn-xs mark_premium" user_id="${lead_deatsils.id}">Mark Premium</button>
                                    <button class="btn btn-primary btn-xs addCreditButton" user_id="${lead_deatsils.id}">Add Credits</button>
                                    <input type="file" name="photo" accept="image/*" class="js-photo-upload" userId="${lead_deatsils.id}">
                                    </button>
                                    </th>
                                    </tr>`;
                                leads_html += `</table>`;
                            }
                            $('.search_details').html(leads_html);
                        } else {
                            $('.search_details').html(
                                '<div class="form-group"><div class="alert alert-danger" role="alert"><strong>No Record Found For This Mobile</strong></div></div>'
                            );
                        }
                    }
                });
            }
        });
        $(document).on('click', '.viewDetails', function(e) {
            e.preventDefault();
            // console.log("adsdasa da");
            getUserDetails($(this).attr('leadid'));
        });

        function getUserDetails(userId) {
            $('.loader').show();
            $('#search_lead_modal').modal('hide');
            $('.profile_btn_div').html('<div class="spinner-border text-danger m-2" role="status"></div>');
            $.ajax({
                url: "{{ route('getuserdatabyid') }}",
                type: "get",
                data: {
                    "user_id": userId
                },
                success: function(userResponse) {
                    $('.loader').hide();
                    let aproveButton = "";
                    aproveButton =
                        `<button class="btn btn-success btn-sm edit_n_approve_user checkNUpdate float-end" user_id=${userResponse.id}>Edit Details</button>`;
                    $('.approve_button').html(aproveButton);
                    let userHeight;
                    let userFt = parseInt(parseInt(userResponse.height_int) / 12);
                    let userInch = parseInt(userResponse.height_int) % 12;
                    userHeight = userFt + "Ft " + userInch + "In";
                    let userIncome = userResponse.annual_income;
                    // setting setUserPreferences 
                    $('#max_height').val(userResponse.userpreference.height_max);
                    $('#min_height').val(userResponse.userpreference.height_min);
                    $('#max_age').val(userResponse.userpreference.age_max);
                    $('#min_age').val(userResponse.userpreference.age_min);
                    $('#maritalStatus').val(userResponse.userpreference.marital_statusPref);
                    $('#manglik_status').val(userResponse.userpreference.manglikPref);
                    $('#foor_choice').val(userResponse.userpreference.food_choicePref);
                    $('#food_pref').val(userResponse.userpreference.food_choicePref);
                    $('#min_income').val(userResponse.userpreference.income_min);
                    $('#max_income').val(userResponse.userpreference.income_max);
                    $('#user_denger').val(userResponse.userpreference.genderCode_user);
                    // end setting userpreference
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
                    /**family details*/
                    $('.userUnmarriedBrothers').text(userResponse.unmarried_brothers);
                    $('.userUnmarriedSisters').text(userResponse.unmarried_sisters);
                    $('.userMarriedBrothers').text(userResponse.married_brothers);
                    $('.userMarriedSisters').text(userResponse.married_sisters);
                    $('.userFamilyType').text(userResponse.familyTypeCode == 1 ? 'Nuclear' : 'Joint');
                    $('.userHouseType').text(userResponse.houseTypeCode == 1 ? 'Owned' : userResponse
                        .houseTypeCode == 2 ? 'Rented' : 'Leased');
                    $('.fatherStatusUser').text(userResponse.occupation_father);
                    $('.motherStatusUser').text(userResponse.occupation_mother);
                    $('.userGotra').text(userResponse.gotra);
                    $('.userIncome').text(userResponse.family_income);
                    $('.userCurrentCity').text(userResponse.city_family);
                    $('.userNativeCity').text(userResponse.native);
                    $('.familyAdress').text(userResponse.about_family);
                    $('.contactAdress').text(userResponse.contact_address);
                    if (userResponse.user_photos.length > 0) {
                        var mainImageHtml = '';
                        mainImageHtml +=
                            `<img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${userResponse.user_photos[0].photo_url}" class="w-75">`;
                        $('.mainImage').html(mainImageHtml);
                    }
                    // setting user prefrences 
                    let setUserPreferences = userResponse.userpreference;
                    $('.minheight_txt').text(setUserPreferences.height_min);
                    $('.maxheight_txt').text(setUserPreferences.height_max);
                    $('.min_income_pref').text(setUserPreferences.income_min);
                    $('.max_income_pref').text(setUserPreferences.income_max);
                    if (setUserPreferences.caste != null && setUserPreferences.caste != undefined) {
                        $('.caste_prefs').text(setUserPreferences.caste.slice(1, setUserPreferences
                            .caste.length - 1));
                    }
                    $('.citizenship_pref').text(setUserPreferences.pref_country);
                    $('.marital_status_pref').text(setUserPreferences.marital_status);
                    $('.manglik_pref').text(setUserPreferences.manglik);
                    $('.city_pref').text(setUserPreferences.pref_city);
                    $('.state_pref').text(setUserPreferences.pref_state);
                    $('.food_pref').text(setUserPreferences.food_choice);
                    $('.religion_prefs').text(userResponse.religion);
                    $('.coutnry_pref').text(setUserPreferences.pref_country);
                    $('.occupation_pref').text(setUserPreferences.occupation);
                    $('.min_age_pref').text(setUserPreferences.age_min);
                    $('.max_age_pref').text(setUserPreferences.age_max);
                    $('#userDetailsModal').modal('show');
                    console.log('hello')
                }
            });
        }


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
                        `<button type="submit" name="submit" class="btn btn-success">Approve</button>`
                    );
                    $('.form_output').html(messageHtml);
                }
            });
        });
        $(document).on('click', '.mark_married', function(e) {
            e.preventDefault();
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "{{ route('markprofilemarried') }}",
                    type: "get",
                    data: {
                        "user_id": $(this).attr('user_id')
                    },
                    success: function(data) {
                        if (data.type == true) {
                            alert("Record Updated");
                            databaseTable.ajax.relod();
                        }
                    }
                });
            }
        });
        $(document).on('click', '.addCreditButton', function(e) {
            e.preventDefault();
            $('#user_id_fetched').val($(this).attr('user_id'));
            $('#addCreditModal').modal('show');
            // window.setTimeout(function() {
            //     $('.submit_btn_credit').trigger('click');
            // }, 1500);
        });
        $(document).on('submit', '#addCreditForm', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(creditResp) {
                    if (creditResp.type == true) {
                        $('.formOutputDiv').html(
                            `<div class="alert alert-success" role="alert"><strong>Success </strong>${creditResp.message}</div>`
                        );
                        $('#addCreditForm')[0].reset();
                        window.setTimeout(function() {
                            $('.formMessage').html('');
                            $('#addCreditModal').modal('hide');
                            $('.formOutputDiv').fadeOut();
                        }, 2000);
                    }
                }
            })
        });
        $(document).on('click', '.mark_premium', function(e) {
            e.preventDefault();
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "{{ route('markprofilepremium') }}",
                    type: "get",
                    data: {
                        "user_id": $(this).attr('user_id')
                    },
                    success: function(data) {
                        if (data.type == true) {
                            alert("Record Updated");
                            databaseTable.ajax.relod();
                        }
                    }
                });
            }
        });
        $(document).on('change', '#select_search_type', function(e) {
            if ($(this).val() == 1) {
                $("#search_mobile_number").get(0).type = 'number';
                $("#search_mobile_number").attr("placeholder", "Mobile Number");
            } else if ($(this).val() == 2) {
                $("#search_mobile_number").get(0).type = 'text';
                $("#search_mobile_number").attr("placeholder", "Type name here");
            } else {
                $("#search_mobile_number").get(0).type = 'number';
                $("#search_mobile_number").attr("placeholder", "Type Userid here");
            }
        });
    }
</script>

@include('form.script.modelProgressFormScript')
