# Tansy Barbershop

Aplikasi web reservasi layanan barbershop, dikembangkan dan diamankan untuk **Tugas Project Mata Kuliah Cyber Security** (Pengembangan & Pengamanan Aplikasi Web).

- **Aplikasi online:** https://tansy-barbershop.vercel.app
- **Repository:** https://github.com/Lenoxy-xz/tansy-barbareshop
- **Akun uji coba:** username dan password admin serta pelanggan dicantumkan pada laporan tugas (tidak ditulis di repository).

## Deskripsi

Tansy Barbershop memudahkan pelanggan memesan layanan barbershop secara daring dan memudahkan pengelola memantau booking, transaksi, dan pendapatan. Pelanggan dapat mendaftar, memilih layanan dan jadwal, mengunggah bukti pembayaran, dan memberi testimoni. Admin mengelola layanan, booking, pelanggan, transaksi, testimoni, dan melihat laporan.

Aplikasi dasar berasal dari proyek mata kuliah semester sebelumnya (commit awal `chore: initial commit (baseline)`). Pada tugas ini dikerjakan audit keamanan, perbaikan celah, upgrade Laravel 10 ke 12, penyesuaian agar dapat berjalan di hosting serverless, deployment, dan pengujian keamanan mandiri. Seluruh riwayat perubahan dapat dilihat pada riwayat commit.

## Fitur

| Kategori | Fitur |
|---|---|
| Autentikasi | Registrasi dan login (username atau email), logout, ganti password |
| Otorisasi | Dua peran: admin dan pelanggan, masing-masing dengan halaman sendiri |
| Pengelolaan data (CRUD) | Layanan (dengan foto), booking, pelanggan, pembayaran (unggah bukti), testimoni |
| Laporan | Dashboard dengan statistik dan grafik pendapatan 7 hari, rekap per periode yang dapat dicetak |
| Fitur lain | Pengecekan bentrok jadwal, moderasi testimoni, profil pelanggan, tampilan responsif |

## Teknologi

| Komponen | Teknologi |
|---|---|
| Framework | Laravel 12 (PHP 8.2 atau lebih baru; di produksi PHP 8.4) |
| Database | MySQL (pengembangan lokal) dan PostgreSQL di Neon (produksi) |
| Hosting | Vercel (fungsi berbasis container dengan FrankenPHP), HTTPS otomatis |
| Sesi dan cache | Database (instance Vercel tidak berbagi disk) |
| Berkas unggahan | Tabel `berkas` di database (foto dan bukti pembayaran, maks. 2 MB) |
| Front end | Blade, Bootstrap, jQuery, DataTables, SweetAlert2, Chart.js |

```
Browser --HTTPS--> Vercel (FrankenPHP + Laravel 12) --SSL--> Neon PostgreSQL
                          ^
                          | deploy otomatis saat push ke branch main
                       GitHub
```

## Instalasi dan Menjalankan Secara Lokal

**Prasyarat:** PHP 8.2 atau lebih baru, Composer, MySQL (misalnya XAMPP), dan Git. Python 3 hanya dibutuhkan untuk skrip uji keamanan.

1. Unduh kode dan pasang dependency:
   ```
   git clone https://github.com/Lenoxy-xz/tansy-barbareshop.git
   cd tansy-barbareshop
   composer install
   ```
2. Salin berkas konfigurasi dan buat kunci aplikasi:
   ```
   copy .env.example .env
   php artisan key:generate
   ```
   (di Linux atau macOS gunakan `cp .env.example .env`)
3. Buat database kosong di MySQL (misalnya `dbtansy` lewat phpMyAdmin), lalu sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
4. Isi password awal di `.env` (wajib minimal 8 karakter dengan huruf besar, huruf kecil, dan angka, kalau tidak seeder menolak):
   ```
   SEED_ADMIN_PASSWORD=...
   SEED_USER_PASSWORD=...
   ```
5. Buat tabel dan isi data awal:
   ```
   php artisan migrate --seed
   ```
   Seeder membuat akun admin (username dari `SEED_ADMIN_USERNAME`, bawaan `admin`), akun pelanggan `budi123`, serta data demo (layanan, booking, pembayaran, testimoni).
6. Jalankan aplikasi:
   ```
   php artisan serve
   ```
   Buka http://127.0.0.1:8000.

**Catatan:** jika database lokal sudah berisi tabel yang dibuat di luar migration, perintah `php artisan migrate` biasa akan gagal dengan pesan "table already exists". Dalam kasus itu jalankan hanya migrasi tabel baru, misalnya `php artisan migrate --path=database/migrations/2024_02_01_000001_create_berkas_table.php`.

## Konfigurasi Environment Variable

Nilai rahasia **tidak** ditulis di repository. Salin dari `.env.example`, isi di `.env` (lokal) atau di dashboard hosting (produksi).

| Variabel | Fungsi |
|---|---|
| `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL` | Identitas dan mode aplikasi (produksi: `APP_ENV=production`, `APP_DEBUG=false`) |
| `APP_KEY` | Kunci enkripsi aplikasi (`php artisan key:generate`; rahasia) |
| `LOG_CHANNEL` | Saluran log (produksi: `stderr`) |
| `DB_CONNECTION` | `mysql` (lokal) atau `pgsql` (produksi) |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Koneksi database lokal |
| `DB_URL`, `DB_SSLMODE` | Koneksi database produksi (Neon); `DB_URL` rahasia |
| `SESSION_DRIVER`, `CACHE_DRIVER`, `CACHE_STORE` | Penyimpanan sesi dan cache (produksi: `database`) |
| `SESSION_SECURE_COOKIE` | Cookie sesi hanya lewat HTTPS (produksi: `true`) |
| `SESSION_LIFETIME` | Masa berlaku sesi dalam menit (bawaan 120) |
| `SEED_ADMIN_USERNAME`, `SEED_ADMIN_PASSWORD`, `SEED_USER_PASSWORD` | Akun awal yang dibuat seeder (password rahasia) |

## Deployment

Aplikasi di-deploy ke **Vercel** (container FrankenPHP) dengan database **Neon (PostgreSQL)**. Setiap push ke branch `main` memicu build otomatis. Langkah lengkap (persiapan database, environment variable, dan pemecahan masalah) ada di [`DEPLOY_VERCEL.md`](DEPLOY_VERCEL.md).

## Penerapan Keamanan

| Kontrol | Penerapan |
|---|---|
| Password | Hash bcrypt; kebijakan min. 8 karakter dengan huruf besar, huruf kecil, dan angka |
| Rate limiting | Login 5 per menit per username+IP dan 20 per menit per IP; registrasi 10 per jam |
| Validasi input | Aturan `validate()` pada semua form; output Blade di-escape; `escapeHtml()` pada dialog JavaScript |
| SQL injection | Eloquent dan query builder berparameter; tidak ada query mentah |
| Sesi | Cookie `HttpOnly`, `Secure`, `SameSite=Lax`; sesi dibuat ulang saat login dan dibatalkan saat logout |
| CSRF | Token CSRF pada semua form dan permintaan AJAX |
| Kontrol akses | Middleware `auth` dan `role`; data pelanggan dibatasi pemiliknya; bukti pembayaran hanya untuk pemilik dan admin |
| Upload | Validasi tipe dari isi berkas, nama acak dari server, disimpan di database (bukan folder publik) |
| Secret | Hanya di environment variable; `.env` masuk `.gitignore` |
| Security headers | CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS |
| Dependency | Laravel 12; `composer audit` tanpa advisory |

Keterbatasan yang diketahui: CSP masih memuat `'unsafe-inline'` dan log aktivitas belum tersedia (lihat laporan, Bab 10).

## Hasil Self-Testing Keamanan

Ringkasan berikut; **bukti lengkap berupa tangkapan layar ada pada laporan tugas**.

| Skenario | Sebelum | Sesudah |
|---|---|---|
| Rate limiting login | Gagal (tidak ada pembatasan) | Lolos (HTTP 429 mulai percobaan ke-6) |
| Password lemah (`12345678`) | Gagal (diterima) | Lolos (ditolak) |
| Security headers | Gagal (tidak ada) | Lolos (securityheaders.com: A; SSL Labs: A+ dan A) |
| XSS pada nama pelanggan (dialog hapus admin) | Gagal | Lolos |
| Kontrol akses (pelanggan ke halaman admin) | Lolos | Lolos |
| IDOR bukti pembayaran | Gagal (berkas publik) | Lolos (hanya pemilik dan admin) |
| Cookie sesi | Tanpa tanda Secure | `HttpOnly`, `Secure`, `SameSite=Lax` |
| Dependency (`composer audit`) | 28 advisory pada 8 paket | Tidak ada advisory |
| SQL injection | Query berparameter, tidak ada query mentah; hasil uji manual pada laporan | |
| Secret di riwayat Git | `.env` masuk `.gitignore` sejak commit pertama; hasil pengecekan pada laporan | |

Skrip uji otomatis tersedia di `tests_keamanan/uji_keamanan.py` (hanya library standar Python). Jalankan hanya terhadap aplikasi milik sendiri. Contoh pada Windows CMD (ganti nilai dengan akun milik sendiri):

```
set "UJI_ADMIN_USER=admin"
set "UJI_ADMIN_PW=..."
set "UJI_USER_USER=budi123"
set "UJI_USER_PW=..."
python tests_keamanan\uji_keamanan.py http://127.0.0.1:8000
```

Skrip membuat beberapa akun uji berawalan `xss` dan `lemah` (email berakhiran `@uji.test`) dan sengaja memicu rate limiting; jalankan `php artisan cache:clear` bila registrasi mulai ditolak. Hasil yang diharapkan: `Ringkasan: 6/6 lolos`.

## Penggunaan AI Agent

**Alat:** Claude (Anthropic), lewat antarmuka chat Claude.ai.

| Bagian pekerjaan | Bantuan AI |
|---|---|
| Audit awal terhadap ketentuan tugas | Meninjau kode dan menyusun daftar kekurangan |
| Perbaikan keamanan | Menulis kode rate limiting, kebijakan password, perbaikan XSS, security headers, bukti pembayaran privat, seeder aman |
| Skrip uji keamanan | Menulis skrip uji otomatis dan skenario uji manual |
| Upgrade dan dependency | Menjelaskan hasil `composer audit` dan menyiapkan upgrade ke Laravel 12 |
| Deployment | Menyusun Dockerfile, konfigurasi Vercel, panduan migrasi ke Neon, dan membantu membaca log error |
| Dokumentasi | Menyusun kerangka laporan dan README |

**Contoh prompt utama:**

- "coba kamu cek dulu, setelah kamu cek apakah dari ketentuan tugas projek cyber security ini sudah ada semua di dalam projek saya (kecuali hosting karena belum) , dan kamu sebutkan apa saja yang kurang"
- "sekarang berikan saya cara memulainya yang git init atau apapun itu, agar bisa dilanjutkan."
- "ini hasil dari perintah : php artisan migrate, apakah ada yang salah?"

**Cara meninjau dan memverifikasi hasil:** setiap perubahan dibaca, dijalankan, dan di-commit terpisah; skrip uji dijalankan sebelum dan sesudah perbaikan (2 dari 6 menjadi 6 dari 6) dan terhadap aplikasi online; temuan diverifikasi manual di browser (XSS, IDOR, SQL injection, cookie); hasil dependency dicek dengan `composer audit`. Kesalahan AI yang ditemukan lewat pengujian (misalnya skrip uji yang awalnya menganggap error 500 sebagai lolos, dan aturan CSS yang menyembunyikan tombol menu di layar kecil) diperbaiki. Secret produksi (`APP_KEY`, connection string database, dan password akun pada aplikasi online) tidak dimasukkan ke prompt AI.

## Struktur Direktori Penting

```
app/Http/Controllers/     Controller (Admin, Pelanggan, Auth, Berkas, BuktiPembayaran)
app/Http/Middleware/      CheckRole, SecurityHeaders, TrustProxies
app/Support/Berkas.php    Penyimpanan berkas di database
database/migrations/      Skema database
database/seeders/         Data awal (password dari environment variable)
resources/views/          Tampilan Blade (admin, pelanggan, auth)
routes/web.php            Definisi route
tests_keamanan/           Skrip uji keamanan
Dockerfile, Caddyfile, vercel.json   Konfigurasi deployment Vercel
```
