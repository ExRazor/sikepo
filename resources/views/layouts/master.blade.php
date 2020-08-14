<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="{{setting('app_description')}}">
    <meta name="author" content="Dikadikkun">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{setting('app_name')}}</title>

    <!-- vendor css -->
    <link href="{{ asset ('assets/lib') }}/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/rickshaw/rickshaw.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/alertify/css/alertify.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/sweetalert/sweetalert2.min.css" rel="stylesheet">
    @yield('style')

    <!-- APP CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/css') }}/bracket.css">
    <link rel="stylesheet" href="{{ asset ('assets') }}/app.css">

    <!-- Custom CSS -->
    @yield('custom-style')
    @stack('custom-style')

    <!-- APP JS -->
    <script src="{{ asset ('assets/lib') }}/jquery/jquery.min.js"></script>
    <script src="{{ asset ('assets/js') }}/bracket.js"></script>
  </head>

  <body>

    <!-- START: LEFT SIDEBAR -->
    <div class="br-logo"><a href="{{url('/')}}"><span><i>[</i></span>{{setting('app_short')}}<span><i>]</i></span></a></div>
    <div class="br-sideleft sideleft-scrollbar">
        @if(Auth::user()->hasRole('dosen'))
            @include('layouts.sidebar-teacher')
        @else
            @include('layouts.sidebar')
        @endif
    </div>
    <!-- END: LEFT SIDEBAR -->

    <!-- START: HEADER -->
    <div class="br-header">
        @include('layouts.header')
    </div>
    <!-- END: HEADER -->

    <!-- START: CONTENT -->
    <div class="br-mainpanel">
        @yield('content')
        <footer class="br-footer my-auto">
            <div class="footer-left">
                <div class="mg-b-2">Copyright &copy; 2019. DikaDikkun. All Rights Reserved.</div>
                <div>Carefully made by ThemePixels. Modified for needs by DikaDikkun.</div>
            </div>
        </footer>
    </div>
    <!-- END: CONTENT -->

    <!-- Vendor JS -->
    <script src="{{ asset ('assets/lib') }}/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="{{ asset ('assets/lib') }}/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/moment/min/moment.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/peity/jquery.peity.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/select2/js/select2.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/select2/js/i18n/id.js"></script>
    <script src="{{ asset ('assets/lib') }}/alertify/alertify.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/sweetalert/sweetalert2.min.js"></script>
    <script src="{{ asset('assets/lib') }}/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="{{ asset('assets/lib') }}/jquery-maskMoney/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('assets/lib') }}/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('assets/js') }}/encrypt_url.js"></script>
    @yield('js')

    <!-- BASE JS -->
    <script src="{{ asset ('assets') }}/cust.func.js"></script>
    <script src="{{ asset ('assets') }}/app.js"></script>

    <!-- CUSTOM JS -->
    @stack('custom-js')
  </body>
</html>
