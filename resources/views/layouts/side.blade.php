<nav class="col-md-2">
    <div class="sidebar-sticky" style="height: 100%; width: 12%; background-color: #ffffff">
        <h5 class="sidebar-heading d-flex justify-content-center align-items-center py-3"
            style="background-color: #808080; color: #ffffff;">
            <i class="fas fa--alt"></i> SAM
        </h5>
        <div class="text-center text-muted my-2">
            <h1>{{ Auth::user()->idn }}</h1>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item btn-info active">
                <a class="nav-link text-white" style="text-decoration: none" href="{{ route('home') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Beranda
                </a>
            </li>
        </ul>
        <div class="mt-auto" style="position: absolute; bottom: 0; width: 100%;">
            <button class="btn w-100" style="background-color: #fd6161">
                <a class="nav-link text-dark" href="{{ route('logout') }}">Logout</a>
            </button>
        </div>
    </div>
</nav>
