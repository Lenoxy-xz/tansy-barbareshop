@extends('layouts.pelanggan')
@section('title','Pembayaran')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Pembayaran</h4>
    <p class="text-secondary small mb-0">Upload bukti pembayaran dan cek status.</p>
</div>

@if($pembayaranList->isEmpty())
    <div class="stat-card text-center py-5">
        <i class="fas fa-receipt text-secondary fs-1 mb-3 d-block"></i>
        <p class="text-secondary">Belum ada data pembayaran.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($pembayaranList as $p)
        <div class="col-12" id="pay-{{ $p->booking->id_booking }}">
            <div class="stat-card {{ $focusBooking===$p->booking->id_booking ? 'border-warning':'' }}"
                 style="{{ $focusBooking===$p->booking->id_booking ? 'border:1px solid #c9a84c !important;':'' }}">
                <div class="row align-items-start">
                    <div class="col-md-5">
                        <div class="text-warning small fw-semibold mb-1">{{ $p->booking->kode_booking }}</div>
                        <div class="text-white fw-semibold mb-1">{{ $p->booking->layanan->nama_layanan }}</div>
                        <div class="text-secondary small">{{ $p->booking->tanggal_booking->format('d F Y') }} — {{ substr($p->booking->jam_booking,0,5) }}</div>
                        <div class="mt-2">
                            <span class="text-warning fw-bold">{{ $p->total_format }}</span>
                            <span class="text-secondary ms-2 small">{{ strtoupper($p->booking->metode_pembayaran) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0">
                        <div class="text-secondary small mb-1">Status Pembayaran:</div>
                        <span class="badge bg-{{ $p->status_badge }} text-{{ $p->status_pembayaran==='menunggu_verifikasi'?'dark':'white' }}">{{ $p->status_label }}</span>
                        @if($p->bukti_pembayaran)
                        <div class="mt-2">
                            <a href="{{ asset('uploads/pembayaran/'.$p->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-image me-1"></i>Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        @if(in_array($p->booking->metode_pembayaran,['transfer','qris']) && $p->status_pembayaran!=='lunas' && $p->booking->status_booking!=='dibatalkan')
                            <div class="p-3 rounded" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                                <p class="text-secondary small mb-2"><i class="fas fa-upload me-1 text-warning"></i>Upload Bukti Pembayaran:</p>
                                <form method="POST" action="{{ route('pelanggan.pembayaran.upload') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_pembayaran" value="{{ $p->id_pembayaran }}">
                                    <input type="file" name="bukti" class="form-control form-control-sm mb-2"
                                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                                           accept="image/*" required>
                                    <button type="submit" class="btn btn-sm btn-gold w-100"><i class="fas fa-upload me-1"></i>Upload</button>
                                </form>
                            </div>
                        @elseif($p->booking->metode_pembayaran==='cash' && $p->status_pembayaran!=='lunas' && $p->booking->status_booking!=='dibatalkan')
                            <div class="text-secondary small p-3 rounded" style="background:rgba(255,255,255,0.03);">
                                <i class="fas fa-info-circle text-warning me-1"></i>Pembayaran cash dilakukan langsung di tempat saat layanan selesai.
                            </div>
                        @elseif($p->status_pembayaran === 'lunas')
                            <div class="text-success small"><i class="fas fa-check-circle me-1"></i>Pembayaran terverifikasi</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
@if($focusBooking)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('pay-{{ $focusBooking }}');
    if (el) setTimeout(() => el.scrollIntoView({ behavior:'smooth', block:'center' }), 500);
});
</script>
@endpush
@endif
