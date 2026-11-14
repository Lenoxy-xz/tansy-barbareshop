@extends('layouts.pelanggan')
@section('title','Profil Saya')
@section('content')
<div class="mb-4">
    <h4 class="text-white fw-bold mb-1">Profil Saya</h4>
    <p class="text-secondary small mb-0">Kelola informasi akun Anda.</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        {{-- Edit Profil --}}
        <div class="stat-card mb-4">
            <h6 class="text-warning mb-3"><i class="fas fa-user-edit me-2"></i>Edit Profil</h6>
            <form method="POST" action="{{ route('pelanggan.profil.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="text-center mb-4">
                    @if($user->foto)
                        <img src="{{ route('foto.show', $user->foto) }}" class="rounded-circle mb-2" id="fotoPreview"
                             style="width:90px;height:90px;object-fit:cover;border:3px solid #c9a84c;">
                    @else
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto mb-2" id="fotoDefault" style="width:90px;height:90px;">
                            <i class="fas fa-user text-dark fs-2"></i>
                        </div>
                    @endif
                    <div>
                        <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none" onchange="previewFoto(this)">
                        <label for="fotoInput" class="btn btn-sm btn-outline-warning" style="cursor:pointer;"><i class="fas fa-camera me-1"></i>Ubah Foto</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-secondary small mb-1 d-block">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama',$user->nama) }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                    @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="text-secondary small mb-1 d-block">Username</label>
                    <input type="text" class="form-control" value="{{ $user->username }}"
                           style="background:rgba(255,255,255,0.04);border-color:rgba(255,255,255,0.1);color:#666;" disabled>
                    <small class="text-secondary">Username tidak bisa diubah</small>
                </div>
                <div class="mb-3">
                    <label class="text-secondary small mb-1 d-block">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="text-secondary small mb-1 d-block">No. HP</label>
                    <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp',$user->no_hp) }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <button type="submit" class="btn btn-gold"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
            </form>
        </div>

        {{-- Ganti Password --}}
        <div class="stat-card">
            <h6 class="text-warning mb-3"><i class="fas fa-lock me-2"></i>Ganti Password</h6>
            <form method="POST" action="{{ route('pelanggan.profil.password') }}">
                @csrf
                <div class="mb-3">
                    <label class="text-secondary small mb-1 d-block">Password Lama</label>
                    <input type="password" name="password_lama" class="form-control"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                    @error('password_lama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="text-secondary small mb-1 d-block">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <div class="mb-4">
                    <label class="text-secondary small mb-1 d-block">Konfirmasi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" class="form-control"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <button type="submit" class="btn btn-outline-warning"><i class="fas fa-key me-1"></i>Ubah Password</button>
            </form>
        </div>
    </div>

    {{-- Info Akun --}}
    <div class="col-lg-5">
        <div class="stat-card">
            <h6 class="text-warning mb-3"><i class="fas fa-info-circle me-2"></i>Info Akun</h6>
            <div class="text-center mb-4">
                @if($user->foto)
                    <img src="{{ route('foto.show', $user->foto) }}" class="rounded-circle" style="width:80px;height:80px;object-fit:cover;border:3px solid #c9a84c;">
                @else
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px;">
                        <i class="fas fa-user text-dark fs-2"></i>
                    </div>
                @endif
                <div class="text-white fw-bold mt-2">{{ $user->nama }}</div>
                <div class="text-secondary small">{{ $user->username }}</div>
                <span class="badge bg-warning text-dark mt-1">Pelanggan</span>
            </div>
            <ul class="list-unstyled text-secondary small">
                <li class="mb-2"><i class="fas fa-envelope text-warning me-2"></i>{{ $user->email }}</li>
                <li class="mb-2"><i class="fas fa-phone text-warning me-2"></i>{{ $user->no_hp }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const existing = document.getElementById('fotoPreview');
            const def = document.getElementById('fotoDefault');
            if (existing) existing.src = e.target.result;
            else if (def) def.outerHTML = `<img src="${e.target.result}" class="rounded-circle mb-2" id="fotoPreview" style="width:90px;height:90px;object-fit:cover;border:3px solid #c9a84c;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
