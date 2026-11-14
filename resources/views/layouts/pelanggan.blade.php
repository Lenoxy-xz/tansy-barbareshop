<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Tansy Barbershop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar" style="background:rgba(10,10,10,0.97);border-bottom:1px solid rgba(201,168,76,0.2);padding:10px 0;">
    <div class="container-fluid px-4">
        <button type="button" class="btn btn-sm btn-outline-warning pelanggan-menu-btn me-2" id="pelangganSidebarToggle" aria-label="Buka menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand fw-bold me-auto" href="{{ route('home') }}">
            <i class="fas fa-cut me-2 text-warning"></i>Tansy <span class="text-warning">Barbershop</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-secondary small d-none d-md-inline">{{ auth()->user()->nama }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="pelanggan-wrapper">
    <div class="pelanggan-overlay" id="pelangganOverlay"></div>
    <div class="pelanggan-sidebar" id="pelangganSidebar">
        <div class="p-4 border-bottom" style="border-color:rgba(255,255,255,0.08) !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:45px;height:45px;flex-shrink:0;">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('uploads/profil/'.auth()->user()->foto) }}" class="rounded-circle w-100 h-100" style="object-fit:cover;">
                    @else
                        <i class="fas fa-user text-dark"></i>
                    @endif
                </div>
                <div>
                    <div class="text-white fw-semibold small">{{ auth()->user()->nama }}</div>
                    <div class="text-secondary" style="font-size:0.75rem;">Pelanggan</div>
                </div>
            </div>
        </div>
        <nav class="p-2">
            <a href="{{ route('pelanggan.dashboard') }}"        class="pelanggan-nav-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('pelanggan.booking.index') }}"    class="pelanggan-nav-link {{ request()->routeIs('pelanggan.booking.*') ? 'active' : '' }}"><i class="fas fa-calendar-plus"></i> Booking Layanan</a>
            <a href="{{ route('pelanggan.riwayat.index') }}"    class="pelanggan-nav-link {{ request()->routeIs('pelanggan.riwayat.*') ? 'active' : '' }}"><i class="fas fa-history"></i> Riwayat Booking</a>
            <a href="{{ route('pelanggan.pembayaran.index') }}" class="pelanggan-nav-link {{ request()->routeIs('pelanggan.pembayaran.*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Pembayaran</a>
            <a href="{{ route('pelanggan.testimoni.index') }}"  class="pelanggan-nav-link {{ request()->routeIs('pelanggan.testimoni.*') ? 'active' : '' }}"><i class="fas fa-star"></i> Beri Testimoni</a>
            <a href="{{ route('pelanggan.profil.index') }}"     class="pelanggan-nav-link {{ request()->routeIs('pelanggan.profil.*') ? 'active' : '' }}"><i class="fas fa-user-edit"></i> Profil</a>
            <hr style="border-color:rgba(255,255,255,0.08);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="pelanggan-nav-link border-0 bg-transparent w-100 text-start" style="color:#ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="pelanggan-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

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
