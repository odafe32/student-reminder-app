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
    </style>
</head>

<body class="bg-gray-50">
   {{ csrf_field() }}
     <section class="h-screen flex items-center justify-center bg-no-repeat inset-0 bg-cover ">

            @yield('content')
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
