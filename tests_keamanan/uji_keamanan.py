#!/usr/bin/env python3
"""Skrip uji keamanan sederhana untuk Tansy Barbershop.
Pemakaian:  python uji_keamanan.py http://127.0.0.1:8000
Hanya pakai library standar Python. Jalankan terhadap aplikasi SENDIRI saja.

Catatan: skrip ini membuat beberapa akun uji dan sengaja memicu rate limiting.
Jika dijalankan berulang kali dan registrasi mulai ditolak (HTTP 429), jalankan dulu
`php artisan cache:clear` di folder projek untuk mereset penghitung rate limit."""
import sys, re, html, urllib.request, urllib.parse, http.cookiejar, uuid

import os
BASE = (sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8000").rstrip("/")
ADMIN_PW = os.environ.get("UJI_ADMIN_PW", "password")   # password akun admin untuk pengujian
USER_PW = os.environ.get("UJI_USER_PW", "password")     # password akun pelanggan
ADMIN_USER = os.environ.get("UJI_ADMIN_USER", "admin")  # username akun admin
USER_USER = os.environ.get("UJI_USER_USER", "budi123")  # username akun pelanggan

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None

def sesi():
    jar = http.cookiejar.CookieJar()
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar), NoRedirect)

def req(op, path, data=None):
    url = BASE + path
    body = urllib.parse.urlencode(data).encode() if data is not None else None
    try:
        r = op.open(urllib.request.Request(url, data=body), timeout=15)
    except urllib.error.HTTPError as e:
        r = e
    return r.status if hasattr(r, "status") else r.code, r.headers, r.read().decode("utf-8", "replace")

def token(op, path):
    _, _, b = req(op, path)
    m = re.search(r'name="_token"\s+value="([^"]+)"', b) or re.search(r'value="([^"]+)"\s+name="_token"', b)
    return m.group(1) if m else ""

LOGIN_STATUS = 0

def login_ok(op, user, pw):
    """Login lalu pastikan benar-benar berhasil (diarahkan ke dashboard, bukan kembali ke /login)."""
    t = token(op, "/login")
    st, h, _ = req(op, "/login", {"_token": t, "username": user, "password": pw})
    global LOGIN_STATUS
    LOGIN_STATUS = st
    return st in (301, 302) and "dashboard" in (h.get("Location") or "")

hasil = []
def catat(nama, lolos, detail):
    hasil.append((nama, lolos)); print(f"[{'LOLOS' if lolos else 'GAGAL'}] {nama}\n        {detail}")

# 1. Rate limiting login
op = sesi(); kode = []
for i in range(12):
    t = token(op, "/login")
    s, _, _ = req(op, "/login", {"_token": t, "username": "ujibrute", "password": f"salah{i}"})
    kode.append(s)
if any(k >= 500 for k in kode):
    catat("Rate limiting login (12 percobaan salah beruntun)", False,
          f"SERVER ERROR (HTTP 500), uji tidak valid. Cek MySQL menyala dan storage/logs/laravel.log. kode HTTP: {kode}")
else:
    catat("Rate limiting login (12 percobaan salah beruntun)", 429 in kode, f"kode HTTP: {kode}")

# 2. Password lemah ditolak saat registrasi
op = sesi(); u = "lemah" + uuid.uuid4().hex[:6]; t = token(op, "/register")
s, h, _ = req(op, "/register", {"_token": t, "nama": "Uji Lemah", "username": u, "email": f"{u}@uji.test",
    "no_hp": "0811111111", "password": "12345678", "password_confirmation": "12345678"})
diterima = s in (301, 302) and "/login" in (h.get("Location") or "")
if s >= 500:
    catat("Password lemah '12345678' ditolak", False, f"SERVER ERROR (HTTP {s}), uji tidak valid. Cek MySQL menyala dan storage/logs/laravel.log")
else:
    catat("Password lemah '12345678' ditolak", not diterima, f"HTTP {s}, " + ("DITERIMA (buruk)" if diterima else "ditolak"))

# 3. Security headers
op = sesi(); _, h, _ = req(op, "/login")
perlu = ["X-Content-Type-Options", "X-Frame-Options", "Referrer-Policy", "Content-Security-Policy", "Permissions-Policy"]
ada = [x for x in perlu if h.get(x)]
catat("Security headers ada", len(ada) == len(perlu), f"ada: {ada}; kurang: {[x for x in perlu if x not in ada]}")

# 4. XSS pada dialog hapus admin (nama pelanggan dipakai di atribut onclick dan judul SweetAlert)
payloads = ["');alert(1);//", "<img src=x onerror=alert(1)>"]
for pl in payloads:
    op = sesi(); u = "xss" + uuid.uuid4().hex[:6]; t = token(op, "/register")
    s_, _, _ = req(op, "/register", {"_token": t, "nama": pl, "username": u, "email": f"{u}@uji.test",
        "no_hp": "0822222222", "password": "KataSandi#2026x", "password_confirmation": "KataSandi#2026x"})
    if s_ == 429:
        print("        (registrasi kena rate limit; jalankan `php artisan cache:clear` lalu ulangi)")
adm = sesi()
if not login_ok(adm, ADMIN_USER, ADMIN_PW):
    catat("XSS di dialog hapus admin (payload JS-breakout dan tag HTML)", False,
          ("SERVER ERROR (HTTP 500) saat login, bukan salah password. Cek MySQL menyala dan storage/logs/laravel.log" if LOGIN_STATUS >= 500 else f"TIDAK DAPAT DIUJI: login admin ({ADMIN_USER}) gagal. Cek UJI_ADMIN_USER dan UJI_ADMIN_PW"))
    b = js_admin = ""
    bocor_attr = None
else:
  _, _, b = req(adm, "/admin/pelanggan")
  _, _, js_admin = req(adm, "/assets/js/admin.js")
  bocor_attr = False
  for m in re.finditer(r'onclick="([^"]*)"', b):
      js = html.unescape(m.group(1))          # persis seperti yang dilakukan browser
      if "alert(1)" in js: bocor_attr = True
  judul_aman = "escapeHtml(nama)" in b and "function escapeHtml" in js_admin
  catat("XSS di dialog hapus admin (payload JS-breakout dan tag HTML)", (not bocor_attr) and judul_aman,
        ("payload lolos keluar dari string JS di atribut onclick; " if bocor_attr else "onclick aman; ")
        + ("judul SweetAlert di-escape" if judul_aman else "judul SweetAlert TIDAK di-escape (dirender sebagai HTML)"))

# 5. Kontrol akses: pelanggan tidak boleh membuka halaman admin
pel = sesi()
if not login_ok(pel, USER_USER, USER_PW):
    catat("Pelanggan tidak bisa membuka /admin/dashboard", False,
          ("SERVER ERROR (HTTP 500) saat login, bukan salah password. Cek MySQL menyala dan storage/logs/laravel.log" if LOGIN_STATUS >= 500 else f"TIDAK DAPAT DIUJI: login pelanggan ({USER_USER}) gagal. Cek UJI_USER_USER dan UJI_USER_PW"))
else:
  s, h, _ = req(pel, "/admin/dashboard")
  catat("Pelanggan tidak bisa membuka /admin/dashboard", s in (301, 302, 403) and "admin" not in (h.get("Location") or "dashboard").replace("pelanggan", ""),
        f"HTTP {s}, Location: {h.get('Location')}")

# 6. Tanpa login tidak bisa membuka halaman terlindungi
anon = sesi()
s, h, _ = req(anon, "/pelanggan/riwayat")
catat("Tanpa login diarahkan ke /login", s in (301, 302) and "/login" in (h.get("Location") or ""), f"HTTP {s}, Location: {h.get('Location')}")

print("\nRingkasan:", f"{sum(1 for _, o in hasil if o)}/{len(hasil)} lolos")
