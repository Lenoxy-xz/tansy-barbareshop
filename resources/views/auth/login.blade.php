<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tansy Barbershop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <i class="fas fa-cut text-warning fs-1"></i>
                <h4 class="text-white mt-2 mb-0 fw-bold">Tansy <span class="text-warning">Barbershop</span></h4>
            </a>
            <p class="text-secondary mt-2 small">Masuk ke akun Anda</p>
        </div>

        @if($errors->any())
        <div class="alert mb-3" style="background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.3);color:#ff6b6b;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert mb-3" style="background:rgba(25,135,84,0.15);border:1px solid rgba(25,135,84,0.3);color:#75b798;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username / Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0" style="border-color:rgba(255,255,255,0.15);color:#888;">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" class="form-control border-start-0"
                           placeholder="Masukkan username atau email"
                           value="{{ old('username') }}"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                           required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0" style="border-color:rgba(255,255,255,0.15);color:#888;">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control border-start-0" id="passwordInput"
                           placeholder="Masukkan password"
                           style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.15);color:#fff;"
                           required>
                    <button type="button" class="input-group-text bg-transparent" id="togglePass"
                            style="border-color:rgba(255,255,255,0.15);color:#888;cursor:pointer;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-2">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </form>

        <hr style="border-color:rgba(255,255,255,0.1);" class="my-4">
        <p class="text-center text-secondary small mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-warning text-decoration-none fw-semibold">Daftar Sekarang</a>
        </p>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePass').addEventListener('click', function() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
        else { input.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
    });
</script>
</body>
</html>
