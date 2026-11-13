<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Tansy Barbershop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<nav class="navbar navbar-dark admin-topbar px-3">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-warning" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-white fw-semibold d-none d-md-block">@yield('title', 'Dashboard')</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-secondary small d-none d-md-block">{{ now()->format('d F Y') }}</span>
        <div class="dropdown">
            <button class="btn btn-sm btn-dark dropdown-toggle text-white" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->nama }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i>Lihat Website</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="admin-wrapper">
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-cut text-warning me-2"></i>
            <span class="sidebar-title">Tansy Admin</span>
        </div>
        <nav class="sidebar-nav">
            <ul class="list-unstyled mb-0">
                <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="{{ route('admin.layanan.index') }}" class="sidebar-link {{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}"><i class="fas fa-scissors"></i><span>Kelola Layanan</span></a></li>
                <li><a href="{{ route('admin.booking.index') }}" class="sidebar-link {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i><span>Kelola Booking</span></a></li>
                <li><a href="{{ route('admin.pelanggan.index') }}" class="sidebar-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}"><i class="fas fa-users"></i><span>Daftar Pelanggan</span></a></li>
                <li><a href="{{ route('admin.transaksi.index') }}" class="sidebar-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i><span>Kelola Transaksi</span></a></li>
                <li><a href="{{ route('admin.testimoni.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}"><i class="fas fa-star"></i><span>Kelola Testimoni</span></a></li>
                <li><a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i><span>Laporan</span></a></li>
                <li class="mt-3"><a href="{{ route('home') }}" class="sidebar-link"><i class="fas fa-home"></i><span>Ke Beranda</span></a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-link text-danger-soft w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    <div class="admin-content" id="adminContent">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
<script>$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>

@if(session('success'))
<script>Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session('success') }}', timer:3000, showConfirmButton:false });</script>
@endif
@if(session('error'))
<script>Swal.fire({ icon:'error', title:'Gagal!', text:'{{ session('error') }}', timer:3000, showConfirmButton:false });</script>
@endif
@if($errors->any())
<script>Swal.fire({ icon:'warning', title:'Perhatian!', html:'{{ implode('<br>', $errors->all()) }}', timer:4000, showConfirmButton:false });</script>
@endif

@stack('scripts')
</body>
</html>
