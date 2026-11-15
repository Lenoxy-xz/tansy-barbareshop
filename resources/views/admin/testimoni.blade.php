@extends('layouts.admin')
@section('title','Kelola Testimoni')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Kelola Testimoni</div><div class="page-breadcrumb">Admin / Testimoni</div></div>
</div>

<div class="admin-card mb-3">
    <form method="GET" action="{{ route('admin.testimoni.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label-dark">Filter Status</label>
            <select name="status" class="form-control form-control-dark">
                <option value="">Semua</option>
                @foreach(['menunggu','disetujui','ditolak'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button type="submit" class="btn btn-gold w-100">Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.testimoni.index') }}" class="btn btn-outline-secondary w-100">Reset</a></div>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-dark-custom dt-table w-100">
            <thead><tr><th>#</th><th>Pelanggan</th><th>Rating</th><th>Komentar</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($testimoni as $i => $t)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="fw-semibold small">{{ $t->user->nama }}</div>
                        <div class="text-secondary" style="font-size:0.75rem;">&#64;{{ $t->user->username }}</div>
                    </td>
                    <td>
                        <div class="text-warning" style="font-size:0.85rem;">
                            @for($s=1;$s<=5;$s++)<i class="fas fa-star {{ $s<=$t->rating?'':'text-secondary' }}"></i>@endfor
                        </div>
                        <small class="text-secondary">{{ $t->rating }}/5</small>
                    </td>
                    <td style="max-width:300px;"><span class="text-secondary small">{{ $t->komentar }}</span></td>
                    <td>
                        @php $cls = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger'][$t->status] ?? 'secondary'; @endphp
                        <span class="badge bg-{{ $cls }} text-{{ $cls==='warning'?'dark':'white' }}">{{ ucfirst($t->status) }}</span>
                    </td>
                    <td>
                        @if($t->status === 'menunggu')
                            <button class="btn btn-sm btn-success me-1" onclick="updateStatus({{ $t->id_testimoni }},'disetujui')" title="Setujui"><i class="fas fa-check"></i></button>
                            <button class="btn btn-sm btn-warning me-1"  onclick="updateStatus({{ $t->id_testimoni }},'ditolak')" title="Tolak"><i class="fas fa-times"></i></button>
                        @elseif($t->status === 'ditolak')
                            <button class="btn btn-sm btn-outline-success me-1" onclick="updateStatus({{ $t->id_testimoni }},'disetujui')"><i class="fas fa-check"></i></button>
                        @elseif($t->status === 'disetujui')
                            <button class="btn btn-sm btn-outline-warning me-1" onclick="updateStatus({{ $t->id_testimoni }},'ditolak')"><i class="fas fa-eye-slash"></i></button>
                        @endif
                        <form action="{{ route('admin.testimoni.destroy', $t->id_testimoni) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return konfirmasiHapus(event)"><i class="fas fa-trash"></i></button>
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
function updateStatus(id, status) {
    $.ajax({
        url: '{{ route("admin.testimoni.status") }}',
        method: 'POST',
        data: { _token:'{{ csrf_token() }}', id_testimoni:id, status:status },
        success: res => { if (res.success) Swal.fire({ icon:'success', title:'Berhasil!', timer:1200, showConfirmButton:false }).then(() => location.reload()); }
    });
}
function konfirmasiHapus(e) {
    e.preventDefault(); const form = e.target.closest('form');
    Swal.fire({ title:'Hapus testimoni?', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal' })
        .then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
