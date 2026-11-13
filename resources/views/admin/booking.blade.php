@extends('layouts.admin')
@section('title','Kelola Booking')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Kelola Booking</div><div class="page-breadcrumb">Admin / Booking</div></div>
    <span class="badge bg-secondary">Total: {{ $bookings->count() }} booking</span>
</div>

<div class="admin-card mb-3">
    <form method="GET" action="{{ route('admin.booking.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label-dark">Filter Status</label>
            <select name="status" class="form-control form-control-dark">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Pending','diproses'=>'Diproses','sedang_melayani'=>'Sedang Melayani','hampir_selesai'=>'Hampir Selesai (10 mnt)','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status')===$val ? 'selected':'' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-dark">Filter Tanggal</label>
            <input type="date" name="tanggal" class="form-control form-control-dark" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-2"><button type="submit" class="btn btn-gold w-100"><i class="fas fa-filter me-1"></i>Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.booking.index') }}" class="btn btn-outline-secondary w-100">Reset</a></div>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-dark-custom dt-table w-100">
            <thead>
                <tr>
                    <th>Kode</th><th>Pelanggan</th><th>Layanan</th>
                    <th>Tanggal & Jam</th><th>Antrian</th>
                    <th>Pembayaran</th><th>Status Booking</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                @php
                    // Hitung jam selesai
                    [$jH,$jM]     = explode(':', $b->jam_booking);
                    $selesaiMenit = (int)$jH*60 + (int)$jM + (int)$b->layanan->estimasi_waktu;
                    $jamSelesai   = str_pad(floor($selesaiMenit/60),2,'0',STR_PAD_LEFT).':'.str_pad($selesaiMenit%60,2,'0',STR_PAD_LEFT);

                    // Badge warna status
                    $badgeText = in_array($b->status_booking, ['sedang_melayani','hampir_selesai']) ? 'dark' : 'white';
                @endphp
                <tr>
                    <td><span class="text-warning small fw-semibold">{{ $b->kode_booking }}</span></td>
                    <td>
                        <div class="fw-semibold small">{{ $b->user->nama }}</div>
                        <div class="text-secondary" style="font-size:0.75rem;">{{ $b->user->no_hp }}</div>
                    </td>
                    <td>
                        <div class="small">{{ $b->layanan->nama_layanan }}</div>
                        <div class="text-secondary" style="font-size:0.75rem;">{{ $b->layanan->harga_format }} · {{ $b->layanan->estimasi_waktu }} mnt</div>
                    </td>
                    <td>
                        <div class="small">{{ $b->tanggal_booking->format('d/m/Y') }}</div>
                        <div class="text-warning" style="font-size:0.75rem;">
                            {{ substr($b->jam_booking,0,5) }} - {{ $jamSelesai }}
                        </div>
                    </td>
                    <td class="text-center"><span class="badge bg-warning text-dark">#{{ $b->nomor_antrian }}</span></td>
                    <td>
                        @if($b->pembayaran)
                            <span class="badge bg-{{ $b->pembayaran->status_badge }} text-{{ $b->pembayaran->status_pembayaran==='menunggu_verifikasi'?'dark':'white' }}">
                                {{ $b->pembayaran->status_label }}
                            </span>
                            <div class="text-secondary mt-1" style="font-size:0.72rem;">{{ $b->pembayaran->total_format }}</div>
                        @else
                            <span class="text-secondary small">-</span>
                        @endif
                    </td>
                    <td>
                        <select class="form-select form-select-sm"
                                style="background:#2a2a2a;border-color:rgba(255,255,255,0.15);color:#ccc;min-width:185px;"
                                data-id="{{ $b->id_booking }}"
                                onchange="updateStatus(this)">
                            <option value="pending"         {{ $b->status_booking==='pending'         ?'selected':'' }}>Pending</option>
                            <option value="diproses"        {{ $b->status_booking==='diproses'        ?'selected':'' }}>Diproses</option>
                            <option value="sedang_melayani" {{ $b->status_booking==='sedang_melayani' ?'selected':'' }}>Sedang Melayani</option>
                            <option value="hampir_selesai"  {{ $b->status_booking==='hampir_selesai'  ?'selected':'' }}>Hampir Selesai (10 mnt)</option>
                            <option value="selesai"         {{ $b->status_booking==='selesai'         ?'selected':'' }}>Selesai</option>
                            <option value="dibatalkan"      {{ $b->status_booking==='dibatalkan'      ?'selected':'' }}>Dibatalkan</option>
                        </select>
                        <div class="mt-1">
                            <span class="badge bg-{{ $b->status_badge }} text-{{ $badgeText }}" style="font-size:0.7rem;">
                                {{ $b->status_label }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('admin.booking.destroy', $b->id_booking) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    data-nama="{{ $b->kode_booking }}" onclick="return konfirmasiHapus(event, this.dataset.nama)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(select) {
    $.ajax({
        url: '{{ route("admin.booking.status") }}',
        method: 'POST',
        data: { _token:'{{ csrf_token() }}', id_booking:select.getAttribute('data-id'), status_booking:select.value },
        success: res => {
            if (res.success) {
                Swal.fire({ icon:'success', title:'Berhasil!', text:res.message, timer:1500, showConfirmButton:false })
                    .then(() => location.reload());
            }
        }
    });
}
function konfirmasiHapus(e, kode) {
    e.preventDefault(); const form = e.target.closest('form');
    Swal.fire({ title:`Hapus booking ${escapeHtml(kode)}?`, icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal' })
        .then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
