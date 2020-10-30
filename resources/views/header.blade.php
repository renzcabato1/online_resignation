<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <link href="{{ asset('/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('/datatable/fixedColumns.dataTables.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/chosen/chosen.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/css/style.css')}}">
    <!-- endinject -->
    <script src="{{ asset('/js/script.js')}}" ></script>
    <script type="text/javascript" src="{{ asset('/js/app.js')}}"></script>
    <link rel="shortcut icon" href="{{ asset('/login_design/images/favicon.ico')}}">
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                // scrollY: 500,
                // scrollX: true,
                // scrollCollapse: true,
                paging : false,
                // fixedColumns: true
            });
        });
        $(document).ready(function() {
            $('#clearance_info').DataTable({
                // scrollY: 500,
                // scrollX: true,
                // scrollCollapse: true,   
                paging : false,
                ordering: false,
                // fixedColumns: true
            });
        });
        $(document).ready(function() {
            $('#example1').DataTable({
                scrollY: 1000,
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: true
            });
        });
    </script>
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('/images/3.gif')}}") 50% 50% no-repeat rgb(249,249,249) ;
            opacity: .8;
            background-size:200px 120px;
        }
    </style>
</head>
<body>   
    <div id='app'>
        <div id = "myDiv" style="display:none;" class="loader">
        </div>
        <div class="container-scroller">
            <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="index.html"><img src="{{asset('images/header.png')}}" alt="logo"/></a>
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{asset('images/front-logo.png')}}" alt="logo"/></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-stretch">
                    
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                {{-- <div class="nav-profile-img">
                                    <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$name->id.'.png'}}" alt="image">
                                    <span class="availability-status online"></span>             
                                </div> --}}
                                <div class="nav-profile-text">
                                    <p class="mb-1 text-black">{{auth()->user()->name}}</p>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item"  data-toggle="modal" data-target="#profile" data-toggle="profle">
                                    <i class="mdi mdi-cached mr-2 text-success"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"  onclick="logout(); show();">
                                    <i class="mdi mdi-logout mr-2 text-primary"></i>
                                    Signout
                                </a>
                            </div>
                            @if(Auth::user())
                            <form id="logout-form"  action="{{ route('logout') }}"  method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            @endif
                        </li>
                        <li class="nav-item nav-settings d-none d-lg-block">
                            <a class="nav-link" href="#">
                                <i class="mdi mdi-format-line-spacing"></i>
                            </a>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
                
            </nav>
        </div>
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image">
                              
                                <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.auth()->user()->employee_info()->id.'.png'}}" alt="profile">
                                <span class="login-status online"></span> <!--change to offline or busy as needed-->              
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">{{substr(auth()->user()->employee_info()->first_name,0,1).'. '.auth()->user()->employee_info()->last_name}}</span>
                                <span class="text-secondary text-small" title='{{auth()->user()->employee_info()->position}}'>{{substr(auth()->user()->employee_info()->position,0,12)}}</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    @php
                    $roles_array = null;
                    if(auth()->user()->role() != null)
                    {
                        $roles = auth()->user()->role()->role_id;
                        $roles_array = json_decode($roles);
                    }
                    @endphp
                    @if($roles_array == null)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/for-clearance') }}">
                            <span class="menu-title">For Clearance</span>
                            <i class="mdi mdi mdi-alert menu-icon"></i>
                        </a>
                    </li>
                    @else
                    
                    @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array))|| (in_array(1,$roles_array)) || (in_array(4,$roles_array)))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employees') }}">
                            <span class="menu-title">Employees</span>
                            <i class="mdi mdi-human-male-female menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    
                    @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/manage-account') }}">
                            <span class="menu-title">Manage Accounts</span>
                            <i class="mdi mdi-account-circle menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users') }}">
                            <span class="menu-title">Users</span>
                            <i class="mdi mdi-human-child menu-icon"></i>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ url('/complete-clearance') }}">
                            <span class="menu-title">Completed Clearance</span>
                            <i class="mdi mdi-human-child menu-icon"></i>
                        </a>
                    </li> --}}
                    @endif
                    @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/cancel-resignations') }}">
                            <span class="menu-title">Cancel Resignation</span>
                            <i class="mdi mdi-account-off menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/uploaded-letter') }}">
                            <span class="menu-title">Uploaded Letter</span>
                            <i class="mdi mdi-file menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/resigned-employee') }}">
                            <span class="menu-title">Resign Employee</span>
                            <i class="mdi mdi mdi-account-remove menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    
                    @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array))|| (in_array(1,$roles_array))|| (in_array(4,$roles_array)))
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/for-clearance') }}">
                            <span class="menu-title">For Clearance</span>
                            <i class="mdi mdi mdi-alert menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/cleared') }}">
                            <span class="menu-title">Cleared</span>
                            <i class="mdi mdi mdi-alert menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    
                    {{-- @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/surveys') }}">
                            <span class="menu-title">Surveys</span>
                            <i class="mdi mdi mdi-newspaper menu-icon"></i>
                        </a>
                    </li>
                    @endif --}}
                    
                    @if((in_array(1,$roles_array)) && (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/surveys-report') }}">
                            <span class="menu-title">Survey Report</span>
                            <i class="mdi mdi mdi-newspaper menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/print-clearance') }}">
                            <span class="menu-title">Print Clearance</span>
                            <i class="mdi mdi mdi-printer menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/clearance-report') }}">
                            <span class="menu-title">Clearance Report</span>
                            <i class="mdi mdi mdi-newspaper menu-icon"></i>
                        </a>
                    </li>
                    @endif
                    
                    @endif
                </ul>
            </nav>
            @include('change_password')
            @yield('content')
        </div>
        <script type='text/javascript'>
            function show() {
                document.getElementById("myDiv").style.display="block";
            }
        </script>
        <script src="{{ asset('chosen/docsupport/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
        <script src="{{ asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
        <script src="{{ asset('chosen/docsupport/prism.js')}}" type="text/javascript" charsgiet="utf-8"></script>
        <script src="{{ asset('chosen/docsupport/init.js')}}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ asset('/datatable/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('/datatable/dataTables.fixedColumns.min.js')}}"></script>
        
    </body>
    </html>
    