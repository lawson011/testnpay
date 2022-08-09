<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <title>NuturePay - Admin</title>
    <meta content="Admin Dashboard" name="description">
    <meta content="" name="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
{{--include css link--}}
    @include('layouts.partials.css')
</head>
<body class="fixed-left"><!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner"></div>
    </div>
</div><!-- Begin page -->
<div id="wrapper">
    <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.sidebar.index')
    <!-- Left Sidebar End -->
    <!-- Start right Content here -->
    <div class="content-page"><!-- Start content -->
        <div class="content"><!-- Top Bar Start -->
            <div class="topbar">
                @include('layouts.header')
            </div>
            <!-- Top Bar End -->
            <div class="page-content-wrapper">
                @yield('content')
                <!-- container -->
            </div>
            <!-- Page content Wrapper -->
        </div>
        <!-- content -->
        <footer class="footer">
            © {{ \Illuminate\Support\Carbon::now()->year }} by NutureTech.
        </footer>
    </div>
    <!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->

{{--include javascript--}}
@include('layouts.partials.js')

<script>
{{--    @if(! auth()->check())--}}
{{--        window.location = '/home';--}}
{{--    @endif--}}
    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

    });
    $(document).ajaxStart(function(){
        $.LoadingOverlay("show");
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });
    $("form").submit(function() {
        $.LoadingOverlay("show");
    });
</script>
@yield('script')
</body>
</html>
