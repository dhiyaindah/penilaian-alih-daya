<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2 text-wrap" 
                  style="font-size: 1.1rem; line-height: 1.3;">
                Sistem Penilaian<br>Kinerja Alih Daya
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>
        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'Admin')
            <li class="menu-item {{ Request::is('admin/pegawai*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">Data Pegawai ASN</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/alih_daya*') ? 'active' : '' }}">
                <a href="{{ route('alih_daya.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">Data Tim Alih Daya</div>
                </a>
            </li>
            <li class="menu-item 
                {{ Request::is('admin/penilaian') && !Request::is('admin/penilaian/rekap*') ? 'active' : '' }}">
                <a href="{{ route('penilaian.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-list-check"></i>
                    <div>Penilaian Kinerja</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/penilaian/rekap*') ? 'active' : '' }}">
                <a href="{{ route('penilaian.rekap') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-list-check"></i>
                    <div data-i18n="Analytics">Rekap Penilaian</div>
                </a>
            </li>
            

        @endif
    </ul>
</aside>
