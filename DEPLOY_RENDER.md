# Panduan Deploy: Render + Neon

Aplikasi berjalan sebagai container Docker di Render (web service) dan memakai database PostgreSQL di Neon.
**Semua nilai rahasia diisi di dashboard hosting, tidak pernah ditulis di kode atau repository.**

## 1. Siapkan database (Neon)
1. Buat project di Neon (region Singapore), database Postgres.
2. Di halaman project, buka **Connect** dan ambil connection string **langsung** (tanpa `-pooler` pada nama host).
   Bentuknya: `postgresql://USER:PASSWORD@ep-xxxx.ap-southeast-1.aws.neon.tech/NAMADB?sslmode=require`
3. Simpan di catatan pribadi. Nilai ini akan menjadi `DB_URL`.

## 2. Buat APP_KEY
Di komputer lokal (folder projek):
```
php artisan key:generate --show
```
Hasilnya diawali `base64:`. Salin ke catatan pribadi (jangan menjalankan tanpa `--show`, karena akan mengubah `.env` lokal).

## 3. Buat web service di Render
1. **New +** > **Web Service** > hubungkan repository GitHub projek ini.
2. **Language/Runtime**: Docker. **Branch**: `main`. **Region**: Singapore. **Instance type**: Free.
3. Tambahkan **Environment Variables** berikut:

| Nama | Nilai |
|---|---|
| `APP_NAME` | `Tansy Barbershop` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | hasil langkah 2 |
| `APP_URL` | `https://NAMA-SERVICE.onrender.com` (isi setelah nama service diketahui) |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | connection string Neon (langkah 1) |
| `DB_SSLMODE` | `require` |
| `SEED_ADMIN_USERNAME` | username admin (mis. `admin`) |
| `SEED_ADMIN_PASSWORD` | password admin yang kuat |
| `SEED_USER_PASSWORD` | password akun uji pelanggan (`budi123`) yang kuat |

Password harus min. 8 karakter dengan huruf besar, huruf kecil, dan angka, kalau tidak seeder menolak dan deploy gagal.

4. Klik **Create Web Service**. Saat container start, `docker/entrypoint.sh` otomatis menjalankan migrasi dan mengisi data awal (hanya jika database masih kosong).

## 4. Setelah berhasil
- Buka `https://NAMA-SERVICE.onrender.com`, login dengan akun uji.
- Cek HTTPS (ikon gembok), https://securityheaders.com, dan https://www.ssllabs.com/ssltest/ untuk bukti laporan.
- Akun uji untuk dosen: admin (`SEED_ADMIN_USERNAME` / `SEED_ADMIN_PASSWORD`) dan pelanggan `budi123` (`SEED_USER_PASSWORD`).

## 5. Batasan dan perilaku yang perlu diketahui
- **Service gratis tidur** setelah sekitar 15 menit tanpa trafik; permintaan pertama bisa lambat sekitar satu menit. Buka aplikasi beberapa menit sebelum diuji.
- **Disk container bersifat sementara.** File yang diunggah (bukti bayar, foto) hilang saat deploy ulang atau restart. Data di database tetap aman.
- Database Neon "tidur" saat tidak dipakai; koneksi pertama sedikit lebih lambat.
- Log aplikasi dan error tampil di tab **Logs** di Render.

## 6. Jika deploy gagal
- Lihat tab **Logs**. Pesan `APP_KEY belum diisi`: isi environment variable `APP_KEY`.
- Pesan `SEED_ADMIN_PASSWORD belum diisi atau terlalu lemah`: perbaiki password seeder.
- Error koneksi database: periksa `DB_URL` dan pastikan memakai koneksi langsung dengan `sslmode=require`.
