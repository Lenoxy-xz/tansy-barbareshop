@extends('layouts.pelanggan')
@section('title','Dashboard')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Selamat Datang, {{ auth()->user()->nama }}! </h4>
    <p class="text-secondary small mb-0">Kelola booking dan layanan Anda di sini.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div style="width:45px;height:45px;background:rgba(13,110,253,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-calendar-alt text-primary"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-4">{{ $totalBooking }}</div>
                    <div class="text-secondary" style="font-size:0.8rem;">Total Booking</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div style="width:45px;height:45px;background:rgba(255,193,7,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-hourglass-half text-warning"></i>
                </div>
                <div>
                    <div class="text-warning fw-bold fs-4">{{ $bookingAktif }}</div>
                    <div class="text-secondary" style="font-size:0.8rem;">Booking Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div style="width:45px;height:45px;background:rgba(25,135,84,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-coins text-success"></i>
                </div>
                <div>
                    <div class="text-success fw-bold">Rp {{ number_format($totalBayar,0,',','.') }}</div>
                    <div class="text-secondary" style="font-size:0.8rem;">Total Dibayar</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($antreanHariIni)
<div class="stat-card mb-4" style="border:1px solid rgba(201,168,76,0.3);background:rgba(201,168,76,0.05);">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <div class="text-warning fw-semibold mb-1"><i class="fas fa-users me-2"></i>Antrean Anda Hari Ini</div>
            <div class="text-secondary small">{{ $antreanHariIni->layanan->nama_layanan }} — {{ substr($antreanHariIni->jam_booking,0,5) }}</div>
        </div>
        <div class="text-center">
            <div class="text-warning fw-bold" style="font-size:2.5rem;">#{{ $antreanHariIni->nomor_antrian }}</div>
            <div class="text-secondary" style="font-size:0.75rem;">Nomor Antrian</div>
        </div>
    </div>
</div>
@endif

<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-warning mb-0"><i class="fas fa-clock me-2"></i>Booking Terbaru</h6>
        <a href="{{ route('pelanggan.riwayat.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
    </div>
    @if($bookingTerbaru->isEmpty())
        <div class="text-center py-4">
            <i class="fas fa-calendar-times text-secondary fs-2 mb-3 d-block"></i>
            <p class="text-secondary mb-3">Belum ada booking.</p>
            <a href="{{ route('pelanggan.booking.index') }}" class="btn btn-gold"><i class="fas fa-plus me-1"></i>Buat Booking</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table" style="color:#ccc;">
                <thead>
                    <tr style="border-color:rgba(255,255,255,0.08);">
                        <th style="color:#c9a84c;font-size:0.82rem;">Kode</th>
                        <th style="color:#c9a84c;font-size:0.82rem;">Layanan</th>
                        <th style="color:#c9a84c;font-size:0.82rem;">Tanggal</th>
                        <th style="color:#c9a84c;font-size:0.82rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookingTerbaru as $b)
                    <tr style="border-color:rgba(255,255,255,0.05);">
                        <td class="small text-warning">{{ $b->kode_booking }}</td>
                        <td class="small">{{ $b->layanan->nama_layanan }}</td>
                        <td class="small">{{ $b->tanggal_booking->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $b->status_badge }}">{{ ucfirst($b->status_booking) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="text-center mt-4">
    <a href="{{ route('pelanggan.booking.index') }}" class="btn btn-gold btn-lg px-5">
        <i class="fas fa-calendar-plus me-2"></i>Booking Sekarang
    </a>
</div>
@endsection
