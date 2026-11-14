# Panduan Deploy: Vercel + Neon

Aplikasi berjalan sebagai container (FrankenPHP) di Vercel dan memakai database PostgreSQL di Neon.
Mengikuti panduan resmi: https://vercel.com/kb/guide/laravel-php-with-docker

**Semua nilai rahasia diisi di dashboard Vercel atau di jendela CMD lokal, tidak pernah ditulis di kode atau repository.**

## Cara kerja singkat
- Instance Vercel bersifat sementara dan tidak berbagi disk, sehingga: **sesi dan cache disimpan di database**, dan **berkas unggahan (foto layanan, foto profil, bukti bayar) disimpan di tabel `berkas`** (bukti bayar tetap privat lewat rute yang mengecek pemilik).
- **Migrasi tidak dijalankan saat container start.** Migrasi dan seeder dijalankan sekali dari komputer lokal ke database Neon.

## 1. Persiapan lokal (sekali)

### a. Aktifkan driver PostgreSQL di PHP XAMPP
Buka `D:\xampp\php\php.ini`, cari dua baris berikut dan hapus tanda `;` di depannya:
```
;extension=pdo_pgsql
;extension=pgsql
```
Simpan, tutup semua CMD, buka CMD baru, lalu cek:
```
php -m | findstr pgsql
```
Harus muncul `pdo_pgsql` dan `pgsql`.

### b. Siapkan dua nilai rahasia (simpan di catatan pribadi)
- **Connection string Neon**, koneksi langsung (host tanpa `-pooler`), diawali `postgresql://`.
- **APP_KEY**: jalankan `php artisan key:generate --show`, hasilnya diawali `base64:`.

### c. Migrasi dan isi data awal ke Neon
Di CMD folder projek, isi variabel (ganti nilainya; tanda kutip ganda wajib, dan variabel hanya berlaku di jendela CMD ini):
```
set "DB_CONNECTION=pgsql"
set "DB_URL=ISI_CONNECTION_STRING_NEON"
set "DB_SSLMODE=require"
set "SEED_ADMIN_USERNAME=admin"
set "SEED_ADMIN_PASSWORD=ISI_PASSWORD_ADMIN_KUAT"
set "SEED_USER_PASSWORD=ISI_PASSWORD_AKUN_UJI_KUAT"
php artisan migrate --force
php artisan db:seed --force
```
Password harus min. 8 karakter dengan huruf besar, huruf kecil, dan angka, kalau tidak seeder menolak. Seeder hanya mengisi database yang masih kosong. **Tutup jendela CMD setelah selesai** agar variabel hilang.

### d. Database lokal (MySQL XAMPP)
Ada tabel baru (`berkas`, `sessions`, `cache`). Di CMD biasa (tanpa variabel di atas):
```
php artisan migrate
```
Jika mengunggah gambar besar di lokal gagal dengan pesan "server has gone away", naikkan `max_allowed_packet=16M` di `D:\xampp\mysql\bin\my.ini` lalu restart MySQL. Ini hanya berlaku untuk MySQL lokal.

## 2. Deploy di Vercel
1. vercel.com > **Add New... > Project** > **Import Git Repository** > pilih repository projek (beri akses ke repo bila diminta).
2. **Framework Preset**: Other. **Root Directory**: biarkan `./`. Vercel membaca `vercel.json`, `Dockerfile`, dan `Caddyfile`.
3. Buka **Environment Variables** dan isi:

| KEY | VALUE |
|---|---|
| `APP_NAME` | `Tansy Barbershop` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | hasil `key:generate --show` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | connection string Neon |
| `DB_SSLMODE` | `require` |
| `SESSION_DRIVER` | `database` |
| `CACHE_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `SESSION_SECURE_COOKIE` | `true` |

`SEED_*` tidak perlu di Vercel (seeder dijalankan lokal). Tandai `APP_KEY` dan `DB_URL` sebagai **Sensitive** bila ada opsinya.
4. Klik **Deploy**. Pantau build di tab **Deployments**.
5. **Penting untuk kecepatan:** di **Settings > Functions**, atur **Function Region** ke wilayah terdekat dengan database Neon (Singapore, `sin1`) bila opsinya tersedia. Tanpa itu setiap halaman bolak-balik ke database antar benua dan terasa lambat.

## 3. Setelah live
- Buka alamat `https://NAMA.vercel.app`, login dengan akun uji.
- Bukti untuk laporan: ikon gembok, https://securityheaders.com, https://www.ssllabs.com/ssltest/, dan cookie sesi (F12 > Application > Cookies: HttpOnly dan Secure).
- Akun uji untuk dosen: admin (`SEED_ADMIN_USERNAME` / `SEED_ADMIN_PASSWORD`) dan pelanggan `budi123` (`SEED_USER_PASSWORD`).
- Setiap `git push` ke `main` memicu deploy ulang otomatis.

## 4. Batasan yang perlu diketahui
- Container runtime Vercel berstatus **beta**; perilakunya bisa berubah.
- Saat tidak ada trafik, instance diturunkan dan permintaan pertama lebih lambat. Buka aplikasi beberapa menit sebelum diuji.
- Database Neon juga "tidur" saat tidak dipakai; koneksi pertama sedikit lebih lambat.
- Berkas di tabel `berkas` memakai kuota database Neon gratis; ukuran maksimum per unggahan 2 MB.

## 5. Jika gagal
- **Build gagal**: lihat log build di Vercel (bagian merah).
- **502**: FrankenPHP gagal start atau port salah; pastikan `Caddyfile` memakai `:{$PORT:80}`.
- **"No application encryption key"**: `APP_KEY` belum diisi; tambahkan lalu **Redeploy**.
- **Error koneksi database**: periksa `DB_URL` (koneksi langsung, `sslmode=require`) dan bahwa langkah 1c sudah dijalankan.
- **Error tabel `sessions` atau `cache` tidak ada**: migrasi (langkah 1c) belum dijalankan ke Neon.
