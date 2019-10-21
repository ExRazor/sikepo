<label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
<ul class="br-sideleft-menu">
  <li class="br-menu-item">
    <a href="{{ route('dashboard') }}" class="br-menu-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
      <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
      <span class="menu-item-label">Beranda</span>
    </a><!-- br-menu-link -->
  </li>
  <li class="br-menu-item">
    <a href="{{ route('collaboration') }}" class="br-menu-link {{ (request()->segment(1) == 'collaboration') ? 'active' : '' }}">
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
          <li class="sub-item"><a href="{{ route('teacher') }}" class="sub-link {{ (request()->segment(2) == 'list') ? 'active' : '' }}">Profil Dosen</a></li>
          <li class="sub-item"><a href="{{ route('teacher.ewmp') }}" class="sub-link {{ (request()->segment(2) == 'ewmp') ? 'active' : '' }}">EWMP</a></li>
          <li class="sub-item"><a href="{{ route('teacher.achievement') }}" class="sub-link {{ (request()->segment(2) == 'achievement') ? 'active' : '' }}">Prestasi</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'student') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-chalkboard-teacher"></i>
          <span class="menu-item-label">Data Mahasiswa</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="#" class="sub-link {{ (request()->segment(2) == 'list') ? 'active' : '' }}">Data Mahasiswa</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'academic') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-atom"></i>
            <span class="menu-item-label">Pendidikan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ url('academic/course') }}" class="sub-link {{ (request()->segment(2) == 'course') ? 'active' : '' }}">Mata Kuliah</a></li>
            <li class="sub-item"><a href="{{ url('academic/minithesis') }}" class="sub-link {{ (request()->segment(2) == 'minithesis') ? 'active' : '' }}">Tugas Akhir</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'master') ? 'active' : '' }}">
            <i class="menu-item-icon icon ion-ios-briefcase-outline tx-22"></i>
            <span class="menu-item-label">Data Master</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('master.academic-year') }}" class="sub-link {{ (request()->segment(2) == 'academic-year') ? 'active' : '' }}">Tahun Akademik</a></li>
            <li class="sub-item"><a href="{{ route('master.study-program') }}" class="sub-link {{ (request()->segment(2) == 'study-program') ? 'active' : '' }}">Program Studi</a></li>
            <li class="sub-item"><a href="{{ route('master.department') }}" class="sub-link {{ (request()->segment(2) == 'department') ? 'active' : '' }}">Jurusan</a></li>
            <li class="sub-item"><a href="{{ route('master.faculty') }}" class="sub-link {{ (request()->segment(2) == 'faculty') ? 'active' : '' }}">Fakultas</a></li>
        </ul>
    </li>
</ul><!-- br-sideleft-menu -->
