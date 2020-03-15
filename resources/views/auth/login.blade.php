<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Sistem Informasi Borang Akreditasi">
    <meta name="author" content="Dikadikkun">

    <title>Login - {{setting('app_name')}}</title>

    <!-- vendor css -->
    <link href="{{ asset ('assets/lib') }}/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="{{ asset ('assets/lib') }}/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/css') }}/bracket.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset ('assets') }}/app.css">
  </head>

  <body>

    <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">

      <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
        <div class="signin-logo tx-center tx-28 tx-bold tx-inverse mg-b-30"><span class="tx-info">[</span> {{setting('app_short')}} <span class="tx-info">]</span></div>
        {{-- <div class="tx-center mg-b-30">The Admin Template For Perfectionist</div> --}}
        @if (session()->has('error'))
            <div class="alert alert-danger mg-b-15" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{route('login_post')}}">
            {!! csrf_field() !!}
            <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Isikan username">
            </div><!-- form-group -->
            <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Isikan kata sandi">
            </div><!-- form-group -->
            <button type="submit" class="btn btn-info btn-block">Sign In</button>
        </form>
      </div><!-- login-wrapper -->
    </div><!-- d-flex -->

    <script src="{{ asset ('assets/lib') }}/jquery/jquery.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="{{ asset ('assets/lib') }}/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
