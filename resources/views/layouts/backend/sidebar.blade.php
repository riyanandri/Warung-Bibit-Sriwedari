<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">{{ $config->name }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">WBS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li class="menu-header">Menu Utama</li>
            <li class="{{ Route::is('admin.order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.order') }}"><i class="fas fa-clipboard-list"></i> <span>Daftar Pesanan</span></a>

            <li class="{{ Route::is('admin.customer') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.customer') }}"><i class="fas fa-user-tie"></i> <span>Pengguna</span></a>

            <li class="dropdown {{ Route::is('admin.categories') || Route::is('admin.product')  ? 'active' : '' }}">
            {{-- <li class="dropdown {{ Route::is('admin.categories')  ? 'active' : '' }}"> --}}
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cube"></i> <span>Produk</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.categories') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.categories') }}">Kategori</a></li>
                    <li class="{{ Route::is('admin.product') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product') }}">Daftar Produk</a></li>
                </ul>
            </li>
            <li class="menu-header">Konfigurasi</li>
            <li class="{{ Route::is('admin.configuration') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.configuration') }}"><i class="fas fa-plug"></i> <span>Konfigurasi Web</span></a>
            {{-- <li class="{{ Route::is('users.*') || Route::is('user.*')  ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.configuration') }}"><i class="fas fa-plug"></i> <span>Configuration Web</span></a> --}}
            </li>
            {{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                <a href="https://github.com/prayogimhd" class="btn btn-primary btn-lg btn-block btn-icon-split">
                  <i class="fas fa-rocket"></i> Github
                </a>
              </div> --}}
        </ul>
    </aside>
</div>
