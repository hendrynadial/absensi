<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin | {{ $title ?? "Absensi YPSIM" }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin YPSIM" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <link rel="shortcut icon" href="{{ asset('/assets/images/logo-ypsim.ico') }}">
    <link href="{{ asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/own.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    <div id="layout-wrapper">
        @include('layouts/header')
        @include('layouts/sidebar')
        <div class="page-layout-content">
            @yield('content')
        </div>
        @include('layouts/footer')
    </div>

    <!-- MODAL -->
    <div class="modal fade staticBackdrop" 
        id="modal-default"
        data-bs-backdrop="static" 
        data-bs-keyboard="false" 
        tabindex="-1" role="dialog" 
        aria-labelledby="staticBackdropLabel" 
        aria-hidden="true">
        <div class="modal-dialog" role="document"></div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    
    <!-- apexcharts -->
    {{-- <script src="{{ asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}
    
    <!-- Plugins js-->
    {{-- <script src="{{ asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('/assets/js/pages/dashboard.init.js') }}"></script>--}}

    <!-- Required datatable js -->
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    
    <!-- Responsive datatable -->
    <script src="{{ asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('/assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('/assets/js/app.js') }}"></script> 
    
    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>
</html>