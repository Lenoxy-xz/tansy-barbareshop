# Tansy Barbershop - Laravel Version

Sistem pelayanan barbershop berbasis **Laravel 10** + MySQL + Bootstrap 5.
Layout dan alur sama persis dengan versi PHP Native.

---

## Persyaratan

- PHP 8.1+
- Composer
- MySQL 5.7+
- XAMPP / Laragon

---

## Cara Instalasi & Menjalankan

### 1. Ekstrak ZIP ke folder mana saja
Contoh: `D:\tansy-laravel\`

### 2. Buka CMD, masuk ke folder:
```
d:
cd tansy-laravel
```

### 3. Install dependensi Laravel:
```
composer install
```

### 4. Generate App Key:
```
php artisan key:generate
```

### 5. Buat database di phpMyAdmin:
Buat database baru bernama: **dbtansy**

### 6. Jalankan migration + data dummy:
```
php artisan migrate --seed
```

> Jika database dbtansy sudah ada (dari versi native), skip langkah ini
> dan langsung ke langkah 7.

### 7. Jalankan server:
```
php artisan serve
```

### 8. Buka browser:
```
http://localhost:8000
```

---

## Akun Demo

| Role       | Username  | Password  |
|------------|-----------|-----------|
| Admin      | admin     | password  |
| Pelanggan  | budi123   | password  |
| Pelanggan  | andi99    | password  |

---

## Struktur Folder

```
tansy-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # DashboardController, LayananController, dll
│   │   │   ├── Auth/        # LoginController, RegisterController
│   │   │   ├── Pelanggan/   # DashboardController, BookingController, dll
│   │   │   └── Controller.php  # Base Controller (WAJIB ADA)
│   │   ├── Middleware/      # CheckRole, Authenticate, dll
│   │   └── Kernel.php
│   └── Models/              # User, Booking, Layanan, Pembayaran, Testimoni
├── config/                  # app, auth, database, view, session, dll
├── database/
│   ├── migrations/          # 5 file migration tabel
│   └── seeders/             # DatabaseSeeder dengan data dummy
├── public/
│   ├── assets/css/          # style.css, admin.css
│   ├── assets/js/           # main.js, admin.js
│   └── uploads/             # layanan, pembayaran, profil
├── resources/views/
│   ├── layouts/             # app.blade.php, admin.blade.php, pelanggan.blade.php
│   ├── admin/               # dashboard, layanan, booking, pelanggan, transaksi, testimoni, laporan
│   ├── auth/                # login.blade.php, register.blade.php
│   ├── pelanggan/           # dashboard, booking, riwayat, pembayaran, testimoni, profil
│   └── home.blade.php       # Landing page
└── routes/
    └── web.php              # Semua route
```

---

## Tabel Database (dbtansy)

| Tabel       | Keterangan                    |
|-------------|-------------------------------|
| users       | Data admin dan pelanggan      |
| layanan     | Daftar layanan barbershop     |
| booking     | Data booking pelanggan        |
| pembayaran  | Data pembayaran booking       |
| testimoni   | Ulasan dan rating pelanggan   |
