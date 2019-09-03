<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - SIBAK</title>

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/css') }}/bracket.css">

    <!-- vendor css -->
    <link href="{{ asset ('assets/lib') }}/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/rickshaw/rickshaw.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/alertify/css/alertify.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/sweetalert/sweetalert2.min.css" rel="stylesheet">  

    <!-- Custom CSS -->
    @yield('style')
    <link rel="stylesheet" href="{{ asset ('assets') }}/custom.css">
  </head>

  <body>

    <!-- START: LEFT SIDEBAR -->
    <div class="br-logo"><a href=""><span>[</span>SI <i>BAK</i><span>]</span></a></div>
    <div class="br-sideleft sideleft-scrollbar">
        @include('layouts.sidebar')
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
        <footer class="br-footer">
          <div class="footer-left">
              <div class="mg-b-2">Copyright &copy; 2017. Bracket Plus. All Rights Reserved.</div>
              <div>Attentively and carefully made by ThemePixels.</div>
          </div>
      </footer>
    </div>
    <!-- END: CONTENT -->

    <script src="{{ asset ('assets/lib') }}/jquery/jquery.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="{{ asset ('assets/lib') }}/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset ('assets/lib') }}/perfect-scrollbar/perfect-scrollbar.min.js"></script> --}}
    <script src="{{ asset ('assets/lib') }}/moment/min/moment.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/peity/jquery.peity.min.js"></script>
    {{-- <script src="{{ asset ('assets/lib') }}/rickshaw/vendor/d3.min.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/rickshaw/vendor/d3.layout.min.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/rickshaw/rickshaw.min.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/jquery.flot/jquery.flot.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/jquery.flot/jquery.flot.resize.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/flot-spline/js/jquery.flot.spline.min.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/jquery-sparkline/jquery.sparkline.min.js"></script> --}}
    {{-- <script src="{{ asset ('assets/lib') }}/echarts/echarts.min.js"></script> --}}
    <script src="{{ asset ('assets/lib') }}/select2/js/select2.full.min.js"></script>
    
    <script src="{{ asset ('assets/js') }}/bracket.js"></script>
    <script src="{{ asset ('assets/js') }}/map.shiftworker.js"></script>
    <script src="{{ asset ('assets/js') }}/ResizeSensor.js"></script>
    <script src="{{ asset ('assets/js') }}/dashboard.js"></script>
    <script src="{{ asset ('assets/lib') }}/alertify/alertify.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/sweetalert/sweetalert2.min.js"></script>
    <script src="{{ asset('assets/lib') }}/jquery.maskedinput/jquery.maskedinput.js"></script>
    
    <!-- Custom JS -->
    @yield('js');
    <script src="{{ asset ('assets') }}/custom.js"></script>

    <script>
      $(function(){
        'use strict'

        // FOR DEMO ONLY
        // menu collapsed by default during first page load or refresh with screen
        // having a size between 992px and 1299px. This is intended on this page only
        // for better viewing of widgets demo.
        $(window).resize(function(){
          minimizeMenu();
        });

        minimizeMenu();

        function minimizeMenu() {
          if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1299px)').matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
          } else if(window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
          }
        }
      });
    </script>
  </body>
</html>
