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
            <span class="logged-name hidden-md-down">Katherine</span>
            <img src="https://via.placeholder.com/500" class="wd-32 rounded-circle" alt="">
            <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
            <div class="tx-center">
                <a href=""><img src="https://via.placeholder.com/500" class="wd-80 rounded-circle" alt=""></a>
                <h6 class="logged-fullname">Katherine P. Lumaad</h6>
                <p>youremail@domain.com</p>
            </div>
            <hr>
            <div class="tx-center">
                <span class="profile-earning-label">Earnings After Taxes</span>
                <h3 class="profile-earning-amount">$13,230 <i class="icon ion-ios-arrow-thin-up tx-success"></i></h3>
                <span class="profile-earning-text">Based on list price.</span>
            </div>
            <hr>
            <ul class="list-unstyled user-profile-nav">
                <li><a href=""><i class="icon ion-ios-person"></i> Edit Profile</a></li>
                <li><a href=""><i class="icon ion-ios-gear"></i> Settings</a></li>
                <li><a href=""><i class="icon ion-ios-download"></i> Downloads</a></li>
                <li><a href=""><i class="icon ion-ios-star"></i> Favorites</a></li>
                <li><a href=""><i class="icon ion-ios-folder"></i> Collections</a></li>
                <li><a href=""><i class="icon ion-power"></i> Sign Out</a></li>
            </ul>
            </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
    </nav>
</div><!-- br-header-right -->
