@extends('layouts.app')
@section('title', 'Tansy Barbershop - Premium Barbershop')
@section('content')

{{-- Hero --}}
<section class="hero-section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="hero-badge">Premium Barbershop</div>
                <h1 class="hero-title mb-4">Tampil <span class="text-warning">Percaya Diri</span> Dengan Potongan Terbaik</h1>
                <p class="hero-subtitle mb-4">Tansy Barbershop menghadirkan layanan potong rambut profesional dengan teknik modern dan nuansa klasik yang elegan. Booking sekarang dan rasakan perbedaannya.</p>
                <div class="d-flex gap-3 flex-wrap" style="position:relative;z-index:10;">
                    @auth
                        @if(auth()->user()->isPelanggan())
                            <a href="{{ route('pelanggan.booking.index') }}" class="btn btn-gold btn-lg"><i class="fas fa-calendar-plus me-2"></i>Booking Sekarang</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gold btn-lg"><i class="fas fa-calendar-plus me-2"></i>Booking Sekarang</a>
                    @endauth
                    <a href="#layanan" class="btn btn-outline-gold btn-lg"><i class="fas fa-scissors me-2"></i>Lihat Layanan</a>
                </div>
                {{-- Stats - hanya rating --}}
                <div class="row mt-5 g-3">
                    <div class="col-4">
                        <div class="text-warning fw-bold fs-3">4.6</div>
                        <div class="text-secondary small">Rating Bintang</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                {{-- Foto logo barbershop --}}
                <div class="hero-image-wrapper text-center">
                    <img src="{{ asset('assets/foto/tansyLogo.png') }}"
                         alt="Tansy Barbershop"
                         style="width:100%;max-width:480px;aspect-ratio:1/1;object-fit:cover;border-radius:15px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Tentang --}}
<section class="about-section" id="tentang">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Tentang Kami</div>
            <h2 class="section-title">Mengapa Memilih <span class="text-warning">Tansy Barbershop?</span></h2>
            <p class="text-secondary mt-3 mx-auto" style="max-width:550px;">Kami berkomitmen memberikan layanan terbaik dengan standar kualitas premium yang terjangkau untuk semua kalangan.</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['fa-medal','Pengalaman Nyaman','Kursi empuk, suasana santai, dan musik yang membuat potong rambut jadi menyenangkan.'],
                ['fa-calendar-check','Booking Online Mudah','Pesan jadwal kapan saja dan di mana saja. Tidak perlu antri, datang tepat waktu.'],
                ['fa-gem','Produk Premium','Menggunakan produk perawatan rambut berkualitas tinggi untuk hasil yang maksimal.'],
                ['fa-shield-alt','Alat Steril & Bersih','Semua peralatan selalu disterilisasi untuk menjaga kebersihan dan kesehatan pelanggan.'],
                ['fa-tag','Harga Terjangkau','Harga yang bersahabat tanpa mengorbankan kualitas layanan yang diberikan.'],
                ['fa-smile','Pelayanan Ramah','Staff yang ramah dan bersahabat siap melayani Anda dengan sepenuh hati.'],
            ] as [$icon, $judul, $desc])
            <div class="col-md-4">
                <div class="about-card">
                    <div class="about-icon"><i class="fas {{ $icon }}"></i></div>
                    <h5 class="text-warning mb-2">{{ $judul }}</h5>
                    <p class="text-secondary mb-0 small">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Layanan --}}
<section class="services-section" id="layanan">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Layanan Kami</div>
            <h2 class="section-title">Pilihan <span class="text-warning">Layanan Premium</span></h2>
            <p class="text-secondary mt-3 mx-auto" style="max-width:550px;">Berbagai layanan perawatan rambut dan wajah tersedia untuk memenuhi kebutuhan penampilan Anda.</p>
        </div>
        <div class="row g-4">
            @forelse($layanan as $l)
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    @if($l->foto)
                        <img src="{{ asset('uploads/layanan/'.$l->foto) }}" class="card-img-top" alt="{{ $l->nama_layanan }}" style="height:200px;object-fit:cover;">
                    @else
                        <div class="service-img-placeholder"><i class="fas fa-cut"></i></div>
                    @endif
                    <div class="p-4">
                        <h5 class="text-white mb-2">{{ $l->nama_layanan }}</h5>
                        <p class="text-secondary small mb-3">{{ $l->deskripsi }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="service-price">{{ $l->harga_format }}</span>
                            <span class="service-duration"><i class="fas fa-clock me-1"></i>{{ $l->estimasi_waktu }} menit</span>
                        </div>
                        @auth
                            @if(auth()->user()->isPelanggan())
                                <a href="{{ route('pelanggan.booking.index', ['layanan' => $l->id_layanan]) }}" class="btn btn-gold w-100 mt-3">
                                    <i class="fas fa-calendar-plus me-1"></i>Booking
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-gold w-100 mt-3">
                                <i class="fas fa-sign-in-alt me-1"></i>Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12 text-center text-secondary">Belum ada layanan tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Testimoni --}}
@if($testimoni->count())
<section class="testimonial-section" id="testimoni">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Testimoni</div>
            <h2 class="section-title">Apa Kata <span class="text-warning">Pelanggan Kami?</span></h2>
        </div>
        <div class="row g-4">
            @foreach($testimoni as $t)
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i <= $t->rating ? '' : 'text-secondary' }}"></i>@endfor
                    </div>
                    <p class="text-secondary mb-3 fst-italic">"{{ $t->komentar }}"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:40px;height:40px;flex-shrink:0;">
                            <i class="fas fa-user text-dark"></i>
                        </div>
                        <div>
                            <div class="text-white fw-semibold small">{{ $t->user->nama }}</div>
                            <div class="text-secondary" style="font-size:0.75rem;">Pelanggan</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Kontak --}}
<section class="contact-section" id="kontak">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Kontak</div>
            <h2 class="section-title">Temukan <span class="text-warning">Kami Disini</span></h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="contact-card h-100">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h6 class="text-warning mb-2">Alamat</h6>
                    <p class="text-secondary small mb-0">Jl. Pramuka, Purwawinangun<br>Kec. Kuningan, Kabupaten Kuningan, Jawa Barat 45512</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="contact-card h-100">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <h6 class="text-warning mb-2">Jam Buka</h6>
                    <p class="text-secondary small mb-0">Senin-Minggu: 10.00 - 20.00</p>
                </div>
            </div>
        </div>

        {{-- Google Maps --}}
        <div class="row mt-5">
            <div class="col-12">
                <div style="border-radius:15px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5);line-height:0;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d416.79526682618734!2d108.48241423536086!3d-6.974661238525922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f17dfa786ac97%3A0xe731abc7e5f7e2b9!2sTansy%20Barbershop!5e0!3m2!1sid!2sid!4v1779621972076!5m2!1sid!2sid"
                        width="100%"
                        height="400"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection