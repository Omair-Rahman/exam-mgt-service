<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'Dashboard | BCS Test Exam')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="BCS Test Exam For student"/>
        <meta name="author" content="codiustechnology"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        @include('backend.partials.css')
        @stack('css')
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.jpg') }}" type="image/x-icon">
    </head>

    <!-- body start -->
    <body data-menu-color="dark" data-sidebar="default">
        <!-- Begin page -->
        <div id="app-layout">
            <!-- Topbar Start -->
            @include('backend.partials.topbar')
            <!-- end Topbar -->

            <!-- Left Sidebar Start -->
            @include('backend.partials.navbar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->

                    @yield('content')
                    
                    <!-- content -->

                    <!-- Footer Start -->
                    @include('backend.partials.footer')
                    <!-- end Footer -->
                
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

            <!--sweetalert-->
            @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11'])
            
        </div>
        <!-- END wrapper -->

        @include('backend.partials.js')
        @stack('scripts')
    </body>
</html>