<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terlalu Banyak Percobaan - Tansy Barbershop</title>
    <style>
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center;
               background:#111; color:#eee; font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif; }
        .box { max-width:420px; padding:2rem; text-align:center; border:1px solid #333; border-radius:12px; background:#1a1a1a; }
        h1 { color:#c9a84c; font-size:1.4rem; margin:0 0 .75rem; }
        p { color:#bbb; line-height:1.5; }
        a { display:inline-block; margin-top:1rem; padding:.55rem 1.2rem; background:#c9a84c; color:#111;
            border-radius:8px; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Terlalu banyak percobaan</h1>
        <p>Demi keamanan akun, permintaan Anda dibatasi sementara.
           Silakan coba lagi dalam {{ (int) ($exception->getHeaders()['Retry-After'] ?? 60) }} detik.</p>
        <a href="{{ url('/') }}">Kembali ke beranda</a>
    </div>
</body>
</html>
