<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $meta_title ?? 'UTHS Student Reminder System' }}</title>

    <!-- Meta -->
    <meta name="description" content="UTHS Student Reminder System – manage lectures, assignments, and academic reminders all in one place.">
    <meta name="author" content="UTHS Student Reminder System" />
    <meta property="og:type" content="website" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- favicon -->

        <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ url('assets/css/app.min.css?v=' .env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="{{ url('assets/css/icons.min.css?v=' .env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />

        <script src="{{ url('assets/js/head.js?v=' .env('CACHE_VERSION')) }}"></script>

    <style>
        /* Scrollbar */
        * { scrollbar-width: thin; scrollbar-color: #0d6efd transparent; }
        *::-webkit-scrollbar { width: 5px; height: 5px; }
        *::-webkit-scrollbar-thumb { background-color: #0d6efd; border-radius: 10px; }

        /* Alerts */
        .custom-alert-success {
            background-color: #d1fae5; border: 1px solid #10b981; color: #065f46;
            padding: 12px 16px; border-radius: 8px; margin-top: 16px; font-size: 14px;
        }

        .custom-alert-error {
            background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b;
            padding: 12px 16px; border-radius: 8px; margin-top: 16px; font-size: 14px;
        }

        .custom-btn-primary {
            background-color: darkgreen; color: white; padding: 10px 24px; border-radius: 8px;
            font-size: 14px; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px;
        }
        .custom-btn-primary:hover { background-color: #006400; }

        /* Inputs */
        .custom-input {
            width: 100%; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px;
            font-size: 14px; color: #374151; background-color: #fff;
        }
        .custom-input:focus { outline: none; border-color: #0d6efd; }

        .custom-label {
            display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #374151;
        }

        /* User Avatar Styles */
        .user-avatar {
            width: 32px;
            height: 32px;
            object-fit: cover;
        }

        .user-avatar-placeholder {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

    <body data-menu-color="light" data-sidebar="default">
   {{ csrf_field() }}
     <section class="h-screen flex items-center justify-center bg-no-repeat inset-0 bg-cover ">
              <!-- Begin page -->
        <div id="app-layout">

                 <!-- Topbar Start -->
            <div class="topbar-custom">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li>
                                <button class="button-toggle-menu nav-link">
                                    <i data-feather="menu" class="noti-icon"></i>
                                </button>
                            </li>
                            <li class="d-none d-lg-block">
                                <h5 class="mb-0">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name ?? 'Guest' }}</h5>
                            </li>
                        </ul>

                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                               <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i data-feather="bell" class="noti-icon"></i>
                                    <span class="badge bg-danger rounded-circle noti-icon-badge">9</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5 class="m-0">
                                            <span class="float-end"><a href="#" class="text-dark"><small>Clear All</small></a></span>Notification
                                        </h5>
                                    </div>

                                    <div class="noti-scroll" data-simplebar>
                                        <!-- item-->
                                        <a href="javascript:void(0);"
                                            class="dropdown-item notify-item text-muted link-primary active">
                                            <div class="notify-icon">
                                                <img src="assets/images/users/user-12.jpg" class="img-fluid rounded-circle" alt="" />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="notify-details">Carl Steadham</p>
                                                <small class="text-muted">5 min ago</small>
                                            </div>
                                            <p class="mb-0 user-msg">
                                                <small class="fs-14">Completed <span class="text-reset">Improve workflow in Figma</span></small>
                                            </p>
                                        </a>

                                   
                                    </div>

                                    <!-- All-->
                                    <a href="" class="dropdown-item text-center text-primary notify-item notify-all">View all
                                        <i class="fe-arrow-right"></i>
                                    </a>
                                </div>
                            </li>
                            <!-- User Dropdown -->
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                             alt="user-image" class="rounded-circle user-avatar" />
                                    @else
                                        <div class="bg-primary rounded-circle text-white user-avatar-placeholder">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="pro-user-name ms-1">
                                        {{ auth()->user()->name ?? 'User' }} 
                                        <i class="mdi mdi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome {{ auth()->user()->name }}!</h6>
                                    </div>

                                    <!-- item-->
                                    <a class='dropdown-item notify-item' href="{{ route('student.profile') }}">
                                        <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                        <span>My Account</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                          <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                          <button class='dropdown-item notify-item' type="submit">
                                        <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                        <span>Logout</span>
                          </button>
                    </form>
                                    <!-- item-->

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end Topbar -->


            <!-- Left Sidebar Start -->
            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a class='logo logo-light' href="{{ route('student.dashboard') }}">
                                <span class="logo-sm">
                                    <img src="{{ url('assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ url('assets/images/logo-light.png') }}" alt="" height="24">
                                </span>
                            </a>
                            <a class='logo logo-dark' href="{{ route('student.dashboard') }}">
                                <span class="logo-sm">
                                    <img src="{{ url('assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ url('assets/images/logo-dark.png') }}" alt="" height="24">
                                </span>
                            </a>
                        </div>
<ul id="side-menu">
    <li class="menu-title">Menu</li>

    <li>
        <a href="{{ route('student.dashboard') }}">
            <i data-feather="home"></i>
            <span> Dashboard </span>
        </a>
    </li>

    <li class="menu-title">Pages</li>

    <li>
        <a class='tp-link' href="{{ route('student.tasks') }}">
            <i data-feather="columns"></i>
            <span> My Tasks List </span>
        </a>
    </li>

    <li>
        <a class='tp-link' href="{{ route('student.calendar') }}">
            <i data-feather="calendar"></i>
            <span> Calendar </span>
        </a>
    </li>

    <li>
        <a class='tp-link' href="{{ route('student.profile') }}">
            <i data-feather="users"></i>
            <span> Profile </span>
        </a>
    </li>

    <li>
        <a class='tp-link' href="">
            <i data-feather="bell"></i>
            <span> Notifications </span>
        </a>
    </li>
</ul>
                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- Left Sidebar End -->

            @yield('content')

             <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col fs-13 text-muted text-center">
                                &copy; <script>document.write(new Date().getFullYear())</script> - Made with <span class="mdi mdi-heart text-danger"></span> by <a href="#!" class="text-reset fw-semibold">Hando</a> 
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
        </div>
     </section>

     <script src="{{ url('assets/libs/jquery/jquery.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/simplebar/simplebar.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/node-waves/waves.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/waypoints/lib/jquery.waypoints.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/jquery.counterup/jquery.counterup.min.js?v=' .env('CACHE_VERSION')) }}"></script>
        <script src="{{ url('assets/libs/feather-icons/feather.min.js?v=' .env('CACHE_VERSION')) }}"></script>

        <!-- App js-->
        <script src="{{ url('assets/js/app.js?v=' .env('CACHE_VERSION')) }}"></script>

</body>
</html>