<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tansy Barbershop')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-cut me-2 text-warning"></i>Tansy <span class="text-warning">Barbershop</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#kontak">Kontak</a></li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link btn btn-warning text-dark px-3 ms-2 rounded-pill" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->nama }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('pelanggan.dashboard') }}"><i class="fas fa-home me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelanggan.booking.index') }}"><i class="fas fa-calendar me-2"></i>Booking</a></li>
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
                        </li>
                    @endif
                @else
                    <li class="nav-item ms-2">
                        <a class="nav-link btn btn-outline-warning px-3 rounded-pill" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn btn-warning text-dark px-3 rounded-pill" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer class="footer-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-warning fw-bold mb-3"><i class="fas fa-cut me-2"></i>Tansy Barbershop</h5>
                <p class="text-secondary">Tempat perawatan rambut profesional untuk pria modern. Kami menghadirkan pengalaman barbershop premium dengan harga terjangkau.</p>

            </div>
            <div class="col-md-2">
                <h6 class="text-warning fw-bold mb-3">Menu</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}#home" class="text-secondary text-decoration-none footer-link">Home</a></li>
                    <li><a href="{{ route('home') }}#tentang" class="text-secondary text-decoration-none footer-link">Tentang</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="text-secondary text-decoration-none footer-link">Layanan</a></li>
                    <li><a href="{{ route('home') }}#kontak" class="text-secondary text-decoration-none footer-link">Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-warning fw-bold mb-3">Jam Operasional</h6>
                <ul class="list-unstyled text-secondary">
                    <li><i class="fas fa-clock me-2 text-warning"></i>Senin - Minggu: 10.00 - 20.00</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-warning fw-bold mb-3">Kontak</h6>
                <ul class="list-unstyled text-secondary">
                    <li><i class="fas fa-map-marker-alt me-2 text-warning"></i>Jl. Pramuka, Purwawinangun, Kec. Kuningan, Kabupaten Kuningan, Jawa Barat 45512</li>
                    <li><i class="fas fa-phone me-2 text-warning"></i>083813404273</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <div class="text-center text-secondary">
            <small>&copy; {{ date('Y') }} Tansy Barbershop. All rights reserved.</small>
        </div>
    </div>
</footer>

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
