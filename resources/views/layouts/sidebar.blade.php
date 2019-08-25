<label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
<ul class="br-sideleft-menu">
  <li class="br-menu-item">
    <a href="{{ url('dashboard') }}" class="br-menu-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
      <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
      <span class="menu-item-label">Beranda</span>
    </a><!-- br-menu-link -->
  <li class="br-menu-item">
    <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'master') ? 'active' : '' }}">
      <i class="menu-item-icon icon ion-ios-briefcase-outline tx-22"></i>
      <span class="menu-item-label">Data Master</span>
    </a><!-- br-menu-link -->
    <ul class="br-menu-sub">
      <li class="sub-item"><a href="{{ url('master/academic-year') }}" class="sub-link {{ (request()->segment(2) == 'academic-year') ? 'active' : '' }}">Tahun Akademik</a></li>
      <li class="sub-item"><a href="{{ url('master/study-program') }}" class="sub-link">Program Studi</a></li>
      <li class="sub-item"><a href="height.html" class="sub-link">Jurusan</a></li>
      <li class="sub-item"><a href="margin.html" class="sub-link">Margin</a></li>
      <li class="sub-item"><a href="padding.html" class="sub-link">Padding</a></li>
      <li class="sub-item"><a href="position.html" class="sub-link">Position</a></li>
      <li class="sub-item"><a href="typography-util.html" class="sub-link">Typography</a></li>
      <li class="sub-item"><a href="width.html" class="sub-link">Width</a></li>
    </ul>
  </li>
</ul><!-- br-sideleft-menu -->
