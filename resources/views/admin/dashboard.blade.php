@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Dashboard</div><div class="page-breadcrumb">Selamat datang, {{ auth()->user()->nama }}!</div></div>
    <span class="badge bg-warning text-dark">{{ now()->format('d F Y') }}</span>
</div>

<div class="row g-3 mb-4">
    @foreach([[$totalPelanggan,'Total Pelanggan','fa-users','rgba(13,110,253,0.15)','text-primary'],[$totalBooking,'Total Booking','fa-calendar-alt','rgba(201,168,76,0.15)','text-warning'],[$bookingHari,'Booking Hari Ini','fa-calendar-day','rgba(25,135,84,0.15)','text-success'],[$bookingPending,'Booking Pending','fa-hourglass-half','rgba(220,53,69,0.15)','text-danger']] as [$val,$label,$icon,$bg,$cls])
    <div class="col-6 col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-number {{ $cls }}">{{ $val }}</div><div class="stat-label mt-1">{{ $label }}</div></div>
                <div class="stat-icon" style="background:{{ $bg }};"><i class="fas {{ $icon }} {{ $cls }}"></i></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card-admin" style="border-left:3px solid #c9a84c;">
            <div class="stat-label mb-1"><i class="fas fa-sun me-2 text-warning"></i>Pendapatan Hari Ini</div>
            <div class="stat-number text-warning">Rp {{ number_format($pendapatanHari,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card-admin" style="border-left:3px solid #198754;">
            <div class="stat-label mb-1"><i class="fas fa-calendar me-2 text-success"></i>Pendapatan Bulan Ini</div>
            <div class="stat-number text-success">Rp {{ number_format($pendapatanBulan,0,',','.') }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="admin-card">
            <h6 class="text-warning mb-3"><i class="fas fa-chart-line me-2"></i>Pendapatan 7 Hari Terakhir</h6>
            <canvas id="grafikPendapatan" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="admin-card h-100">
            <h6 class="text-warning mb-3"><i class="fas fa-fire me-2"></i>Layanan Terlaris</h6>
            @foreach($terlaris as $i => $item)
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark">{{ $i+1 }}</span>
                    <span class="text-secondary small">{{ $item->nama_layanan }}</span>
                </div>
                <span class="text-white fw-semibold small">{{ $item->bookings_count }} booking</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-warning mb-0"><i class="fas fa-clock me-2"></i>Booking Terbaru</h6>
        <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-dark-custom">
            <thead><tr><th>Kode</th><th>Pelanggan</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($bookingTerbaru as $b)
                <tr>
                    <td><span class="text-warning small">{{ $b->kode_booking }}</span></td>
                    <td>{{ $b->user->nama }}</td>
                    <td>{{ $b->layanan->nama_layanan }}</td>
                    <td>{{ $b->tanggal_booking->format('d/m/Y') }} <small class="text-secondary">{{ substr($b->jam_booking,0,5) }}</small></td>
                    <td><span class="badge bg-{{ $b->status_badge }}">{{ ucfirst($b->status_booking) }}</span></td>
                    <td><a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
const ctx = document.getElementById('grafikPendapatan').getContext('2d');
const grafik = @json($grafik);
new Chart(ctx, {
    type: 'bar',
    data: { labels: grafik.map(d=>d.label), datasets: [{ label:'Pendapatan', data: grafik.map(d=>d.nilai), backgroundColor:'rgba(201,168,76,0.5)', borderColor:'#c9a84c', borderWidth:2, borderRadius:6 }] },
    options: { responsive:true, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:ctx=>'Rp '+parseInt(ctx.raw).toLocaleString('id-ID')}} }, scales:{ x:{grid:{color:'rgba(255,255,255,0.05)'},ticks:{color:'#888'}}, y:{grid:{color:'rgba(255,255,255,0.05)'},ticks:{color:'#888'}} } }
});
</script>
@endpush
