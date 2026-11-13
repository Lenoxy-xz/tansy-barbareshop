@extends('layouts.admin')
@section('title','Laporan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Laporan</div><div class="page-breadcrumb">Admin / Laporan</div></div>
    <button class="btn btn-gold" onclick="window.print()"><i class="fas fa-print me-1"></i>Print Laporan</button>
</div>

<div class="admin-card mb-3">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label-dark">Jenis Laporan</label>
            <select name="tipe" class="form-control form-control-dark">
                <option value="transaksi"        {{ $tipe==='transaksi'        ?'selected':'' }}>Transaksi (Lunas)</option>
                <option value="booking"          {{ $tipe==='booking'          ?'selected':'' }}>Booking</option>
                <option value="layanan_terlaris" {{ $tipe==='layanan_terlaris' ?'selected':'' }}>Layanan Terlaris</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-dark">Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" class="form-control form-control-dark" value="{{ $tglMulai }}">
        </div>
        <div class="col-md-3">
            <label class="form-label-dark">Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" class="form-control form-control-dark" value="{{ $tglAkhir }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-gold w-100"><i class="fas fa-search me-1"></i>Tampilkan</button>
        </div>
    </form>
</div>

<div class="admin-card" id="printArea">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-warning mb-0">
            <i class="fas fa-chart-bar me-2"></i>
            Laporan {{ ucfirst(str_replace('_',' ',$tipe)) }}
            <span class="text-secondary ms-2 small fw-normal">
                {{ \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->format('d/m/Y') }}
            </span>
        </h6>
        <span class="badge bg-secondary">{{ $data->count() }} data</span>
    </div>

    @if($tipe === 'transaksi')
        <div class="alert mb-3" style="background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);">
            <i class="fas fa-coins text-warning me-2"></i>
            <span class="text-warning fw-semibold">Total Pendapatan: Rp {{ number_format($totalPendapatan,0,',','.') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-dark-custom dt-table w-100">
                <thead><tr><th>#</th><th>Tgl Bayar</th><th>Kode Booking</th><th>Pelanggan</th><th>Layanan</th><th>Metode</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($data as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $d->tanggal_pembayaran ? $d->tanggal_pembayaran->format('d/m/Y') : '-' }}</td>
                        <td class="text-warning small">{{ $d->booking->kode_booking }}</td>
                        <td>{{ $d->booking->user->nama }}</td>
                        <td>{{ $d->booking->layanan->nama_layanan }}</td>
                        <td>{{ strtoupper($d->booking->metode_pembayaran) }}</td>
                        <td class="text-warning fw-semibold">{{ $d->total_format }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end fw-bold text-warning">Total:</td>
                        <td class="fw-bold text-warning">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    @elseif($tipe === 'booking')
        <div class="table-responsive">
            <table class="table table-dark-custom dt-table w-100">
                <thead><tr><th>#</th><th>Tgl Booking</th><th>Kode</th><th>Pelanggan</th><th>Layanan</th><th>Harga</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($data as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $d->tanggal_booking->format('d/m/Y') }}</td>
                        <td class="text-warning small">{{ $d->kode_booking }}</td>
                        <td>{{ $d->user->nama }}</td>
                        <td>{{ $d->layanan->nama_layanan }}</td>
                        <td>{{ $d->layanan->harga_format }}</td>
                        <td><span class="badge bg-{{ $d->status_badge }}">{{ ucfirst($d->status_booking) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($tipe === 'layanan_terlaris')
        <div class="table-responsive">
            <table class="table table-dark-custom dt-table w-100">
                <thead><tr><th>#</th><th>Nama Layanan</th><th>Harga</th><th>Est. Waktu</th><th>Total Booking</th></tr></thead>
                <tbody>
                    @foreach($data as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="fw-semibold">{{ $d->nama_layanan }}</td>
                        <td>{{ $d->harga_format }}</td>
                        <td>{{ $d->estimasi_waktu }} mnt</td>
                        <td><span class="badge bg-primary">{{ $d->total_booking }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
@push('styles')
<style>
@media print {
    .admin-topbar,.sidebar,.d-flex.justify-content-between:first-of-type,.admin-card:first-of-type { display:none !important; }
    .admin-content { margin-left:0 !important; padding:0 !important; }
    body,.admin-body { background:#fff !important; color:#000 !important; }
    .table-dark-custom thead th { background:#f5f5f5 !important; color:#000 !important; }
    .table-dark-custom tbody td { color:#000 !important; }
}
</style>
@endpush
