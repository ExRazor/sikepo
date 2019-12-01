<label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
<ul class="br-sideleft-menu">
  <li class="br-menu-item">
    <a href="{{ route('dashboard') }}" class="br-menu-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
      <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
      <span class="menu-item-label">Beranda</span>
    </a><!-- br-menu-link -->
  </li>
  <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'teacher') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-chalkboard-teacher"></i>
          <span class="menu-item-label">Data Dosen</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="{{ route('teacher') }}" class="sub-link {{ (request()->is('teacher/list*')) ? 'active' : '' }}">Profil Dosen</a></li>
          <li class="sub-item"><a href="{{ route('teacher.achievement') }}" class="sub-link {{ (request()->is('teacher/achievement*')) ? 'active' : '' }}">Prestasi</a></li>
          <li class="sub-item"><a href="{{ route('teacher.ewmp') }}" class="sub-link {{ (request()->is('teacher/ewmp*')) ? 'active' : '' }}">EWMP</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'student') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-user-graduate"></i>
          <span class="menu-item-label">Data Mahasiswa</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
        <li class="sub-item"><a href="{{ route('student.quota') }}" class="sub-link {{ (request()->is('student/quota*')) ? 'active' : '' }}">Kuota Mahasiswa</a></li>
          <li class="sub-item"><a href="{{ route('student') }}" class="sub-link {{ (request()->is('student/list*')) ? 'active' : '' }}">Data Mahasiswa</a></li>
          <li class="sub-item"><a href="{{ route('student.foreign') }}" class="sub-link {{ (request()->is('student/foreign*')) ? 'active' : '' }}">Mahasiswa Asing</a></li>
          <li class="sub-item"><a href="{{ route('student.achievement') }}" class="sub-link {{ (request()->is('student/achievement*')) ? 'active' : '' }}">Prestasi</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'academic') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-atom"></i>
            <span class="menu-item-label">Pendidikan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('academic.curriculum') }}" class="sub-link {{ (request()->is('academic/curriculum*')) ? 'active' : '' }}">Kurikulum</a></li>
            <li class="sub-item"><a href="{{ route('academic.schedule') }}" class="sub-link {{ (request()->is('academic/schedule*')) ? 'active' : '' }}">Jadwal Kurikulum</a></li>
            <li class="sub-item"><a href="{{ route('academic.integration') }}" class="sub-link {{ (request()->is('academic/integration*')) ? 'active' : '' }}">Integrasi Kurikulum</a></li>
            <li class="sub-item"><a href="{{ route('academic.minithesis') }}" class="sub-link {{ (request()->is('academic/minithesis*')) ? 'active' : '' }}">Tugas Akhir</a></li>
            <li class="sub-item"><a href="{{ route('academic.satisfaction') }}" class="sub-link {{ (request()->is('academic/satisfaction*')) ? 'active' : '' }}">Kepuasan</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="{{ route('collaboration') }}" class="br-menu-link {{ (request()->is('collaboration*')) ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-handshake"></i>
            <span class="menu-item-label">Kerja Sama</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('research') }}" class="br-menu-link {{ (request()->segment(1) == 'research') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-book-reader"></i>
          <span class="menu-item-label">Penelitian</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('community-service') }}" class="br-menu-link {{ (request()->segment(1) == 'community-service') ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-american-sign-language-interpreting"></i>
          <span class="menu-item-label">Pengabdian</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'publication') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-newspaper"></i>
            <span class="menu-item-label">Publikasi</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('publication.category') }}" class="sub-link {{ (request()->is('publication/category*')) ? 'active' : '' }}">Jenis Publikasi</a></li>
            <li class="sub-item"><a href="{{ route('publication') }}" class="sub-link {{ (request()->is('publication/list*')) ? 'active' : '' }}">Daftar Publikasi</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
            <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'output-activity') ? 'active' : '' }}">
                <i class="menu-item-icon fa fa-boxes"></i>
                <span class="menu-item-label">Luaran Kegiatan</span>
            </a><!-- br-menu-link -->
            <ul class="br-menu-sub">
                <li class="sub-item"><a href="{{ route('output-activity.category') }}" class="sub-link {{ (request()->is('output-activity/category*')) ? 'active' : '' }}">Kategori Luaran</a></li>
                <li class="sub-item"><a href="{{ route('output-activity') }}" class="sub-link {{ (request()->is('output-activity/list*')) ? 'active' : '' }}">Data Luaran</a></li>
            </ul>
        </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'funding') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-balance-scale"></i>
            <span class="menu-item-label">Keuangan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('funding.category') }}" class="sub-link {{ (request()->is('funding/category*')) ? 'active' : '' }}">Kategori Dana</a></li>
            <li class="sub-item"><a href="{{ route('funding.faculty') }}" class="sub-link {{ (request()->is('funding/faculty*')) ? 'active' : '' }}">Keuangan Fakultas</a></li>
            <li class="sub-item"><a href="{{ route('funding.study-program') }}" class="sub-link {{ (request()->is('funding/study-program*')) ? 'active' : '' }}">Keuangan Program Studi</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'alumnus') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-graduation-cap"></i>
            <span class="menu-item-label">Lulusan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('alumnus.attainment') }}" class="sub-link {{ (request()->is('alumnus/attainment*')) ? 'active' : '' }}">Capaian Pembelajaran</a></li>
            <li class="sub-item"><a href="{{ route('alumnus.satisfaction') }}" class="sub-link {{ (request()->is('alumnus/satisfaction*')) ? 'active' : '' }}">Kepuasan Pengguna</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'master') ? 'active' : '' }}">
            <i class="menu-item-icon icon ion-ios-briefcase-outline tx-22"></i>
            <span class="menu-item-label">Data Master</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('master.academic-year') }}" class="sub-link {{ (request()->is('master/academic-year*')) ? 'active' : '' }}">Tahun Akademik</a></li>
            <li class="sub-item"><a href="{{ route('master.study-program') }}" class="sub-link {{ (request()->is('master/study-program*')) ? 'active' : '' }}">Program Studi</a></li>
            <li class="sub-item"><a href="{{ route('master.department') }}" class="sub-link {{ (request()->is('master/department*')) ? 'active' : '' }}">Jurusan</a></li>
            <li class="sub-item"><a href="{{ route('master.faculty') }}" class="sub-link {{ (request()->is('master/faculty*')) ? 'active' : '' }}">Fakultas</a></li>
            <li class="sub-item"><a href="{{ route('master.satisfaction-category') }}" class="sub-link {{ (request()->is('master/satisfaction-category*')) ? 'active' : '' }}">Aspek Kepuasan</a></li>
        </ul>
    </li>
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ (request()->segment(1) == 'setting') ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-cogs"></i>
            <span class="menu-item-label">Setelan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('setting.general')}}" class="sub-link {{ (request()->is('setting/general*')) ? 'active' : '' }}">Umum</a></li>
            <li class="sub-item"><a href="#" class="sub-link {{ (request()->is('setting/users*')) ? 'active' : '' }}">Pengguna</a></li>
        </ul>
    </li>
</ul><!-- br-sideleft-menu -->
