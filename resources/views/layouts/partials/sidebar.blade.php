<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">SMK GRAFIKA</a>
        </div>
        {{-- SIDEBAR --}}
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">AM</a>
        </div>
        @php
            $user = auth()->user();
        @endphp
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Manajemen Data</li>
            <li class="{{ request()->routeIs('admin.data.kriteria.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.data.kriteria.index') }}">
                    <i class="fas fa-check-square"></i> <span>Kriteria</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.data.siswa.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.data.siswa.index') }}">
                    <i class="fas fa-th-large""></i> <span>Data Siswa</span>
                </a>
            </li>

            <li class=" {{ request()->routeIs('admin.data.penilaian.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.data.penilaian.index') }}">
                    <i class="fas fa-pen-nib"></i> <span>Penilaian Alternatif</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.data.perhitungan.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.data.perhitungan.index') }}">
                    <i class="fas fa-calculator"></i> <span>Data Perhitungan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.data.perhitungan.result') ? 'active' : '' }}">
                <a class=" nav-link" href="{{ route('admin.data.perhitungan.result') }}">
                    <i class="fas fa-table"></i> <span>Hasil Perhitungan</span>
                </a>
            </li>
            {{-- <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Layout</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="layout-default.html">Default Layout</a></li>
                    <li><a class="nav-link" href="layout-transparent.html">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="layout-top-navigation.html">Top Navigation</a></li>
                </ul>
            </li> --}}
            @role('admin')
                <li class="menu-header">Management User</li>
                <li class="{{ request()->routeIs('admin.personil.user.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.personil.user.index') }}">
                        <i class="fas fa-users"></i> <span>Pegawai</span>
                    </a>
                </li>
            @endrole
            {{-- <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i> <span>Forms</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="forms-advanced-form.html">Advanced Form</a></li>
                    <li><a class="nav-link" href="forms-editor.html">Editor</a></li>
                    <li><a class="nav-link" href="forms-validation.html">Validation</a></li>
                </ul>
            </li> --}}
            {{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                    <i class="fas fa-rocket"></i> Documentation
                </a>
            </div> --}}
    </aside>
</div>
