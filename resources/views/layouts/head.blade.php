@yield('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- Select2 -->
<link href="{{ URL::asset('/assets/libs/select2/select2.min.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- JQUERY UI -->
<link href="{{ URL::asset('/assets/libs/jquery-ui-1.13.0/jquery-ui.min.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- DROPZONE -->
<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<!-- Toastr -->
<link href="{{ URL::asset('/assets/libs/toastr/toastr.min.css') }}" id="icons-style" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('/assets/css/pixie.css') }}" id="icons-style" rel="stylesheet" type="text/css" />
