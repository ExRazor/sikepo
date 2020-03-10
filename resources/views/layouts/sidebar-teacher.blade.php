<label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
<ul class="br-sideleft-menu">
    <li class="br-menu-item">
    <a href="{{ route('dashboard') }}" class="br-menu-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
        <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
        <span class="menu-item-label">Beranda</span>
    </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.biodata') }}" class="br-menu-link {{ (request()->is('profile/biodata*')) ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-user-edit"></i>
            <span class="menu-item-label">Biodata</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.achievement') }}" class="br-menu-link {{ (request()->is('profile/achievement*')) ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-trophy"></i>
            <span class="menu-item-label">Prestasi</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.ewmp') }}" class="br-menu-link {{ (request()->is('profile/ewmp*')) ? 'active' : '' }}">
            <i class="menu-item-icon fa fa-stopwatch"></i>
            <span class="menu-item-label">EWMP</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.research') }}" class="br-menu-link {{ (request()->is('profile/research*')) ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-book-reader"></i>
          <span class="menu-item-label">Penelitian</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.community-service') }}" class="br-menu-link {{ (request()->is('profile/community-service*')) ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-american-sign-language-interpreting"></i>
          <span class="menu-item-label">Pengabdian</span>
        </a><!-- br-menu-link -->
    </li>
    <li class="br-menu-item">
        <a href="{{ route('profile.publication') }}" class="br-menu-link {{ (request()->is('profile/publication*')) ? 'active' : '' }}">
          <i class="menu-item-icon fa fa-newspaper"></i>
          <span class="menu-item-label">Publikasi</span>
        </a><!-- br-menu-link -->
    </li>
</ul><!-- br-sideleft-menu -->
