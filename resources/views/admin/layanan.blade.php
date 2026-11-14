@extends('layouts.admin')
@section('title','Kelola Layanan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><div class="page-title">Kelola Layanan</div><div class="page-breadcrumb">Admin / Layanan</div></div>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalLayanan" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i>Tambah Layanan
    </button>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-dark-custom dt-table w-100">
            <thead><tr><th>#</th><th>Foto</th><th>Nama Layanan</th><th>Deskripsi</th><th>Harga</th><th>Estimasi</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($layanan as $i => $l)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        @if($l->foto)
                            <img src="{{ route('foto.show', $l->foto) }}" class="rounded" style="width:60px;height:45px;object-fit:cover;">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center" style="width:60px;height:45px;background:#2a2a2a;">
                                <i class="fas fa-cut text-warning"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $l->nama_layanan }}</td>
                    <td><span class="text-secondary small">{{ Str::limit($l->deskripsi, 50) }}</span></td>
                    <td class="text-warning fw-semibold">{{ $l->harga_format }}</td>
                    <td>{{ $l->estimasi_waktu }} menit</td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning me-1"
                                data-bs-toggle="modal" data-bs-target="#modalLayanan"
                                onclick="editLayanan({{ $l->toJson() }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    data-nama="{{ $l->nama_layanan }}" onclick="return konfirmasiHapus(event, this.dataset.nama)">
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

<div class="modal fade modal-dark" id="modalLayanan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="modalTitle"><i class="fas fa-scissors me-2"></i>Tambah Layanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formLayanan" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_layanan" id="inputNama" class="form-control form-control-dark" placeholder="Contoh: Potong Rambut Reguler" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Deskripsi</label>
                        <textarea name="deskripsi" id="inputDeskripsi" class="form-control form-control-dark" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label-dark">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" id="inputHarga" class="form-control form-control-dark" placeholder="35000" min="1000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label-dark">Estimasi (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="estimasi_waktu" id="inputEstimasi" class="form-control form-control-dark" placeholder="30" min="5" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label-dark">Foto Layanan</label>
                        <input type="file" name="foto" class="form-control form-control-dark" accept="image/*" onchange="previewFoto(this)">
                        <small class="text-secondary">Format: JPG, PNG, WEBP. Maks 2MB</small>
                        <div class="mt-2" id="fotoPreview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function resetForm() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-scissors me-2"></i>Tambah Layanan';
    document.getElementById('formLayanan').action = '{{ route("admin.layanan.store") }}';
    document.getElementById('formMethod').value = 'POST';
    ['inputNama','inputDeskripsi','inputHarga','inputEstimasi'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fotoPreview').innerHTML = '';
}
function editLayanan(data) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Layanan';
    document.getElementById('formLayanan').action = `/admin/layanan/${data.id_layanan}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('inputNama').value = data.nama_layanan;
    document.getElementById('inputDeskripsi').value = data.deskripsi || '';
    document.getElementById('inputHarga').value = data.harga;
    document.getElementById('inputEstimasi').value = data.estimasi_waktu;
    document.getElementById('fotoPreview').innerHTML = data.foto ? `<img src="/foto/${data.foto}" class="rounded" style="height:80px;object-fit:cover;">` : '';
}
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('fotoPreview').innerHTML = `<img src="${e.target.result}" class="rounded" style="height:80px;object-fit:cover;">`;
        reader.readAsDataURL(input.files[0]);
    }
}
function konfirmasiHapus(e, nama) {
    e.preventDefault();
    const form = e.target.closest('form');
    Swal.fire({ title:`Hapus ${escapeHtml(nama)}?`, text:'Data tidak bisa dikembalikan!', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', cancelButtonColor:'#6c757d', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal' })
        .then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
