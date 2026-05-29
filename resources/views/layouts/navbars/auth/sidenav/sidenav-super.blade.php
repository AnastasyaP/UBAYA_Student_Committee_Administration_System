<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dashboard') }}"
            target="_blank">
            <img src="{{ asset('./img/logo-ubaya.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Dashboard Kepanitiaan</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">   
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'dashboard.super' ? 'active' : '' }}" href="{{ route('dashboard.super') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3 d-flex align-items-center">
                <div class="ps-4">
                    <i class="fab fa-laravel" style="color: #f4645f;"></i>
                </div>
                <h6 class="ms-2 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Data Pengguna</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'students') == true ? 'active' : '' }}" href="{{ route('students.super') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-hat-3 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Mahasiswa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admins') == true ? 'active' : '' }}" href="{{ route('admins.super') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-badge text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin Kepanitiaan</span>
                </a>
            </li>
            <li class="nav-item mt-3 d-flex align-items-center">
                <div class="ps-4">
                    <i class="fab fa-laravel" style="color: #f4645f;"></i>
                </div>
                <h6 class="ms-2 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Data Kepanitiaan</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'organizer-units') == true ? 'active' : '' }}" href="{{ route('units.super') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Unit Penyelenggara</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'committees') == true ? 'active' : '' }}" href="{{ route('committees.super') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-briefcase-24 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kepanitiaan</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- <div class="sidenav-footer mx-3 ">
        <a href="/docs/bootstrap/overview/argon-dashboard/index.html" target="_blank"
            class="btn btn-dark btn-sm w-100 mb-3">Documentation</a>
        <a class="btn btn-primary btn-sm mb-0 w-100"
            href="https://www.creative-tim.com/product/argon-dashboard-pro-laravel" target="_blank" type="button">Upgrade to PRO</a>
    </div> -->
</aside>
