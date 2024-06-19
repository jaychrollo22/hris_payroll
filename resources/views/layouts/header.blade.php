<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Timekeeping and Payroll</title>
    <link rel="shortcut icon" href="{{ URL::asset(config('logo.logos')::first()->icon) }}">
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    @yield('css_header')
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/body_css/js/select.dataTables.min.css') }}">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.6/dist/sweetalert2.min.css"> --}}
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/body_css/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="{{asset('assets/font-awesome.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('assets/fonts.css')}}" rel="stylesheet" type="text/css">

    <script src="{{asset('assets/vue2.7.14.js')}}"></script>
    <script src="{{asset('assets/axios.min.js')}}"></script>

    <script src="{{asset('assets/jquery-3.6.0.min.js')}}"></script>
    
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('login_css/images/loader.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }

        .redbox1 {
            background-color: lightgrey;
            width: 15px;
            height: 15px;
            border: 10px solid red;
            display: inline-block;

        }

        .orangebox {
            background-color: lightgrey;
            width: 15px;
            height: 15px;
            border: 10px solid orange;
            float: right;
        }

        .orangebox1 {
            background-color: lightgrey;
            width: 15px;
            height: 15px;
            border: 10px solid orange;
            display: inline-block;
        }

        .green {
            background-color: lightgrey;
            width: 15px;
            height: 15px;
            border: 10px solid green;
            display: inline-block;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        #msform fieldset .form-card {
            text-align: left;
            color: #9E9E9E;
        }



        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue;
        }


        #msform .action-button-previous:hover,
        #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #616161;
        }

        /*Dropdown List Exp Date*/
        select.list-dt {
            border: none;
            outline: 0;
            border-bottom: 1px solid #ccc;
            padding: 2px 5px 3px 5px;
            margin: 2px;
        }

        select.list-dt:focus {
            border-bottom: 2px solid skyblue;
        }

        /*The background card*/
        .card {
            z-index: 0;
            border: none;
            border-radius: 0.5rem;
            position: relative;
        }

        /*FieldSet headings*/
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 25%;
            float: left;
            position: relative;
        }

        /*Icons in the ProgressBar*/
        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f09d";
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f090";
        }

        .user:before {
            font-family: FontAwesome;
            content: "\f02d";
        }

        .employment:before {
            font-family: FontAwesome;
            content: "\f0f0";
        }

        /*ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: skyblue;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .tab-content {
            padding: 20px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>
</head>

<body>
    <div id="loader" style="display:none;" class="loader">
    </div>

    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="{{ url('/') }}"><img src="{{ auth()->user()->employee->company ? URL::asset(auth()->user()->employee->company->logo)  : ""}}" onerror="this.src='{{ URL::asset('/images/no_image.png') }}';" style="height:auto;max-height:60px" class="mr-2 ml-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{ auth()->user()->employee->company ? URL::asset(auth()->user()->employee->company->logo)  : ""}}" onerror="this.src='{{ URL::asset('/images/no_image.png') }}';" style="height:auto" alt="logo" /></a>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <span id="span"></span>
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img class="rounded-circle" style='width:34px;height:34px;' src='{{ URL::asset(auth()->user()->employee->avatar) }}' onerror="this.src='{{ URL::asset('/images/no_image.png') }}';">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ url('account-setting') }}">
                                <i class="ti-settings text-primary"></i>
                                Employee Details
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(); show();">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item @if ($header == 'account-setting') active @endif">
                        <a class="nav-link" href="{{ url('/account-setting') }}" onclick='show()'>
                            <i class="ti-settings menu-icon"></i>
                            <span class="menu-title">{{auth()->user()->employee->first_name . ' ' . auth()->user()->employee->last_name}}</span>   
                        </a>
                    </li>
                    <li class="nav-item">
                        <hr>
                        <h5>Employee</h5>
                    </li>
                    <li class="nav-item @if ($header == '') active @endif">
                        <a class="nav-link" href="{{ url('/') }}" onclick='show()'>
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item @if ($header == 'attendances') active @endif">
                        <a class="nav-link" href="{{ url('/attendances') }}" onclick='show()'>
                            <i class="icon-watch menu-icon"></i>
                            <span class="menu-title">Attendances</span>
                        </a>
                    </li>
                    <li class="nav-item @if ($header == 'forms') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-layout menu-icon"></i>
                            <span class="menu-title">Forms</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse @if ($header == 'forms') show @endif" id="forms">
                            <ul class="nav flex-column sub-menu @if ($header == 'forms') show @endif">
                                <li class="nav-item "> <a class="nav-link active" href="{{ url('/file-leave') }}">Leave</a></li>
                                @php
                                    $user_allowed_overtime = auth()->user()->allowed_overtime ? auth()->user()->allowed_overtime->allowed_overtime : "";
                                @endphp
                                @if(checkUserAllowedOvertime(auth()->user()->id) == 'yes' || $user_allowed_overtime == 'on')
                                    <li class="nav-item "> <a class="nav-link " href="{{ url('/overtime') }}">Overtime</a></li>
                                @endif
                                <li class="nav-item "> <a class="nav-link " href="{{ url('/work-from-home') }}">Work from home</a></li>
                                <li class="nav-item "> <a class="nav-link " href="{{ url('/official-business') }}">Official Business</a>
                                </li>
                                {{-- <li class="nav-item "> <a class="nav-link " href="{{ url('/dtr-correction') }}">DTR Correction</a></li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item @if ($header == 'payslips') active @endif">
                        <a class="nav-link" href="{{ url('/payslips') }}" onclick='show()'>
                            <i class="icon-briefcase menu-icon"></i>
                            <span class="menu-title">Payslips</span>
                        </a>
                    </li>
                    <li class="nav-item @if ($header == 'Loans') active @endif">
                        <a class="nav-link" href="{{ url('/loans') }}" onclick='show()'>
                            <i class="fa fa-money menu-icon"></i>
                            <span class="menu-title">Loans</span>
                        </a>
                    </li>
                    <li class="nav-item @if ($header == 'Proof') active @endif">
                        <a class="nav-link" href="https://docs.google.com/forms/d/e/1FAIpQLScC5Xl_2IgYLHeZNd5EwwEX3-pO9p6u1-WO7CMLomS-FZ5tZQ/viewform" target="_blank">
                            <i class="fa fa-money menu-icon"></i>
                            <span class="menu-title">Proof of Availment</span>
                        </a>
                    </li>
                    @if (auth()->user()->employee_under->count() != 0)
                    <li class="nav-item">
                        <hr>
                        <h5>Manager</h5>
                    </li>
                    <li class="nav-item @if ($header == 'for-approval') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#for-approval" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-check menu-icon"></i>
                            <span class="menu-title">For Approval <span class="badge badge-warning">{{ session('pending_leave_count')+session('pending_overtime_count')+session('pending_wfh_count')+session('pending_ob_count')+session('pending_dtr_count') }}</span></span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse @if ($header == 'for-approval') show @endif" id="for-approval">
                            <ul class="nav flex-column sub-menu @if ($header == 'for-approval') show @endif">
                                <li class="nav-item "><a class="nav-link active" href="{{ url('/for-leave') }}">Leave <span class="badge badge-warning">{{ session('pending_leave_count') }}</span></a></li>
                                <li class="nav-item "><a class="nav-link " href="{{ url('/for-overtime') }}">Overtime <span class="badge badge-warning">{{ session('pending_overtime_count') }}</span></a></li>
                                {{-- <li class="nav-item "><a class="nav-link " href="{{ url('/for-work-from-home') }}">Work From Home <span class="badge badge-warning">{{ session('pending_wfh_count') }}</span></a></li> --}}
                                <li class="nav-item "><a class="nav-link " href="{{ url('/for-official-business') }}">Official Business <span class="badge badge-warning">{{ session('pending_ob_count') }}</span></a></li>
                                {{-- <li class="nav-item "><a class="nav-link " href="{{ url('/for-dtr-correction') }}">DTR Correction <span class="badge badge-warning">{{ session('pending_dtr_count') }}</span></a></li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item @if ($header == 'subordinates') active @endif">
                        <a class="nav-link" href="{{ url('/subordinates') }}" onclick='show()'>
                            <i class="icon-monitor menu-icon"></i>
                            <span class="menu-title">Subordinates</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->role == 'Admin')
                    <li class="nav-item">
                        <hr>
                        <h5>Super Admin</h5>
                    </li>

                    @if (checkUserPrivilege('timekeeping_dashboard',auth()->user()->id) == 'yes')
                    <li class="nav-item @if ($header == 'Timekeeping') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#Timekeeping" aria-expanded="@if ($header == 'Timekeeping') true @else false @endif" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Timekeeping</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse @if ($header == 'Timekeeping') show @endif" id="Timekeeping">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/timekeeping-dashboard') }}">Timekeeping</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/generated-timekeeping') }}">Generated Timekeeping</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (checkUserPrivilege('employees_view',auth()->user()->id) == 'yes')
                    <li class="nav-item @if ($header == 'employees') active @endif ">
                        <a class="nav-link" href="{{ url('/employees') }}" onclick='show()'>
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">Employees</span>
                        </a>
                    </li>
                    @endif

                    @if (checkUserPrivilege('biometrics_per_employee',auth()->user()->id) == 'yes' 
                            || checkUserPrivilege('biometrics_per_location',auth()->user()->id) == 'yes' 
                            || checkUserPrivilege('biometrics_per_company',auth()->user()->id) == 'yes' 
                            || checkUserPrivilege('biometrics_per_seabased',auth()->user()->id) == 'yes' 
                            || checkUserPrivilege('biometrics_per_hik_vision',auth()->user()->id) == 'yes' 
                            || checkUserPrivilege('biometrics_sync',auth()->user()->id) == 'yes')
                    <li class="nav-item @if ($header == 'biometrics') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#biometrics" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-clock menu-icon"></i>
                            <span class="menu-title">Biometrics</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="biometrics">
                            <ul class="nav flex-column sub-menu">
                                {{-- <li class="nav-item"> <a class="nav-link" href="{{ url('/get-biometrics') }}">Biometrics</a></li> --}}
                                @if (checkUserPrivilege('biometrics_per_employee',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/biologs-employee') }}">Per Employee</a></li>
                                @endif
                                @if (checkUserPrivilege('biometrics_per_location',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/bio-per-location') }}">Per Location</a></li>
                                @endif
                                {{-- @if (checkUserPrivilege('biometrics_per_location_hik',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/bio-per-location-hik') }}">Per Location (HIK)</a></li>
                                @endif --}}
                                @if (checkUserPrivilege('biometrics_per_company',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/biometrics-per-company') }}">Per Company</a></li>
                                @endif
                                {{-- @if (checkUserPrivilege('biometrics_per_seabased',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/seabased-attendances') }}">Per Seabased</a></li>
                                @endif --}}
                                {{-- @if (checkUserPrivilege('biometrics_per_hik_vision',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/hik-attendances') }}">Per HIK Vision</a></li>
                                @endif --}}
                                @if (checkUserPrivilege('biometrics_sync',auth()->user()->id) == 'yes')
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/sync-biometrics') }}">Sync Biometrics</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (checkUserPrivilege('settings_view',auth()->user()->id) == 'yes')
                    <li class="nav-item @if ($header == 'settings') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="@if ($header == 'settings') true @else false @endif" aria-controls="ui-basic">
                            <i class="icon-cog menu-icon"></i>
                            <span class="menu-title">Settings</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse @if ($header == 'settings') show @endif" id="settings">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/holidays') }}">Holidays</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/schedules') }}">Schedules</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/allowances') }}">Allowances</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/incentives') }}">Incentives</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/handbooks') }}">Handbook</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/leavee-settings') }}">Leave Type</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/announcements') }}">Announcements</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/logos') }}">Logos</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/hr-approver-setting') }}">HR Approver Setting</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item @if ($header == 'Payroll') active @endif">
                        <a class="nav-link" data-toggle="collapse" href="#payroll" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-align-center menu-icon"></i>
                            <span class="menu-title">Payroll</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="payroll">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/pay-reg') }}">Payroll Register</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link" href="{{ url('/loan-reg') }}">Loan Register</a></li>
                            </ul>
                        </div>
        </li>
                    @endif

                    
        @if (checkUserPrivilege('masterfiles_companies',auth()->user()->id) == 'yes' || checkUserPrivilege('masterfiles_departments',auth()->user()->id) == 'yes' || checkUserPrivilege('masterfiles_loan_types',auth()->user()->id) == 'yes' || checkUserPrivilege('masterfiles_employee_leave_credits',auth()->user()->id) == 'yes')
        <li class="nav-item @if ($header == 'masterfiles') active @endif">
            <a class="nav-link" data-toggle="collapse" href="#masterfiles" aria-expanded="false" aria-controls="ui-basic">
                <i class="icon-align-center menu-icon"></i>
                <span class="menu-title">Masterfiles</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="masterfiles">
                <ul class="nav flex-column sub-menu">
                    @if(checkUserPrivilege('masterfiles_companies',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/company') }}">Companies</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_departments',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/department') }}">Departments</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_locations',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/location') }}">Locations</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_projects',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/project') }}">Projects</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_loan_types',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/loan-type') }}">Loan Types</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-incentive') }}">Employee Incentives</a>
                    </li>
                    @if(checkUserPrivilege('masterfiles_employee_allowances',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-allowance') }}">Employee Allowances</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/salary-management') }}">Salary Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-companies') }}">Employee Groups</a>
                    </li>
                    @if(checkUserPrivilege('masterfiles_employee_leave_credits',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-leave-credits') }}">Employee Leave Credits</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_employee_leave_credits',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/manual-employee-earned-leaves') }}">Manual Earned Leaves</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-leave-balances') }}">Employee Leave Balances</a>
                    </li>
                    @endif
                    @if(checkUserPrivilege('masterfiles_employee_leave_earned',auth()->user()->id) == 'yes')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee-earned-leaves') }}">Employee Earned Leaves</a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
        @endif
        @if (checkUserPrivilege('reports_leave',auth()->user()->id) == 'yes' || checkUserPrivilege('reports_overtime',auth()->user()->id) == 'yes' || checkUserPrivilege('reports_wfh',auth()->user()->id) == 'yes' || checkUserPrivilege('reports_ob',auth()->user()->id) == 'yes')
        <li class="nav-item @if ($header == 'reports') active @endif">
            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="ui-basic">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="reports">
                <ul class="nav flex-column sub-menu">
                    {{-- <li class="nav-item"> <a class="nav-link" href="{{ url('/employee-report') }}">Employees</a></li> --}}
                    @if (checkUserPrivilege('reports_leave',auth()->user()->id) == 'yes')
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/leave-report') }}">Leave Reports</a></li>
                    @endif
                    @if (checkUserPrivilege('reports_overtime',auth()->user()->id) == 'yes')
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/overtime-report') }}">Overtime Reports</a></li>
                    @endif
                    @if (checkUserPrivilege('reports_wfh',auth()->user()->id) == 'yes')
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/wfh-report') }}">WFH Reports</a></li>
                    @endif
                    @if (checkUserPrivilege('reports_ob',auth()->user()->id) == 'yes')
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/ob-report') }}">OB Reports</a></li>
                    @endif
                    @if (checkUserPrivilege('reports_dtr',auth()->user()->id) == 'yes')
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/dtr-report') }}">DTR Reports</a></li>
                    @endif
                    {{-- <li class="nav-item"> <a class="nav-link" href="{{ url('/dtr-report') }}">DTR Reports</a></li> --}}
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/totalExpense-report') }}">Total Expenses</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/loan-report') }}">Loans Report</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/incentive-report') }}">Incentive Reports</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/payroll-report') }}">Payroll Reports</a></li>
                </ul>
            </div>
        </li>
        @endif
        @endif
        </ul>
        </nav>
        <!-- partial -->

        @yield('content')
        <!-- main-panel ends -->

    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    @include('sweetalert::alert')
    <!-- plugins:js -->
    <script src="{{ asset('/body_css/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('/body_css/vendors/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('/body_css/vendors/select2/select2.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->

    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('/body_css/js/dashboard.js') }}"></script>
    <script src="{{ asset('/body_css/js/select2.js') }}"></script>


    <script src="{{ asset('/body_css/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/body_css/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('/body_css/vendors/jquery.repeater/jquery.repeater.min.js') }}"></script>

    <script src="{{ asset('/body_css/js/dataTables.select.min.js') }}"></script>

    <script src="{{ asset('/body_css/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/body_css/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('/body_css/js/template.js') }}"></script>
    <script src="{{ asset('/body_css/js/settings.js') }}"></script>
    <script src="{{ asset('/body_css/js/todolist.js') }}"></script>

    <script src="{{ asset('/body_css/js/tabs.js') }}"></script>
    <script src="{{ asset('/body_css/js/form-repeater.js') }}"></script>
    <script src="{{ asset('/body_css/vendors/sweetalert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('/body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script src="{{ asset('/body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script src="{{ asset('/body_css/js/inputmask.js') }}"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.6/dist/sweetalert2.all.min.js"></script> --}}
    {{-- <script src="{{ asset('/body_css/js/form-validation.js') }}"></script>
    <script src="{{ asset('/body_css/js/bt-maxLength.js') }}"></script> --}}
    @yield('footer')
    <script>
        var span = document.getElementById('span');
      
        function time() {
        var d = new Date();
        var s = d.getSeconds();
        var m = d.getMinutes();
        var h = d.getHours();
        span.textContent = 
            ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
        }
      
        setInterval(time, 1000);
      </script>
    <script type='text/javascript'>
        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }

        function show() {
            document.getElementById("loader").style.display = "block";
        }

        function logout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
        $(document).ready(function() {

            $('.tablewithSearch').DataTable({
                //"ordering": true,
                //"pageLength": 100,
                //"paging": false,
                //"fixedColumns": {
                //	"left": 2
                //}
            });

            $('#datatableEmployee thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#datatableEmployee thead');

                var datatable = $('#datatableEmployee').DataTable({
                    // stateSave: true
                    orderCellsTop: true
                    , fixedHeader: true
                    , fixedColumns: {
                        left: 1
                        , right: 1
                    }
                    , initComplete: function() {
                        var api = this.api();

                        // For each column
                        api
                            .columns()
                            .eq(0)
                            .each(function(colIdx) {
                                // Set the header cell to contain the input element
                                var cell = $('.filters th').eq(
                                    $(api.column(colIdx).header()).index()
                                );
                                var title = $(cell).text();
                                $(cell).html('<input type="text" placeholder="' + title + '" />');

                                // On every keypress in this input
                                $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                                    .off('keyup change')
                                    .on('keyup change', function(e) {
                                        e.stopPropagation();

                                        // Get the search value
                                        $(this).attr('title', $(this).val());
                                        var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                        var cursorPosition = this.selectionStart;
                                        // Search the column for that value
                                        api
                                            .column(colIdx)
                                            .search(
                                                this.value != '' ?
                                                regexr.replace('{search}', '(((' + this.value + ')))') :
                                                ''
                                                , this.value != ''
                                                , this.value == ''
                                            )
                                            .draw();

                                        $(this)
                                            .focus()[0]
                                            .setSelectionRange(cursorPosition, cursorPosition);
                                    });
                            });
                    }
                , });

            $('.employees-table').DataTable({
                 // "ordering": true,
                // "pageLength": 100,
                // "paging":         false,
                // "fixedColumns":   {
                //     "left": 2
                // }
            });
            $('.user-table').DataTable({
                // "ordering": true,
                // "pageLength": 100,
                // "paging":         false,
                // "fixedColumns":   {
                //     "left": 2
                // }
            });
            $('.users_table').DataTable({
                // "ordering": true,
                // "pageLength": 100,
                // "paging":         false,
                // "fixedColumns":   {
                //     "left": 2
                // }
            });

            $('.employee_seabased_attendance').DataTable({
                // "ordering": true,
                // "pageLength": 100,
                // "paging":         false,
                // "fixedColumns":   {
                //     "left": 2
                // }
            });

            $('.tablewithSearchonly').DataTable({
                "paging": false
                , "sDom": "lfrti"

            });
            $('#validateLevel').change(function() {
                var selectedValue = $(this).val();
                console.log(selectedValue);
                
                if (selectedValue != '1' ) {
                    $('#isAllowedOvertime').show();
                } else {
                    $('#isAllowedOvertime').hide();
                }
            });
            $('#validateLevel').load(function() {
                var selectedValue = $(this).val();
                console.log(selectedValue);
                
                if (selectedValue != '1' ) {
                    $('#isAllowedOvertime').show();
                } else {
                    $('#isAllowedOvertime').hide();
                }
            });

        });

    </script>
    </div>
    <script>
        $(document).ready(function() {

            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;

            $(".next").click(function() {
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                var fld = $(this).closest("fieldset").attr('id');
                // alert(fld);
                var isValid = true;
                var classname = 'required';
                $('#' + fld + ' .' + classname + '').each(function(i, obj) {
                    if (obj.value == '') {
                        isValid = false;
                        return isValid;
                    }
                });

                if (!isValid) {
                    $('#' + fld + ' .' + classname + '').each(function(i, obj) {
                        if (obj.value == '') {

                            var d = (obj.className).includes("js-example-basic-single");
                            if (d == false) {
                                // return false;
                                obj.style.border = '1px solid red';
                            } else {

                                $("select[name='" + obj.getAttribute("name") + "']").next("span").css(
                                    'border', '1px solid red');
                                console.log(obj.getAttribute("name"));
                            }
                        } else {
                            $("select[name='" + obj.getAttribute("name") + "']").next("span").css(
                                'border', '1px solid #CED4DA');
                            obj.style.border = '1px solid #CED4DA';
                        }
                    });
                }
                if (isValid) {


                    //Add Class Active
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            // for making fielset appear animation
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none'
                                , 'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        }
                        , duration: 600
                    });
                }
                return isValid;

            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none'
                            , 'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    }
                    , duration: 600
                });
            });

            $('.radio-group .radio').click(function() {
                $(this).parent().find('.radio').removeClass('selected');
                $(this).addClass('selected');
            });

            $(".halfDayStatus").hide();
            $("#leaveHalfday").change(function(){
                if($(this).is(':checked')){
                    $(".halfDayStatus").show(300);
                }else{
                    $(".halfDayStatus").hide(200);
                }
            });

            $("#editViewleaveHalfday").change(function(){
                if($(this).is(':checked')){
                $(".edithalfDayStatus").show(300);
                }else{
                $(".edithalfDayStatus").hide(200);
                }
            });

            $("#privacy-check").click(function() {
                $("#privacy").attr("disabled", !this.checked); 
                if(this.checked == false) {
                    $("#privacy").prop('checked', false); 
                    $("#privacy").removeAttr('checked'); 
                    $("#submit-btn").attr("disabled",true);
                }
            });

            $("#privacy").click(function() {
                $("#submit-btn").attr("disabled", !this.checked);
            });

            $("#privacy-contact-check").click(function() {
                $("#privacy-contact").attr("disabled", !this.checked);

                if(this.checked == false) {
                    $("#privacy-contact").prop('checked', false); 
                    $("#privacy-contact").removeAttr('checked'); 
                    $("#submit-contact-btn").attr("disabled",true);
                }

            });

            $("#privacy-contact").click(function() {
                $("#submit-contact-btn").attr("disabled", !this.checked);
            });

            $("#privacy-beneficiaries-check").click(function() {
                $("#privacy-beneficiaries").attr("disabled", !this.checked);

                if(this.checked == false) {
                    $("#privacy-beneficiaries").prop('checked', false); 
                    $("#privacy-beneficiaries").removeAttr('checked'); 
                    $("#submit-beneficiaries-btn").attr("disabled",true);
                }

            });

            $("#privacy-beneficiaries").click(function() {
                $("#submit-beneficiaries-btn").attr("disabled", !this.checked);
            });

           
            // Get references to the input fields
            var $break_hrs = $('#break_hrs');
            var $approve_hrs = $('#approve_hrs');
            var $total_approve_hours = $('#total_approve_hours');

            // Add event listeners to the input fields
            $break_hrs.on('keyup', calculate);
            $approve_hrs.on('keyup', calculate);

            // Define the calculate function
            function calculate() {
                var value_break_hrs = parseFloat($break_hrs.val()) || 0;
                var value_approve_hrs = parseFloat($approve_hrs.val()) || 0;
                var total_approve_hrs = value_approve_hrs - value_break_hrs;
                $total_approve_hours.val(total_approve_hrs);
            }
   

        });
    </script>

    @include('sweetalert::alert')
    @yield('allowanceScript')
    @yield('incentivescript')
    @yield('masterfilesScript')
    @yield('LeaveScript')
    @yield('OvertimeScript')
    @yield('wfhScript')
    @yield('obScript')
    @yield('dtrScript')
    @yield('ForApprovalScript')

    @yield('loanRegScripts')
    @yield('empAllowScript')
    @yield('empIncentiveScript')
</body>

</html>
