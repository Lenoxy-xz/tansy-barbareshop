@extends('layouts.admin')
@section('title','Kelola Transaksi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Kelola Transaksi</div><div class="page-breadcrumb">Admin / Transaksi</div></div>
</div>

<div class="admin-card mb-3">
    <form method="GET" action="{{ route('admin.transaksi.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label-dark">Status Pembayaran</label>
            <select name="status" class="form-control form-control-dark">
                <option value="">Semua</option>
                <option value="belum_dibayar"       {{ request('status')==='belum_dibayar'       ? 'selected':'' }}>Belum Dibayar</option>
                <option value="menunggu_verifikasi" {{ request('status')==='menunggu_verifikasi' ? 'selected':'' }}>Menunggu Verifikasi</option>
                <option value="lunas"               {{ request('status')==='lunas'               ? 'selected':'' }}>Lunas</option>
            </select>
        </div>
        <div class="col-md-2"><button type="submit" class="btn btn-gold w-100">Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary w-100">Reset</a></div>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-dark-custom dt-table w-100">
            <thead><tr><th>#</th><th>Kode Booking</th><th>Pelanggan</th><th>Layanan</th><th>Metode</th><th>Total</th><th>Bukti</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($transaksi as $i => $t)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><span class="text-warning small">{{ $t->booking->kode_booking }}</span></td>
                    <td class="small">{{ $t->booking->user->nama }}</td>
                    <td class="small">{{ $t->booking->layanan->nama_layanan }}</td>
                    <td>
                        @php $icon = ['cash'=>'fa-money-bill-wave','transfer'=>'fa-university','qris'=>'fa-qrcode'][$t->booking->metode_pembayaran] ?? 'fa-credit-card'; @endphp
                        <i class="fas {{ $icon }} text-warning me-1"></i>{{ strtoupper($t->booking->metode_pembayaran) }}
                    </td>
                    <td class="text-warning fw-semibold">{{ $t->total_format }}</td>
                    <td>
                        @if($t->bukti_pembayaran)
                            <a href="{{ route('bukti.show', $t->id_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-image me-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-secondary small">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $t->status_badge }} text-{{ $t->status_pembayaran==='menunggu_verifikasi'?'dark':'white' }}">
                            {{ $t->status_label }}
                        </span>
                    </td>
                    <td>
                        @if($t->status_pembayaran === 'menunggu_verifikasi')
                            <button class="btn btn-sm btn-success me-1" onclick="verifikasi({{ $t->id_pembayaran }},'lunas')" title="Setujui"><i class="fas fa-check"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="verifikasi({{ $t->id_pembayaran }},'belum_dibayar')" title="Tolak"><i class="fas fa-times"></i></button>
                        @endif
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
function verifikasi(id, status) {
    const msg = status === 'lunas' ? 'Konfirmasi pembayaran ini sebagai LUNAS?' : 'Tolak bukti pembayaran ini?';
    Swal.fire({ title:'Konfirmasi', text:msg, icon:'question', showCancelButton:true, confirmButtonText:'Ya', cancelButtonText:'Batal', confirmButtonColor: status==='lunas'?'#198754':'#dc3545' })
        .then(r => {
            if (r.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.transaksi.verifikasi") }}',
                    method: 'POST',
                    data: { _token:'{{ csrf_token() }}', id_pembayaran:id, status_pembayaran:status },
                    success: res => { if (res.success) Swal.fire({ icon:'success', title:'Berhasil!', timer:1500, showConfirmButton:false }).then(() => location.reload()); }
                });
            }
        });
}
</script>
@endpush
