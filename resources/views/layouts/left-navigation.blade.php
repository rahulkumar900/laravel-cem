        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo logo-dark text-center">
                    <span class="logo-sm">
                        <img src="{{ url('/images/logo-sm-dark.png') }}" alt="" height="40">
                        <!-- <span class="logo-lg-text-light">Minton</span> -->
                    </span>
                    <span class="logo-lg">
                        <img src="{{ url('/images/logo-dark.png') }}" alt="" height="35">
                        <!-- <span class="logo-lg-text-light">M</span> -->
                    </span>
                </a>

                <a href="index.html" class="logo logo-light text-center">
                    <span class="logo-sm">
                        <img src="{{ url('/images/logo-sm.png') }}" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ url('/images/makeajodi.png') }}" alt="" height="35">
                    </span>
                </a>
            </div>

            <div class="h-100" data-simplebar>

                <!-- User box -->
                <div class="user-box text-center">
                    <img src="{{ url('/images/users/avatar-1.jpg') }}" alt="user-img" title="Mat Helme"
                        class="rounded-circle avatar-md">
                    <div class="dropdown">
                        <a href="#" class="text-reset dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown">Nik Patel</a>
                        <div class="dropdown-menu user-pro-dropdown">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user me-1"></i>
                                <span>My Account</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings me-1"></i>
                                <span>Settings</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock me-1"></i>
                                <span>Lock Screen</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-log-out me-1"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </div>
                    <p class="text-reset">Admin Head</p>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul id="side-menu">
                        <li class="menu-title mt-2">Dashboards</li>
                        {{-- @if (Auth::user()->role == config('constants.roles_ineger')['telesales'] || Auth::user()->role == config('constants.roles_ineger')['team_leader'] || Auth::user()->role == config('constants.roles_ineger')['customer_support']) --}}
                        {{-- leads --}}
                        <li>
                            <a href="#sidebarAuth" data-bs-toggle="collapse" aria-expanded="false"
                                aria-controls="sidebarAuth">
                                <i class="ri-shield-user-line"></i>
                                <span> Sales </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarAuth">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('crmleads') }}">Leads</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('unassigned-leads') }}">UnAssigned Leads</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('renwaalnupgrade') }}">Renewal & Upgradation</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('subscriptionseen') }}">Subscription Seen</a>
                                    </li>
                                    <li>
                                    <li>
                                        <a href="#">Request Leads <i class="fa fa-angle-double-right"></i></a>
                                        <div class="collapse" id="sidebarAuth">
                                            <ul class="nav-second-level">
                                                {{-- <li>
                                                    <a href="{{ route('requestleads') }}">Request Facebook Leads</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('requestwebsiteleads') }}">Request Website
                                                        Leads</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('requestexhaustleads') }}">Request Exhaust
                                                        Leads</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('requestoperatorcalls') }}">Request Operator
                                                        Calls</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('requestedleads') }}">Today's Requested
                                                        Leads</a>
                                                </li> --}}
                                    </li>
                                </ul>
                            </div>
                        </li>
                        </li>
                        <li>
                            <a href="{{ route('alloperatorcalls') }}">Operator Calls</a>
                        </li>
                        <li>
                            <a href="{{ route('addreceipts') }}">Add Receipt</a>
                        </li>
                        <li>
                            <a href="{{ route('todaysfollowup') }}">Today's Followup</a>
                        </li>
                        @if (Auth::user()->role == config('constants.roles_ineger')['admin'] )
                        <li>
                            <a href="{{ route('sampleprofile') }}">Sample Profile</a>
                        </li>
                        @endif

                        
                        <li>
                            <a href="{{ route('myappoitments') }}">Appointments</a>
                        </li>
                        <li>
                            <a href="#" class="generatePaymentLink">Payment Link</a>
                        </li>
                        <li>
                            <a href="{{ route('failedtransactions') }}">Failed Transaction</a>
                        </li>
                    </ul>
                </div>
                </li>
                {{-- @endif --}}

                @if (Auth::user()->role == config('constants.roles_ineger')['approvals'])
                    {{-- approval --}}
                    <li>
                        <a href="#sidebarExpages" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="sidebarExpages">
                            <i class="ri-pages-line"></i>
                            <span> Approval </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarExpages">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('mydatabsecrm') }}">My Database</a>
                                </li>
                                <li>
                                    <a href="{{ route('mydatabsepending') }}">My Database Pending</a>
                                </li>
                                <li>
                                    <a href="{{ route('approveprofile') }}">Photo_Pending</a>
                                </li>
                                <li>
                                    <a href="{{ route('approvependingprofile') }}">Profile Pending</a>
                                </li>
                                <li>
                                    <a href="{{ route('approvephotoprofile') }}">Photo_Verification</a>
                                </li>
                                {{-- <li>
                                            <a href="{{ route('approvedoubleprofile') }}">Double</a>
                                        </li> --}}
                                <li>
                                    <a href="{{ route('approvedprofiles') }}">Approved Profiles</a>
                                </li>
                                <li>
                                    <a href="{{ route('rejectedprofiles') }}">Rejected Profiles</a>
                                </li>
                                <li>
                                    <a href="{{ route('incompleteleadspending') }}">Incomplete Leads Pending</a>
                                </li>
                                <li>
                                    <a href="{{ route('clientcallinteract') }}">Welcome / Verification</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (Auth::user()->role == config('constants.roles_ineger')['relationship_mananger'])
                    {{-- ps dahboard --}}
                    <li>
                        <a href="#personalizedDashboard" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="sidebarExpages">
                            <i class="ri-message-2-line"></i>
                            <span> Personalized </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="personalizedDashboard">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('database') }}">My Database</a>
                                </li>
                                <li>
                                    <a href="{{ route('daywiseprofilesent') }}">Today Profile Not Sent</a>
                                </li>
                                <li>
                                    <a href="{{ route('weeklyprofilenotsent') }}">This Week Not Sent</a>
                                </li>
                                <li>
                                    <a href="{{ route('yespending') }}">Yes Pending</a>
                                </li>
                                <li>
                                    <a href="{{ route('overallyesmeeting') }}">Overall Yes Meeting</a>
                                </li>
                                <li>
                                    <a href="{{ route('transferprofile') }}">Transfer Profile</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (Auth::user()->role == config('constants.roles_ineger')['admin'])
                    <li>
                        <a href="#temLeaderNavigation" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="temLeaderNavigation">
                            <i class="ri-briefcase-line"></i>
                            <span> Team Leader </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="temLeaderNavigation">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('transferleads') }}">Transfer Leads</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (Auth::user()->role == config('constants.roles_ineger')['hr'])
                    <li>
                        <a href="#sidebarHRMS" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="sidebarExpages">
                            <i class="ri-pages-line"></i>
                            <span> HRMS </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarHRMS">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('employeespage') }}">Employees</a>
                                </li>
                                <li>
                                    <a href="{{ route('employeesattpage') }}">Attendance</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (Auth::user()->role == config('constants.roles_ineger')['customer_support'])
                    {{-- customer care --}}
                    <li>
                        <a href="#sidebarCC" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="sidebarExpages">
                            <i class="ri-phone-lock-line"></i>
                            <span> Customer Care </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarCC">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('opentickets') }}">Open Tickets</a>
                                </li>
                                <li>
                                    <a href="{{ route('myopenticket') }}">My Tickets</a>
                                </li>
                                <li>
                                    <a href="{{ route('clientcallinteract') }}">Welcome / Verification</a>
                                </li>
                                <li>
                                    <a href="{{ route('mydatabsecrm') }}">My Database</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                <li>
                    <a href="#sidebarCC" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="sidebarExpages">
                        <i class="ri-award-fill"></i>
                        <span> Attendence </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCC">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('checkin') }}">Checkin</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarMaps" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="sidebarMaps">
                        <i class="ri-map-pin-line"></i>
                        <span> Other Stuffs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMaps">
                        <ul class="nav-second-level">
                            @if (Auth::user()->role == config('constants.roles_ineger')['admin'] ||
                                    Auth::user()->role == config('constants.roles_ineger')['team_leader']
                            )
                                <li>
                                    <a href="{{ route('loginotersaccount') }}">Login Other Account</a>
                                </li>
                            @endif

                            @if (Auth::user()->role == config('constants.roles_ineger')['admin'] ||
                                    Auth::user()->role == config('constants.roles_ineger')['marketting']
                            )
                                <li>
                                    <a href="{{ route('addfbtoken') }}">Facebook Ad Token</a>
                                </li>
                            @endif

                            @if (Auth::user()->role == config('constants.roles_ineger')['admin'])
                                <li>
                                    <a href="{{ route('managecategory') }}">Relations</a>
                                </li>
                                <li>
                                    <a href="{{ route('manageteamleader') }}">Teamleaders</a>
                                </li>
                                <li>
                                    <a href="{{ route('mangewebplans') }}">Manage Website Plans</a>
                                </li>
                                <li>
                                    <a href="{{ route('userlogindetails') }}">User Login Details</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                @if (Auth::user()->role == config('constants.roles_ineger')['admin'])
                    <li>
                        <a href="#masterdatas" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="masterdatas">
                            <i class="ri-pie-chart-box-line"></i>
                            <span> Master Data </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="masterdatas">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('departmentmaster') }}">Departnemt</a>
                                </li>
                                <li>
                                    <a href="{{ route('designationmaster') }}">Designation</a>
                                </li>
                                <li>
                                    <a href="maps-mapael.html">Plan Category</a>
                                </li>
                                <li>
                                    <a href="{{ route('managecaste') }}">Caste</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                </ul>
            </div>
            <!-- End Sidebar -->
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -left -->
        </div>
        <!-- Left Sidebar End -->
