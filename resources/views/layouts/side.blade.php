<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link text-center">
        <span class="brand-text font-weight-light mx-auto">SAM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block" style="font-size: 0.7cm">{{ Auth::user()->idn }}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item {{ !request()->is('hubungan_bk_cpl') ? 'menu-open' : '' }}">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ !request()->is('hubungan_bk_cpl') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Beranda
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('hubungan_bk_cpl') ? 'menu-open' : '' }}">
                    <a href="{{ route('hubungan_bk_cpl') }}"
                        class="nav-link {{ request()->is('hubungan_bk_cpl') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Relasi CPL dan BK
                        </p>
                    </a>
                </li>
            </ul>
            <div style="position: absolute; bottom: 0; width: 100%;">
                <button class="btn bg-danger">
                    <a class="nav-link text-dark" href="{{ route('logout') }}">Logout</a>
                </button>
            </div>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
