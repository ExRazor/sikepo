<div class="br-header-left">
    <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
    <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
    <div class="ht-65 pd-x-20 d-flex align-items-center justify-content-start">
        <ul class="nav nav-gray-600 active-info tx-uppercase tx-12 tx-medium tx-spacing-2 flex-column flex-sm-row" role="tablist">
                <li class="nav-item">{{setting('app_faculty_name')}} - {{setting('app_department_name')}}</li>

                {{-- <li class="nav-item {{ Session::get('prodi_aktif')==$p->kd_prodi ? 'active' : '' }}"><a class="nav-link btn-prodi-active" href="/setpro/{{encrypt($p->kd_prodi)}}" role="tab">{{$p->nama}}</a></li> --}}

        </ul>
    </div>
</div><!-- br-header-left -->
<div class="br-header-right">
    <nav class="nav">
        <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
            <span class="logged-name hidden-md-down">{{Auth::user()->name}}</span>
            @if(!Auth::user()->hasRole('dosen'))
                <img class="wd-32 rounded-circle" src="{{isset(Auth::user()->foto) ? route('download.avatar','type=user&id='.encrypt(Auth::user()->foto)) : route('download.avatar','type=avatar&id='.encrypt('user.png'))}}" alt="{{Auth::user()->name}}">
            @else
                <img class="wd-32 rounded-circle" src="{{isset(Auth::user()->teacher->foto) ? route('download.avatar','type=teacher&id='.encrypt(Auth::user()->teacher->foto)) : route('download.avatar','type=avatar&id='.encrypt('user.png'))}}" alt="{{Auth::user()->name}}">
            @endif
            <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
            <div class="tx-center">
                @if(!Auth::user()->hasRole('dosen'))
                    <img class="wd-80 rounded-circle" src="{{isset(Auth::user()->foto) ? route('download.avatar','type=user&id='.encrypt(Auth::user()->foto)) : route('download.avatar','type=avatar&id='.encrypt('user.png'))}}" alt="{{Auth::user()->name}}">
                @else
                    <img class="wd-80 rounded-circle" src="{{isset(Auth::user()->teacher->foto) ? route('download.avatar','type=teacher&id='.encrypt(Auth::user()->teacher->foto)) : route('download.avatar','type=avatar&id='.encrypt('user.png'))}}" alt="{{Auth::user()->name}}">
                @endif
                <h6 class="logged-fullname">{{Auth::user()->name}}</h6>
                <p>{{ucfirst(Auth::user()->role)}}{{isset(Auth::user()->kd_prodi) ? ' - '.Auth::user()->studyProgram->nama : ''}}</p>
            </div>
            <hr>
            <ul class="list-unstyled user-profile-nav">
                @if(!Auth::user()->hasRole('dosen'))
                <li><a href="{{route('account.editprofile')}}"><i class="icon ion-ios-person"></i> Ubah Profil</a></li>
                @endif
                <li><a href="{{route('account.editpassword')}}"><i class="icon ion-ios-gear"></i> Ganti Kata Sandi</a></li>
                <li><a href="{{route('logout')}}"><i class="icon ion-power"></i> Keluar</a></li>
            </ul>
            </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
    </nav>
</div><!-- br-header-right -->
