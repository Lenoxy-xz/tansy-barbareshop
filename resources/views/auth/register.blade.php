<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Tansy Barbershop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:520px;">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <i class="fas fa-cut text-warning fs-1"></i>
                <h4 class="text-white mt-2 mb-0 fw-bold">Tansy <span class="text-warning">Barbershop</span></h4>
            </a>
            <p class="text-secondary mt-2 small">Buat akun baru Anda</p>
        </div>

        @if($errors->any())
        <div class="alert mb-3" style="background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.3);color:#ff6b6b;">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label text-secondary small">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap"
                           value="{{ old('nama') }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Username unik"
                           value="{{ old('username') }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small">No. HP / WhatsApp</label>
                    <input type="tel" name="no_hp" class="form-control" placeholder="0812xxxx"
                           value="{{ old('no_hp') }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <div class="col-12">
                    <label class="form-label text-secondary small">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com"
                           value="{{ old('email') }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small">Password</label>
                    <input type="password" name="password" class="form-control" id="pass1"
                           placeholder="Min. 8 karakter"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                    <div id="passStrength" class="mt-1" style="font-size:0.75rem;"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="pass2"
                           placeholder="Ulangi password"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;" required>
                    <div id="passMatch" class="mt-1" style="font-size:0.75rem;"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-2 mt-4">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>
        </form>

        <hr style="border-color:rgba(255,255,255,0.1);" class="my-4">
        <p class="text-center text-secondary small mb-0">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-warning text-decoration-none fw-semibold">Login Sekarang</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('pass1').addEventListener('input', function() {
        const el = document.getElementById('passStrength');
        if (!this.value) { el.innerHTML = ''; return; }
        const v = this.value;
        const ok = v.length >= 8 && /[a-z]/.test(v) && /[A-Z]/.test(v) && /[0-9]/.test(v);
        if (!ok) el.innerHTML = '<span style="color:#dc3545;">Min. 8 karakter, huruf besar, huruf kecil, dan angka</span>';
        else if (v.length < 12) el.innerHTML = '<span style="color:#fd7e14;">Cukup kuat</span>';
        else el.innerHTML = '<span style="color:#198754;">Kuat</span>';
    });
    document.getElementById('pass2').addEventListener('input', function() {
        const el = document.getElementById('passMatch');
        if (!this.value) { el.innerHTML = ''; return; }
        if (this.value === document.getElementById('pass1').value)
            el.innerHTML = '<span style="color:#198754;"><i class="fas fa-check me-1"></i>Password cocok</span>';
        else
            el.innerHTML = '<span style="color:#dc3545;">Password tidak cocok</span>';
    });
</script>
</body>
</html>
