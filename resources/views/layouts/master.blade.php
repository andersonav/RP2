<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.title-meta')
    @include('layouts.head')
</head>

@section('body')
    <body>
        <div
            class="modal fade bs-example-modal-lg"
            tabindex="-1"
            aria-labelledby="largeModal"
            aria-hidden="true"
            id="nivel1"
            style="display:none;"
            data-bs-backdrop="static"
        >
            <div class="modal-dialog modal-lg">
                @yield('layout-modal')
            </div>
        </div>

    @show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                @yield('page-title')
                                <div class="page-title-right">
                                    @yield('page-title-right')
                                </div>
                            </div>
                        </div>
                    </div>

                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
