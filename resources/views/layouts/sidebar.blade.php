<label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
<ul class="br-sideleft-menu">
  <li class="br-menu-item">
    <a href="{{ url('dashboard') }}" class="br-menu-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
      <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
      <span class="menu-item-label">Beranda</span>
    </a><!-- br-menu-link -->
  </li>
  <li class="br-menu-item">
    <a href="{{ url('collaboration') }}" class="br-menu-link {{ (request()->segment(1) == 'collaboration') ? 'active' : '' }}">
      <i class="menu-item-icon fa fa-handshake"></i>
      <span class="menu-item-label">Kerja Sama</span>
    </a><!-- br-menu-link -->
  </li>
  <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'teacher') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-chalkboard-teacher"></i>
          <span class="menu-item-label">Data Dosen</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="{{ url('teacher') }}" class="sub-link {{ (request()->segment(1) == 'teacher') ? 'active' : '' }}">Profil Dosen</a></li>
          <li class="sub-item"><a href="{{ url('teacher/ewmp') }}" class="sub-link {{ (request()->segment(2) == 'ewmp') ? 'active' : '' }}">Ekuivalen Waktu Mengajar</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'master') ? 'active' : '' }}">
            <i class="menu-item-icon icon ion-ios-briefcase-outline tx-22"></i>
            <span class="menu-item-label">Data Master</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ url('master/academic-year') }}" class="sub-link {{ (request()->segment(2) == 'academic-year') ? 'active' : '' }}">Tahun Akademik</a></li>
            <li class="sub-item"><a href="{{ url('master/study-program') }}" class="sub-link {{ (request()->segment(2) == 'study-program') ? 'active' : '' }}">Program Studi</a></li>
        </ul>
    </li>
</ul><!-- br-sideleft-menu -->
