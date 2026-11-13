@extends('layouts.admin')
@section('title','Kelola Pelanggan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Kelola Pelanggan</div><div class="page-breadcrumb">Admin / Pelanggan</div></div>
    <span class="badge bg-secondary">Total: {{ $pelanggan->count() }} pelanggan</span>
</div>
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-dark-custom dt-table w-100">
            <thead><tr><th>#</th><th>Foto</th><th>Nama</th><th>Username</th><th>Email</th><th>No. HP</th><th>Total Booking</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pelanggan as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        @if($p->foto)
                            <img src="{{ asset('uploads/profil/'.$p->foto) }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="fas fa-user text-dark small"></i></div>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $p->nama }}</td>
                    <td class="text-secondary">{{ $p->username }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->no_hp }}</td>
                    <td><span class="badge bg-primary">{{ $p->bookings_count }}</span></td>
                    <td>
                        <form action="{{ route('admin.pelanggan.destroy', $p->id_user) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return konfirmasiHapus(event, '{{ $p->nama }}')">
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
function konfirmasiHapus(e, nama) {
    e.preventDefault(); const form = e.target.closest('form');
    Swal.fire({ title:`Hapus ${nama}?`, icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal' })
        .then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
