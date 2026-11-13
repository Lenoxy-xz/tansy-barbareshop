@extends('layouts.pelanggan')
@section('title','Riwayat Booking')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Riwayat Booking</h4>
    <p class="text-secondary small mb-0">Semua history booking Anda.</p>
</div>

@if($bookings->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="fas fa-calendar-times text-secondary fs-1 mb-3 d-block"></i>
        <p class="text-secondary">Belum ada riwayat booking.</p>
        <a href="{{ route('pelanggan.booking.index') }}" class="btn btn-gold mt-2">Buat Booking Sekarang</a>
    </div>
@else
    <div class="row g-3">
        @foreach($bookings as $b)
        <div class="col-12">
            <div class="stat-card" style="border-left:3px solid {{ match($b->status_booking) {'selesai'=>'#198754','diproses'=>'#0d6efd','dibatalkan'=>'#dc3545',default=>'#6c757d'} }};">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="text-warning fw-semibold small">{{ $b->kode_booking }}</span>
                            <span class="badge bg-{{ $b->status_badge }}">{{ ucfirst($b->status_booking) }}</span>
                        </div>
                        <h6 class="text-white mb-1">{{ $b->layanan->nama_layanan }}</h6>
                        <div class="text-secondary small">
                            <i class="fas fa-calendar me-1"></i>{{ $b->tanggal_booking->format('d F Y') }}
                            <i class="fas fa-clock ms-3 me-1"></i>{{ substr($b->jam_booking,0,5) }}
                            <i class="fas fa-users ms-3 me-1"></i>Antrian #{{ $b->nomor_antrian }}
                        </div>
                        @if($b->catatan)
                        <div class="text-secondary mt-1" style="font-size:0.78rem;"><i class="fas fa-sticky-note me-1"></i>{{ $b->catatan }}</div>
                        @endif
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0">
                        <div class="text-warning fw-bold">Rp {{ number_format($b->pembayaran?->total_bayar ?? $b->layanan->harga, 0, ',', '.') }}</div>
                        <div class="text-secondary small">{{ strtoupper($b->metode_pembayaran) }}</div>
                        @if($b->pembayaran)
                            <span class="badge bg-{{ $b->pembayaran->status_badge }} text-{{ $b->pembayaran->status_pembayaran==='menunggu_verifikasi'?'dark':'white' }} mt-1">
                                {{ $b->pembayaran->status_label }}
                            </span>
                        @endif
                    </div>
                    <div class="col-md-2 mt-3 mt-md-0 d-flex flex-column gap-2 align-items-end">
                        @if($b->status_booking==='pending' && $b->pembayaran?->status_pembayaran==='belum_dibayar')
                            <a href="{{ route('pelanggan.pembayaran.index', ['id'=>$b->id_booking]) }}" class="btn btn-sm btn-gold w-100">
                                <i class="fas fa-credit-card me-1"></i>Bayar
                            </a>
                        @endif
                        @if($b->status_booking === 'pending')
                            <form action="{{ route('pelanggan.riwayat.cancel', $b->id_booking) }}" method="POST" class="w-100 form-cancel">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="fas fa-times me-1"></i>Batal</button>
                            </form>
                        @endif
                        @if($b->status_booking === 'selesai')
                            <a href="{{ route('pelanggan.testimoni.index') }}" class="btn btn-sm btn-outline-warning w-100"><i class="fas fa-star me-1"></i>Ulasan</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
@push('scripts')
<script>
document.querySelectorAll('.form-cancel').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title:'Batalkan Booking?', text:'Booking yang dibatalkan tidak bisa dipulihkan.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Batalkan', cancelButtonText:'Tidak', confirmButtonColor:'#dc3545' })
            .then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@endpush
