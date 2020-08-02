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

    <div class="d-flex align-items-center justify-content-center ht-100v">
        <img src="{{asset('assets/images/login-bg.png')}}" class="wd-100p ht-100p object-fit-cover" alt="">
        <div class="overlay-body d-flex align-items-center justify-content-center">
          <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 rounded bd bd-white-2 bg-black-7">
            <div class="text-center mg-b-10">
                <img src="{{asset('assets/images/logo-ung-sm.png')}}" class="wd-100" alt="Universitas Negeri Gorontalo">
            </div>
            <div class="signin-logo tx-center tx-28 tx-bold tx-white"><span class="tx-normal tx-info">[</span> {{setting('app_short')}} <span class="tx-normal tx-info">]</span></div>
            <div class="tx-white-5 tx-center mg-b-30">
                {{setting('app_name')}}
                <br>
                Teknik Informatika
                <br>
                Universitas Negeri Gorontalo
            </div>

            @if (session()->has('error'))
                <div class="alert alert-danger mg-b-15" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{route('login_post')}}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <select name="role" class="form-control" title="Akses" required>
                        <option value="">- Pilih Akses -</option>
                        <option value="admin">Operator</option>
                        <option value="kajur">Kajur</option>
                        <option value="kaprodi">Kaprodi</option>
                        <option value="dosen">Dosen</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-user op-6"></i>
                            </div>
                        </div>
                        <input type="text" name="username" class="form-control" placeholder="Username/NIDN" title="Username/NIDN" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-key op-6"></i>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Kata sandi" title="Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-block">Masuk</button>
            </form>
          </div><!-- login-wrapper -->
        </div><!-- overlay-body -->
    </div><!-- d-flex -->

    <script src="{{ asset ('assets/lib') }}/jquery/jquery.min.js"></script>
    <script src="{{ asset ('assets/lib') }}/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="{{ asset ('assets/lib') }}/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
